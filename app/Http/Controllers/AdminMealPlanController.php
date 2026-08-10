<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMealPlanController extends Controller
{
    protected function ensureAdmin(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->role === 'admin',
            403,
            'You are not authorized to manage meal plans.'
        );
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $mealPlans = MealPlan::query()
            ->withCount([
                'meals',
                'subscriptions',
            ])
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.meal-plans.index', compact('mealPlans'));
    }



    public function create(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.meal-plans.create');
    }



    public function show(
        Request $request,
        MealPlan $mealPlan
    ): View {
        $this->ensureAdmin($request);

        $mealPlan->load([
            'meals' => function ($query) {
                $query
                    ->orderBy('day_of_week')
                    ->orderByRaw("
                        CASE meal_type
                            WHEN 'breakfast' THEN 1
                            WHEN 'lunch' THEN 2
                            WHEN 'supper' THEN 3
                            ELSE 4
                        END
                    ")
                    ->orderBy('name');
            }
        ]);

        return view(
            'admin.meal-plans.show',
            compact('mealPlan')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'meal_limit' => [
                'required',
                'integer',
                'min:1',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        MealPlan::create($validated);

        return redirect()
            ->route('admin.meal-plans.index')
            ->with(
                'success',
                'Meal plan created successfully.'
            );
    }

    public function edit(
        Request $request,
        MealPlan $mealPlan
    ): View {
        $this->ensureAdmin($request);

        return view(
            'admin.meal-plans.edit',
            compact('mealPlan')
        );
    }

    public function update(
        Request $request,
        MealPlan $mealPlan
    ): RedirectResponse {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'meal_limit' => [
                'required',
                'integer',
                'min:1',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        $mealPlan->update($validated);

        return redirect()
            ->route('admin.meal-plans.index')
            ->with(
                'success',
                'Meal plan updated successfully.'
            );
    }

    public function destroy(
        Request $request,
        MealPlan $mealPlan
    ): RedirectResponse {
        $this->ensureAdmin($request);

        if ($mealPlan->subscriptions()->exists()) {
            return back()->with(
                'error',
                'This meal plan has subscription history and cannot be deleted. Deactivate it instead.'
            );
        }

        if ($mealPlan->meals()->exists()) {
            return back()->with(
                'error',
                'This meal plan still has meals attached. Remove or deactivate the meals first.'
            );
        }

        $mealPlan->delete();

        return redirect()
            ->route('admin.meal-plans.index')
            ->with(
                'success',
                'Meal plan deleted successfully.'
            );
    }

    public function toggle(
        Request $request,
        MealPlan $mealPlan
    ): RedirectResponse {
        $this->ensureAdmin($request);

        $mealPlan->update([
            'is_active' => !$mealPlan->is_active,
        ]);

        return back()->with(
            'success',
            $mealPlan->is_active
                ? 'Meal plan activated.'
                : 'Meal plan deactivated.'
        );
    }
}