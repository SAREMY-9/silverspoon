<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Services\Payments\MpesaService;
use App\Services\Payments\PaystackService;
use App\Services\Payments\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\SubscriptionStatus;
use Throwable;

class CheckoutController extends Controller
{
    public function initiate(
        Request $request,
        MealPlan $mealPlan,
        SubscriptionService $subscriptionService,
        PaymentService $paymentService,
        MpesaService $mpesaService,
        PaystackService $paystackService
    ): JsonResponse {
        $validated = $request->validate([
            'payment_method' => [
                'required',
                'string',
                'in:mpesa,paystack',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        $subscription = null;
        $payment = null;

        try {
            /*
             * 1. Create the pending subscription.
             */
            $subscription = $subscriptionService->createPending(
                $request->user(),
                $mealPlan
            );

            /*
             * 2. Create our internal pending payment.
             */
            $payment = $paymentService->createPayment(
                $subscription,
                $validated['payment_method']
            );

            /*
             * 3. Process payment using the selected provider.
             */
            if ($validated['payment_method'] === 'mpesa') {

                if (empty($validated['phone'])) {
                    throw new \RuntimeException(
                        'A phone number is required for M-Pesa payments.'
                    );
                }

                /*
                 * M-Pesa STK Push.
                 */
                $response = $mpesaService->stkPush(
                    phone: $validated['phone'],
                    amount: (float) $payment->amount,
                    accountReference: $payment->transaction_reference,
                    transactionDescription: 'Silver Spoon subscription'
                );

                /*
                 * Save M-Pesa identifiers.
                 */
                $payment->update([
                    'checkout_request_id' =>
                        $response['CheckoutRequestID'] ?? null,

                    'merchant_request_id' =>
                        $response['MerchantRequestID'] ?? null,

                    'phone' => $validated['phone'],

                    'provider_response' => json_encode(
                        $response
                    ),
                ]);

                Log::info('M-Pesa STK Push initiated', [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'checkout_request_id' =>
                        $response['CheckoutRequestID'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'provider' => 'mpesa',
                    'message' =>
                        'Payment request sent. Please enter your M-Pesa PIN.',
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'checkout_request_id' =>
                        $response['CheckoutRequestID'] ?? null,
                ]);
            }

            /*
             * Paystack Checkout.
             *
             * Paystack will handle the actual payment UI.
             */
            if ($validated['payment_method'] === 'paystack') {

                $user = $request->user();

                if (!$user->email) {
                    throw new \RuntimeException(
                        'A valid email address is required for Paystack payments.'
                    );
                }

                $response = $paystackService->initializeTransaction(
                    email: $user->email,
                    amount: (float) $payment->amount,
                    reference: $payment->transaction_reference,
                    callbackUrl: route('paystack.callback')
                );

                /*
                 * Save the Paystack initialization response.
                 */
                $payment->update([
                    'provider_response' => json_encode(
                        $response
                    ),
                ]);

                Log::info('Paystack transaction initialized', [
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'reference' =>
                        $payment->transaction_reference,
                ]);

                return response()->json([
                    'success' => true,
                    'provider' => 'paystack',
                    'message' =>
                        'Payment initialized successfully.',
                    'payment_id' => $payment->id,
                    'subscription_id' => $subscription->id,
                    'reference' =>
                        $payment->transaction_reference,
                    'authorization_url' =>
                        $response['authorization_url'] ?? null,
                ]);
            }

            throw new \RuntimeException(
                'Unsupported payment provider.'
            );

        } catch (Throwable $e) {

            /*
             * Payment initialization failed after we created
             * the subscription/payment. Preserve the audit trail.
             */
            if ($payment) {
                try {
                    $paymentService->markFailed($payment);
                } catch (Throwable $paymentException) {
                    Log::error(
                        'Failed to mark payment as failed',
                        [
                            'payment_id' => $payment->id,
                            'error' =>
                                $paymentException->getMessage(),
                        ]
                    );
                }
            }


            Log::error('Silver Spoon checkout failed', [
                'user_id' => $request->user()?->id,
                'meal_plan_id' => $mealPlan->id,
                'subscription_id' => $subscription?->id,
                'payment_id' => $payment?->id,
                'payment_method' =>
                    $validated['payment_method'] ?? null,
                'error' => $e->getMessage(),
            ]);

            $message = $e->getMessage();

            return response()->json([
                'success' => false,
                'message' => $message !== ''
                    ? $message
                    : 'Unable to initiate payment. Please try again.',
            ], 422);
            
        }
    }


    public function show(
            MealPlan $mealPlan,
            SubscriptionService $subscriptionService
        ) {
            if (!$mealPlan->is_active) {
                abort(404);
            }

            $mealPlan->load([
                'meals' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('day_of_week')
                        ->orderBy('meal_type');
                }
            ]);

            $subscription = auth()->user()
                ->subscriptions()
                ->where('status', \App\Enums\SubscriptionStatus::PENDING)
                ->latest()
                ->first();

            if ($subscription) {
                $subscription->load([
                    'mealSelections.meal'
                ]);
            }

            $isCustom = $subscription
                && $subscription->mealSelections->isNotEmpty();

            $customTotal = null;

            if ($isCustom) {
                $customTotal = app(
                    \App\Services\MealCustomizationService::class
                )->calculateTotal($subscription);
            }

            return view('checkout', compact(
                'mealPlan',
                'subscription',
                'isCustom',
                'customTotal'
            ));
        }
}