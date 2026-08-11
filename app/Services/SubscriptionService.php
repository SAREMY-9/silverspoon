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
     * Create a pending subscription for a user.
     *
     * An active subscription always blocks a new subscription.
     *
     * A previous pending subscription is considered an abandoned
     * checkout attempt and is cancelled before creating a new one.
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

            return DB::transaction(function () use ($user, $mealPlan) {

                /*
                * Never allow multiple active subscriptions.
                */
                $activeSubscription = $user->subscriptions()
                    ->where('status', 'active')
                    ->first();

                if ($activeSubscription) {
                    throw new RuntimeException(
                        'You already have an active subscription.'
                    );
                }

                /*
                * Look for an existing pending subscription.
                */
                $pendingSubscription = $user->subscriptions()
                    ->where('status', 'pending')
                    ->latest()
                    ->first();

                if ($pendingSubscription) {

                    /*
                    * Does this subscription still have a
                    * payment waiting to be completed?
                    */
                    $hasPendingPayment = $pendingSubscription
                        ->payments()
                        ->where('status', 'pending')
                        ->exists();

                    if ($hasPendingPayment) {
                        throw new RuntimeException(
                            'You already have a payment in progress. Please complete it or wait for it to expire.'
                        );
                    }

                    /*
                    * No pending payment remains.
                    *
                    * This is an abandoned/incomplete subscription
                    * attempt, so cancel it and allow a fresh attempt.
                    */
                    $pendingSubscription->update([
                        'status' => 'cancelled',
                    ]);
                }

                /*
                * Create a completely new subscription attempt.
                */
                return $user->subscriptions()->create([
                    'meal_plan_id' => $mealPlan->id,
                    'starts_at' => now(),
                    'ends_at' => now()
                        ->addDays($mealPlan->duration_days - 1)
                        ->endOfDay(),
                    'status' => 'pending',
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