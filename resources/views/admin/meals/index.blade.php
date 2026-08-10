<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meals - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gray-100 min-h-screen">


{{-- NAVIGATION --}}

<nav class="bg-black text-white">

    <div class="max-w-7xl mx-auto px-6 py-4">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <a href="{{ route('admin.meals.dashboard') }}">

                    <h1 class="font-bold text-lg">
                        Silver Spoon
                    </h1>

                    <p class="text-xs text-gray-400">
                        Meal Management
                    </p>

                </a>

            </div>


            <div class="flex flex-wrap items-center gap-2 text-sm">

                <a
                    href="{{ route('admin.meals.dashboard') }}"
                    class="px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10"
                >
                    Dashboard
                </a>


                <a
                    href="{{ route('admin.meal-plans.index') }}"
                    class="px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10"
                >
                    Meal Plans
                </a>


                <a
                    href="{{ route('admin.meals.index') }}"
                    class="px-3 py-2 rounded-lg bg-white/10 text-white"
                >
                    Meals
                </a>


                <a
                    href="{{ route('admin.meals.report') }}"
                    class="px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10"
                >
                    Reports
                </a>


                <a
                    href="{{ route('staff.meals.scan') }}"
                    class="px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10"
                >
                    Scanner
                </a>


                <div class="ml-2 pl-3 border-l border-gray-700 text-gray-300">

                    {{ auth()->user()->name }}

                </div>

            </div>

        </div>

    </div>

</nav>



<main class="max-w-7xl mx-auto px-6 py-8">


{{-- FLASH MESSAGES --}}

@if(session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">

        {{ session('success') }}

    </div>

@endif


@if(session('error'))

    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">

        {{ session('error') }}

    </div>

@endif



{{-- HEADER --}}

<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">

    <div>

        <h2 class="text-3xl font-bold">
            Meals
        </h2>

        <p class="text-gray-500 mt-2">
            Manage meals assigned to your meal plans.
        </p>

    </div>


    <div class="flex flex-wrap gap-3">

        <a
            href="{{ route('admin.meal-plans.index') }}"
            class="px-5 py-3 rounded-xl border border-gray-300 bg-white
                   font-semibold text-sm hover:bg-gray-50"
        >
            Meal Plans
        </a>


        <a
            href="{{ route('admin.meals.create') }}"
            class="px-5 py-3 rounded-xl bg-black text-white
                   font-semibold text-sm hover:bg-gray-800"
        >
            + Create Meal
        </a>

    </div>

</div>



{{-- FILTERS --}}

<div class="bg-white border rounded-2xl p-6 mb-8">

    <form
        method="GET"
        action="{{ route('admin.meals.index') }}"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4"
    >

        {{-- SEARCH --}}

        <div class="lg:col-span-2">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Search
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search meal name or description..."
                class="w-full rounded-xl border-gray-300 px-4 py-3
                       focus:border-black focus:ring-black"
            >

        </div>


        {{-- MEAL PLAN --}}

        <div>

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Meal Plan
            </label>

            <select
                name="meal_plan_id"
                class="w-full rounded-xl border-gray-300 px-4 py-3
                       focus:border-black focus:ring-black"
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

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Meal Type
            </label>

            <select
                name="meal_type"
                class="w-full rounded-xl border-gray-300 px-4 py-3
                       focus:border-black focus:ring-black"
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

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Status
            </label>

            <select
                name="status"
                class="w-full rounded-xl border-gray-300 px-4 py-3
                       focus:border-black focus:ring-black"
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

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Day
            </label>

            <select
                name="day_of_week"
                class="w-full rounded-xl border-gray-300 px-4 py-3
                       focus:border-black focus:ring-black"
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
                    7 => 'Sunday'
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

        <div class="md:col-span-2 lg:col-span-5 flex flex-wrap gap-3">

            <button
                type="submit"
                class="px-5 py-3 rounded-xl bg-black text-white
                       font-semibold text-sm hover:bg-gray-800"
            >
                Apply Filters
            </button>


            <a
                href="{{ route('admin.meals.index') }}"
                class="px-5 py-3 rounded-xl border border-gray-300
                       bg-white font-semibold text-sm hover:bg-gray-50"
            >
                Clear Filters
            </a>

        </div>

    </form>

</div>



{{-- RESULTS HEADER --}}

<div class="flex items-center justify-between mb-4">

    <div>

        <h3 class="text-xl font-bold">
            All Meals
        </h3>

        <p class="text-sm text-gray-500 mt-1">

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



{{-- MEALS --}}

@if($meals->isEmpty())

    <div class="bg-white border rounded-2xl p-12 text-center">

        <div class="text-5xl mb-4">
            🍽️
        </div>

        <h3 class="text-xl font-bold">
            No meals found
        </h3>

        <p class="text-gray-500 mt-2">
            No meals match your current filters.
        </p>


        <div class="mt-6">

            <a
                href="{{ route('admin.meals.create') }}"
                class="inline-flex px-5 py-3 rounded-xl
                       bg-black text-white font-semibold"
            >
                + Create Meal
            </a>

        </div>

    </div>

@else


    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 font-semibold">
                            Meal
                        </th>

                        <th class="text-left px-6 py-4 font-semibold">
                            Meal Plan
                        </th>

                        <th class="text-left px-6 py-4 font-semibold">
                            Schedule
                        </th>

                        <th class="text-left px-6 py-4 font-semibold">
                            Status
                        </th>

                        <th class="text-right px-6 py-4 font-semibold">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @foreach($meals as $meal)

                        <tr class="hover:bg-gray-50">


                            {{-- MEAL --}}

                            <td class="px-6 py-5">

                                <div class="flex items-center gap-4">


                                    @if($meal->image)

                                        <img
                                            src="{{ asset('storage/' . $meal->image) }}"
                                            alt="{{ $meal->name }}"
                                            class="w-14 h-14 rounded-xl object-cover border"
                                        >

                                    @else

                                        <div
                                            class="w-14 h-14 rounded-xl bg-gray-100
                                                   border flex items-center justify-center
                                                   text-xl"
                                        >
                                            🍽️
                                        </div>

                                    @endif


                                    <div class="min-w-0">

                                        <p class="font-bold text-gray-900">
                                            {{ $meal->name }}
                                        </p>

                                        @if($meal->description)

                                            <p class="text-xs text-gray-500 mt-1 max-w-xs truncate">
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
                                        class="font-semibold text-indigo-700 hover:text-indigo-900"
                                    >
                                        {{ $meal->mealPlan->name }}
                                    </a>

                                @else

                                    <span class="text-gray-400">
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

                                    <span class="font-semibold">
                                        {{ $days[$meal->day_of_week] ?? 'Unknown Day' }}
                                    </span>

                                    <span class="text-gray-400 mx-1">
                                        ·
                                    </span>

                                    <span class="capitalize text-gray-600">
                                        {{ $meal->meal_type }}
                                    </span>

                                </div>

                            </td>



                            {{-- STATUS --}}

                            <td class="px-6 py-5">

                                @if($meal->is_active)

                                    <span
                                        class="inline-flex items-center px-3 py-1
                                               rounded-full bg-green-100
                                               text-green-700 text-xs font-semibold"
                                    >
                                        Active
                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center px-3 py-1
                                               rounded-full bg-gray-200
                                               text-gray-600 text-xs font-semibold"
                                    >
                                        Inactive
                                    </span>

                                @endif

                            </td>



                            {{-- ACTIONS --}}

                            <td class="px-6 py-5">

                                <div class="flex items-center justify-end gap-2">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route('admin.meals.show', $meal) }}"
                                        class="px-3 py-2 rounded-lg
                                               bg-gray-100 text-gray-700
                                               text-xs font-semibold
                                               hover:bg-gray-200"
                                    >
                                        View
                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('admin.meals.edit', $meal) }}"
                                        class="px-3 py-2 rounded-lg
                                               bg-indigo-50 text-indigo-700
                                               text-xs font-semibold
                                               hover:bg-indigo-100"
                                    >
                                        Edit
                                    </a>


                                    {{-- TOGGLE --}}

                                    <form
                                        method="POST"
                                        action="{{ route('admin.meals.toggle', $meal) }}"
                                        class="inline"
                                        onsubmit="return confirm('{{ $meal->is_active ? 'Deactivate this meal?' : 'Activate this meal?' }}')"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="px-3 py-2 rounded-lg text-xs font-semibold
                                                {{ $meal->is_active
                                                    ? 'bg-red-50 text-red-700 hover:bg-red-100'
                                                    : 'bg-green-50 text-green-700 hover:bg-green-100'
                                                }}"
                                        >

                                            {{ $meal->is_active ? 'Deactivate' : 'Activate' }}

                                        </button>

                                    </form>


                                    {{-- DELETE --}}

                                    <form
                                        method="POST"
                                        action="{{ route('admin.meals.destroy', $meal) }}"
                                        class="inline"
                                        onsubmit="return confirm('Permanently delete this meal? This cannot be undone.')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-3 py-2 rounded-lg
                                                   bg-red-50 text-red-700
                                                   text-xs font-semibold
                                                   hover:bg-red-100"
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

            <div class="px-6 py-5 border-t">

                {{ $meals->links() }}

            </div>

        @endif

    </div>

@endif


</main>

</body>

</html>