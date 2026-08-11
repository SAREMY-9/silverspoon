<?php

namespace App\Services\Payments;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    /**
     * Create a pending payment for a pending subscription.
     *
     * There should only be one live pending payment attempt
     * for a subscription at a time.
     */
    public function createPayment(
        Subscription $subscription,
        string $provider = 'mpesa'
    ): Payment {
        if ($subscription->status !== SubscriptionStatus::PENDING) {
            throw new RuntimeException(
                'A payment can only be created for a pending subscription.'
            );
        }

        /*
         * Never create another payment if this subscription
         * already has a successful payment.
         */
        if (
            $subscription->payments()
                ->where('status', PaymentStatus::SUCCESSFUL)
                ->exists()
        ) {
            throw new RuntimeException(
                'This subscription has already been paid for.'
            );
        }

        /*
         * There must never be two simultaneous pending
         * payment attempts for the same subscription.
         */
        if (
            $subscription->payments()
                ->where('status', PaymentStatus::PENDING)
                ->exists()
        ) {
            throw new RuntimeException(
                'This subscription already has a payment in progress.'
            );
        }

        return Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->mealPlan->price,
            'currency' => 'KES',
            'provider' => $provider,
            'transaction_reference' => $this->generateReference(),
            'status' => PaymentStatus::PENDING,
        ]);
    }

    /**
     * Mark a payment as successful and activate its subscription.
     *
     * Provider confirmation is authoritative.
     *
     * This method is idempotent.
     */
    public function markSuccessful(
        Payment $payment,
        ?string $providerReference = null
    ): Payment {
        return DB::transaction(function () use (
            $payment,
            $providerReference
        ) {
            /*
             * Lock payment so duplicate callbacks/webhooks
             * cannot process it simultaneously.
             */
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                throw new RuntimeException(
                    'Payment record no longer exists.'
                );
            }

            /*
             * Already successful.
             *
             * This makes Paystack redirects/webhooks safely
             * repeatable.
             */
            if ($payment->status === PaymentStatus::SUCCESSFUL) {
                return $payment->fresh([
                    'subscription',
                    'subscription.entitlements',
                ]);
            }

            /*
             * IMPORTANT:
             *
             * A payment marked FAILED locally does not necessarily
             * mean Paystack failed it.
             *
             * For example:
             *
             * 10:00 - checkout started
             * 10:06 - our 5-minute reservation expires
             * 10:07 - Paystack confirms SUCCESS
             *
             * Provider confirmation must win.
             *
             * Therefore FAILED -> SUCCESSFUL is allowed here.
             */
            $subscription = Subscription::whereKey(
                $payment->subscription_id
            )
                ->lockForUpdate()
                ->first();

            if (!$subscription) {
                throw new RuntimeException(
                    'Payment does not have a valid subscription.'
                );
            }

            /*
             * A deliberately cancelled subscription should not
             * be resurrected by an old payment callback.
             *
             * Our checkout flow no longer automatically cancels
             * subscriptions, so this represents a genuine
             * cancellation elsewhere in the application.
             */
            if (
                $subscription->status ===
                SubscriptionStatus::CANCELLED
            ) {
                throw new RuntimeException(
                    'This subscription has been cancelled and cannot be activated.'
                );
            }

            /*
             * If another payment on this same subscription has
             * already succeeded, don't silently process a second
             * successful charge as the subscription payment.
             *
             * The current payment remains untouched so it can be
             * investigated/refunded rather than creating a false
             * financial state.
             */
            $anotherSuccessfulPayment = $subscription
                ->payments()
                ->where('status', PaymentStatus::SUCCESSFUL)
                ->where('id', '!=', $payment->id)
                ->exists();

            if ($anotherSuccessfulPayment) {
                throw new RuntimeException(
                    'Another payment has already successfully paid for this subscription.'
                );
            }

            /*
             * Record provider-confirmed payment success.
             */
            $payment->update([
                'status' => PaymentStatus::SUCCESSFUL,
                'paid_at' => $payment->paid_at ?? now(),
                'payment_reference' =>
                    $providerReference ?? $payment->payment_reference,
            ]);

            /*
             * Activate subscription.
             */
            if (
                $subscription->status !==
                SubscriptionStatus::ACTIVE
            ) {
                $startsAt = now();

                $subscription->update([
                    'status' => SubscriptionStatus::ACTIVE,
                    'starts_at' => $startsAt,
                    'ends_at' => $startsAt
                        ->copy()
                        ->addDays(
                            $subscription->mealPlan->duration_days - 1
                        )
                        ->endOfDay(),
                ]);
            }

            /*
             * Generate meal entitlements.
             *
             * This is idempotent.
             */
            $this->createEntitlements($subscription);

            return $payment->fresh([
                'subscription',
                'subscription.entitlements',
            ]);
        });
    }

    /**
     * Mark a payment as failed.
     *
     * Never downgrade a successful payment.
     *
     * This method is idempotent.
     */
    public function markFailed(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                throw new RuntimeException(
                    'Payment record no longer exists.'
                );
            }

            /*
             * Never downgrade a successful payment.
             */
            if ($payment->status === PaymentStatus::SUCCESSFUL) {
                return $payment;
            }

            /*
             * Already failed.
             */
            if ($payment->status === PaymentStatus::FAILED) {
                return $payment;
            }

            $payment->update([
                'status' => PaymentStatus::FAILED,
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Generate meal entitlements belonging to a subscription.
     */
    protected function createEntitlements(
        Subscription $subscription
    ): void {
        $subscription->loadMissing(
            'mealPlan',
            'entitlements'
        );



        $startsAt = now();
        $subscription->update([
            'starts_at' => $startsAt,

            'ends_at' => $startsAt
                ->copy()
                ->addDays(
                    $subscription->mealPlan->duration_days - 1
                )
                ->endOfDay(),

            'status' => SubscriptionStatus::ACTIVE,
        ]);


        /*
         * Idempotency protection.
         */
        if ($subscription->entitlements()->exists()) {
            return;
        }

        $mealPlan = $subscription->mealPlan;

        if (!$mealPlan) {
            throw new RuntimeException(
                'Subscription does not have a valid meal plan.'
            );
        }

        if (!$subscription->starts_at || !$subscription->ends_at) {
            throw new RuntimeException(
                'Subscription dates are not configured.'
            );
        }

        $start = $subscription->starts_at
            ->copy()
            ->startOfDay();

        $end = $subscription->ends_at
            ->copy()
            ->startOfDay();

        $meals = $mealPlan->meals()
            ->where('is_active', true)
            ->get()
            ->keyBy(function ($meal) {
                return $meal->day_of_week . ':' . $meal->meal_type;
            });

        for (
            $date = $start->copy();
            $date->lte($end);
            $date->addDay()
        ) {
            $dayOfWeek = $date->dayOfWeekIso;

            foreach ([
                'breakfast',
                'lunch',
                'supper',
            ] as $mealType) {

                $key = $dayOfWeek . ':' . $mealType;

                $meal = $meals->get($key);

                if (!$meal) {
                    throw new RuntimeException(
                        "No {$mealType} meal configured for "
                        . "{$date->format('l')}."
                    );
                }

                $subscription->entitlements()->create([
                    'meal_id' => $meal->id,
                    'scheduled_for' => $date->toDateString(),
                    'status' => 'available',
                    'expires_at' => $date->copy()->endOfDay(),
                ]);
            }
        }
    }

    /**
     * Generate an internal payment reference.
     */
    protected function generateReference(): string
    {
        do {
            $reference = 'SS-' . strtoupper(
                Str::random(12)
            );
        } while (
            Payment::where(
                'transaction_reference',
                $reference
            )->exists()
        );

        return $reference;
    }
}