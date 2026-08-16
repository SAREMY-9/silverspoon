<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Services\MealCustomizationService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class MealCustomizationController extends Controller
{
    public function create(MealPlan $mealPlan)
    {
        abort_unless($mealPlan->is_active, 404);

        $meals = $mealPlan->meals()
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderByRaw("
                CASE meal_type
                    WHEN 'breakfast' THEN 1
                    WHEN 'lunch' THEN 2
                    WHEN 'supper' THEN 3
                    ELSE 4
                END
            ")
            ->get();

        return view(
            'meal-plans.customize',
            compact(
                'mealPlan',
                'meals'
            )
        );
    }

    
    public function store(Request $request,MealPlan $mealPlan,SubscriptionService $subscriptionService,MealCustomizationService $customizationService
        ) {
            abort_unless($mealPlan->is_active, 404);

            $validated = $request->validate([
                'meal_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'meal_ids.*' => [
                    'integer',
                    'distinct',
                    'exists:meals,id',
                ],
            ]);

            $allowedMealIds = $mealPlan->meals()
                ->where('is_active', true)
                ->pluck('id');

            $selectedMealIds = collect($validated['meal_ids']);

            if ($selectedMealIds->diff($allowedMealIds)->isNotEmpty()) {
                return back()
                    ->withErrors([
                        'meal_ids' => 'One or more selected meals are not available for this meal plan.',
                    ])
                    ->withInput();
            }

            $subscription = $subscriptionService->createPending(
                $request->user(),
                $mealPlan
            );

            $customizationService->replaceSelections(
                $subscription,
                $selectedMealIds->values()->all()
            );

            return redirect()->route(
                'checkout.show',
                $mealPlan
            );
        }
}