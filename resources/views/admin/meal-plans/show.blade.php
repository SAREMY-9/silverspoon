<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $mealPlan->name }} - Meal Plan
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
                Meal Plan Management
            </p>
        </div>

        <div class="flex items-center gap-4 text-sm">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="text-gray-300 hover:text-white"
            >
                Meal Plans
            </a>

            <span>
                {{ auth()->user()->name }}
            </span>

        </div>

    </div>
</nav>


<main class="max-w-7xl mx-auto px-6 py-8">

    {{-- Back --}}
    <div class="mb-6">

        <a
            href="{{ route('admin.meal-plans.index') }}"
            class="text-sm text-gray-500 hover:text-black"
        >
            ← Back to Meal Plans
        </a>

    </div>


    {{-- Flash messages --}}
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

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

    <div>
        <h1 class="text-3xl font-bold">
            {{ $mealPlan->name }}
        </h1>

        <p class="text-gray-500 mt-2">
            {{ $mealPlan->description ?: 'No description provided.' }}
        </p>
    </div>

    <div class="flex gap-3">

        <a
            href="{{ route('admin.meal-plans.edit', $mealPlan) }}"
            class="px-5 py-3 rounded-xl border border-gray-300 font-semibold hover:bg-gray-50"
        >
            Edit Plan
        </a>

        <a
            href="{{ route('admin.meals.create', ['meal_plan_id' => $mealPlan->id]) }}"
            class="px-5 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800"
        >
            + Add Meal
        </a>

    </div>

</div>

            <div class="flex items-center gap-3 mb-3">

                <h2 class="text-3xl font-bold">
                    {{ $mealPlan->name }}
                </h2>

                @if($mealPlan->is_active)

                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                        ACTIVE
                    </span>

                @else

                    <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-bold">
                        INACTIVE
                    </span>

                @endif

            </div>

            @if($mealPlan->description)

                <p class="text-gray-500 max-w-2xl">
                    {{ $mealPlan->description }}
                </p>

            @endif

        </div>

<!--
        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('admin.meal-plans.edit', $mealPlan) }}"
                class="px-5 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800"
            >
                Edit Plan
            </a>

            <a
                href="{{ route('admin.meals.create', ['meal_plan_id' => $mealPlan->id]) }}"
                class="px-5 py-3 rounded-xl bg-gray-200 text-gray-900 font-semibold hover:bg-gray-300"
            >
                + Add Meal
            </a>

        </div>

 ----->

    </div>


    {{-- Plan statistics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

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
                Meal Limit
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->meal_limit }}
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Meals Configured
            </p>

            <p class="text-2xl font-bold mt-2">
                {{ $mealPlan->meals->count() }}
            </p>

        </div>

    </div>


    {{-- Weekly menu --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-6 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>

                <h3 class="text-xl font-bold">
                    Weekly Menu
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Meals configured for this plan.
                </p>

            </div>

            <a
                href="{{ route('admin.meals.index', ['meal_plan_id' => $mealPlan->id]) }}"
                class="text-sm font-semibold text-gray-600 hover:text-black"
            >
                Manage All Meals →
            </a>

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
        @endphp


        <div class="divide-y">

            @foreach($days as $dayNumber => $dayName)

                @php
                    $dayMeals = $mealPlan->meals
                        ->where('day_of_week', $dayNumber);
                @endphp


                <div class="p-6">

                    <div class="flex flex-col lg:flex-row lg:items-start gap-5">

                        <div class="lg:w-32 flex-shrink-0">

                            <h4 class="font-bold text-lg">
                                {{ $dayName }}
                            </h4>

                            <p class="text-xs text-gray-400">
                                Day {{ $dayNumber }}
                            </p>

                        </div>


                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">

                            @foreach(['breakfast', 'lunch', 'supper'] as $type)

                                @php
                                    $meal = $dayMeals
                                        ->firstWhere('meal_type', $type);
                                @endphp


                                <div class="border rounded-xl p-4">

                                    <p class="text-xs uppercase tracking-wide font-bold text-gray-400 mb-2">
                                        {{ ucfirst($type) }}
                                    </p>


                                    @if($meal)

                                        <div class="flex gap-3">

                                            @if($meal->image)

                                                <img
                                                    src="{{ asset('storage/' . $meal->image) }}"
                                                    alt="{{ $meal->name }}"
                                                    class="w-16 h-16 rounded-lg object-cover"
                                                >

                                            @endif


                                            <div class="min-w-0 flex-1">

                                                <p class="font-semibold truncate">
                                                    {{ $meal->name }}
                                                </p>

                                                @if($meal->description)

                                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                                        {{ $meal->description }}
                                                    </p>

                                                @endif

                                                <div class="mt-3 flex items-center gap-2">

                                                    @if($meal->is_active)

                                                        <span class="text-xs font-semibold text-green-600">
                                                            Active
                                                        </span>

                                                    @else

                                                        <span class="text-xs font-semibold text-gray-400">
                                                            Inactive
                                                        </span>

                                                    @endif

                                                    <a
                                                        href="{{ route('admin.meals.edit', $meal) }}"
                                                        class="text-xs font-semibold text-black hover:underline"
                                                    >
                                                        Edit
                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    @else

                                        <div class="py-4">

                                            <p class="text-sm text-gray-400">
                                                No meal configured
                                            </p>

                                            <a
                                                href="{{ route('admin.meals.create', [
                                                    'meal_plan_id' => $mealPlan->id,
                                                    'day_of_week' => $dayNumber,
                                                    'meal_type' => $type,
                                                ]) }}"
                                                class="inline-block mt-2 text-xs font-semibold text-black hover:underline"
                                            >
                                                + Add {{ ucfirst($type) }}
                                            </a>

                                        </div>

                                    @endif

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- All meals --}}
    <div class="bg-white border rounded-2xl overflow-hidden mt-8">

        <div class="p-6 border-b">

            <h3 class="text-xl font-bold">
                All Meals in This Plan
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                {{ $mealPlan->meals->count() }}
                {{ $mealPlan->meals->count() === 1 ? 'meal' : 'meals' }}
                configured.
            </p>

        </div>


        @if($mealPlan->meals->isEmpty())

            <div class="p-10 text-center">

                <p class="text-gray-500">
                    No meals have been configured for this plan yet.
                </p>

                <a
                    href="{{ route('admin.meals.create', ['meal_plan_id' => $mealPlan->id]) }}"
                    class="inline-block mt-4 bg-black text-white px-5 py-3 rounded-xl font-semibold"
                >
                    + Add First Meal
                </a>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="text-left px-6 py-4">
                                Meal
                            </th>

                            <th class="text-left px-6 py-4">
                                Day
                            </th>

                            <th class="text-left px-6 py-4">
                                Type
                            </th>

                            <th class="text-left px-6 py-4">
                                Status
                            </th>

                            <th class="text-right px-6 py-4">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($mealPlan->meals as $meal)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        @if($meal->image)

                                            <img
                                                src="{{ asset('storage/' . $meal->image) }}"
                                                alt="{{ $meal->name }}"
                                                class="w-12 h-12 rounded-lg object-cover"
                                            >

                                        @endif

                                        <div>

                                            <p class="font-semibold">
                                                {{ $meal->name }}
                                            </p>

                                            @if($meal->description)

                                                <p class="text-xs text-gray-500 max-w-md truncate">
                                                    {{ $meal->description }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-4">
                                    {{ $days[$meal->day_of_week] ?? 'Unknown' }}
                                </td>


                                <td class="px-6 py-4">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                        {{ ucfirst($meal->meal_type) }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    @if($meal->is_active)

                                        <span class="text-green-600 font-semibold">
                                            Active
                                        </span>

                                    @else

                                        <span class="text-gray-400 font-semibold">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-3">

                                        <a
                                            href="{{ route('admin.meals.edit', $meal) }}"
                                            class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 font-semibold"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.meals.toggle', $meal) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 font-semibold"
                                            >
                                                {{ $meal->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</main>

</body>
</html>