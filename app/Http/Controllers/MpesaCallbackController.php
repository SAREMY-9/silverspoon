<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class MpesaCallbackController extends Controller
{
    public function handle(
        Request $request,
        PaymentService $paymentService
    ) {
        $payload = $request->all();

        Log::info('M-Pesa callback received', [
            'payload' => $payload,
        ]);

        $callback = data_get(
            $payload,
            'Body.stkCallback'
        );

        if (!$callback) {
            Log::warning(
                'M-Pesa callback missing stkCallback',
                ['payload' => $payload]
            );

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted',
            ]);
        }

        $checkoutRequestId =
            $callback['CheckoutRequestID'] ?? null;

        if (!$checkoutRequestId) {
            Log::warning(
                'M-Pesa callback missing CheckoutRequestID',
                ['callback' => $callback]
            );

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted',
            ]);
        }

        $payment = Payment::where(
            'checkout_request_id',
            $checkoutRequestId
        )->first();

        if (!$payment) {
            Log::error(
                'No payment found for M-Pesa callback',
                [
                    'checkout_request_id' =>
                        $checkoutRequestId,
                ]
            );

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted',
            ]);
        }

        /*
         * Store the complete callback response.
         */
        $payment->update([
            'provider_response' => json_encode(
                $payload
            ),
        ]);

        $resultCode = $callback['ResultCode'] ?? null;

        /*
         * ResultCode 0 = successful payment.
         */
        if ((int) $resultCode === 0) {
            try {
                $metadata = collect(
                    $callback['CallbackMetadata']['Item'] ?? []
                );

                $mpesaReceipt =
                    $metadata
                        ->firstWhere('Name', 'MpesaReceiptNumber')
                        ['Value']
                    ?? null;

                $paymentService->markSuccessful(
                    $payment,
                    $mpesaReceipt
                );

                Log::info(
                    'M-Pesa payment successfully completed',
                    [
                        'payment_id' => $payment->id,
                        'subscription_id' =>
                            $payment->subscription_id,
                        'mpesa_receipt' =>
                            $mpesaReceipt,
                    ]
                );
            } catch (Throwable $e) {
                Log::error(
                    'Failed to process successful M-Pesa payment',
                    [
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        } else {
            $paymentService->markFailed($payment);

            Log::info(
                'M-Pesa payment failed',
                [
                    'payment_id' => $payment->id,
                    'result_code' => $resultCode,
                    'result_desc' =>
                        $callback['ResultDesc'] ?? null,
                ]
            );
        }

        /*
         * Safaricom expects a successful HTTP response.
         */
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }
}