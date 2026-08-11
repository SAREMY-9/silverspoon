@extends('layouts.app')

@section('title', 'Staff Dashboard - Silver Spoon')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

        <div>

            <p class="text-sm font-semibold text-slate-500">
                Staff Portal
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                Staff Dashboard
            </h1>

            <p class="mt-2 text-slate-500">
                Manage today's meal service and customer redemptions.
            </p>

        </div>

        <div class="text-sm text-slate-500">
            {{ now()->format('l, d F Y') }}
        </div>

    </div>


    {{-- WELCOME --}}
    <div class="rounded-2xl bg-slate-900 p-6 text-white">

        <p class="text-sm text-slate-400">
            Welcome back
        </p>

        <h2 class="mt-1 text-2xl font-bold">
            {{ $user->name }}
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            You're logged in as a staff member.
        </p>

    </div>


    {{-- QUICK ACTIONS --}}
    <div>

        <div class="mb-5">

            <h2 class="text-xl font-bold text-slate-900">
                Today's Operations
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Access the tools you need to serve customers.
            </p>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">


            {{-- SCANNER --}}
            <a
                href="{{ route('staff.meals.scan') }}"
                class="group rounded-2xl bg-slate-900 p-6 text-white transition hover:bg-slate-800"
            >

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-2xl">
                        📱
                    </div>

                    <span class="text-slate-400 transition group-hover:text-white">
                        →
                    </span>

                </div>

                <h3 class="mt-5 text-lg font-bold">
                    Meal Scanner
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    Scan customer QR codes and redeem meals.
                </p>

            </a>


        
        </div>

    </div>


    {{-- STAFF NOTICE --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6">

        <div class="flex gap-4">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                ℹ️
            </div>

            <div>

                <h3 class="font-bold text-slate-900">
                    Staff responsibilities
                </h3>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Use the Meal Scanner to verify customer entitlements
                    and record meal redemptions. Make sure each customer
                    receives the correct meal for the current service period.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection