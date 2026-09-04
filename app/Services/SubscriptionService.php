<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\MealPlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class SubscriptionService
{
    /**
     * How long a pending payment attempt reserves
     * the user's checkout slot.
     */
    protected int $paymentAttemptLifetime = 1;

    /**
     * Create or reuse a pending subscription.
     *
     * IMPORTANT:
     *
     * A failed/abandoned payment does NOT cancel the subscription.
     *
     * The subscription remains pending and can receive another
     * payment attempt.
     */
    public function createPending(
        User $user,
        MealPlan $mealPlan
    ): Subscription {
        if (!$mealPlan->is_active) {
            throw new RuntimeException(
                'This meal plan is currently unavailable.'
            );
        }

        return DB::transaction(function () use (
            $user,
            $mealPlan
        ) {

            /*
             * ---------------------------------------------------------
             * 1. ACTIVE SUBSCRIPTION
             * ---------------------------------------------------------
             */
            $activeSubscription = $user->subscriptions()
                ->where(
                    'status',
                    SubscriptionStatus::ACTIVE
                )
                ->lockForUpdate()
                ->first();

            if ($activeSubscription) {
                throw new RuntimeException(
                    'You already have an active subscription.'
                );
            }

            /*
             * ---------------------------------------------------------
             * 2. FIND EXISTING PENDING SUBSCRIPTION
             * ---------------------------------------------------------
             *
             * We reuse the pending subscription instead of creating
             * a new subscription every time the customer retries.
             */
            $pendingSubscription = $user->subscriptions()
                ->where(
                    'status',
                    SubscriptionStatus::PENDING
                )
                ->latest()
                ->lockForUpdate()
                ->first();

            /*
             * ---------------------------------------------------------
             * 3. EXISTING PENDING SUBSCRIPTION
             * ---------------------------------------------------------
             */
            if ($pendingSubscription) {

                /*
                 * Find ALL pending payments.
                 *
                 * There should normally only be one, but checking all
                 * protects us against duplicate attempts/race conditions.
                 */
                $pendingPayments = $pendingSubscription
                    ->payments()
                    ->where(
                        'status',
                        PaymentStatus::PENDING
                    )
                    ->lockForUpdate()
                    ->get();

                $hasLivePayment = false;

                foreach ($pendingPayments as $pendingPayment) {

                    $expiresAt = $pendingPayment
                        ->created_at
                        ->copy()
                        ->addMinutes(
                            $this->paymentAttemptLifetime
                        );

                    /*
                     * Still inside our local checkout reservation.
                     */
                    if (now()->lt($expiresAt)) {

                        $hasLivePayment = true;

                        $remainingSeconds = now()
                            ->diffInSeconds($expiresAt);

                        $remainingMinutes = max(
                            0,
                            (int) ceil(
                                $remainingSeconds / 60
                            )
                        );

                        break;
                    }

                    /*
                     * Our local reservation has expired.
                     *
                     * This does NOT necessarily mean Paystack
                     * failed the transaction.
                     *
                     * It only means Silver Spoon will no longer
                     * block a new checkout attempt because of it.
                     */
                    $pendingPayment->update([
                        'status' => PaymentStatus::FAILED,
                    ]);
                }

                /*
                 * A live payment still exists.
                 */
                if ($hasLivePayment) {
                    throw new RuntimeException(
                        'You already have a payment in progress. '
                        . 'Please complete it or wait '
                        . $remainingMinutes
                        . ' minute(s) before trying again.'
                    );
                }

                /*
                 * No payment is currently active.
                 *
                 * Reuse the existing pending subscription.
                 *
                 * Reset the subscription dates because this is now
                 * a fresh checkout attempt.
                 */
                
                $pendingSubscription->update([
                    'meal_plan_id' => $mealPlan->id,
                    'starts_at' => null,
                    'ends_at' => null,
                ]);

                return $pendingSubscription->fresh();
            }

            /*
             * ---------------------------------------------------------
             * 4. NO PENDING SUBSCRIPTION
             * ---------------------------------------------------------
             *
             * Create a completely new checkout/subscription.
             */
            return $user->subscriptions()->create([
                'meal_plan_id' => $mealPlan->id,

                'starts_at' => null,
                'ends_at' => null,

                'status' => SubscriptionStatus::PENDING,

                'access_code' => $this->generateAccessCode(),

                'qr_token' => (string) Str::uuid(),
            ]);

        });
    }

    /**
     * Generate a unique customer access code.
     */
    protected function generateAccessCode(): string
    {
        do {
            $code = 'SS-' . strtoupper(
                Str::random(10)
            );
        } while (
            Subscription::where(
                'access_code',
                $code
            )->exists()
        );

        return $code;
    }
}