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
    protected int $paymentAttemptLifetime = 5;

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
                * 1. FIND EXISTING PENDING SUBSCRIPTION
                * ---------------------------------------------------------
                *
                * We only reuse a pending subscription for the SAME
                * meal plan.
                *
                * An active subscription does not prevent a customer
                * from purchasing another subscription.
                */

                $pendingSubscription = $user->subscriptions()
                    ->where(
                        'status',
                        SubscriptionStatus::PENDING
                    )
                    ->where(
                        'meal_plan_id',
                        $mealPlan->id
                    )
                    ->latest()
                    ->lockForUpdate()
                    ->first();


                /*
                * ---------------------------------------------------------
                * 2. EXISTING PENDING SUBSCRIPTION
                * ---------------------------------------------------------
                */

                if ($pendingSubscription) {

                    /*
                    * Find all pending payment attempts.
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
                        * Payment attempt is still active.
                        */
                        if (now()->lt($expiresAt)) {

                            $hasLivePayment = true;

                            $remainingSeconds = now()
                                ->diffInSeconds($expiresAt);

                            $remainingMinutes = max(
                                1,
                                (int) ceil(
                                    $remainingSeconds / 60
                                )
                            );

                            break;
                        }

                        /*
                        * Local reservation expired.
                        *
                        * This does not necessarily mean the provider
                        * rejected the payment.
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
                    * No live payment.
                    *
                    * Reuse the pending subscription.
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
                * 3. NO PENDING SUBSCRIPTION
                * ---------------------------------------------------------
                *
                * Create a completely new checkout.
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