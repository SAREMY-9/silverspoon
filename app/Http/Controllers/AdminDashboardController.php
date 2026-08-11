<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Meal;
use App\Models\MealPlan;
use App\Models\Subscription;
use App\Enums\SubscriptionStatus;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalStaff = User::where('role', 'staff')->count();

        $totalCustomers = User::where('role', 'user')->count();

        $totalMealPlans = MealPlan::count();

        $activeMealPlans = MealPlan::where('is_active', true)->count();

        $totalMeals = Meal::count();

        $activeMeals = Meal::where('is_active', true)->count();

        $activeSubscriptions = Subscription::where(
            'status',
            SubscriptionStatus::ACTIVE
        )->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalStaff' => $totalStaff,
            'totalCustomers' => $totalCustomers,
            'totalMealPlans' => $totalMealPlans,
            'activeMealPlans' => $activeMealPlans,
            'totalMeals' => $totalMeals,
            'activeMeals' => $activeMeals,
            'activeSubscriptions' => $activeSubscriptions,
        ]);
    }
}