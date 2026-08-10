<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Meal - Silver Spoon</title>

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
                Meal Management
            </p>

        </div>

        <div class="text-sm">
            {{ auth()->user()->name }}
        </div>

    </div>

</nav>


<main class="max-w-4xl mx-auto px-6 py-8">

    {{-- Back --}}

    <div class="mb-6">

        <a
            href="{{ url()->previous() }}"
            class="text-sm text-gray-500 hover:text-black"
        >
            ← Back
        </a>

    </div>


    {{-- Header --}}

    <div class="mb-8">

        <h2 class="text-3xl font-bold">
            Add Meal
        </h2>

        <p class="text-gray-500 mt-2">
            Add a meal to a meal plan's weekly schedule.
        </p>

    </div>


    {{-- Validation errors --}}

    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

            <p class="font-semibold text-red-800 mb-2">
                Please fix the following:
            </p>

            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}

    <form
        method="POST"
        action="{{ route('admin.meals.store') }}"
        enctype="multipart/form-data"
        class="bg-white border rounded-2xl p-6 md:p-8"
    >

        @csrf


        {{-- Meal Plan --}}
        
    <div class="mb-6">
        <label class="block text-sm font-semibold mb-2">
            Meal Plan
        </label>

        <select
            name="meal_plan_id"
            required
            class="w-full border rounded-xl px-4 py-3"
        >
            <option value="">
                Select meal plan
            </option>

            @foreach($mealPlans as $plan)

                <option
                    value="{{ $plan->id }}"
                    @selected(
                        old('meal_plan_id', $selectedMealPlanId ?? '') == $plan->id
                    )
                >
                    {{ $plan->name }}
                </option>

            @endforeach
        </select>

        @error('meal_plan_id')
            <p class="text-red-600 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror
    </div>

        {{-- Meal Name --}}

        <div class="mb-6">

            <label
                for="name"
                class="block text-sm font-semibold mb-2"
            >
                Meal Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="255"
                placeholder="e.g. Beef Pilau & Vegetables"
                class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-black"
            >

        </div>


        {{-- Description --}}

        <div class="mb-6">

            <label
                for="description"
                class="block text-sm font-semibold mb-2"
            >
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Describe the meal..."
                class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-black"
            >{{ old('description') }}</textarea>

        </div>


        {{-- Day + Meal Type --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- Day --}}

            <div>

                <label
                    for="day_of_week"
                    class="block text-sm font-semibold mb-2"
                >
                    Day of Week
                </label>

                <select
                    id="day_of_week"
                    name="day_of_week"
                    required
                    class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-black"
                >

                    <option value="">
                        Select day
                    </option>

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

                    @foreach($days as $number => $day)

                        <option
                            value="{{ $number }}"
                            @selected(
                                old('day_of_week', $selectedDayOfWeek) == $number
                            )
                        >
                            {{ $day }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Meal Type --}}

            <div>

                <label
                    for="meal_type"
                    class="block text-sm font-semibold mb-2"
                >
                    Meal Type
                </label>

                <select
                    id="meal_type"
                    name="meal_type"
                    required
                    class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-black"
                >

                    <option value="">
                        Select meal type
                    </option>

                    <option
                        value="breakfast"
                        @selected(
                            old('meal_type', $selectedMealType) === 'breakfast'
                        )
                    >
                        Breakfast
                    </option>

                    <option
                        value="lunch"
                        @selected(
                            old('meal_type', $selectedMealType) === 'lunch'
                        )
                    >
                        Lunch
                    </option>

                    <option
                        value="supper"
                        @selected(
                            old('meal_type', $selectedMealType) === 'supper'
                        )
                    >
                        Supper
                    </option>

                </select>

            </div>

        </div>


        {{-- Image --}}

        <div class="mb-6">

            <label
                for="image"
                class="block text-sm font-semibold mb-2"
            >
                Meal Image
            </label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
                class="w-full border rounded-xl px-4 py-3"
            >

            <p class="text-xs text-gray-500 mt-2">
                Maximum size: 5MB.
            </p>

        </div>


        {{-- Status --}}

        <div class="mb-8">

            <label class="flex items-center gap-3 cursor-pointer">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', true))
                    class="w-5 h-5"
                >

                <span>

                    <span class="block font-semibold">
                        Active meal
                    </span>

                    <span class="block text-sm text-gray-500">
                        Customers can see and redeem this meal.
                    </span>

                </span>

            </label>

        </div>


        {{-- Actions --}}

        <div class="flex flex-col sm:flex-row gap-3">

            <button
                type="submit"
                class="flex-1 bg-black text-white px-5 py-3 rounded-xl font-semibold hover:bg-gray-800"
            >
                Create Meal
            </button>

            <a
                href="{{ url()->previous() }}"
                class="px-6 py-3 rounded-xl bg-gray-100 text-center font-semibold hover:bg-gray-200"
            >
                Cancel
            </a>

        </div>

    </form>

</main>

</body>

</html>