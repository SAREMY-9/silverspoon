<?php

namespace App\Services\Payments;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use App\Services\MealCustomizationService;

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
            string $provider = 'mpesa',
            ?MealCustomizationService $customizationService = null
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



        $amount = $subscription->mealSelections()->exists()
            ? app(MealCustomizationService::class)
                ->calculateTotal($subscription)
            : $subscription->mealPlan->price;

        if ((float) $amount <= 0) {
            throw new RuntimeException(
                'The subscription total must be greater than zero.'
            );
        }


        return Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'amount' => $amount,
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
                'entitlements',
                'mealSelections.meal'
            );

            
        /*
        * ---------------------------------------------------------
        * CUSTOM SUBSCRIPTION
        * ---------------------------------------------------------
        *
        * For custom subscriptions:
        *
        * The customer's selected meals define the weekly
        * recurring schedule.
        *
        * Example:
        *
        * Monday supper
        * Tuesday breakfast
        * Tuesday supper
        * Wednesday lunch
        *
        * A 7-day plan:
        *   1 occurrence of each selection
        *
        * A 30-day plan:
        *   4 occurrences of each selection
        *
        * A 90-day plan:
        *   12 occurrences of each selection
        *
        * The number of occurrences is therefore determined
        * by the meal plan duration.
        */

        $customSelections = $subscription
            ->mealSelections()
            ->get();

        if ($customSelections->isNotEmpty()) {

            /*
            * Number of complete recurring weeks.
            *
            * 7 days  = 1 week
            * 30 days = 4 weeks
            * 90 days = 12 weeks
            */
            $occurrences = max(
                1,
                intdiv(
                    $subscription->mealPlan->duration_days,
                    7
                )
            );

            /*
            * Start scheduling from today.
            */
            $start = now()
                ->copy()
                ->startOfDay();

            /*
            * Store all generated delivery dates.
            */
            $deliveryDates = collect();

            /*
            * -----------------------------------------------------
            * GENERATE OCCURRENCES
            * -----------------------------------------------------
            *
            * Each selected meal is repeated once per week
            * for the duration of the plan.
            */
            foreach ($customSelections as $selection) {

                $dayOfWeek = (int) $selection->day_of_week;

                /*
                * Find the next occurrence of the selected weekday.
                */
                $date = $start->copy();

                while ($date->dayOfWeekIso !== $dayOfWeek) {
                    $date->addDay();
                }

                /*
                * Repeat the selected meal according to the
                * number of weeks in the meal plan.
                */
                for ($i = 0; $i < $occurrences; $i++) {

                    $deliveryDate = $date
                        ->copy()
                        ->addWeeks($i);

                    $deliveryDates->push([
                        'meal_id' => $selection->meal_id,
                        'scheduled_for' => $deliveryDate->toDateString(),
                        'status' => 'available',
                        'expires_at' => $deliveryDate
                            ->copy()
                            ->endOfDay(),
                    ]);
                }
            }

            /*
            * Sort deliveries chronologically.
            */
            $deliveryDates = $deliveryDates
                ->sortBy('scheduled_for')
                ->values();

            /*
            * Make sure we actually generated deliveries.
            */
            $firstDelivery = $deliveryDates->first();
            $lastDelivery = $deliveryDates->last();

            if (!$firstDelivery || !$lastDelivery) {
                throw new RuntimeException(
                    'No custom meal deliveries could be generated.'
                );
            }

            /*
            * Subscription dates follow the generated custom
            * delivery schedule.
            */
            $subscription->update([
                'starts_at' => \Carbon\Carbon::parse(
                    $firstDelivery['scheduled_for']
                )->startOfDay(),

                'ends_at' => \Carbon\Carbon::parse(
                    $lastDelivery['scheduled_for']
                )->endOfDay(),

                'status' => SubscriptionStatus::ACTIVE,
            ]);

            /*
            * Idempotency protection.
            *
            * Never create duplicate entitlements if this payment
            * callback is received more than once.
            */
            if ($subscription->entitlements()->exists()) {
                return;
            }

            /*
            * Create the actual meal delivery entitlements.
            */
            foreach ($deliveryDates as $delivery) {

                $subscription->entitlements()->create($delivery);
            }

            return;
        }
            /*
            * ---------------------------------------------------------
            * STANDARD MEAL PLAN
            * ---------------------------------------------------------
            *
            * Only normal subscriptions use the meal plan duration.
            */

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
                    return $meal->day_of_week
                        . ':' . $meal->meal_type;
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