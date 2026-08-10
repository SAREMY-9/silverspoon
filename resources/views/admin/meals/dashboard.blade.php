<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meal Operations Dashboard - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gray-100 min-h-screen">


<nav class="bg-black text-white">

    <div class="max-w-7xl mx-auto px-6 py-4">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            {{-- BRAND --}}

            <div>

                <a href="{{ route('admin.meals.dashboard') }}"
                   class="block">

                    <h1 class="font-bold text-lg">
                        Silver Spoon
                    </h1>

                    <p class="text-xs text-gray-400">
                        Meal Operations
                    </p>

                </a>

            </div>


            {{-- NAVIGATION --}}

            <div class="flex flex-wrap items-center gap-2 text-sm">

                {{-- Operations Dashboard --}}

                <a
                    href="{{ route('admin.meals.dashboard') }}"
                    class="px-3 py-2 rounded-lg
                           bg-white/10 text-white
                           hover:bg-white/20"
                >
                    Dashboard
                </a>


                {{-- Meal Plans --}}

                <a
                    href="{{ route('admin.meal-plans.index') }}"
                    class="px-3 py-2 rounded-lg
                           text-gray-300 hover:text-white
                           hover:bg-white/10"
                >
                    Meal Plans
                </a>


                {{-- Meals --}}

                <a
                    href="{{ route('admin.meals.index') }}"
                    class="px-3 py-2 rounded-lg
                           text-gray-300 hover:text-white
                           hover:bg-white/10"
                >
                    Meals
                </a>


                {{-- Reports --}}

                <a
                    href="{{ route('admin.meals.report') }}"
                    class="px-3 py-2 rounded-lg
                           text-gray-300 hover:text-white
                           hover:bg-white/10"
                >
                    Reports
                </a>


                {{-- Scanner --}}

                <a
                    href="{{ route('staff.meals.scan') }}"
                    class="px-3 py-2 rounded-lg
                           text-gray-300 hover:text-white
                           hover:bg-white/10"
                >
                    Scanner
                </a>


                {{-- USER --}}

                <div class="ml-2 pl-3 border-l border-gray-700">

                    <span class="text-gray-300">
                        {{ auth()->user()->name }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</nav>

<main class="max-w-7xl mx-auto px-6 py-8">


    {{-- HEADER --}}

    <div class="mb-8">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

            <div>

                <h2 class="text-3xl font-bold">
                    Meal Operations
                </h2>

                <p class="text-gray-500 mt-2">
                    Live overview of today's meal service.
                </p>

            </div>


            <div class="text-sm text-gray-500">

                {{ \Carbon\Carbon::parse($today)->format('l, d F Y') }}

            </div>

        </div>

    </div>



    {{-- TOP STATISTICS --}}

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Expected
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $totalExpected }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                meals today
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Served
            </p>

            <p class="text-3xl font-bold mt-2 text-green-600">
                {{ $totalServed }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                meals completed
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Remaining
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $totalRemaining }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                still available
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Expired
            </p>

            <p class="text-3xl font-bold mt-2 text-red-600">
                {{ $totalExpired }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                expired entitlements
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Active Plans
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $activeSubscriptions }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                active subscriptions
            </p>

        </div>

    </div>



    {{-- MEAL PERIODS --}}

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">


        @foreach(['breakfast', 'lunch', 'supper'] as $type)

            @php
                $stats = $mealStats[$type];
            @endphp


            <div class="bg-white border rounded-2xl p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
                            {{ $type }}
                        </p>

                        <h3 class="text-2xl font-bold mt-1">
                            {{ $stats['served'] }}
                            /
                            {{ $stats['expected'] }}
                        </h3>

                    </div>


                    <div class="text-right">

                        <p class="text-sm text-gray-500">
                            Remaining
                        </p>

                        <p class="text-2xl font-bold">
                            {{ $stats['remaining'] }}
                        </p>

                    </div>

                </div>


                @php
                    $percentage = $stats['expected'] > 0
                        ? round(($stats['served'] / $stats['expected']) * 100)
                        : 0;
                @endphp


                <div class="mt-5">

                    <div class="flex justify-between text-xs text-gray-500 mb-2">

                        <span>
                            Service progress
                        </span>

                        <span>
                            {{ $percentage }}%
                        </span>

                    </div>


                    <div class="w-full bg-gray-100 rounded-full h-3">

                        <div
                            class="bg-black h-3 rounded-full"
                            style="width: {{ $percentage }}%"
                        ></div>

                    </div>

                </div>


                @if($stats['expired'] > 0)

                    <p class="text-xs text-red-600 mt-4">
                        {{ $stats['expired'] }}
                        expired
                    </p>

                @endif

            </div>

        @endforeach

    </div>



    {{-- SERVICE SPLIT --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">


        <div class="bg-white border rounded-2xl p-6">

            <h3 class="text-xl font-bold">
                Today's Service
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                How today's meals are being redeemed.
            </p>


            <div class="grid grid-cols-2 gap-4 mt-6">


                <div class="border rounded-xl p-5">

                    <p class="text-sm text-gray-500">
                        Staff Served
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        {{ $staffServed }}
                    </p>

                </div>


                <div class="border rounded-xl p-5">

                    <p class="text-sm text-gray-500">
                        Customer Redeemed
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        {{ $customerSelfRedeemed }}
                    </p>

                </div>

            </div>

        </div>



        <div class="bg-white border rounded-2xl p-6">

            <h3 class="text-xl font-bold">
                Service by Meal
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Meals completed today.
            </p>


            <div class="space-y-3 mt-6">

                @foreach(['breakfast', 'lunch', 'supper'] as $type)

                    @php
                        $served = $serviceByMealType
                            ->get($type)?->total ?? 0;
                    @endphp


                    <div class="flex items-center justify-between border rounded-xl p-4">

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

    <div class="bg-white border rounded-2xl p-6 mb-8">

        <div class="mb-5">

            <h3 class="text-xl font-bold">
                Staff Activity
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Staff members who have served meals today.
            </p>

        </div>


        @if($staffPerformance->isEmpty())

            <div class="text-center text-gray-500 py-8">
                No staff service activity yet today.
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="text-left px-5 py-4">
                                Staff
                            </th>

                            <th class="text-left px-5 py-4">
                                Meals Served
                            </th>

                            <th class="text-left px-5 py-4">
                                First Service
                            </th>

                            <th class="text-left px-5 py-4">
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

                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 font-semibold">

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

    <div class="bg-white border rounded-2xl overflow-hidden mb-8">

        <div class="p-6 border-b">

            <h3 class="text-xl font-bold">
                Latest Service Activity
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                The most recent meals served today.
            </p>

        </div>


        @if($latestRedemptions->isEmpty())

            <div class="p-10 text-center text-gray-500">

                No meals have been served yet today.

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="text-left px-6 py-4">
                                Time
                            </th>

                            <th class="text-left px-6 py-4">
                                Customer
                            </th>

                            <th class="text-left px-6 py-4">
                                Meal
                            </th>

                            <th class="text-left px-6 py-4">
                                Type
                            </th>

                            <th class="text-left px-6 py-4">
                                Served By
                            </th>

                            <th class="text-left px-6 py-4">
                                Reference
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($latestRedemptions as $redemption)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 whitespace-nowrap font-semibold">

                                    {{ \Carbon\Carbon::parse($redemption->redeemed_at)->format('H:i:s') }}

                                </td>


                                <td class="px-6 py-4 font-semibold">

                                    {{ $redemption->customer_name }}

                                </td>


                                <td class="px-6 py-4">

                                    {{ $redemption->meal_name }}

                                </td>


                                <td class="px-6 py-4">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-xs font-semibold">

                                        {{ ucfirst($redemption->meal_type) }}

                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    {{ $redemption->staff_name ?? 'Customer' }}

                                </td>


                                <td class="px-6 py-4">

                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">

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

    <div class="bg-white border rounded-2xl p-6 mb-8">

        <div class="mb-5">

            <h3 class="text-xl font-bold">
                Exceptions
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Customers who may require attention today.
            </p>

        </div>


        @if($customersWithoutEntitlements->isEmpty())

            <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-green-700">

                All active subscriptions currently have meal entitlements scheduled for today.

            </div>

        @else

            <div class="space-y-3">

                @foreach($customersWithoutEntitlements as $subscription)

                    <div class="border border-red-200 bg-red-50 rounded-xl p-5">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div>

                                <p class="font-bold text-red-800">

                                    {{ $subscription->user->name }}

                                </p>

                                <p class="text-sm text-red-700 mt-1">

                                    Active subscription:
                                    {{ $subscription->mealPlan->name ?? 'Unknown plan' }}

                                </p>

                            </div>


                            <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                No entitlement today

                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>



{{-- QUICK LINKS --}}

<div class="mb-8">

    <div class="mb-5">

        <h3 class="text-xl font-bold">
            Quick Actions
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Manage menus, meals and today's operations.
        </p>

    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">


        {{-- Meal Plans --}}

        <a
            href="{{ route('admin.meal-plans.index') }}"
            class="group bg-white border rounded-2xl p-5
                   hover:border-black hover:shadow-sm transition"
        >

            <div class="flex items-center justify-between">

                <div class="text-2xl">
                    📋
                </div>

                <span class="text-gray-400 group-hover:text-black">
                    →
                </span>

            </div>

            <h4 class="font-bold mt-4">
                Create Meal
            </h4>

            <p class="text-sm text-gray-500 mt-1">
                Create and manage subscription plans.
            </p>

        </a>


        {{-- Meals --}}

        <a
            href="{{ route('admin.meals.index') }}"
            class="group bg-white border rounded-2xl p-5
                   hover:border-black hover:shadow-sm transition"
        >

            <div class="flex items-center justify-between">

                <div class="text-2xl">
                    🍽️
                </div>

                <span class="text-gray-400 group-hover:text-black">
                    →
                </span>

            </div>

            <h4 class="font-bold mt-4">
                Manage Meals
            </h4>

            <p class="text-sm text-gray-500 mt-1">
                Add, edit, activate or remove meals.
            </p>

        </a>


        {{-- Add Meal --}}

        <a
            href="{{ route('admin.meals.create') }}"
            class="group bg-white border rounded-2xl p-5
                   hover:border-black hover:shadow-sm transition"
        >

            <div class="flex items-center justify-between">

                <div class="text-2xl">
                    ➕
                </div>

                <span class="text-gray-400 group-hover:text-black">
                    →
                </span>

            </div>

            <h4 class="font-bold mt-4">
                Add Meal
            </h4>

            <p class="text-sm text-gray-500 mt-1">
                Create a meal for a meal plan.
            </p>

        </a>


        {{-- Scanner --}}

        <a
            href="{{ route('staff.meals.scan') }}"
            class="group bg-black text-white rounded-2xl p-5
                   hover:bg-gray-800 transition"
        >

            <div class="flex items-center justify-between">

                <div class="text-2xl">
                    📱
                </div>

                <span class="text-gray-400 group-hover:text-white">
                    →
                </span>

            </div>

            <h4 class="font-bold mt-4">
                Meal Scanner
            </h4>

            <p class="text-sm text-gray-400 mt-1">
                Scan customer QR codes and serve meals.
            </p>

        </a>

    </div>

</div>


{{-- REPORTING --}}

<div class="bg-white border rounded-2xl p-6 mb-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h3 class="text-xl font-bold">
                Reporting & Analytics
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Review historical meal service and export operational data.
            </p>

        </div>


        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('admin.meals.report') }}"
                class="px-5 py-3 rounded-xl bg-black text-white
                       font-semibold text-sm hover:bg-gray-800"
            >
                Service History
            </a>


            <a
                href="{{ route('admin.meals.report.export') }}"
                class="px-5 py-3 rounded-xl border border-gray-300
                       bg-white font-semibold text-sm hover:bg-gray-50"
            >
                Export CSV
            </a>

        </div>

    </div>

</div>


</main>

</body>
</html>