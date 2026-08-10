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
            return response()->json([
                'success' => false,
                'message' => 'Payment reference is missing.',
            ], 400);
        }

        try {
            /*
             * Verify directly with Paystack.
             */
            $transaction =
                $paystackService->verifyTransaction($reference);

            /*
             * Find our internal payment.
             */
            $payment = Payment::where(
                'transaction_reference',
                $reference
            )->first();

            if (!$payment) {
                Log::error('Paystack payment not found', [
                    'reference' => $reference,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment record not found.',
                ], 404);
            }

            /*
             * Store the complete Paystack response.
             */
            $payment->update([
                'provider_response' =>
                    json_encode($transaction),
            ]);

            /*
             * Only mark successful when Paystack explicitly
             * says the transaction succeeded.
             */
            if (($transaction['status'] ?? null) === 'success') {

                $paymentService->markSuccessful(
                    $payment,
                    $transaction['reference'] ?? $reference
                );

                Log::info('Paystack payment successfully completed', [
                    'payment_id' => $payment->id,
                    'subscription_id' =>
                        $payment->subscription_id,
                    'reference' => $reference,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful.',
                    'reference' => $reference,
                ]);
            }

            /*
             * Transaction exists but wasn't successful.
             */
            $paymentService->markFailed($payment);

            return response()->json([
                'success' => false,
                'message' => 'Payment was not completed.',
                'status' => $transaction['status'] ?? null,
            ], 402);

        } catch (Throwable $e) {

            Log::error('Paystack callback processing failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Unable to verify payment. Please contact support.',
            ], 500);
        }
    }
}