@extends('layouts.app')

@section('title', 'Add Meal - Silver Spoon')

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- HEADER --}}

    <div class="mb-8">

        <div class="mb-4">

            <a
                href="{{ url()->previous() }}"
                class="text-sm font-medium text-slate-500 transition hover:text-slate-900"
            >
                ← Back
            </a>

        </div>

        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
            Add Meal
        </h1>

        <p class="mt-2 text-slate-500">
            Add a meal to a meal plan's weekly schedule.
        </p>

    </div>


    {{-- FORM --}}

    <form
        method="POST"
        action="{{ route('admin.meals.store') }}"
        enctype="multipart/form-data"
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white"
    >

        @csrf


        <div class="p-6 md:p-8">

            {{-- MEAL PLAN --}}

            <div class="mb-6">

                <label
                    for="meal_plan_id"
                    class="mb-2 block text-sm font-semibold text-slate-900"
                >
                    Meal Plan
                </label>

                <select
                    id="meal_plan_id"
                    name="meal_plan_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

                    <option value="">
                        Select meal plan
                    </option>

                    @foreach($mealPlans as $plan)

                        <option
                            value="{{ $plan->id }}"
                            @selected(
                                old(
                                    'meal_plan_id',
                                    request('meal_plan_id', '')
                                ) == $plan->id
                            )
                        >
                            {{ $plan->name }}
                        </option>

                    @endforeach

                </select>

                @error('meal_plan_id')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- MEAL NAME --}}

            <div class="mb-6">

                <label
                    for="name"
                    class="mb-2 block text-sm font-semibold text-slate-900"
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
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

                @error('name')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- DESCRIPTION --}}

            <div class="mb-6">

                <label
                    for="description"
                    class="mb-2 block text-sm font-semibold text-slate-900"
                >
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Describe the meal..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >{{ old('description') }}</textarea>

                @error('description')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- DAY + TYPE --}}

            <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- DAY --}}

                <div>

                    <label
                        for="day_of_week"
                        class="mb-2 block text-sm font-semibold text-slate-900"
                    >
                        Day of Week
                    </label>

                    <select
                        id="day_of_week"
                        name="day_of_week"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                    >

                        <option value="">
                            Select day
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
                                @selected(
                                    old(
                                        'day_of_week',
                                        request('day_of_week', '')
                                    ) == $number
                                )
                            >
                                {{ $day }}
                            </option>

                        @endforeach

                    </select>

                    @error('day_of_week')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- TYPE --}}

                <div>

                    <label
                        for="meal_type"
                        class="mb-2 block text-sm font-semibold text-slate-900"
                    >
                        Meal Type
                    </label>

                    <select
                        id="meal_type"
                        name="meal_type"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                    >

                        <option value="">
                            Select meal type
                        </option>

                        @foreach([
                            'breakfast' => 'Breakfast',
                            'lunch' => 'Lunch',
                            'supper' => 'Supper',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected(
                                    old(
                                        'meal_type',
                                        request('meal_type', '')
                                    ) === $value
                                )
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                    @error('meal_type')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>


            {{-- IMAGE --}}

            <div class="mb-6">

                <label
                    for="image"
                    class="mb-2 block text-sm font-semibold text-slate-900"
                >
                    Meal Image
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                <p class="mt-2 text-xs text-slate-500">
                    Maximum size: 5MB.
                </p>

                @error('image')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- ACTIVE --}}

            <div>

                <label class="flex cursor-pointer items-start gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', true))
                        class="mt-1 h-5 w-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                    >

                    <span>

                        <span class="block font-semibold text-slate-900">
                            Active meal
                        </span>

                        <span class="mt-1 block text-sm text-slate-500">
                            Customers can see and redeem this meal.
                        </span>

                    </span>

                </label>

            </div>

        </div>


        {{-- ACTIONS --}}

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 p-6 sm:flex-row sm:justify-end">

            <a
                href="{{ url()->previous() }}"
                class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Create Meal
            </button>

        </div>

    </form>

</div>

@endsection