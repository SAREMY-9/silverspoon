<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\MealPlan;
use App\Models\Payment;
use App\Services\Payments\PaystackService;
use App\Services\Payments\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class PaystackController extends Controller
{
    /**
     * Initialize a Paystack checkout.
     */
    public function initiate(
        Request $request,
        MealPlan $mealPlan,
        SubscriptionService $subscriptionService,
        PaymentService $paymentService,
        PaystackService $paystackService
    ): JsonResponse {
        $user = $request->user();

        $subscription = null;
        $payment = null;

        try {

            /*
             * ---------------------------------------------------------
             * 1. CREATE/REUSE PENDING SUBSCRIPTION
             * ---------------------------------------------------------
             */
            $subscription = $subscriptionService->createPending(
                $user,
                $mealPlan
            );

            /*
             * ---------------------------------------------------------
             * 2. CREATE PAYMENT ATTEMPT
             * ---------------------------------------------------------
             */
            $payment = $paymentService->createPayment(
                $subscription,
                'paystack'
            );

            /*
             * ---------------------------------------------------------
             * 3. INITIALIZE PAYSTACK
             * ---------------------------------------------------------
             */
            $response = $paystackService->initializeTransaction(
                email: $user->email,
                amount: (float) $payment->amount,
                reference: $payment->transaction_reference,
                callbackUrl: route('paystack.callback')
            );

            /*
             * Paystack must give us an authorization URL.
             */
            if (
                empty($response['authorization_url'])
            ) {
                throw new RuntimeException(
                    'Paystack did not return an authorization URL.'
                );
            }

            /*
             * ---------------------------------------------------------
             * 4. SAVE PROVIDER RESPONSE
             * ---------------------------------------------------------
             */
            $payment->update([
                'provider_response' => json_encode(
                    $response,
                    JSON_THROW_ON_ERROR
                ),
            ]);

            Log::info(
                'Paystack transaction initialized',
                [
                    'payment_id' =>
                        $payment->id,

                    'subscription_id' =>
                        $subscription->id,

                    'reference' =>
                        $payment->transaction_reference,

                    'amount' =>
                        $payment->amount,
                ]
            );

            /*
             * ---------------------------------------------------------
             * 5. RETURN CHECKOUT URL
             * ---------------------------------------------------------
             */
            return response()->json([
                'success' => true,

                'message' =>
                    'Payment initialized successfully.',

                'payment_id' =>
                    $payment->id,

                'subscription_id' =>
                    $subscription->id,

                'reference' =>
                    $payment->transaction_reference,

                'authorization_url' =>
                    $response['authorization_url'],
            ]);

        } catch (Throwable $e) {

            /*
             * ---------------------------------------------------------
             * INITIALIZATION FAILED
             * ---------------------------------------------------------
             *
             * This means Paystack checkout was NOT successfully
             * handed to the customer.
             *
             * Therefore it is safe to invalidate the payment attempt.
             */
            if ($payment) {
                try {
                    if (
                        $payment->status ===
                        PaymentStatus::PENDING
                    ) {
                        $paymentService->markFailed(
                            $payment
                        );
                    }
                } catch (Throwable $paymentException) {

                    Log::error(
                        'Failed to mark Paystack initialization payment as failed',
                        [
                            'payment_id' =>
                                $payment->id,

                            'error' =>
                                $paymentException->getMessage(),
                        ]
                    );
                }
            }

            /*
             * IMPORTANT:
             *
             * DO NOT CANCEL THE SUBSCRIPTION.
             *
             * The customer should be able to retry using the same
             * pending subscription.
             */
            Log::error(
                'Paystack checkout initialization failed',
                [
                    'user_id' =>
                        $user?->id,

                    'meal_plan_id' =>
                        $mealPlan->id,

                    'subscription_id' =>
                        $subscription?->id,

                    'payment_id' =>
                        $payment?->id,

                    'error' =>
                        $e->getMessage(),

                    'exception' =>
                        get_class($e),
                ]
            );

            return response()->json([
                'success' => false,

                'message' =>
                    'Unable to initialize Paystack payment. Please try again.',
            ], 422);
        }
    }

    /**
     * Paystack redirects the customer here after checkout.
     *
     * NEVER trust the redirect itself.
     *
     * Always verify the transaction directly with Paystack.
     */
    public function callback(
        Request $request,
        PaystackService $paystackService,
        PaymentService $paymentService
    ) {
        $reference = trim(
            (string) $request->query('reference')
        );

        if ($reference === '') {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Payment reference is missing. Please try again.'
                );
        }

        /*
         * -------------------------------------------------------------
         * 1. FIND OUR PAYMENT
         * -------------------------------------------------------------
         */
        $payment = Payment::where(
            'transaction_reference',
            $reference
        )->first();

        if (!$payment) {

            Log::error(
                'Paystack payment not found',
                [
                    'reference' =>
                        $reference,
                ]
            );

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'We could not find your payment record. Please contact support.'
                );
        }

        try {

            /*
             * ---------------------------------------------------------
             * 2. IDEMPOTENCY
             * ---------------------------------------------------------
             */
            if (
                $payment->status ===
                PaymentStatus::SUCCESSFUL
            ) {

                return redirect()
                    ->route('dashboard')
                    ->with(
                        'success',
                        'Payment successful! Your Silver Spoon subscription is active.'
                    );
            }

            /*
             * ---------------------------------------------------------
             * 3. VERIFY WITH PAYSTACK
             * ---------------------------------------------------------
             */
            $transaction =
                $paystackService->verifyTransaction(
                    $reference
                );

            /*
             * ---------------------------------------------------------
             * 4. SAVE COMPLETE PROVIDER RESPONSE
             * ---------------------------------------------------------
             */
            $payment->update([
                'provider_response' => json_encode(
                    $transaction,
                    JSON_THROW_ON_ERROR
                ),
            ]);

            $status = strtolower(
                trim(
                    (string) (
                        $transaction['status'] ?? ''
                    )
                )
            );

            Log::info(
                'Paystack transaction verified',
                [
                    'payment_id' =>
                        $payment->id,

                    'subscription_id' =>
                        $payment->subscription_id,

                    'reference' =>
                        $reference,

                    'status' =>
                        $status,
                ]
            );

            /*
             * ---------------------------------------------------------
             * 5. SUCCESS
             * ---------------------------------------------------------
             */
            if ($status === 'success') {

                /*
                 * Provider says the customer paid.
                 *
                 * PaymentService is responsible for:
                 *
                 * payment → successful
                 * subscription → active
                 * entitlements → created
                 */
                $paymentService->markSuccessful(
                    $payment,
                    $transaction['reference'] ??
                        $reference
                );

                Log::info(
                    'Paystack payment successfully completed',
                    [
                        'payment_id' =>
                            $payment->id,

                        'subscription_id' =>
                            $payment->subscription_id,

                        'reference' =>
                            $reference,
                    ]
                );

                return redirect()
                    ->route('dashboard')
                    ->with(
                        'success',
                        'Payment successful! Your Silver Spoon subscription is now active.'
                    );
            }

            /*
             * ---------------------------------------------------------
             * 6. DEFINITIVE FAILURE
             * ---------------------------------------------------------
             *
             * ONLY provider-confirmed failure reaches here.
             */
            if (
                in_array(
                    $status,
                    [
                        'failed',
                        'abandoned',
                        'cancelled',
                    ],
                    true
                )
            ) {

                /*
                 * Never downgrade a successful payment.
                 */
                if (
                    $payment->status !==
                    PaymentStatus::SUCCESSFUL
                ) {
                    $paymentService->markFailed(
                        $payment
                    );
                }

                /*
                 * IMPORTANT:
                 *
                 * DO NOT CANCEL THE SUBSCRIPTION.
                 *
                 * A failed payment attempt should simply allow
                 * another payment attempt.
                 */
                Log::info(
                    'Paystack payment was not completed',
                    [
                        'payment_id' =>
                            $payment->id,

                        'subscription_id' =>
                            $payment->subscription_id,

                        'reference' =>
                            $reference,

                        'status' =>
                            $status,
                    ]
                );

                return redirect()
                    ->route('dashboard')
                    ->with(
                        'error',
                        'Payment was not completed. You can try subscribing again.'
                    );
            }

            /*
             * ---------------------------------------------------------
             * 7. NON-FINAL / UNKNOWN STATUS
             * ---------------------------------------------------------
             *
             * Never guess.
             */
            Log::warning(
                'Paystack returned a non-final transaction status',
                [
                    'payment_id' =>
                        $payment->id,

                    'subscription_id' =>
                        $payment->subscription_id,

                    'reference' =>
                        $reference,

                    'status' =>
                        $status,

                    'transaction' =>
                        $transaction,
                ]
            );

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Your payment is still being verified. Please check your subscription shortly.'
                );

        } catch (Throwable $e) {

            /*
             * ---------------------------------------------------------
             * CRITICAL PAYMENT SAFETY RULE
             * ---------------------------------------------------------
             *
             * NEVER mark the payment failed here.
             *
             * At this point we don't know whether:
             *
             * - Paystack failed
             * - Paystack succeeded but our app failed
             * - the network failed
             * - entitlement generation failed
             * - the database transaction failed
             *
             * Therefore we preserve the payment state.
             */
            Log::error(
                'Paystack callback processing failed',
                [
                    'payment_id' =>
                        $payment->id,

                    'subscription_id' =>
                        $payment->subscription_id,

                    'reference' =>
                        $reference,

                    'payment_status' =>
                        $payment->status instanceof
                        PaymentStatus
                            ? $payment->status->value
                            : $payment->status,

                    'error' =>
                        $e->getMessage(),

                    'exception' =>
                        get_class($e),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'We received your payment response but could not finish processing it. If you were charged, your payment remains under verification.'
                );
        }
    }
}