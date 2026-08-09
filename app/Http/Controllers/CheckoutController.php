<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Services\Payments\MpesaService;
use App\Services\Payments\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckoutController extends Controller
{
    public function initiate(
        Request $request,
        MealPlan $mealPlan,
        SubscriptionService $subscriptionService,
        PaymentService $paymentService,
        MpesaService $mpesaService
    ): JsonResponse {
        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
        ]);

        try {
            /*
             * 1. Create the subscription.
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
                'mpesa'
            );

            /*
             * 3. Ask M-Pesa to initiate STK Push.
             */
           $response = $mpesaService->stkPush(
                phone: $validated['phone'],
                amount: (float) $payment->amount,
                accountReference: $payment->transaction_reference,
                transactionDescription: 'Silver Spoon subscription'
            );

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

            /*
             * 4. Store the M-Pesa checkout identifier.
             *
             * We'll add proper columns for this shortly.
             */
            Log::info('M-Pesa STK Push initiated', [
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'checkout_request_id' =>
                    $response['CheckoutRequestID'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'Payment request sent. Please enter your M-Pesa PIN.',
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'checkout_request_id' =>
                    $response['CheckoutRequestID'] ?? null,
            ]);

        } catch (Throwable $e) {

            Log::error('Silver Spoon checkout failed', [
                'user_id' => $request->user()?->id,
                'meal_plan_id' => $mealPlan->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}

