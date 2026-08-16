<?php

namespace App\Services;

use App\Models\Meal;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class MealCustomizationService
{
    /**
     * Replace the customer's custom meal selections.
     */
    public function replaceSelections(
        Subscription $subscription,
        array $mealIds
    ): Subscription {
        if ($subscription->status->value !== 'pending') {
            throw new RuntimeException(
                'Meal customization is only available before payment.'
            );
        }

        $mealIds = array_values(array_unique(
            array_map('intval', $mealIds)
        ));

        if (empty($mealIds)) {
            throw ValidationException::withMessages([
                'meal_ids' => 'Please select at least one meal.',
            ]);
        }

        $meals = Meal::query()
            ->whereIn('id', $mealIds)
            ->where('meal_plan_id', $subscription->meal_plan_id)
            ->where('is_active', true)
            ->get();

        if ($meals->count() !== count($mealIds)) {
            throw ValidationException::withMessages([
                'meal_ids' =>
                    'One or more selected meals are invalid.',
            ]);
        }

        foreach ($meals as $meal) {
            if ($meal->price === null) {
                throw ValidationException::withMessages([
                    'meal_ids' =>
                        "The meal \"{$meal->name}\" does not have a price configured yet.",
                ]);
            }
        }

        return DB::transaction(function () use (
            $subscription,
            $meals
        ) {
            $subscription
                ->mealSelections()
                ->delete();

            foreach ($meals as $meal) {
                $subscription->mealSelections()->create([
                    'meal_id' => $meal->id,
                    'day_of_week' => $meal->day_of_week,
                    'meal_type' => $meal->meal_type,
                    'unit_price' => $meal->price,
                ]);
            }

            return $subscription->fresh([
                'mealPlan',
                'mealSelections.meal',
            ]);
        });
    }

    /**
 * Calculate the total for a customized subscription.
 *
 * Model B:
 * The customer's selections define a recurring weekly schedule.
 *
 * Example:
 *
 * Monday supper    × 4 weeks
 * Tuesday supper   × 4 weeks
 * Wednesday supper × 4 weeks
 *
 * The total is therefore based on actual selected meal occurrences,
 * not the meal plan's flat price.
 */
    public function calculateTotal(
            Subscription $subscription
        ): string {
            $subscription->loadMissing([
                'mealPlan',
                'mealSelections',
            ]);

            /*
            * No customization = use the normal meal plan price.
            */
            if ($subscription->mealSelections->isEmpty()) {
                return number_format(
                    $subscription->mealPlan->price,
                    2,
                    '.',
                    ''
                );
            }

            /*
            * Model B uses complete recurring weeks.
            *
            * Example:
            * 30-day plan = 4 recurring weeks.
            * 7-day plan  = 1 recurring week.
            */
            $weeks = intdiv(
                $subscription->mealPlan->duration_days,
                7
            );

            /*
            * Always allow at least one week.
            */
            $weeks = max(1, $weeks);

            $total = 0;

            foreach ($subscription->mealSelections as $selection) {
                $total +=
                    (float) $selection->unit_price
                    * $weeks;
            }

            return number_format(
                $total,
                2,
                '.',
                ''
            );
        }
}
