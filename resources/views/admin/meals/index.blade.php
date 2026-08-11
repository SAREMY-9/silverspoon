@extends('layouts.app')

@section('title', 'Meals - Silver Spoon')

@section('content')

<div class="space-y-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                Meals
            </h1>

            <p class="mt-2 text-slate-500">
                Manage meals assigned to your meal plans.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Meal Plans
            </a>

            <a
                href="{{ route('admin.meals.create') }}"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                + Create Meal
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTERS --}}
    {{-- ========================================================= --}}

    <section class="rounded-2xl border border-slate-200 bg-white p-6">

        <form
            method="GET"
            action="{{ route('admin.meals.index') }}"
            class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-5"
        >

            {{-- SEARCH --}}

            <div class="lg:col-span-2">

                <label
                    for="search"
                    class="mb-2 block text-sm font-semibold text-slate-800"
                >
                    Search
                </label>

                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search meal name or description..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

            </div>


            {{-- MEAL PLAN --}}

            <div>

                <label
                    for="meal_plan_id"
                    class="mb-2 block text-sm font-semibold text-slate-800"
                >
                    Meal Plan
                </label>

                <select
                    id="meal_plan_id"
                    name="meal_plan_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

                    <option value="">
                        All Plans
                    </option>

                    @foreach($mealPlans as $plan)

                        <option
                            value="{{ $plan->id }}"
                            @selected(request('meal_plan_id') == $plan->id)
                        >
                            {{ $plan->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- MEAL TYPE --}}

            <div>

                <label
                    for="meal_type"
                    class="mb-2 block text-sm font-semibold text-slate-800"
                >
                    Meal Type
                </label>

                <select
                    id="meal_type"
                    name="meal_type"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

                    <option value="">
                        All Types
                    </option>

                    <option
                        value="breakfast"
                        @selected(request('meal_type') === 'breakfast')
                    >
                        Breakfast
                    </option>

                    <option
                        value="lunch"
                        @selected(request('meal_type') === 'lunch')
                    >
                        Lunch
                    </option>

                    <option
                        value="supper"
                        @selected(request('meal_type') === 'supper')
                    >
                        Supper
                    </option>

                </select>

            </div>


            {{-- STATUS --}}

            <div>

                <label
                    for="status"
                    class="mb-2 block text-sm font-semibold text-slate-800"
                >
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- DAY --}}

            <div>

                <label
                    for="day_of_week"
                    class="mb-2 block text-sm font-semibold text-slate-800"
                >
                    Day
                </label>

                <select
                    id="day_of_week"
                    name="day_of_week"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

                    <option value="">
                        All Days
                    </option>

                    @foreach([
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        7 => 'Sunday',
                    ] as $number => $day)

                        <option
                            value="{{ $number }}"
                            @selected(request('day_of_week') == $number)
                        >
                            {{ $day }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- ACTIONS --}}

            <div class="flex flex-wrap items-end gap-3 md:col-span-2 lg:col-span-5">

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    Apply Filters
                </button>

                <a
                    href="{{ route('admin.meals.index') }}"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Clear Filters
                </a>

            </div>

        </form>

    </section>


    {{-- ========================================================= --}}
    {{-- RESULTS HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-xl font-bold text-slate-900">
                All Meals
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Showing
                {{ $meals->firstItem() ?? 0 }}
                -
                {{ $meals->lastItem() ?? 0 }}
                of
                {{ $meals->total() }}
                meals
            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EMPTY STATE --}}
    {{-- ========================================================= --}}

    @if($meals->isEmpty())

        <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center">

            <div class="mb-4 text-5xl">
                🍽️
            </div>

            <h3 class="text-xl font-bold text-slate-900">
                No meals found
            </h3>

            <p class="mt-2 text-slate-500">
                No meals match your current filters.
            </p>

            <div class="mt-6">

                <a
                    href="{{ route('admin.meals.create') }}"
                    class="inline-flex rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    + Create Meal
                </a>

            </div>

        </div>

    @else


        {{-- ===================================================== --}}
        {{-- MEALS TABLE --}}
        {{-- ===================================================== --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 font-semibold text-slate-700">
                                Meal
                            </th>

                            <th class="px-6 py-4 font-semibold text-slate-700">
                                Meal Plan
                            </th>

                            <th class="px-6 py-4 font-semibold text-slate-700">
                                Schedule
                            </th>

                            <th class="px-6 py-4 font-semibold text-slate-700">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right font-semibold text-slate-700">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($meals as $meal)

                            <tr class="transition hover:bg-slate-50">

                                {{-- MEAL --}}

                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4">

                                        @if($meal->image)

                                            <img
                                                src="{{ asset('storage/' . $meal->image) }}"
                                                alt="{{ $meal->name }}"
                                                class="h-14 w-14 rounded-xl border border-slate-200 object-cover"
                                            >

                                        @else

                                            <div class="flex h-14 w-14 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-xl">
                                                🍽️
                                            </div>

                                        @endif


                                        <div class="min-w-0">

                                            <p class="font-bold text-slate-900">
                                                {{ $meal->name }}
                                            </p>

                                            @if($meal->description)

                                                <p class="mt-1 max-w-xs truncate text-xs text-slate-500">
                                                    {{ $meal->description }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- PLAN --}}

                                <td class="px-6 py-5">

                                    @if($meal->mealPlan)

                                        <a
                                            href="{{ route('admin.meal-plans.show', $meal->mealPlan) }}"
                                            class="font-semibold text-slate-700 hover:text-slate-900 hover:underline"
                                        >
                                            {{ $meal->mealPlan->name }}
                                        </a>

                                    @else

                                        <span class="text-slate-400">
                                            No plan
                                        </span>

                                    @endif

                                </td>


                                {{-- SCHEDULE --}}

                                <td class="px-6 py-5">

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

                                    @endphp

                                    <div>

                                        <span class="font-semibold text-slate-900">
                                            {{ $days[$meal->day_of_week] ?? 'Unknown Day' }}
                                        </span>

                                        <span class="mx-1 text-slate-300">
                                            ·
                                        </span>

                                        <span class="capitalize text-slate-600">
                                            {{ $meal->meal_type }}
                                        </span>

                                    </div>

                                </td>


                                {{-- STATUS --}}

                                <td class="px-6 py-5">

                                    @if($meal->is_active)

                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Active
                                        </span>

                                    @else

                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td class="px-6 py-5">

                                    <div class="flex flex-wrap items-center justify-end gap-2">

                                        {{-- VIEW --}}

                                        <a
                                            href="{{ route('admin.meals.show', $meal) }}"
                                            class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200"
                                        >
                                            View
                                        </a>


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route('admin.meals.edit', $meal) }}"
                                            class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
                                        >
                                            Edit
                                        </a>


                                        {{-- TOGGLE --}}

                                        <form
                                            method="POST"
                                            action="{{ route('admin.meals.toggle', $meal) }}"
                                            onsubmit="return confirm('{{ $meal->is_active ? 'Deactivate this meal?' : 'Activate this meal?' }}')"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="rounded-lg px-3 py-2 text-xs font-semibold transition
                                                {{ $meal->is_active
                                                    ? 'bg-red-50 text-red-700 hover:bg-red-100'
                                                    : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'
                                                }}"
                                            >
                                                {{ $meal->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>

                                        </form>


                                        {{-- DELETE --}}

                                        <form
                                            method="POST"
                                            action="{{ route('admin.meals.destroy', $meal) }}"
                                            onsubmit="return confirm('Permanently delete this meal? This cannot be undone.')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if($meals->hasPages())

                <div class="border-t border-slate-200 px-6 py-5">
                    {{ $meals->links() }}
                </div>

            @endif

        </div>

    @endif

</div>

@endsection