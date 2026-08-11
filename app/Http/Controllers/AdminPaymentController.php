<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\Payments\PaystackService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class AdminPaymentController extends Controller
{
    /**
     * Ensure the authenticated user is an administrator.
     */
    protected function ensureAdmin(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->role === 'admin',
            403,
            'You are not authorized to manage payments.'
        );
    }

    /**
     * Payment dashboard / payment list.
     */
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $query = Payment::query()
            ->with([
                'user:id,name,email,phone',
                'subscription.mealPlan:id,name',
            ]);

        /*
         * ---------------------------------------------------------
         * SEARCH
         * ---------------------------------------------------------
         */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'transaction_reference',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'payment_reference',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'checkout_request_id',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'merchant_request_id',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        /*
         * ---------------------------------------------------------
         * STATUS FILTER
         * ---------------------------------------------------------
         */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
         * ---------------------------------------------------------
         * PROVIDER FILTER
         * ---------------------------------------------------------
         */

        if ($request->filled('provider')) {
            $query->where(
                'provider',
                $request->input('provider')
            );
        }

        /*
         * ---------------------------------------------------------
         * DATE RANGE
         * ---------------------------------------------------------
         */

        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->input('date_from')
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->input('date_to')
            );
        }

        /*
         * ---------------------------------------------------------
         * PAGINATION
         * ---------------------------------------------------------
         */

        $payments = $query
            ->latest()
            ->paginate(25)
            ->withQueryString();

        /*
         * ---------------------------------------------------------
         * DASHBOARD TOTALS
         * ---------------------------------------------------------
         */

        $totalPayments = Payment::count();

        $successfulPayments = Payment::where(
            'status',
            PaymentStatus::SUCCESSFUL
        )->count();

        $pendingPayments = Payment::where(
            'status',
            PaymentStatus::PENDING
        )->count();

        $failedPayments = Payment::where(
            'status',
            PaymentStatus::FAILED
        )->count();

        $refundedPayments = Payment::where(
            'status',
            PaymentStatus::REFUNDED
        )->count();

        /*
         * Successful revenue.
         */
        $successfulAmount = Payment::where(
            'status',
            PaymentStatus::SUCCESSFUL
        )->sum('amount');

        /*
         * Providers available in the database.
         */
        $providers = Payment::query()
            ->whereNotNull('provider')
            ->where('provider', '!=', '')
            ->distinct()
            ->orderBy('provider')
            ->pluck('provider');

        return view('admin.payments.index', [
            'payments' => $payments,

            'totalPayments' => $totalPayments,
            'successfulPayments' => $successfulPayments,
            'pendingPayments' => $pendingPayments,
            'failedPayments' => $failedPayments,
            'refundedPayments' => $refundedPayments,
            'successfulAmount' => $successfulAmount,

            'providers' => $providers,
        ]);
    }

    /**
     * Payment details.
     */
    public function show(
        Request $request,
        Payment $payment
    ): View {
        $this->ensureAdmin($request);

        $payment->load([
            'user',
            'subscription.mealPlan',
            'subscription.entitlements.meal',
        ]);

        $providerResponse = null;

        if ($payment->provider_response) {
            try {
                $providerResponse = json_decode(
                    $payment->provider_response,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            } catch (Throwable) {
                /*
                 * Keep malformed provider responses visible
                 * rather than crashing the admin page.
                 */
                $providerResponse = [
                    'raw_response' => $payment->provider_response,
                ];
            }
        }

        return view('admin.payments.show', [
            'payment' => $payment,
            'providerResponse' => $providerResponse,
        ]);
    }

    /**
     * Retry verification against the payment provider.
     *
     * IMPORTANT:
     *
     * This does not manually mark a payment successful.
     * The provider must confirm success.
     */
    public function verify(
        Request $request,
        Payment $payment,
        PaymentService $paymentService,
        PaystackService $paystackService
    ): RedirectResponse {
        $this->ensureAdmin($request);

        /*
         * Never re-verify an already successful payment.
         */
        if ($payment->status === PaymentStatus::SUCCESSFUL) {
            return back()->with(
                'info',
                'This payment is already marked as successful.'
            );
        }

        try {

            /*
             * -----------------------------------------------------
             * PAYSTACK
             * -----------------------------------------------------
             */

            if ($payment->provider === 'paystack') {

                if (!$payment->transaction_reference) {
                    return back()->with(
                        'error',
                        'This payment has no Paystack transaction reference.'
                    );
                }

                $transaction =
                    $paystackService->verifyTransaction(
                        $payment->transaction_reference
                    );

                /*
                 * Always preserve the provider response.
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

                /*
                 * Provider confirmed success.
                 */
                if ($status === 'success') {

                    $paymentService->markSuccessful(
                        $payment,
                        $transaction['reference']
                            ?? $payment->transaction_reference
                    );

                    return back()->with(
                        'success',
                        'Paystack confirmed the payment as successful.'
                    );
                }

                /*
                 * Provider confirmed failure.
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

                    $paymentService->markFailed(
                        $payment
                    );

                    return back()->with(
                        'warning',
                        'Paystack confirmed that this payment was not completed.'
                    );
                }

                /*
                 * Unknown / pending provider state.
                 */
                return back()->with(
                    'info',
                    'Paystack returned a non-final status: ' .
                    ($status ?: 'unknown')
                );
            }

            /*
             * -----------------------------------------------------
             * M-PESA
             * -----------------------------------------------------
             *
             * We intentionally do NOT guess here.
             *
             * M-Pesa STK status verification should be implemented
             * through the existing Daraja service once its exact
             * service API is confirmed.
             */

            if ($payment->provider === 'mpesa') {

                return back()->with(
                    'info',
                    'M-Pesa manual verification is not yet connected to the Daraja query service. The callback remains the authoritative payment confirmation.'
                );
            }

            /*
             * -----------------------------------------------------
             * UNKNOWN PROVIDER
             * -----------------------------------------------------
             */

            return back()->with(
                'error',
                'There is no verification handler for provider: ' .
                ($payment->provider ?: 'unknown')
            );

        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Payment verification failed. The payment status was not manually changed.'
            );
        }
    }
}