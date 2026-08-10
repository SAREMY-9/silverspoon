<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $mealPlan->name }} - Weekly Menu
    </title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<nav class="bg-black text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <div>
            <h1 class="font-bold text-lg">
                Silver Spoon
            </h1>

            <p class="text-xs text-gray-400">
                Weekly Menu Builder
            </p>
        </div>

        <div class="flex items-center gap-4">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="text-sm text-gray-300 hover:text-white"
            >
                Meal Plans
            </a>

            <a
                href="{{ route('admin.meals.index') }}"
                class="text-sm text-gray-300 hover:text-white"
            >
                All Meals
            </a>

            <span class="text-sm">
                {{ auth()->user()->name }}
            </span>

        </div>

    </div>
</nav>


<main class="max-w-7xl mx-auto px-6 py-8">

    {{-- Flash Messages --}}

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


    {{-- Header --}}

    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">

        <div>

            <div class="flex items-center gap-3 mb-2">

                <h2 class="text-3xl font-bold">
                    {{ $mealPlan->name }}
                </h2>

                @if($mealPlan->is_active)

                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                        Active
                    </span>

                @else

                    <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-semibold">
                        Inactive
                    </span>

                @endif

            </div>

            <p class="text-gray-500">
                {{ $mealPlan->description ?: 'No description provided.' }}
            </p>

        </div>


        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('admin.meal-plans.edit', $mealPlan) }}"
                class="px-5 py-3 rounded-xl border border-gray-300 bg-white font-semibold hover:bg-gray-50"
            >
                Edit Plan
            </a>

            <a
                href="{{ route('admin.meals.index', ['meal_plan_id' => $mealPlan->id]) }}"
                class="px-5 py-3 rounded-xl border border-gray-300 bg-white font-semibold hover:bg-gray-50"
            >
                All Meals
            </a>

            <a
                href="{{ route('admin.meals.create', [
                    'meal_plan_id' => $mealPlan->id
                ]) }}"
                class="px-5 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800"
            >
                + Add Meal
            </a>

        </div>

    </div>


    {{-- Plan Statistics --}}

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Price
            </p>

            <p class="text-2xl font-bold mt-2">
                KES {{ number_format($mealPlan->price, 2) }}
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Meal Limit
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->meal_limit }}
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Duration
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->duration_days }}

                <span class="text-sm font-medium text-gray-500">
                    days
                </span>
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Meals Assigned
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->meals->count() }}
            </p>

        </div>

    </div>


    {{-- Weekly Menu Header --}}

    <div class="mb-5">

        <h3 class="text-2xl font-bold">
            Weekly Menu
        </h3>

        <p class="text-gray-500 mt-1">
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

            <div class="bg-white border rounded-2xl overflow-hidden">

                {{-- Day Header --}}

                <div class="px-6 py-4 bg-gray-50 border-b">

                    <div class="flex items-center justify-between">

                        <div>

                            <h4 class="text-lg font-bold">
                                {{ $dayName }}
                            </h4>

                            <p class="text-xs text-gray-500">
                                Day {{ $dayNumber }}
                            </p>

                        </div>

                        <span class="text-xs text-gray-400">
                            {{ $mealPlan->meals
                                ->where('day_of_week', $dayNumber)
                                ->count()
                            }}
                            meal(s)
                        </span>

                    </div>

                </div>


                {{-- Meal Slots --}}

                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x">

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

                                <h5 class="font-semibold">
                                    {{ $label }}
                                </h5>

                                @if($meal)

                                    @if($meal->is_active)

                                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">
                                            Active
                                        </span>

                                    @else

                                        <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-600">
                                            Inactive
                                        </span>

                                    @endif

                                @else

                                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                        Empty
                                    </span>

                                @endif

                            </div>


                            {{-- OCCUPIED SLOT --}}

                            @if($meal)

                                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm">

                                    {{-- Image --}}

                                    @if($meal->image)

                                        <img
                                            src="{{ asset('storage/' . $meal->image) }}"
                                            alt="{{ $meal->name }}"
                                            class="h-36 w-full object-cover"
                                        >

                                    @else

                                        <div class="h-36 bg-gray-100 flex items-center justify-center">

                                            <span class="text-gray-400 text-sm">
                                                No image
                                            </span>

                                        </div>

                                    @endif


                                    <div class="p-4">

                                        <h4 class="font-semibold text-gray-900">
                                            {{ $meal->name }}
                                        </h4>


                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ ucfirst($meal->meal_type) }}
                                        </p>


                                        @if($meal->description)

                                            <p class="mt-3 text-sm text-gray-500 line-clamp-2">
                                                {{ $meal->description }}
                                            </p>

                                        @endif


                                        {{-- Actions --}}

                                        <div class="mt-4 grid grid-cols-2 gap-2">

                                            {{-- Edit / Replace --}}

                                            <a
                                                href="{{ route('admin.meals.edit', $meal) }}"
                                                class="rounded-lg bg-indigo-50 px-3 py-2 text-center text-xs
                                                    font-medium text-indigo-700 hover:bg-indigo-100"
                                            >
                                                Edit / Replace
                                            </a>


                                            {{-- Remove / Restore --}}

                                            @if($meal->is_active)

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.meals.toggle', $meal) }}"
                                                    onsubmit="return confirm('Remove this meal from the weekly menu? The meal will be deactivated but its history will be preserved.');"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-lg bg-red-50 px-3 py-2 text-xs
                                                            font-medium text-red-700 hover:bg-red-100"
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
                                                        class="w-full rounded-lg bg-green-50 px-3 py-2 text-xs
                                                            font-medium text-green-700 hover:bg-green-100"
                                                    >
                                                        Restore
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                            {{-- EMPTY SLOT --}}

                            @else

                                <div class="h-36 rounded-xl border-2 border-dashed border-gray-200
                                    flex flex-col items-center justify-center">

                                    <div class="text-3xl text-gray-300 mb-2">
                                        +
                                    </div>

                                    <p class="text-sm text-gray-400">
                                        No {{ strtolower($label) }} assigned
                                    </p>

                                </div>


                                <a
                                    href="{{ route('admin.meals.create', [
                                        'meal_plan_id' => $mealPlan->id,
                                        'day_of_week' => $dayNumber,
                                        'meal_type' => $type,
                                    ]) }}"
                                    class="block text-center mt-4 px-4 py-3 rounded-xl bg-black text-white
                                        text-sm font-semibold hover:bg-gray-800"
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
            class="px-5 py-3 rounded-xl border border-gray-300 bg-white font-semibold hover:bg-gray-50"
        >
            ← Back to Meal Plans
        </a>

        <a
            href="{{ route('admin.meals.index', [
                'meal_plan_id' => $mealPlan->id
            ]) }}"
            class="px-5 py-3 rounded-xl border border-gray-300 bg-white font-semibold hover:bg-gray-50"
        >
            Manage All Meals
        </a>

    </div>

</main>

</body>
</html>