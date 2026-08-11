@extends('layouts.app')

@section('title', 'Meal Operations Dashboard - Silver Spoon')

@section('content')

    {{-- HEADER --}}
    <div class="mb-8">

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

            <div>

                <h2 class="text-3xl font-bold text-slate-900">
                    Meal Operations
                </h2>

                <p class="mt-2 text-slate-500">
                    Live overview of today's meal service.
                </p>

            </div>

            <div class="text-sm text-slate-500">
                {{ \Carbon\Carbon::parse($today)->format('l, d F Y') }}
            </div>

        </div>

    </div>


    {{-- TOP STATISTICS --}}
    <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-5">

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Expected</p>

            <p class="mt-2 text-3xl font-bold">
                {{ $totalExpected }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                meals today
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Served</p>

            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $totalServed }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                meals completed
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Remaining</p>

            <p class="mt-2 text-3xl font-bold">
                {{ $totalRemaining }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                still available
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Expired</p>

            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $totalExpired }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                expired entitlements
            </p>
        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Active Plans</p>

            <p class="mt-2 text-3xl font-bold">
                {{ $activeSubscriptions }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                active subscriptions
            </p>
        </div>

    </div>


    {{-- MEAL PERIODS --}}
    <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">

        @foreach(['breakfast', 'lunch', 'supper'] as $type)

            @php
                $stats = $mealStats[$type];

                $percentage = $stats['expected'] > 0
                    ? round(($stats['served'] / $stats['expected']) * 100)
                    : 0;
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            {{ $type }}
                        </p>

                        <h3 class="mt-1 text-2xl font-bold">
                            {{ $stats['served'] }}
                            /
                            {{ $stats['expected'] }}
                        </h3>
                    </div>

                    <div class="text-right">

                        <p class="text-sm text-slate-500">
                            Remaining
                        </p>

                        <p class="text-2xl font-bold">
                            {{ $stats['remaining'] }}
                        </p>

                    </div>

                </div>


                <div class="mt-5">

                    <div class="mb-2 flex justify-between text-xs text-slate-500">

                        <span>Service progress</span>

                        <span>{{ $percentage }}%</span>

                    </div>

                    <div class="h-3 w-full rounded-full bg-slate-100">

                        <div
                            class="h-3 rounded-full bg-slate-900"
                            style="width: {{ $percentage }}%"
                        ></div>

                    </div>

                </div>


                @if($stats['expired'] > 0)

                    <p class="mt-4 text-xs text-red-600">
                        {{ $stats['expired'] }} expired
                    </p>

                @endif

            </div>

        @endforeach

    </div>


    {{-- SERVICE SPLIT --}}
    <div class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h3 class="text-xl font-bold">
                Today's Service
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                How today's meals are being redeemed.
            </p>

            <div class="mt-6 grid grid-cols-2 gap-4">

                <div class="rounded-xl border border-slate-200 p-5">

                    <p class="text-sm text-slate-500">
                        Staff Served
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $staffServed }}
                    </p>

                </div>

                <div class="rounded-xl border border-slate-200 p-5">

                    <p class="text-sm text-slate-500">
                        Customer Redeemed
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $customerSelfRedeemed }}
                    </p>

                </div>

            </div>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h3 class="text-xl font-bold">
                Service by Meal
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Meals completed today.
            </p>

            <div class="mt-6 space-y-3">

                @foreach(['breakfast', 'lunch', 'supper'] as $type)

                    @php
                        $served = $serviceByMealType
                            ->get($type)?->total ?? 0;
                    @endphp

                    <div class="flex items-center justify-between rounded-xl border border-slate-200 p-4">

                        <span class="font-semibold">
                            {{ ucfirst($type) }}
                        </span>

                        <span class="text-xl font-bold">
                            {{ $served }}
                        </span>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- STAFF PERFORMANCE --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">

        <div class="mb-5">

            <h3 class="text-xl font-bold">
                Staff Activity
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Staff members who have served meals today.
            </p>

        </div>


        @if($staffPerformance->isEmpty())

            <div class="py-8 text-center text-slate-500">
                No staff service activity yet today.
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-4 text-left">
                                Staff
                            </th>

                            <th class="px-5 py-4 text-left">
                                Meals Served
                            </th>

                            <th class="px-5 py-4 text-left">
                                First Service
                            </th>

                            <th class="px-5 py-4 text-left">
                                Last Service
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach($staffPerformance as $member)

                            <tr>

                                <td class="px-5 py-4 font-semibold">
                                    {{ $member->staff_name }}
                                </td>

                                <td class="px-5 py-4">

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-semibold">
                                        {{ $member->meals_served }}
                                    </span>

                                </td>

                                <td class="px-5 py-4">
                                    {{ \Carbon\Carbon::parse($member->first_service)->format('H:i:s') }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ \Carbon\Carbon::parse($member->last_service)->format('H:i:s') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- LATEST SERVICE --}}
    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 p-6">

            <h3 class="text-xl font-bold">
                Latest Service Activity
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                The most recent meals served today.
            </p>

        </div>


        @if($latestRedemptions->isEmpty())

            <div class="p-10 text-center text-slate-500">
                No meals have been served yet today.
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-left">Time</th>
                            <th class="px-6 py-4 text-left">Customer</th>
                            <th class="px-6 py-4 text-left">Meal</th>
                            <th class="px-6 py-4 text-left">Type</th>
                            <th class="px-6 py-4 text-left">Served By</th>
                            <th class="px-6 py-4 text-left">Reference</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach($latestRedemptions as $redemption)

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4 font-semibold">
                                    {{ \Carbon\Carbon::parse($redemption->redeemed_at)->format('H:i:s') }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    {{ $redemption->customer_name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $redemption->meal_name }}
                                </td>

                                <td class="px-6 py-4">

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold">
                                        {{ ucfirst($redemption->meal_type) }}
                                    </span>

                                </td>

                                <td class="px-6 py-4">
                                    {{ $redemption->staff_name ?? 'Customer' }}
                                </td>

                                <td class="px-6 py-4">

                                    <code class="rounded bg-slate-100 px-2 py-1 text-xs">
                                        {{ $redemption->reference }}
                                    </code>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- EXCEPTIONS --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">

        <div class="mb-5">

            <h3 class="text-xl font-bold">
                Exceptions
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Customers who may require attention today.
            </p>

        </div>


        @if($customersWithoutEntitlements->isEmpty())

            <div class="rounded-xl border border-green-200 bg-green-50 p-5 text-green-700">
                All active subscriptions currently have meal entitlements scheduled for today.
            </div>

        @else

            <div class="space-y-3">

                @foreach($customersWithoutEntitlements as $subscription)

                    <div class="rounded-xl border border-red-200 bg-red-50 p-5">

                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                            <div>

                                <p class="font-bold text-red-800">
                                    {{ $subscription->user->name }}
                                </p>

                                <p class="mt-1 text-sm text-red-700">
                                    Active subscription:
                                    {{ $subscription->mealPlan->name ?? 'Unknown plan' }}
                                </p>

                            </div>

                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                No entitlement today
                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>


    {{-- QUICK ACTIONS --}}
    <div class="mb-8">

        <div class="mb-5">

            <h3 class="text-xl font-bold">
                Quick Actions
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Manage menus, meals and today's operations.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-900 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="text-2xl">
                        📋
                    </div>

                    <span class="text-slate-400 group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h4 class="mt-4 font-bold">
                    Meal Plans
                </h4>

                <p class="mt-1 text-sm text-slate-500">
                    Create and manage subscription plans.
                </p>

            </a>


            <a
                href="{{ route('admin.meals.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-900 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="text-2xl">
                        🍽️
                    </div>

                    <span class="text-slate-400 group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h4 class="mt-4 font-bold">
                    Manage Meals
                </h4>

                <p class="mt-1 text-sm text-slate-500">
                    Add, edit, activate or remove meals.
                </p>

            </a>


            <a
                href="{{ route('admin.meals.create') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-900 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="text-2xl">
                        ➕
                    </div>

                    <span class="text-slate-400 group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h4 class="mt-4 font-bold">
                    Add Meal
                </h4>

                <p class="mt-1 text-sm text-slate-500">
                    Create a meal for a meal plan.
                </p>

            </a>


            <a
                href="{{ route('staff.meals.scan') }}"
                class="group rounded-2xl bg-slate-900 p-5 text-white transition hover:bg-slate-800"
            >

                <div class="flex items-center justify-between">

                    <div class="text-2xl">
                        📱
                    </div>

                    <span class="text-slate-400 group-hover:text-white">
                        →
                    </span>

                </div>

                <h4 class="mt-4 font-bold">
                    Meal Scanner
                </h4>

                <p class="mt-1 text-sm text-slate-400">
                    Scan customer QR codes and serve meals.
                </p>

            </a>

        </div>

    </div>


    {{-- REPORTING --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h3 class="text-xl font-bold">
                    Reporting & Analytics
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Review historical meal service and export operational data.
                </p>

            </div>

            <div class="flex flex-wrap gap-3">

                <a
                    href="{{ route('admin.meals.report') }}"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Service History
                </a>

                <a
                    href="{{ route('admin.meals.report.export') }}"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold hover:bg-slate-50"
                >
                    Export CSV
                </a>

            </div>

        </div>

    </div>

@endsection