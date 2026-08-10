<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealPlan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $mealPlans = MealPlan::query()
            ->where('is_active', true)
            ->withCount([
                'meals' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderBy('price')
            ->get();

        $featuredMeals = Meal::query()
            ->where('is_active', true)
            ->whereHas('mealPlan', function ($query) {
                $query->where('is_active', true);
            })
            ->with('mealPlan')
            ->orderBy('day_of_week')
            ->orderByRaw("
                CASE meal_type
                    WHEN 'breakfast' THEN 1
                    WHEN 'lunch' THEN 2
                    WHEN 'supper' THEN 3
                    ELSE 4
                END
            ")
            ->take(6)
            ->get();

        return view('home', compact(
            'mealPlans',
            'featuredMeals'
        ));
    }
}