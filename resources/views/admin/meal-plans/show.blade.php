@extends('layouts.app')

@section('title', $mealPlan->name . ' - Weekly Menu')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">

        <div>

            <div class="flex items-center gap-3 mb-2">

                <h1 class="text-3xl font-bold text-slate-900">
                    {{ $mealPlan->name }}
                </h1>

                @if($mealPlan->is_active)

                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                        Active
                    </span>

                @else

                    <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-600 text-xs font-semibold">
                        Inactive
                    </span>

                @endif

            </div>

            <p class="text-slate-500">
                {{ $mealPlan->description ?: 'No description provided.' }}
            </p>

        </div>


        {{-- Actions --}}
        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('admin.meal-plans.edit', $mealPlan) }}"
                class="px-5 py-3 rounded-xl border border-slate-300
                       bg-white font-semibold hover:bg-slate-50 transition"
            >
                Edit Plan
            </a>

            <a
                href="{{ route('admin.meals.index', [
                    'meal_plan_id' => $mealPlan->id
                ]) }}"
                class="px-5 py-3 rounded-xl border border-slate-300
                       bg-white font-semibold hover:bg-slate-50 transition"
            >
                All Meals
            </a>

            <a
                href="{{ route('admin.meals.create', [
                    'meal_plan_id' => $mealPlan->id
                ]) }}"
                class="px-5 py-3 rounded-xl bg-slate-900 text-white
                       font-semibold hover:bg-slate-800 transition"
            >
                + Add Meal
            </a>

        </div>

    </div>


    {{-- Plan Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <p class="text-sm text-slate-500">
                Price
            </p>

            <p class="text-2xl font-bold mt-2">
                KES {{ number_format($mealPlan->price, 2) }}
            </p>

        </div>


        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <p class="text-sm text-slate-500">
                Meal Limit
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->meal_limit }}
            </p>

        </div>


        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <p class="text-sm text-slate-500">
                Duration
            </p>

            <p class="text-2xl font-bold mt-2">

                {{ $mealPlan->duration_days }}

                <span class="text-sm font-medium text-slate-500">
                    days
                </span>

            </p>

        </div>


        <div class="bg-white border border-slate-200 rounded-2xl p-5">

            <p class="text-sm text-slate-500">
                Meals Assigned
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->meals->count() }}
            </p>

        </div>

    </div>


    {{-- Weekly Menu --}}
    <div class="mb-5">

        <h2 class="text-2xl font-bold">
            Weekly Menu
        </h2>

        <p class="text-slate-500 mt-1">
            Build the breakfast, lunch and supper schedule for this meal plan.
        </p>

    </div>


    @php

        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        $mealTypes = [
            'breakfast' => 'Breakfast',
            'lunch' => 'Lunch',
            'supper' => 'Supper',
        ];

    @endphp


    {{-- Weekly Grid --}}
    <div class="space-y-5">

        @foreach($days as $dayNumber => $dayName)

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

                {{-- Day Header --}}
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-bold">
                                {{ $dayName }}
                            </h3>

                            <p class="text-xs text-slate-500">
                                Day {{ $dayNumber }}
                            </p>

                        </div>

                        <span class="text-xs text-slate-400">

                            {{ $mealPlan->meals
                                ->where('day_of_week', $dayNumber)
                                ->count()
                            }}

                            meal(s)

                        </span>

                    </div>

                </div>


                {{-- Meal Slots --}}
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-200">

                    @foreach($mealTypes as $type => $label)

                        @php

                            $meal = $mealPlan->meals->first(
                                function ($item) use ($dayNumber, $type) {

                                    return
                                        (int) $item->day_of_week === $dayNumber
                                        &&
                                        $item->meal_type === $type;

                                }
                            );

                        @endphp


                        <div class="p-5 min-h-[260px]">

                            {{-- Slot Header --}}
                            <div class="flex items-center justify-between mb-4">

                                <h4 class="font-semibold">
                                    {{ $label }}
                                </h4>

                                @if($meal)

                                    @if($meal->is_active)

                                        <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                            Active
                                        </span>

                                    @else

                                        <span class="text-xs px-2 py-1 rounded-full bg-slate-200 text-slate-600">
                                            Inactive
                                        </span>

                                    @endif

                                @else

                                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                        Empty
                                    </span>

                                @endif

                            </div>


                            {{-- Occupied --}}
                            @if($meal)

                                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm">

                                    {{-- Image --}}
                                    @if($meal->image)

                                        <img
                                            src="{{ asset('storage/' . $meal->image) }}"
                                            alt="{{ $meal->name }}"
                                            class="h-36 w-full object-cover"
                                        >

                                    @else

                                        <div class="h-36 bg-slate-100 flex items-center justify-center">

                                            <span class="text-slate-400 text-sm">
                                                No image
                                            </span>

                                        </div>

                                    @endif


                                    <div class="p-4">

                                        <h5 class="font-semibold text-slate-900">
                                            {{ $meal->name }}
                                        </h5>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ ucfirst($meal->meal_type) }}
                                        </p>


                                        @if($meal->description)

                                            <p class="mt-3 text-sm text-slate-500 line-clamp-2">
                                                {{ $meal->description }}
                                            </p>

                                        @endif


                                        {{-- Actions --}}
                                        <div class="mt-4 grid grid-cols-2 gap-2">

                                            <a
                                                href="{{ route('admin.meals.edit', $meal) }}"
                                                class="rounded-lg bg-indigo-50 px-3 py-2 text-center
                                                       text-xs font-medium text-indigo-700
                                                       hover:bg-indigo-100"
                                            >
                                                Edit / Replace
                                            </a>


                                            @if($meal->is_active)

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.meals.toggle', $meal) }}"
                                                    onsubmit="return confirm('Remove this meal from the weekly menu? The meal will be deactivated but its history will be preserved.');"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-lg bg-red-50 px-3 py-2
                                                               text-xs font-medium text-red-700
                                                               hover:bg-red-100"
                                                    >
                                                        Remove
                                                    </button>

                                                </form>

                                            @else

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.meals.toggle', $meal) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-lg bg-emerald-50 px-3 py-2
                                                               text-xs font-medium text-emerald-700
                                                               hover:bg-emerald-100"
                                                    >
                                                        Restore
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                            {{-- Empty --}}
                            @else

                                <div class="h-36 rounded-xl border-2 border-dashed border-slate-200
                                            flex flex-col items-center justify-center">

                                    <div class="text-3xl text-slate-300 mb-2">
                                        +
                                    </div>

                                    <p class="text-sm text-slate-400">
                                        No {{ strtolower($label) }} assigned
                                    </p>

                                </div>


                                <a
                                    href="{{ route('admin.meals.create', [
                                        'meal_plan_id' => $mealPlan->id,
                                        'day_of_week' => $dayNumber,
                                        'meal_type' => $type,
                                    ]) }}"
                                    class="block text-center mt-4 px-4 py-3 rounded-xl
                                           bg-slate-900 text-white text-sm font-semibold
                                           hover:bg-slate-800"
                                >
                                    + Add {{ $label }}
                                </a>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @endforeach

    </div>


    {{-- Bottom Navigation --}}
    <div class="mt-8 flex flex-wrap gap-3">

        <a
            href="{{ route('admin.meal-plans.index') }}"
            class="px-5 py-3 rounded-xl border border-slate-300
                   bg-white font-semibold hover:bg-slate-50"
        >
            ← Back to Meal Plans
        </a>

        <a
            href="{{ route('admin.meals.index', [
                'meal_plan_id' => $mealPlan->id
            ]) }}"
            class="px-5 py-3 rounded-xl border border-slate-300
                   bg-white font-semibold hover:bg-slate-50"
        >
            Manage All Meals
        </a>

    </div>

</div>

@endsection