<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;

class MealPlanController extends Controller
{
    public function index()
    {
        $mealPlans = MealPlan::query()
            ->where('is_active', true)
            ->with([
                'meals' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('day_of_week')
                        ->orderByRaw("
                            CASE meal_type
                                WHEN 'breakfast' THEN 1
                                WHEN 'lunch' THEN 2
                                WHEN 'supper' THEN 3
                                ELSE 4
                            END
                        ");
                }
            ])
            ->get();

        return view('meal-plans.index', compact('mealPlans'));
    }

    public function show(MealPlan $mealPlan)
    {
        abort_unless($mealPlan->is_active, 404);

        $mealPlan->load([
            'meals' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('day_of_week')
                    ->orderByRaw("
                        CASE meal_type
                            WHEN 'breakfast' THEN 1
                            WHEN 'lunch' THEN 2
                            WHEN 'supper' THEN 3
                            ELSE 4
                        END
                    ");
            }
        ]);

        return view('meal-plans.show', compact('mealPlan'));
    }
}