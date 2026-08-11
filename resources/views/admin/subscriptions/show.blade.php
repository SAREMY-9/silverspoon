@extends('layouts.app')

@section('title', 'Subscription #' . $subscription->id)

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <a
                href="{{ route('admin.subscriptions.index') }}"
                class="text-sm text-slate-500 hover:text-slate-900"
            >
                ← Back to subscriptions
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-900">
                Subscription #{{ $subscription->id }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $subscription->access_code }}
            </p>

        </div>


        {{-- STATUS --}}

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

        <span class="inline-flex w-fit items-center rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses }}">
            {{ ucfirst($status) }}
        </span>

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


    {{-- MAIN GRID --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}

        <div class="lg:col-span-2 space-y-6">


            {{-- CUSTOMER --}}

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <h2 class="text-lg font-bold text-slate-900 mb-5">
                    Customer
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Name
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $subscription->user->name ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Email
                        </p>

                        <p class="mt-1 text-slate-700">
                            {{ $subscription->user->email ?? '—' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            User ID
                        </p>

                        <p class="mt-1 text-slate-700">
                            #{{ $subscription->user_id }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Access Code
                        </p>

                        <p class="mt-1 font-mono font-semibold text-slate-900">
                            {{ $subscription->access_code }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- PLAN --}}

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <h2 class="text-lg font-bold text-slate-900 mb-5">
                    Meal Plan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div class="md:col-span-2">

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Plan
                        </p>

                        <p class="mt-1 text-lg font-semibold text-slate-900">
                            {{ $subscription->mealPlan->name ?? 'Deleted Plan' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Price
                        </p>

                        <p class="mt-1 text-lg font-bold text-slate-900">

                            @if($subscription->mealPlan?->price !== null)
                                KES {{ number_format($subscription->mealPlan->price, 2) }}
                            @else
                                —
                            @endif

                        </p>

                    </div>

                </div>

            </div>


            {{-- SUBSCRIPTION PERIOD --}}

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <h2 class="text-lg font-bold text-slate-900 mb-5">
                    Subscription Period
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Starts
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">

                            {{ $subscription->starts_at
                                ? $subscription->starts_at->format('d M Y H:i')
                                : 'Not started'
                            }}

                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Ends
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">

                            {{ $subscription->ends_at
                                ? $subscription->ends_at->format('d M Y H:i')
                                : 'Not started'
                            }}

                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold uppercase text-slate-400">
                            Created
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $subscription->created_at->format('d M Y H:i') }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- PAYMENTS --}}

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-200">

                    <h2 class="text-lg font-bold text-slate-900">
                        Payment History
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        All payment attempts associated with this subscription.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs uppercase text-slate-500">
                                    Reference
                                </th>

                                <th class="px-6 py-3 text-left text-xs uppercase text-slate-500">
                                    Provider
                                </th>

                                <th class="px-6 py-3 text-left text-xs uppercase text-slate-500">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs uppercase text-slate-500">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-left text-xs uppercase text-slate-500">
                                    Date
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($subscription->payments as $payment)

                                <tr>

                                    <td class="px-6 py-4">

                                        <span class="font-mono text-xs text-slate-700">
                                            {{ $payment->transaction_reference ?? '—' }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ ucfirst($payment->provider ?? '—') }}
                                    </td>


                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                        KES {{ number_format($payment->amount, 2) }}
                                    </td>


                                    <td class="px-6 py-4">

                                        @php
                                            $paymentStatus = $payment->status?->value ?? $payment->status;

                                            $paymentClasses = match($paymentStatus) {
                                                'successful' => 'bg-emerald-100 text-emerald-700',
                                                'pending' => 'bg-amber-100 text-amber-700',
                                                'failed' => 'bg-red-100 text-red-700',
                                                'refunded' => 'bg-purple-100 text-purple-700',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp

                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentClasses }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $payment->created_at->format('d M Y H:i') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-6 py-10 text-center text-sm text-slate-400"
                                    >
                                        No payment attempts found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- RIGHT SIDEBAR --}}

        <div class="space-y-6">


            {{-- ADMIN ACTIONS --}}

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    Administration
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Actions available for this subscription.
                </p>


                <div class="mt-6 space-y-3">

                    @if($status !== 'cancelled' && $status !== 'expired')

                        <form
                            method="POST"
                            action="{{ route('admin.subscriptions.cancel', $subscription) }}"
                            onsubmit="return confirm('Are you sure you want to cancel this subscription?')"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-100"
                            >
                                Cancel Subscription
                            </button>

                        </form>

                    @endif


                    @if($status === 'cancelled')

                        <form
                            method="POST"
                            action="{{ route('admin.subscriptions.reactivate', $subscription) }}"
                            onsubmit="return confirm('Reactivate this subscription?')"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700"
                            >
                                Reactivate Subscription
                            </button>

                        </form>

                    @endif


                    @if($status === 'active')

                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4">

                            <div class="font-semibold text-emerald-800">
                                Subscription Active
                            </div>

                            @if($subscription->ends_at)

                                <div class="text-sm text-emerald-700 mt-1">
                                    Expires
                                    {{ $subscription->ends_at->format('d M Y') }}
                                </div>

                            @endif

                        </div>

                    @endif


                    @if($status === 'pending')

                        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">

                            <div class="font-semibold text-amber-800">
                                Payment Pending
                            </div>

                            <div class="text-sm text-amber-700 mt-1">
                                This subscription has not yet been activated.
                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- ENTITLEMENTS --}}

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    Meal Entitlements
                </h2>

                <div class="mt-4">

                    <div class="text-3xl font-bold text-slate-900">
                        {{ $subscription->entitlements->count() }}
                    </div>

                    <p class="text-sm text-slate-500">
                        entitlement records
                    </p>

                </div>

                @if($subscription->entitlements->count())

                    <div class="mt-5 space-y-2 max-h-64 overflow-y-auto">

                        @foreach($subscription->entitlements as $entitlement)

                            <div class="rounded-lg bg-slate-50 p-3 text-sm">

                                <div class="font-medium text-slate-800">
                                    Entitlement #{{ $entitlement->id }}
                                </div>

                                @if(isset($entitlement->redeemed_at))

                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $entitlement->redeemed_at
                                            ? 'Redeemed'
                                            : 'Not redeemed'
                                        }}
                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- QR TOKEN --}}

            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    QR Token
                </h2>

                <p class="mt-2 text-xs text-slate-500">
                    Internal subscription QR identifier.
                </p>

                <div class="mt-4 rounded-xl bg-slate-50 p-4 break-all">

                    <code class="text-xs text-slate-700">
                        {{ $subscription->qr_token }}
                    </code>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection