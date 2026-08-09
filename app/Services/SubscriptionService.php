<?php

namespace App\Services;

use App\Models\MealPlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubscriptionService
{
    /**
     * Create a pending subscription for a user.
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
            // Prevent multiple active/pending subscriptions
            // for the same plan.
            $existing = $user->subscriptions()
                ->whereIn('status', ['pending', 'active'])
                ->first();

            if ($existing) {
                throw new RuntimeException(
                    'You already have an active or pending subscription.'
                );
            }

            return $user->subscriptions()->create([
                'meal_plan_id' => $mealPlan->id,
                'starts_at' => now(),
                'ends_at' => now()->addDays(
                    $mealPlan->duration_days
                ),
                'status' => 'pending',
            ]);
        });
    }
}