<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>


<body class="bg-gray-50 min-h-screen">


{{-- =========================================================
     NAVIGATION
========================================================= --}}

<nav class="bg-white border-b">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a
            href="{{ route('home') }}"
            class="text-xl font-bold"
        >
            Silver Spoon
        </a>

        <div class="flex items-center gap-5">

            <span class="text-sm text-gray-600">
                {{ $user->name }}
            </span>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="text-sm text-red-600 hover:underline"
                >
                    Logout
                </button>

            </form>

        </div>

    </div>

</nav>



<main class="max-w-7xl mx-auto px-6 py-10">


{{-- =========================================================
     HEADER
========================================================= --}}

<div class="mb-8">

    <p class="text-sm text-gray-500">
        {{ now()->format('l, d F Y') }}
    </p>

    <h1 class="text-3xl md:text-4xl font-bold mt-1">
        Welcome, {{ $user->name }}
    </h1>

    <p class="text-gray-500 mt-2">
        Manage your Silver Spoon meals and subscription.
    </p>

</div>



@if ($activeSubscription)

    @php

        /*
         * Determine whether this is a custom subscription.
         */
        $isCustomSubscription =
            $activeSubscription->mealSelections->isNotEmpty();

        /*
         * Actual meals that were generated for this subscription.
         */
        $scheduledMealCount =
            $activeSubscription->entitlements->count();

        /*
         * Custom selections.
         */
        $customSelections =
            $activeSubscription->mealSelections;

        /*
         * Group custom selections by meal type.
         */
        $selectionGroups =
            $customSelections->groupBy('meal_type');

    @endphp



    {{-- =====================================================
         SUBSCRIPTION SUMMARY
    ====================================================== --}}

    <div class="bg-black text-white rounded-2xl p-7 mb-8">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

            <div>

                <p class="text-sm text-gray-400">
                    Active subscription
                </p>

                <h2 class="text-2xl font-bold mt-1">
                    {{ $activeSubscription->mealPlan->name }}
                </h2>

                <p class="text-gray-400 mt-2">

                    {{ $activeSubscription->starts_at?->format('d M Y') }}

                    —

                    {{ $activeSubscription->ends_at?->format('d M Y') }}

                </p>

            </div>


            <div class="grid grid-cols-2 md:grid-cols-3 gap-8">

                {{-- Subscription days --}}

                {{-- Actual meals --}}

                <div>

                    <p class="text-sm text-gray-400">
                        Scheduled meals
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $scheduledMealCount }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        deliveries
                    </p>

                </div>


                {{-- Days remaining --}}

                <div>

                    <p class="text-sm text-gray-400">
                        Days remaining
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $daysRemaining }}
                    </p>

                </div>

            </div>

        </div>

    </div>


<!-----
    {{-- =====================================================
         CUSTOM MEAL SCHEDULE
    ====================================================== --}}

    @if ($isCustomSubscription)

        <section class="mb-10">

            <div class="mb-5">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-2xl font-bold">
                            Your Meal Schedule
                        </h2>

                        <p class="text-gray-500 mt-1">
                            Your selected meals repeat on the chosen days
                            throughout your subscription.
                        </p>

                    </div>

                    <span class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-black text-white text-xs font-semibold">
                        Custom Plan
                    </span>

                </div>

            </div>



            <div class="grid md:grid-cols-3 gap-5">

                @foreach ([
                    'breakfast' => 'Breakfast',
                    'lunch' => 'Lunch',
                    'supper' => 'Supper'
                ] as $type => $label)

                    @php
                        $selectionsForType =
                            $customSelections->where(
                                'meal_type',
                                $type
                            );
                    @endphp


                    @if ($selectionsForType->isNotEmpty())

                        <div class="bg-white border rounded-2xl p-6">

                            <div class="flex items-center justify-between mb-5">

                                <h3 class="font-bold text-lg">
                                    {{ $label }}
                                </h3>

                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    {{ $selectionsForType->count() }}
                                    {{ $selectionsForType->count() === 1 ? 'day' : 'days' }}
                                </span>

                            </div>


                            <div class="space-y-3">

                                @foreach ($selectionsForType as $selection)

                                    @php

                                        $dayName = match (
                                            (int) $selection->day_of_week
                                        ) {
                                            1 => 'Monday',
                                            2 => 'Tuesday',
                                            3 => 'Wednesday',
                                            4 => 'Thursday',
                                            5 => 'Friday',
                                            6 => 'Saturday',
                                            7 => 'Sunday',
                                            default => 'Unknown day',
                                        };

                                    @endphp


                                    <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $dayName }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $selection->meal->name }}
                                            </p>

                                        </div>


                                        <span class="font-semibold text-gray-900">
                                            KES {{ number_format($selection->unit_price, 2) }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>

        </section>


    @else

        {{-- =================================================
             STANDARD PLAN
        ================================================== --}}

        <section class="mb-10">

            <div class="bg-white border rounded-2xl p-6">

                <h2 class="text-xl font-bold">
                    Standard Meal Plan
                </h2>

                <p class="text-gray-500 mt-2">
                    Your meals follow the standard schedule configured
                    for the {{ $activeSubscription->mealPlan->name }}.
                </p>

            </div>

        </section>

    @endif

------>

    {{-- =====================================================
         CUSTOMER QR CODE
    ====================================================== --}}

    <div class="bg-white border rounded-2xl p-6 mb-10">

        <div class="flex flex-col md:flex-row md:items-center gap-8">

            <div class="flex-1">

                <p class="text-sm text-gray-500">
                    Your Silver Spoon Pass
                </p>

                <h2 class="text-2xl font-bold mt-1">
                    Show this QR when collecting your meal
                </h2>

                <p class="text-gray-500 mt-2">
                    A Silver Spoon staff member will scan this code
                    to verify your subscription and serve your scheduled meal.
                </p>

                <div class="mt-4">

                    <span class="inline-flex px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                        {{ $scheduledMealCount }} scheduled deliveries
                    </span>

                </div>

            </div>


            <div class="flex justify-center">

                <div
                    id="customer-qr"
                    class="bg-white p-4 rounded-2xl border"
                ></div>

            </div>

        </div>

    </div>



    {{-- =====================================================
         TODAY'S MEALS
    ====================================================== --}}

    <section class="mb-12">

        <div class="mb-5">

            <h2 class="text-2xl font-bold">
                Today's Meals
            </h2>

            <p class="text-gray-500 mt-1">
                Meals scheduled for today.
            </p>

        </div>


        @if ($todayEntitlements->isNotEmpty())

            <div class="grid md:grid-cols-3 gap-6">

                @foreach ($todayEntitlements as $entitlement)

                    <div
                        class="bg-white rounded-2xl border shadow-sm overflow-hidden"
                        id="entitlement-{{ $entitlement->id }}"
                    >

                        @if ($entitlement->meal->image)

                            <img
                                src="{{ asset('storage/' . $entitlement->meal->image) }}"
                                alt="{{ $entitlement->meal->name }}"
                                class="w-full h-44 object-cover"
                            >

                        @else

                            <div class="w-full h-44 bg-gray-100 flex items-center justify-center">

                                <span class="text-gray-400">
                                    No image
                                </span>

                            </div>

                        @endif


                        <div class="p-6">

                            <div class="flex items-center justify-between">

                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    {{ ucfirst($entitlement->meal->meal_type) }}
                                </span>


                                @if ($entitlement->status->value === 'available')

                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Available
                                    </span>

                                @elseif ($entitlement->status->value === 'redeemed')

                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Redeemed
                                    </span>

                                @else

                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                        Expired
                                    </span>

                                @endif

                            </div>


                            <h3 class="text-xl font-bold mt-3">
                                {{ $entitlement->meal->name }}
                            </h3>


                            @if ($entitlement->meal->description)

                                <p class="text-sm text-gray-500 mt-2">
                                    {{ $entitlement->meal->description }}
                                </p>

                            @endif


                            @if ($entitlement->status->value === 'available')

                                <button
                                    type="button"
                                    class="redeem-button w-full mt-6 bg-black text-white rounded-xl py-3 font-semibold hover:bg-gray-800 transition"
                                    data-url="{{ route('dashboard.meals.redeem', $entitlement) }}"
                                    data-meal="{{ $entitlement->meal->name }}"
                                    data-type="{{ ucfirst($entitlement->meal->meal_type) }}"
                                >
                                    Redeem Meal
                                </button>


                            @elseif ($entitlement->status->value === 'redeemed')

                                <div class="mt-6 text-center">

                                    <div class="text-green-600 font-semibold">
                                        ✓ Meal redeemed
                                    </div>

                                    @php
                                        $redemption =
                                            $entitlement->redemption ?? null;
                                    @endphp

                                    @if ($redemption)

                                        <p class="text-xs text-gray-500 mt-1">
                                            Reference:
                                            {{ $redemption->reference }}
                                        </p>

                                    @endif

                                </div>


                            @else

                                <div class="mt-6 text-center">

                                    <p class="text-gray-400 font-semibold">
                                        Meal expired
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="bg-white border rounded-2xl p-10 text-center">

                <div class="text-3xl mb-3">
                    🍽️
                </div>

                <p class="text-gray-500">
                    You don't have a meal scheduled for today.
                </p>

            </div>

        @endif

    </section>



    {{-- =====================================================
         UPCOMING MEALS
    ====================================================== --}}

    <section class="mb-12">

        <div class="mb-5">

            <h2 class="text-2xl font-bold">
                Upcoming Meals
            </h2>

            <p class="text-gray-500 mt-1">
                Your next scheduled meal deliveries.
            </p>

        </div>


        @if ($upcomingEntitlements->isNotEmpty())

            <div class="bg-white border rounded-2xl overflow-hidden">

                <div class="divide-y">

                    @foreach ($upcomingEntitlements as $entitlement)

                        <div
                            class="px-6 py-5 flex items-center justify-between gap-4"
                        >

                            <div class="flex items-center gap-4">

                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-sm font-bold">

                                    {{ $entitlement->scheduled_for->format('d') }}

                                </div>


                                <div>

                                    <p class="text-sm text-gray-500">

                                        {{ $entitlement->scheduled_for->format('l, d M') }}

                                    </p>

                                    <p class="font-semibold mt-1">

                                        {{ $entitlement->meal->name }}

                                    </p>

                                </div>

                            </div>


                            <div class="text-right">

                                <span class="text-xs uppercase font-semibold text-gray-500">

                                    {{ ucfirst($entitlement->meal->meal_type) }}

                                </span>

                                <p class="text-xs text-gray-400 mt-1">
                                    Scheduled
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


        @else

            <div class="bg-white border rounded-2xl p-8 text-center">

                <p class="text-gray-500">
                    No upcoming meals.
                </p>

            </div>

        @endif

    </section>



@else

    {{-- =====================================================
         NO ACTIVE SUBSCRIPTION
    ====================================================== --}}

    <div class="bg-white border rounded-2xl p-10 text-center mb-10">

        <div class="text-4xl mb-4">
            🍽️
        </div>

        <h2 class="text-2xl font-bold">
            You don't have an active meal plan
        </h2>

        <p class="text-gray-500 mt-2">
            Choose your meals and create your Silver Spoon schedule.
        </p>

        <a
            href="{{ route('meal-plans.index') }}"
            class="inline-block mt-6 bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-800"
        >
            Browse Meal Plans
        </a>

    </div>

@endif



{{-- =========================================================
     SUBSCRIPTION HISTORY
========================================================= --}}

<section>

    <h2 class="text-2xl font-bold mb-5">
        Subscription History
    </h2>


    @forelse ($subscriptions as $subscription)

        <div class="bg-white border rounded-xl p-5 mb-3">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                <div>

                    <h3 class="font-semibold">
                        {{ $subscription->mealPlan->name }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">

                        {{ $subscription->starts_at?->format('d M Y') }}

                        —

                        {{ $subscription->ends_at?->format('d M Y') }}

                    </p>

                </div>


                <span class="inline-flex w-fit px-3 py-1 rounded-full text-xs font-semibold bg-gray-100">

                    {{ ucfirst($subscription->status->value) }}

                </span>

            </div>

        </div>

    @empty

        <div class="bg-white border rounded-xl p-8 text-center">

            <p class="text-gray-500">
                No subscription history yet.
            </p>

        </div>

    @endforelse

</section>


</main>



{{-- =========================================================
     REDEMPTION MODAL
========================================================= --}}

<div
    id="redeemModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
>

    <div
        class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6"
        id="redeemModalContent"
    >

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-xl font-bold">
                Redeem Meal
            </h2>

            <button
                type="button"
                id="closeRedeemModal"
                class="text-gray-400 hover:text-gray-700 text-2xl"
            >
                &times;
            </button>

        </div>


        <div class="bg-gray-50 rounded-xl p-4 mb-5">

            <p
                id="modalMealName"
                class="font-bold text-lg"
            ></p>

            <p
                id="modalMealType"
                class="text-sm text-gray-500 mt-1"
            ></p>

        </div>


        <p class="text-gray-600 text-sm mb-6">
            Are you sure you want to redeem this meal?
            Once redeemed, it cannot be used again.
        </p>


        <div class="flex gap-3">

            <button
                type="button"
                id="cancelRedeem"
                class="flex-1 border border-gray-300 rounded-xl py-3 font-semibold hover:bg-gray-50"
            >
                Cancel
            </button>

            <button
                type="button"
                id="confirmRedeem"
                class="flex-1 bg-black text-white rounded-xl py-3 font-semibold hover:bg-gray-800"
            >
                Confirm Redemption
            </button>

        </div>

    </div>

</div>



{{-- =========================================================
     REDEMPTION JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', () => {

    const modal =
        document.getElementById('redeemModal');

    const closeModal =
        document.getElementById('closeRedeemModal');

    const cancelRedeem =
        document.getElementById('cancelRedeem');

    const confirmRedeem =
        document.getElementById('confirmRedeem');

    const modalMealName =
        document.getElementById('modalMealName');

    const modalMealType =
        document.getElementById('modalMealType');


    let selectedButton = null;
    let selectedUrl = null;


    function openModal(button) {

        selectedButton = button;
        selectedUrl = button.dataset.url;

        modalMealName.textContent =
            button.dataset.meal;

        modalMealType.textContent =
            button.dataset.type;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }


    function closeRedemptionModal() {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

        selectedButton = null;
        selectedUrl = null;

    }


    document
        .querySelectorAll('.redeem-button')
        .forEach(button => {

            button.addEventListener('click', () => {
                openModal(button);
            });

        });


    closeModal.addEventListener(
        'click',
        closeRedemptionModal
    );


    cancelRedeem.addEventListener(
        'click',
        closeRedemptionModal
    );


    modal.addEventListener('click', event => {

        if (event.target === modal) {
            closeRedemptionModal();
        }

    });


    confirmRedeem.addEventListener(
        'click',
        async () => {

            if (!selectedButton || !selectedUrl) {
                return;
            }


            const button = selectedButton;


            confirmRedeem.disabled = true;

            confirmRedeem.textContent =
                'Redeeming...';


            try {

                const response = await fetch(
                    selectedUrl,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute(
                                        'content'
                                    )
                        }
                    }
                );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Unable to redeem meal.'
                    );

                }


                closeRedemptionModal();


                const container =
                    button.parentElement;


                container.innerHTML = `

                    <div class="mt-6 text-center">

                        <div class="text-green-600 font-semibold">
                            ✓ Meal redeemed
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            Reference:
                            ${data.reference}
                        </p>

                    </div>

                `;


            } catch (error) {

                alert(
                    error.message ||
                    'Unable to redeem meal.'
                );


                confirmRedeem.disabled = false;

                confirmRedeem.textContent =
                    'Confirm Redemption';

            }

        }
    );

});

</script>



{{-- =========================================================
     QR CODE
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>


@if($activeSubscription)

<script>

new QRCode(
    document.getElementById('customer-qr'),
    {
        text:
            "{{ route('staff.meals.scan') }}?token={{ $activeSubscription->qr_token }}",

        width: 220,

        height: 220,

        correctLevel:
            QRCode.CorrectLevel.H
    }
);

</script>

@endif


</body>
</html>