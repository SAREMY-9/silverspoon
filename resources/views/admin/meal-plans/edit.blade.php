@extends('layouts.app')

@section('title', 'Edit Meal Plan - Silver Spoon')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">

        <div class="flex items-center gap-3 mb-2">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="text-sm text-slate-500 hover:text-slate-900"
            >
                ← Meal Plans
            </a>

        </div>

        <p class="text-sm text-slate-500 uppercase font-semibold tracking-wider">
            Meal Management
        </p>

        <h1 class="text-3xl font-bold mt-1 text-slate-900">
            Edit Meal Plan
        </h1>

        <p class="text-slate-500 mt-2">
            Update {{ $mealPlan->name }}.
        </p>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-5 mb-6">

            <p class="font-bold mb-2">
                Please fix the following:
            </p>

            <ul class="list-disc ml-5 text-sm space-y-1">

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
        action="{{ route('admin.meal-plans.update', $mealPlan) }}"
        class="bg-white border border-slate-200 rounded-2xl p-6 space-y-6 shadow-sm"
    >

        @csrf

        @method('PUT')


        {{-- Plan Name --}}
        <div>

            <label
                for="name"
                class="block text-sm font-semibold mb-2 text-slate-700"
            >
                Plan Name
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name', $mealPlan->name) }}"
                required
                class="w-full border border-slate-300 rounded-xl px-4 py-3
                       focus:ring-2 focus:ring-slate-900 focus:outline-none"
            >

        </div>


        {{-- Description --}}
        <div>

            <label
                for="description"
                class="block text-sm font-semibold mb-2 text-slate-700"
            >
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                class="w-full border border-slate-300 rounded-xl px-4 py-3
                       focus:ring-2 focus:ring-slate-900 focus:outline-none"
            >{{ old('description', $mealPlan->description) }}</textarea>

        </div>


        {{-- Pricing --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>

                <label
                    for="price"
                    class="block text-sm font-semibold mb-2 text-slate-700"
                >
                    Price (KES)
                </label>

                <input
                    id="price"
                    type="number"
                    name="price"
                    value="{{ old('price', $mealPlan->price) }}"
                    min="0"
                    step="0.01"
                    required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3
                           focus:ring-2 focus:ring-slate-900 focus:outline-none"
                >

            </div>


            <div>

                <label
                    for="meal_limit"
                    class="block text-sm font-semibold mb-2 text-slate-700"
                >
                    Meal Limit
                </label>

                <input
                    id="meal_limit"
                    type="number"
                    name="meal_limit"
                    value="{{ old('meal_limit', $mealPlan->meal_limit) }}"
                    min="1"
                    required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3
                           focus:ring-2 focus:ring-slate-900 focus:outline-none"
                >

            </div>


            <div>

                <label
                    for="duration_days"
                    class="block text-sm font-semibold mb-2 text-slate-700"
                >
                    Duration (Days)
                </label>

                <input
                    id="duration_days"
                    type="number"
                    name="duration_days"
                    value="{{ old('duration_days', $mealPlan->duration_days) }}"
                    min="1"
                    required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3
                           focus:ring-2 focus:ring-slate-900 focus:outline-none"
                >

            </div>

        </div>


        {{-- Active --}}
        <label class="flex items-center gap-3 cursor-pointer">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $mealPlan->is_active))
                class="w-5 h-5 rounded border-slate-300"
            >

            <span>

                <span class="font-semibold block text-slate-900">
                    Active plan
                </span>

                <span class="text-sm text-slate-500">
                    Customers can purchase this plan.
                </span>

            </span>

        </label>


        {{-- Actions --}}
        <div class="flex gap-3 pt-4">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="flex-1 text-center border border-slate-300
                       rounded-xl px-5 py-3 font-semibold
                       hover:bg-slate-50 transition"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="flex-1 bg-slate-900 text-white rounded-xl
                       px-5 py-3 font-semibold hover:bg-slate-800 transition"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection