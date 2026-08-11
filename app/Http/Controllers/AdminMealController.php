<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMealController extends Controller
{
    protected function ensureAdmin(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->role === 'admin',
            403,
            'You are not authorized to manage meals.'
        );
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $query = Meal::query()
            ->with('mealPlan');

        if ($request->filled('meal_plan_id')) {
            $query->where(
                'meal_plan_id',
                $request->meal_plan_id
            );
        }

        if ($request->filled('meal_type')) {
            $query->where(
                'meal_type',
                $request->meal_type
            );
        }

        if ($request->filled('day_of_week')) {
            $query->where(
                'day_of_week',
                $request->day_of_week
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $meals = $query
            ->orderByDesc('is_active')
            ->orderBy('day_of_week')
            ->orderByRaw("
                CASE meal_type
                    WHEN 'breakfast' THEN 1
                    WHEN 'lunch' THEN 2
                    WHEN 'supper' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        $mealPlans = MealPlan::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.meals.index',
            compact('meals', 'mealPlans')
        );
    }




    public function create(Request $request): View
    {
        $this->ensureAdmin($request);

        $mealPlans = MealPlan::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedMealPlanId = $request->input('meal_plan_id');

        $selectedDayOfWeek = $request->input('day_of_week');

        $selectedMealType = $request->input('meal_type');

        return view(
            'admin.meals.create',
            compact(
                'mealPlans',
                'selectedMealPlanId',
                'selectedDayOfWeek',
                'selectedMealType'
            )
        );
    }



    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'meal_plan_id' => [
                'required',
                'exists:meal_plans,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'meal_type' => [
                'required',
                'in:breakfast,lunch,supper',
            ],

            'day_of_week' => [
                'required',
                'integer',
                'between:1,7',
            ],

            'image' => [
                'nullable',
                'image',
                'max:5120',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] =
                $request->file('image')
                    ->store('meals', 'public');
        }


        
        $exists = Meal::query()
            ->where('meal_plan_id', $validated['meal_plan_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('meal_type', $validated['meal_type'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A meal already exists for this day and meal type.'
                );
        }

        $meal = Meal::create($validated);

        return redirect()
            ->route('admin.meal-plans.show', $meal->meal_plan_id)
            ->with(
                'success',
                'Meal created successfully.'
            );

    }

    public function edit(
        Request $request,
        Meal $meal
    ): View {
        $this->ensureAdmin($request);

        $mealPlans = MealPlan::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.meals.edit',
            compact('meal', 'mealPlans')
        );
    }

    public function update(
        Request $request,
        Meal $meal
    ): RedirectResponse {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'meal_plan_id' => [
                'required',
                'exists:meal_plans,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'meal_type' => [
                'required',
                'in:breakfast,lunch,supper',
            ],

            'day_of_week' => [
                'required',
                'integer',
                'between:1,7',
            ],

            'image' => [
                'nullable',
                'image',
                'max:5120',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] =
                $request->file('image')
                    ->store('meals', 'public');
        }

        $meal->update($validated);

        return redirect()
            ->route('admin.meals.index')
            ->with(
                'success',
                'Meal updated successfully.'
            );
    }

    public function destroy(
        Request $request,
        Meal $meal
    ): RedirectResponse {
        $this->ensureAdmin($request);

        if ($meal->entitlements()->exists()) {
            return back()->with(
                'error',
                'This meal has entitlement history and cannot be deleted. Deactivate it instead.'
            );
        }

        if ($meal->redemptions()->exists()) {
            return back()->with(
                'error',
                'This meal has redemption history and cannot be deleted.'
            );
        }

        $meal->delete();

        return redirect()
            ->route('admin.meals.index')
            ->with(
                'success',
                'Meal deleted successfully.'
            );
    }

    public function toggle(
        Request $request,
        Meal $meal
    ): RedirectResponse {
        $this->ensureAdmin($request);

        $meal->update([
            'is_active' => !$meal->is_active,
        ]);

        return back()->with(
            'success',
            $meal->is_active
                ? 'Meal activated.'
                : 'Meal deactivated.'
        );
    }

    public function show($meal)
    {
        $meal = Meal::with('mealPlan')->findOrFail($meal);

        return view('admin.meals.show', compact('meal'));
    }
}