@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('meal-plans.show', $mealPlan) }}"
               class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to {{ $mealPlan->name }}
            </a>

            <div class="mt-4">
                <h1 class="text-3xl font-bold text-gray-900">
                    Customize Your {{ $mealPlan->name }} Plan
                </h1>

                <p class="mt-2 text-gray-600">
                    Choose exactly which meals you want during your subscription.
                </p>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                <div class="font-semibold text-red-800">
                    Please fix the following:
                </div>

                <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form
            method="POST"
            action="{{ route('meal-plans.customize.store', $mealPlan) }}"
            id="customize-form"
        >

            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- MEALS --}}
                <div class="lg:col-span-2 space-y-6">

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


                    @foreach ($days as $dayNumber => $dayName)

                        @php
                            $dayMeals = $meals->where(
                                'day_of_week',
                                $dayNumber
                            );
                        @endphp

                        @if ($dayMeals->count())

                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                                {{-- Day Header --}}
                                <div class="px-5 py-4 bg-gray-900 text-white">
                                    <h2 class="font-bold text-lg">
                                        {{ $dayName }}
                                    </h2>
                                </div>


                                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">

                                    @foreach ($mealTypes as $type => $typeName)

                                        @php
                                            $meal = $dayMeals
                                                ->where('meal_type', $type)
                                                ->first();
                                        @endphp

                                        @if ($meal)

                                            <label
                                                class="meal-option relative cursor-pointer"
                                            >

                                                <input
                                                    type="checkbox"
                                                    name="meal_ids[]"
                                                    value="{{ $meal->id }}"
                                                    class="meal-checkbox peer sr-only"
                                                    data-price="{{ $meal->price }}"
                                                    {{ in_array(
                                                        $meal->id,
                                                        old('meal_ids', [])
                                                    ) ? 'checked' : '' }}
                                                >

                                                <div class="
                                                    rounded-xl
                                                    border-2
                                                    border-gray-200
                                                    p-4
                                                    transition
                                                    peer-checked:border-gray-900
                                                    peer-checked:bg-gray-50
                                                    hover:border-gray-400
                                                ">

                                                    @if ($meal->image)

                                                        <img
                                                            src="{{ asset('storage/' . $meal->image) }}"
                                                            alt="{{ $meal->name }}"
                                                            class="w-full h-32 object-cover rounded-lg mb-3"
                                                        >

                                                    @endif


                                                    <div class="flex items-start justify-between gap-3">

                                                        <div>
                                                            <p class="text-xs uppercase tracking-wide text-gray-500">
                                                                {{ $typeName }}
                                                            </p>

                                                            <h3 class="font-semibold text-gray-900 mt-1">
                                                                {{ $meal->name }}
                                                            </h3>
                                                        </div>


                                                        <div class="
                                                            w-5 h-5
                                                            rounded-full
                                                            border-2
                                                            border-gray-300
                                                            peer-checked:bg-gray-900
                                                            peer-checked:border-gray-900
                                                            flex-shrink-0
                                                        "></div>

                                                    </div>


                                                    @if ($meal->description)

                                                        <p class="text-sm text-gray-500 mt-2">
                                                            {{ $meal->description }}
                                                        </p>

                                                    @endif


                                                    <div class="mt-4 font-bold text-gray-900">
                                                        KES {{ number_format($meal->price, 2) }}
                                                    </div>

                                                </div>

                                            </label>

                                        @endif

                                    @endforeach

                                </div>

                            </div>

                        @endif

                    @endforeach

                </div>


                {{-- SUMMARY --}}
                <div class="lg:col-span-1">

                    <div class="sticky top-6">

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                            <h2 class="text-xl font-bold text-gray-900">
                                Your Custom Plan
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $mealPlan->duration_days }} day subscription
                            </p>


                            <div class="mt-6">

                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Selected meals</span>

                                    <span id="selected-count">
                                        0
                                    </span>
                                </div>


                                <div class="flex justify-between mt-3 text-sm text-gray-600">
                                    <span>Meal price total</span>

                                    <span>
                                        KES
                                        <span id="meal-total">
                                            0.00
                                        </span>
                                    </span>
                                </div>

                            </div>


                            <div class="border-t border-gray-200 mt-6 pt-5">

                                <div class="flex justify-between items-end">

                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Estimated total
                                        </p>

                                        <p class="text-2xl font-bold text-gray-900">
                                            KES
                                            <span id="custom-total">
                                                0.00
                                            </span>
                                        </p>
                                    </div>

                                </div>

                            </div>


                            <button
                                type="submit"
                                id="continue-button"
                                disabled
                                class="
                                    mt-6
                                    w-full
                                    rounded-xl
                                    bg-gray-900
                                    px-5
                                    py-3
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-gray-800
                                    disabled:opacity-40
                                    disabled:cursor-not-allowed
                                "
                            >
                                Continue to Checkout
                            </button>


                            <p class="text-xs text-gray-500 text-center mt-4">
                                Your final selection will be saved to your subscription before payment.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.meal-checkbox');

    const selectedCount = document.getElementById('selected-count');
    const mealTotal = document.getElementById('meal-total');
    const customTotal = document.getElementById('custom-total');
    const continueButton = document.getElementById('continue-button');


    function calculateTotal() {

        let count = 0;
        let total = 0;


        checkboxes.forEach(function (checkbox) {

            if (checkbox.checked) {

                count++;

                total += parseFloat(
                    checkbox.dataset.price || 0
                );

            }

        });


        selectedCount.textContent = count;

        mealTotal.textContent = total.toFixed(2);

        customTotal.textContent = total.toFixed(2);

        continueButton.disabled = count === 0;

    }


    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            calculateTotal
        );

    });


    calculateTotal();

});

</script>

@endsection