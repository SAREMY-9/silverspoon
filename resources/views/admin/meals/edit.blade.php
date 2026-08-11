@extends('layouts.app')

@section('title', 'Edit Meal - Silver Spoon')

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- HEADER --}}

    <div class="mb-8">

        <div class="mb-4">

            <a
                href="{{ route('admin.meals.index') }}"
                class="text-sm font-medium text-slate-500 transition hover:text-slate-900"
            >
                ← Back to Meals
            </a>

        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    Edit Meal
                </h1>

                <p class="mt-2 text-slate-500">
                    Update the meal's details, schedule or availability.
                </p>

            </div>

            @if($meal->is_active)

                <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    Active
                </span>

            @else

                <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    Inactive
                </span>

            @endif

        </div>

    </div>


    {{-- FORM --}}

    <form
        method="POST"
        action="{{ route('admin.meals.update', $meal) }}"
        enctype="multipart/form-data"
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white"
    >

        @csrf
        @method('PUT')


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

                    @foreach($mealPlans as $plan)

                        <option
                            value="{{ $plan->id }}"
                            @selected(
                                old(
                                    'meal_plan_id',
                                    $meal->meal_plan_id
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


            {{-- NAME --}}

            <div class="mb-6">

                <label
                    for="name"
                    class="mb-2 block text-sm font-semibold text-slate-900"
                >
                    Meal Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $meal->name) }}"
                    required
                    maxlength="255"
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
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm transition focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >{{ old('description', $meal->description) }}</textarea>

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
                                        $meal->day_of_week
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
                                        $meal->meal_type
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


            {{-- CURRENT IMAGE --}}

            @if($meal->image)

                <div class="mb-6">

                    <p class="mb-2 text-sm font-semibold text-slate-900">
                        Current Image
                    </p>

                    <img
                        src="{{ asset('storage/' . $meal->image) }}"
                        alt="{{ $meal->name }}"
                        class="h-48 w-full max-w-md rounded-2xl border border-slate-200 object-cover"
                    >

                </div>

            @endif


            {{-- NEW IMAGE --}}

            <div class="mb-6">

                <label
                    for="image"
                    class="mb-2 block text-sm font-semibold text-slate-900"
                >
                    {{ $meal->image ? 'Replace Image' : 'Meal Image' }}
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"
                >

                <p class="mt-2 text-xs text-slate-500">
                    Maximum size: 5MB. Leave empty to keep the current image.
                </p>

                @error('image')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- STATUS --}}

            <div>

                <label class="flex cursor-pointer items-start gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $meal->is_active))
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

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">

            <a
                href="{{ route('admin.meals.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Save Changes
            </button>

        </div>

    </form>


    {{-- DANGER ZONE --}}

    <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-6">

        <h2 class="font-bold text-red-900">
            Danger Zone
        </h2>

        <p class="mt-1 text-sm text-red-700">
            Deleting a meal is permanent. Meals with entitlement or redemption history cannot be deleted.
        </p>

        <form
            method="POST"
            action="{{ route('admin.meals.destroy', $meal) }}"
            class="mt-4"
            onsubmit="return confirm('Permanently delete this meal? This cannot be undone.')"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="rounded-xl border border-red-300 bg-white px-5 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100"
            >
                Delete Meal
            </button>

        </form>

    </div>

</div>

@endsection