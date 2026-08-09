<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\MealPlan;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    public function run(): void
    {
        $basic = MealPlan::where('name', 'Basic Plan')->first();
        $standard = MealPlan::where('name', 'Standard Plan')->first();
        $premium = MealPlan::where('name', 'Premium Plan')->first();

        Meal::create([
            'meal_plan_id' => $basic->id,
            'name' => 'Ugali & Beef',
            'description' => 'Ugali served with beef stew and vegetables.',
            'image' => null,
            'is_active' => true,
        ]);

        Meal::create([
            'meal_plan_id' => $basic->id,
            'name' => 'Rice & Beans',
            'description' => 'Rice served with beans and vegetables.',
            'image' => null,
            'is_active' => true,
        ]);

        Meal::create([
            'meal_plan_id' => $standard->id,
            'name' => 'Chicken & Rice',
            'description' => 'Seasoned chicken served with rice and vegetables.',
            'image' => null,
            'is_active' => true,
        ]);

        Meal::create([
            'meal_plan_id' => $standard->id,
            'name' => 'Beef Pilau',
            'description' => 'Kenyan-style beef pilau served with kachumbari.',
            'image' => null,
            'is_active' => true,
        ]);

        Meal::create([
            'meal_plan_id' => $premium->id,
            'name' => 'Grilled Chicken',
            'description' => 'Grilled chicken served with vegetables and potatoes.',
            'image' => null,
            'is_active' => true,
        ]);

        Meal::create([
            'meal_plan_id' => $premium->id,
            'name' => 'Beef Steak',
            'description' => 'Tender beef steak served with vegetables and potatoes.',
            'image' => null,
            'is_active' => true,
        ]);

        Meal::create([
            'meal_plan_id' => $premium->id,
            'name' => 'Chicken Pilau',
            'description' => 'Spiced chicken pilau served with kachumbari.',
            'image' => null,
            'is_active' => true,
        ]);
    }
}