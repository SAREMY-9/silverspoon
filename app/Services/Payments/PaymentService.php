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
     * Create a pending payment for a subscription.
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

        if ($subscription->payments()
            ->where('status', PaymentStatus::SUCCESSFUL)
            ->exists()) {
            throw new RuntimeException(
                'This subscription has already been paid for.'
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
     * Mark a payment as successful and activate the subscription.
     */
    public function markSuccessful(
        Payment $payment,
        ?string $providerReference = null
    ): Payment {
        return DB::transaction(function () use (
            $payment,
            $providerReference
        ) {
            // Idempotency: don't process the same successful payment twice.
            if ($payment->status === PaymentStatus::SUCCESSFUL) {
                return $payment;
            }


            $payment->update([
                'status' => PaymentStatus::SUCCESSFUL,
                'paid_at' => now(),
                'payment_reference' => $providerReference,
            ]);


            $subscription = $payment->subscription()->lockForUpdate()->first();

            if (!$subscription) {
                throw new RuntimeException(
                    'Payment does not have a valid subscription.'
                );
            }

            if ($subscription->status !== SubscriptionStatus::ACTIVE) {
                $subscription->update([
                    'status' => SubscriptionStatus::ACTIVE,
                ]);
            }

            $this->createEntitlements($subscription);

            return $payment->fresh([
                'subscription',
                'subscription.entitlements',
            ]);
        });
    }

    /**
     * Mark a payment as failed.
     */
    public function markFailed(Payment $payment): Payment
    {
        if ($payment->status === PaymentStatus::SUCCESSFUL) {
            throw new RuntimeException(
                'A successful payment cannot be marked as failed.'
            );
        }

        $payment->update([
            'status' => PaymentStatus::FAILED,
        ]);

        return $payment->fresh();
    }

    /**
     * Generate the meal entitlements belonging to a subscription.
     */
    protected function createEntitlements(
            Subscription $subscription
        ): void {
            $subscription->loadMissing(
                'mealPlan',
                'entitlements'
            );

            // Idempotency protection.
            if ($subscription->entitlements()->exists()) {
                return;
            }

            $mealPlan = $subscription->mealPlan;

            $start = $subscription->starts_at->copy()->startOfDay();
            $end = $subscription->ends_at->copy()->startOfDay();

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
                            "No {$mealType} meal configured for {$date->format('l')}."
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
            $reference = 'SS-' . strtoupper(Str::random(12));
        } while (
            Payment::where(
                'transaction_reference',
                $reference
            )->exists()
        );

        return $reference;
    }
}