@extends('layouts.app')

@section('title', 'Subscription Management')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Subscription Management
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Manage Silver Spoon customer subscriptions.
            </p>
        </div>

    </div>


    {{-- FLASH MESSAGES --}}

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif


    {{-- STATISTICS --}}

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-500 uppercase">
                Total
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ number_format($stats['total']) }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-amber-200 p-5">
            <p class="text-xs font-medium text-amber-600 uppercase">
                Pending
            </p>

            <p class="mt-2 text-2xl font-bold text-amber-700">
                {{ number_format($stats['pending']) }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-emerald-200 p-5">
            <p class="text-xs font-medium text-emerald-600 uppercase">
                Active
            </p>

            <p class="mt-2 text-2xl font-bold text-emerald-700">
                {{ number_format($stats['active']) }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-500 uppercase">
                Expired
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-700">
                {{ number_format($stats['expired']) }}
            </p>
        </div>


        <div class="bg-white rounded-2xl border border-red-200 p-5">
            <p class="text-xs font-medium text-red-600 uppercase">
                Cancelled
            </p>

            <p class="mt-2 text-2xl font-bold text-red-700">
                {{ number_format($stats['cancelled']) }}
            </p>
        </div>

    </div>


    {{-- FILTERS --}}

    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-6">

        <form
            method="GET"
            action="{{ route('admin.subscriptions.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name, email, access code or meal plan..."
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            {{-- Status --}}
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        All statuses
                    </option>

                    @foreach(\App\Enums\SubscriptionStatus::cases() as $status)

                        <option
                            value="{{ $status->value }}"
                            @selected(request('status') === $status->value)
                        >
                            {{ ucfirst($status->value) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Meal plan --}}
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Meal Plan
                </label>

                <select
                    name="meal_plan_id"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        All plans
                    </option>

                    @foreach($mealPlans as $plan)

                        <option
                            value="{{ $plan->id }}"
                            @selected((string) request('meal_plan_id') === (string) $plan->id)
                        >
                            {{ $plan->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="md:col-span-4 flex gap-3">

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Filter
                </button>

                <a
                    href="{{ route('admin.subscriptions.index') }}"
                    class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Clear
                </a>

            </div>

        </form>

    </div>


    {{-- TABLE --}}

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                            Customer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                            Meal Plan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                            Period
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">
                            Payments
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($subscriptions as $subscription)

                        <tr class="hover:bg-slate-50">

                            {{-- CUSTOMER --}}

                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-900">
                                    {{ $subscription->user->name ?? 'Unknown User' }}
                                </div>

                                <div class="text-sm text-slate-500">
                                    {{ $subscription->user->email ?? '—' }}
                                </div>

                                <div class="text-xs text-slate-400 mt-1">
                                    {{ $subscription->access_code }}
                                </div>

                            </td>


                            {{-- PLAN --}}

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ $subscription->mealPlan->name ?? 'Deleted Plan' }}
                                </div>

                                @if($subscription->mealPlan?->price !== null)

                                    <div class="text-sm text-slate-500">
                                        KES {{ number_format($subscription->mealPlan->price, 2) }}
                                    </div>

                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td class="px-6 py-4">

                                @php
                                    $status = $subscription->status?->value ?? $subscription->status;

                                    $statusClasses = match($status) {
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'expired' => 'bg-slate-100 text-slate-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ ucfirst($status) }}
                                </span>

                            </td>


                            {{-- PERIOD --}}

                            <td class="px-6 py-4 text-sm">

                                @if($subscription->starts_at)

                                    <div class="text-slate-700">
                                        {{ $subscription->starts_at->format('d M Y') }}
                                    </div>

                                    @if($subscription->ends_at)
                                        <div class="text-slate-400">
                                            → {{ $subscription->ends_at->format('d M Y') }}
                                        </div>
                                    @endif

                                @else

                                    <span class="text-slate-400">
                                        Not started
                                    </span>

                                @endif

                            </td>


                            {{-- PAYMENTS --}}

                            <td class="px-6 py-4 text-sm">

                                <div class="font-semibold text-slate-900">
                                    {{ $subscription->payments_count }}
                                </div>

                                <div class="text-xs text-slate-400">
                                    payment attempts
                                </div>

                            </td>


                            {{-- ACTION --}}

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.subscriptions.show', $subscription) }}"
                                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-16 text-center"
                            >

                                <div class="text-slate-400">
                                    No subscriptions found.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if($subscriptions->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $subscriptions->links() }}
            </div>

        @endif

    </div>

</div>

@endsection