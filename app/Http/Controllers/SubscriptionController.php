<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(
        Request $request,
        SubscriptionService $subscriptionService
    ) {
        $request->validate([
            'meal_plan_id' => [
                'required',
                'integer',
                'exists:meal_plans,id',
            ],
        ]);

        $mealPlan = MealPlan::findOrFail(
            $request->meal_plan_id
        );

        $subscription = $subscriptionService->createPending(
            $request->user(),
            $mealPlan
        );

        return response()->json([
            'message' => 'Subscription created successfully.',
            'subscription' => $subscription->load('mealPlan'),
        ], 201);
    }
}