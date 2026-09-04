@extends('layouts.app')

@section('title', $meal->name . ' - Silver Spoon')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>
            <p class="text-sm text-slate-500 mb-1">
                Meal Details
            </p>

            <h1 class="text-3xl font-bold text-slate-900">
                {{ $meal->name }}
            </h1>

            <p class="text-sm text-slate-500 mt-2">
                View meal information and scheduling details.
            </p>
        </div>

        <div class="flex gap-3">

            <a
                href="{{ route('admin.meals.edit', $meal) }}"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
            >
                Edit Meal
            </a>

            <a
                href="{{ route('admin.meals.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Back to Meals
            </a>

        </div>

    </div>


    {{-- Main card --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Image --}}
        <div class="lg:col-span-1">

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                @if($meal->image)

                    <img
                        src="{{ asset('storage/' . $meal->image) }}"
                        alt="{{ $meal->name }}"
                        class="w-full aspect-square object-cover"
                    >

                @else

                    <div class="flex aspect-square items-center justify-center bg-slate-100">

                        <div class="text-center">

                            <div class="text-5xl mb-3">
                                🍽️
                            </div>

                            <p class="text-sm text-slate-500">
                                No image uploaded
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>


      {{-- Details --}}
        <div class="lg:col-span-2">

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex items-center justify-between mb-6">

                    <h2 class="text-xl font-bold">
                        Meal Information
                    </h2>

                    @if($meal->is_active)

                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Active
                        </span>

                    @else

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            Inactive
                        </span>

                    @endif

                </div>


                <div class="divide-y divide-slate-100">

                    {{-- Name --}}
                    <div class="flex items-center justify-between py-4">

                        <span class="text-sm text-slate-500">
                            Meal Name
                        </span>

                        <span class="font-semibold text-slate-900">
                            {{ $meal->name }}
                        </span>

                    </div>


                    {{-- Price --}}
                    <div class="flex items-center justify-between py-4">

                        <span class="text-sm text-slate-500">
                            Meal Price
                        </span>

                        <span class="text-lg font-bold text-slate-900">
                            KES {{ number_format($meal->price ?? 0, 2) }}
                        </span>

                    </div>


                    {{-- Meal Plan --}}
                    <div class="flex items-center justify-between py-4">

                        <span class="text-sm text-slate-500">
                            Meal Plan
                        </span>

                        <span class="font-semibold text-slate-900">

                            {{ $meal->mealPlan->name ?? 'No meal plan' }}

                        </span>

                    </div>


                    {{-- Day --}}
                    <div class="flex items-center justify-between py-4">

                        <span class="text-sm text-slate-500">
                            Day
                        </span>

                        <span class="font-semibold text-slate-900">

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

                            {{ $days[$meal->day_of_week] ?? 'Unknown' }}

                        </span>

                    </div>


                    {{-- Meal Type --}}
                    <div class="flex items-center justify-between py-4">

                        <span class="text-sm text-slate-500">
                            Meal Type
                        </span>

                        <span class="font-semibold capitalize text-slate-900">
                            {{ $meal->meal_type }}
                        </span>

                    </div>


                    {{-- Description --}}
                    <div class="py-4">

                        <p class="text-sm text-slate-500 mb-2">
                            Description
                        </p>

                        <p class="text-slate-700">
                            {{ $meal->description ?: 'No description provided.' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection