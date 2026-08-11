@extends('layouts.app')

@section('title', 'Admin Dashboard - Silver Spoon')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

        <div>
            <p class="text-sm font-semibold text-slate-500">
                Administration
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                Admin Dashboard
            </h1>

            <p class="mt-2 text-slate-500">
                Manage Silver Spoon's users, meals, subscriptions and operations.
            </p>
        </div>

        <div class="text-sm text-slate-500">
            {{ now()->format('l, d F Y') }}
        </div>

    </div>


    {{-- OVERVIEW --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- USERS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Total Users
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalUsers }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Registered accounts
            </p>

        </div>


        {{-- CUSTOMERS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Customers
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalCustomers }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Customer accounts
            </p>

        </div>


        {{-- STAFF --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Staff
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalStaff }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Staff accounts
            </p>

        </div>


        {{-- SUBSCRIPTIONS --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Active Subscriptions
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $activeSubscriptions }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Currently active
            </p>

        </div>

    </div>


    {{-- MANAGEMENT --}}
    <div>

        <div class="mb-5">

            <h2 class="text-xl font-bold text-slate-900">
                Management
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Manage the core Silver Spoon system.
            </p>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">


            {{-- USERS --}}
            <a
                href="{{ route('admin.users.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-400 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl">
                        👥
                    </div>

                    <span class="text-slate-400 transition group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h3 class="mt-5 font-bold text-slate-900">
                    Users
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Manage customers, staff and administrator accounts.
                </p>

            </a>


            {{-- MEAL PLANS --}}
            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-400 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl">
                        📋
                    </div>

                    <span class="text-slate-400 transition group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h3 class="mt-5 font-bold text-slate-900">
                    Meal Plans
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Create and manage subscription meal plans.
                </p>

            </a>


            {{-- MEALS --}}
            <a
                href="{{ route('admin.meals.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-400 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl">
                        🍽️
                    </div>

                    <span class="text-slate-400 transition group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h3 class="mt-5 font-bold text-slate-900">
                    Meals
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Add, edit, activate and manage meals.
                </p>

                <p class="mt-4 text-xs text-slate-400">
                    {{ $activeMeals }} active / {{ $totalMeals }} total
                </p>

            </a>


            {{-- OPERATIONS --}}
            <a
                href="{{ route('admin.meals.dashboard') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-400 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl">
                        📊
                    </div>

                    <span class="text-slate-400 transition group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h3 class="mt-5 font-bold text-slate-900">
                    Meal Operations
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Monitor today's service, redemptions and staff activity.
                </p>

            </a>


            {{-- REPORTS --}}
            <a
                href="{{ route('admin.meals.report') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-400 hover:shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl">
                        📈
                    </div>

                    <span class="text-slate-400 transition group-hover:text-slate-900">
                        →
                    </span>

                </div>

                <h3 class="mt-5 font-bold text-slate-900">
                    Reports
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Review historical meal service and export reports.
                </p>

            </a>


            {{-- SCANNER --}}
            <a
                href="{{ route('staff.meals.scan') }}"
                class="group rounded-2xl bg-slate-900 p-6 text-white transition hover:bg-slate-800"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-xl">
                        📱
                    </div>

                    <span class="text-slate-400 transition group-hover:text-white">
                        →
                    </span>

                </div>

                <h3 class="mt-5 font-bold">
                    Meal Scanner
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    Scan customer QR codes and serve meals.
                </p>

            </a>

        </div>

    </div>


    {{-- MEAL PLAN SUMMARY --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h2 class="text-xl font-bold text-slate-900">
                Meal Plans
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Current meal plan configuration.
            </p>

            <div class="mt-6 grid grid-cols-2 gap-4">

                <div class="rounded-xl border border-slate-200 p-5">

                    <p class="text-sm text-slate-500">
                        Total Plans
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $totalMealPlans }}
                    </p>

                </div>

                <div class="rounded-xl border border-slate-200 p-5">

                    <p class="text-sm text-slate-500">
                        Active Plans
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $activeMealPlans }}
                    </p>

                </div>

            </div>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-6">

            <h2 class="text-xl font-bold text-slate-900">
                Quick Actions
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Common administrative tasks.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                <a
                    href="{{ route('admin.meals.create') }}"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    + Add Meal
                </a>

                <a
                    href="{{ route('admin.meal-plans.index') }}"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold hover:bg-slate-50"
                >
                    Manage Plans
                </a>

                <a
                    href="{{ route('admin.meals.report') }}"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold hover:bg-slate-50"
                >
                    View Reports
                </a>

            </div>

        </div>

    </div>

</div>

@endsection