<?php

namespace Database\Seeders;

use App\Models\MealPlan;
use Illuminate\Database\Seeder;

class MealPlanSeeder extends Seeder
{
    public function run(): void
    {
        MealPlan::create([
            'name' => 'Basic Plan',
            'description' => 'A simple meal plan for students who need affordable daily meals.',
            'price' => 2500.00,
            'meal_limit' => 20,
            'duration_days' => 30,
            'is_active' => true,
        ]);

        MealPlan::create([
            'name' => 'Standard Plan',
            'description' => 'Our balanced monthly meal plan with more meal options.',
            'price' => 4000.00,
            'meal_limit' => 30,
            'duration_days' => 30,
            'is_active' => true,
        ]);

        MealPlan::create([
            'name' => 'Premium Plan',
            'description' => 'The complete Silver Spoon meal experience.',
            'price' => 6000.00,
            'meal_limit' => 60,
            'duration_days' => 30,
            'is_active' => true,
        ]);
    }
}