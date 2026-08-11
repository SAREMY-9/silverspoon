@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')

<main class="max-w-6xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">

        <div>

            <a
                href="{{ route('admin.payments.index') }}"
                class="text-sm text-gray-500 hover:text-black"
            >
                ← Back to payments
            </a>

            <h1 class="text-3xl font-bold mt-3">
                Payment Details
            </h1>

            <p class="text-gray-500 mt-1">
                Payment #{{ $payment->id }}
            </p>

        </div>


        @php
            $status = $payment->status instanceof \BackedEnum
                ? $payment->status->value
                : $payment->status;
        @endphp


        <div>

            @if ($status === 'successful')

                <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                    Successful
                </span>

            @elseif ($status === 'pending')

                <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                    Pending
                </span>

            @elseif ($status === 'failed')

                <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                    Failed
                </span>

            @elseif ($status === 'refunded')

                <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">
                    Refunded
                </span>

            @endif

        </div>

    </div>


    {{-- Flash messages --}}
    @foreach (['success', 'error', 'warning', 'info'] as $message)

        @if (session($message))

            <div class="mb-6 rounded-xl px-5 py-4
                @if($message === 'success') bg-green-50 border border-green-200 text-green-800
                @elseif($message === 'error') bg-red-50 border border-red-200 text-red-800
                @elseif($message === 'warning') bg-yellow-50 border border-yellow-200 text-yellow-800
                @else bg-blue-50 border border-blue-200 text-blue-800
                @endif
            ">
                {{ session($message) }}
            </div>

        @endif

    @endforeach


    {{-- Main payment information --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


        {{-- Payment --}}
        <section class="lg:col-span-2 bg-white border rounded-2xl">

            <div class="px-6 py-5 border-b">

                <h2 class="font-bold text-lg">
                    Payment Information
                </h2>

            </div>


            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Amount
                    </p>

                    <p class="text-2xl font-bold mt-1">
                        {{ $payment->currency }}
                        {{ number_format($payment->amount, 2) }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Provider
                    </p>

                    <p class="font-semibold mt-1 capitalize">
                        {{ $payment->provider ?? '—' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Transaction Reference
                    </p>

                    <p class="font-mono text-sm mt-1 break-all">
                        {{ $payment->transaction_reference }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Payment Reference
                    </p>

                    <p class="font-mono text-sm mt-1 break-all">
                        {{ $payment->payment_reference ?? '—' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Paid At
                    </p>

                    <p class="font-semibold mt-1">
                        {{ $payment->paid_at?->format('d M Y H:i:s') ?? 'Not paid' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Created At
                    </p>

                    <p class="font-semibold mt-1">
                        {{ $payment->created_at?->format('d M Y H:i:s') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Phone
                    </p>

                    <p class="font-semibold mt-1">
                        {{ $payment->phone ?? '—' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Checkout Request ID
                    </p>

                    <p class="font-mono text-xs mt-1 break-all">
                        {{ $payment->checkout_request_id ?? '—' }}
                    </p>
                </div>


                <div class="md:col-span-2">

                    <p class="text-xs uppercase tracking-wide text-gray-500">
                        Merchant Request ID
                    </p>

                    <p class="font-mono text-xs mt-1 break-all">
                        {{ $payment->merchant_request_id ?? '—' }}
                    </p>

                </div>

            </div>

        </section>


        {{-- Customer --}}
        <section class="bg-white border rounded-2xl">

            <div class="px-6 py-5 border-b">

                <h2 class="font-bold text-lg">
                    Customer
                </h2>

            </div>


            <div class="p-6">

                @if ($payment->user)

                    <p class="text-xl font-bold">
                        {{ $payment->user->name }}
                    </p>

                    <p class="text-gray-500 mt-1">
                        {{ $payment->user->email }}
                    </p>

                    @if ($payment->user->phone)
                        <p class="text-gray-500 mt-1">
                            {{ $payment->user->phone }}
                        </p>
                    @endif

                    <a
                        href="{{ route('admin.users.show', $payment->user) }}"
                        class="inline-block mt-5 font-semibold hover:underline"
                    >
                        View customer →
                    </a>

                @else

                    <p class="text-gray-500">
                        Customer record unavailable.
                    </p>

                @endif

            </div>

        </section>

    </div>


    {{-- Subscription --}}
    <section class="bg-white border rounded-2xl mt-6">

        <div class="px-6 py-5 border-b">

            <h2 class="font-bold text-lg">
                Linked Subscription
            </h2>

        </div>


        <div class="p-6">

            @if ($payment->subscription)

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            Meal Plan
                        </p>

                        <p class="font-semibold mt-1">
                            {{ $payment->subscription->mealPlan?->name ?? '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            Status
                        </p>

                        <p class="font-semibold mt-1">
                            {{ ucfirst($payment->subscription->status->value) }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            Starts
                        </p>

                        <p class="font-semibold mt-1">
                            {{ $payment->subscription->starts_at?->format('d M Y') }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500">
                            Ends
                        </p>

                        <p class="font-semibold mt-1">
                            {{ $payment->subscription->ends_at?->format('d M Y') }}
                        </p>
                    </div>

                </div>


                <div class="mt-6 pt-6 border-t">

                    <p class="text-sm text-gray-500">
                        Access code
                    </p>

                    <p class="font-mono font-semibold mt-1">
                        {{ $payment->subscription->access_code ?? '—' }}
                    </p>

                </div>

            @else

                <p class="text-gray-500">
                    This payment is not linked to a subscription.
                </p>

            @endif

        </div>

    </section>


    {{-- Entitlements --}}
    @if ($payment->subscription)

        <section class="bg-white border rounded-2xl mt-6">

            <div class="px-6 py-5 border-b">

                <h2 class="font-bold text-lg">
                    Meal Entitlements
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Entitlements generated for this subscription.
                </p>

            </div>


            <div class="divide-y">

                @forelse ($payment->subscription->entitlements as $entitlement)

                    <div class="px-6 py-4 flex items-center justify-between gap-4">

                        <div>

                            <p class="font-semibold">
                                {{ $entitlement->meal?->name ?? 'Unknown meal' }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $entitlement->scheduled_for?->format('d M Y') ?? 'No scheduled date' }}
                            </p>

                        </div>


                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100">
                            {{ ucfirst($entitlement->status->value) }}
                        </span>

                    </div>

                @empty

                    <div class="p-6 text-gray-500">
                        No meal entitlements found.
                    </div>

                @endforelse

            </div>

        </section>

    @endif


    {{-- Provider response --}}
    <section class="bg-white border rounded-2xl mt-6">

        <div class="px-6 py-5 border-b flex items-center justify-between gap-4">

            <div>

                <h2 class="font-bold text-lg">
                    Provider Response
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Raw response received from the payment provider.
                </p>

            </div>

        </div>


        <div class="p-6">

            @if ($providerResponse)

                <pre class="bg-gray-950 text-gray-100 rounded-xl p-5 overflow-x-auto text-xs leading-6">{{ json_encode($providerResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

            @else

                <p class="text-gray-500">
                    No provider response has been stored yet.
                </p>

            @endif

        </div>

    </section>


    {{-- Actions --}}
    <section class="bg-white border rounded-2xl mt-6">

        <div class="px-6 py-5 border-b">

            <h2 class="font-bold text-lg">
                Payment Actions
            </h2>

        </div>


        <div class="p-6">

            @if (
                $status !== 'successful' &&
                in_array($payment->provider, ['paystack', 'mpesa'], true)
            )

                <form
                    method="POST"
                    action="{{ route('admin.payments.verify', $payment) }}"
                    onsubmit="return confirm('Verify this payment directly with the provider?');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="bg-black text-white rounded-xl px-5 py-3 font-semibold hover:bg-gray-800"
                    >
                        Verify with {{ ucfirst($payment->provider) }}
                    </button>

                </form>

                <p class="text-sm text-gray-500 mt-3">
                    Verification never manually marks a payment as successful.
                    The provider must confirm the transaction.
                </p>

            @else

                <p class="text-sm text-gray-500">
                    No verification action is currently available for this payment.
                </p>

            @endif

        </div>

    </section>

</main>

@endsection