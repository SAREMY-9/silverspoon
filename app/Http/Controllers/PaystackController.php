<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\Payment;
use App\Services\Payments\PaystackService;
use App\Services\Payments\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\SubscriptionStatus;
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
             * 1. Create the pending subscription.
             */
            $subscription = $subscriptionService->createPending(
                $user,
                $mealPlan
            );

            /*
             * 2. Create our internal pending payment.
             */
            $payment = $paymentService->createPayment(
                $subscription,
                'paystack'
            );

            /*
             * 3. Initialize Paystack transaction.
             *
             * Payment amount is stored in KES.
             * PaystackService converts it to kobo/cents.
             */
            $response = $paystackService->initializeTransaction(
                email: $user->email,
                amount: (float) $payment->amount,
                reference: $payment->transaction_reference,
                callbackUrl: route('paystack.callback')
            );

            /*
             * 4. Store Paystack initialization response.
             */
            $payment->update([
                'provider_response' => json_encode($response),
            ]);

            Log::info('Paystack transaction initialized', [
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'reference' => $payment->transaction_reference,
            ]);

            /*
             * 5. Give the frontend the Paystack checkout URL.
             */
            return response()->json([
                'success' => true,
                'message' => 'Payment initialized successfully.',
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'reference' => $payment->transaction_reference,
                'authorization_url' =>
                    $response['authorization_url'] ?? null,
            ]);

        } catch (Throwable $e) {

            /*
             * Paystack initialization failed after we created
             * the subscription/payment.
             *
             * Preserve the audit trail but invalidate this attempt.
             */
            if ($payment) {
                try {
                    $paymentService->markFailed($payment);
                } catch (Throwable $paymentException) {
                    Log::error(
                        'Failed to mark Paystack payment as failed',
                        [
                            'payment_id' => $payment->id,
                            'error' =>
                                $paymentException->getMessage(),
                        ]
                    );
                }
            }

            if ($subscription) {
                try {
                    $subscription->update([
                        'status' => 'cancelled',
                    ]);
                } catch (Throwable $subscriptionException) {
                    Log::error(
                        'Failed to cancel subscription after Paystack failure',
                        [
                            'subscription_id' => $subscription->id,
                            'error' =>
                                $subscriptionException->getMessage(),
                        ]
                    );
                }
            }

            Log::error('Paystack checkout failed', [
                'user_id' => $user?->id,
                'meal_plan_id' => $mealPlan->id,
                'subscription_id' => $subscription?->id,
                'payment_id' => $payment?->id,
                'error' => $e->getMessage(),
            ]);

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
 * We NEVER trust the redirect alone.
 * The transaction is verified against Paystack.
 */
    public function callback(
            Request $request,
            PaystackService $paystackService,
            PaymentService $paymentService
        ) {
            $reference = $request->query('reference');

            if (!$reference) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Payment reference is missing. Please try again.');
            }

            try {
                /*
                * 1. Verify directly with Paystack.
                */
                $transaction = $paystackService->verifyTransaction($reference);

                /*
                * 2. Find our internal payment.
                */
                $payment = Payment::where(
                    'transaction_reference',
                    $reference
                )->first();

                if (!$payment) {
                    Log::error('Paystack payment not found', [
                        'reference' => $reference,
                    ]);

                    return redirect()
                        ->route('dashboard')
                        ->with(
                            'error',
                            'We could not find your payment record. Please contact support.'
                        );
                }

                /*
                * 3. Store the complete Paystack response.
                */
                $payment->update([
                    'provider_response' => json_encode($transaction),
                ]);

                /*
                * 4. Payment successful.
                */
                if (($transaction['status'] ?? null) === 'success') {

                    $paymentService->markSuccessful(
                        $payment,
                        $transaction['reference'] ?? $reference
                    );

                    Log::info('Paystack payment successfully completed', [
                        'payment_id' => $payment->id,
                        'subscription_id' => $payment->subscription_id,
                        'reference' => $reference,
                    ]);

                    return redirect()
                        ->route('dashboard')
                        ->with(
                            'success',
                            'Payment successful! Your Silver Spoon subscription is now active.'
                        );
                }

                /*
                * 5. Payment was cancelled, abandoned,
                *    declined, or otherwise unsuccessful.
                *
                * This attempt must NOT lock the user out
                * from subscribing again.
                */
                $paymentService->markFailed($payment);

                if ($payment->subscription) {
                    $payment->subscription->update([
                        'status' => 'cancelled',
                    ]);
                }

                Log::info('Paystack payment was not completed', [
                    'payment_id' => $payment->id,
                    'subscription_id' => $payment->subscription_id,
                    'reference' => $reference,
                    'status' => $transaction['status'] ?? null,
                ]);

                return redirect()
                    ->route('dashboard')
                    ->with(
                        'error',
                        'Payment was not completed. You can try subscribing again.'
                    );

            } catch (Throwable $e) {

                Log::error('Paystack callback processing failed', [
                    'reference' => $reference,
                    'error' => $e->getMessage(),
                ]);

                /*
                * If we can identify the payment, invalidate
                * the associated subscription attempt.
                */
                try {
                    $payment = Payment::where(
                        'transaction_reference',
                        $reference
                    )->first();

                    if ($payment) {

                        $paymentService->markFailed($payment);

                        if ($payment->subscription) {
                            $payment->subscription->update([
                                'status' => SubscriptionStatus::CANCELLED,
                            ]);
                        }
                    }

                } catch (Throwable $cleanupException) {

                    Log::error('Failed to clean up Paystack payment attempt', [
                        'reference' => $reference,
                        'error' => $cleanupException->getMessage(),
                    ]);
                }

                return redirect()
                    ->route('dashboard')
                    ->with(
                        'error',
                        'We could not verify your payment. No subscription was activated. Please try again.'
                    );
            }
        }
}