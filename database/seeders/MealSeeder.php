<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\MealPlan;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    public function run(): void
    {
        $plans = MealPlan::all();

        foreach ($plans as $plan) {

            /*
             * Each plan has its own menu.
             *
             * 1 = Monday
             * 2 = Tuesday
             * ...
             * 7 = Sunday
             */

            $menus = match ($plan->name) {

                'Basic Plan' => [

                    1 => [
                        'breakfast' => 'Tea, Bread & Eggs',
                        'lunch'     => 'Ugali & Beef',
                        'supper'    => 'Rice & Beans',
                    ],

                    2 => [
                        'breakfast' => 'Porridge & Mandazi',
                        'lunch'     => 'Rice & Beef Stew',
                        'supper'    => 'Ugali & Sukuma Wiki',
                    ],

                    3 => [
                        'breakfast' => 'Tea, Bread & Sausage',
                        'lunch'     => 'Ugali & Chicken',
                        'supper'    => 'Chapati & Beans',
                    ],

                    4 => [
                        'breakfast' => 'Porridge & Bread',
                        'lunch'     => 'Rice & Beans',
                        'supper'    => 'Ugali & Beef',
                    ],

                    5 => [
                        'breakfast' => 'Tea, Bread & Eggs',
                        'lunch'     => 'Ugali & Fish',
                        'supper'    => 'Rice & Chicken',
                    ],

                    6 => [
                        'breakfast' => 'Tea & Mandazi',
                        'lunch'     => 'Pilau & Beef',
                        'supper'    => 'Ugali & Sukuma Wiki',
                    ],

                    7 => [
                        'breakfast' => 'Pancakes & Tea',
                        'lunch'     => 'Rice & Chicken',
                        'supper'    => 'Chapati & Beef Stew',
                    ],
                ],

                'Premium Plan' => [

                    1 => [
                        'breakfast' => 'Tea, Eggs & Toast',
                        'lunch'     => 'Beef Pilau & Vegetables',
                        'supper'    => 'Chicken Rice & Salad',
                    ],

                    2 => [
                        'breakfast' => 'Pancakes, Eggs & Fruit',
                        'lunch'     => 'Chicken & Chapati',
                        'supper'    => 'Beef Stew & Rice',
                    ],

                    3 => [
                        'breakfast' => 'Porridge, Eggs & Toast',
                        'lunch'     => 'Beef & Ugali',
                        'supper'    => 'Chicken & Potatoes',
                    ],

                    4 => [
                        'breakfast' => 'Tea, Sausage & Bread',
                        'lunch'     => 'Chicken Pilau',
                        'supper'    => 'Beef & Chapati',
                    ],

                    5 => [
                        'breakfast' => 'Pancakes & Eggs',
                        'lunch'     => 'Fish, Ugali & Vegetables',
                        'supper'    => 'Chicken Rice & Salad',
                    ],

                    6 => [
                        'breakfast' => 'Tea, Eggs & Toast',
                        'lunch'     => 'Beef Pilau',
                        'supper'    => 'Chicken & Chapati',
                    ],

                    7 => [
                        'breakfast' => 'Pancakes, Eggs & Fruit',
                        'lunch'     => 'Chicken & Rice',
                        'supper'    => 'Beef Stew & Potatoes',
                    ],
                ],

                'Executive Plan' => [

                    1 => [
                        'breakfast' => 'Full English Breakfast',
                        'lunch'     => 'Premium Beef Steak & Rice',
                        'supper'    => 'Grilled Chicken, Potatoes & Salad',
                    ],

                    2 => [
                        'breakfast' => 'Pancakes, Eggs, Sausage & Fruit',
                        'lunch'     => 'Chicken Biryani & Salad',
                        'supper'    => 'Beef Steak & Chapati',
                    ],

                    3 => [
                        'breakfast' => 'Omelette, Toast & Fresh Juice',
                        'lunch'     => 'Grilled Fish, Rice & Vegetables',
                        'supper'    => 'Chicken Curry & Chapati',
                    ],

                    4 => [
                        'breakfast' => 'French Toast, Eggs & Fruit',
                        'lunch'     => 'Premium Beef Pilau & Salad',
                        'supper'    => 'Grilled Chicken & Potatoes',
                    ],

                    5 => [
                        'breakfast' => 'Pancakes, Sausage & Fresh Juice',
                        'lunch'     => 'Fish Fillet, Rice & Vegetables',
                        'supper'    => 'Beef Steak & Chapati',
                    ],

                    6 => [
                        'breakfast' => 'Full Breakfast & Fresh Juice',
                        'lunch'     => 'Chicken Biryani & Salad',
                        'supper'    => 'Beef Curry, Rice & Vegetables',
                    ],

                    7 => [
                        'breakfast' => 'Pancakes, Eggs & Fruit',
                        'lunch'     => 'Premium Chicken & Rice',
                        'supper'    => 'Grilled Beef, Potatoes & Salad',
                    ],
                ],

                default => [],
            };

            foreach ($menus as $dayOfWeek => $dayMeals) {

                foreach ($dayMeals as $mealType => $mealName) {

                    Meal::create([
                        'meal_plan_id' => $plan->id,
                        'name' => $mealName,
                        'description' => "Scheduled {$mealType} for {$plan->name}.",
                        'image' => null,
                        'day_of_week' => $dayOfWeek,
                        'meal_type' => $mealType,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}