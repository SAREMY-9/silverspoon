@extends('layouts.app')

@section('title', 'Payment Management')

@section('content')

<main class="max-w-7xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">

        <div>
            <p class="text-sm text-gray-500">
                Administration
            </p>

            <h1 class="text-3xl md:text-4xl font-bold mt-1">
                Payments
            </h1>

            <p class="text-gray-500 mt-2">
                Monitor, inspect and verify customer payments.
            </p>
        </div>

        <div class="text-sm text-gray-500">
            Total collected:
            <span class="font-bold text-gray-900">
                KES {{ number_format($successfulAmount, 2) }}
            </span>
        </div>

    </div>


    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-6 rounded-xl bg-yellow-50 border border-yellow-200 px-5 py-4 text-yellow-800">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('info'))
        <div class="mb-6 rounded-xl bg-blue-50 border border-blue-200 px-5 py-4 text-blue-800">
            {{ session('info') }}
        </div>
    @endif


    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Total
            </p>
            <p class="text-2xl font-bold mt-2">
                {{ number_format($totalPayments) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Successful
            </p>
            <p class="text-2xl font-bold mt-2 text-green-600">
                {{ number_format($successfulPayments) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Pending
            </p>
            <p class="text-2xl font-bold mt-2 text-yellow-600">
                {{ number_format($pendingPayments) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Failed
            </p>
            <p class="text-2xl font-bold mt-2 text-red-600">
                {{ number_format($failedPayments) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Refunded
            </p>
            <p class="text-2xl font-bold mt-2">
                {{ number_format($refundedPayments) }}
            </p>
        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white border rounded-2xl p-5 mb-6">

        <form method="GET"
              action="{{ route('admin.payments.index') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">

                <div class="lg:col-span-2">

                    <label class="block text-sm font-medium mb-1">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Reference, customer, email, phone..."
                        class="w-full border rounded-xl px-4 py-2.5"
                    >

                </div>


                <div>

                    <label class="block text-sm font-medium mb-1">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full border rounded-xl px-4 py-2.5"
                    >

                        <option value="">
                            All statuses
                        </option>

                        <option value="pending"
                            @selected(request('status') === 'pending')}>
                            Pending
                        </option>

                        <option value="successful"
                            @selected(request('status') === 'successful')}>
                            Successful
                        </option>

                        <option value="failed"
                            @selected(request('status') === 'failed')}>
                            Failed
                        </option>

                        <option value="refunded"
                            @selected(request('status') === 'refunded')}>
                            Refunded
                        </option>

                    </select>

                </div>


                <div>

                    <label class="block text-sm font-medium mb-1">
                        Provider
                    </label>

                    <select
                        name="provider"
                        class="w-full border rounded-xl px-4 py-2.5"
                    >

                        <option value="">
                            All providers
                        </option>

                        @foreach ($providers as $provider)

                            <option
                                value="{{ $provider }}"
                                @selected(request('provider') === $provider)
                            >
                                {{ ucfirst($provider) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div>

                    <label class="block text-sm font-medium mb-1">
                        From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full border rounded-xl px-4 py-2.5"
                    >

                </div>


                <div>

                    <label class="block text-sm font-medium mb-1">
                        To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full border rounded-xl px-4 py-2.5"
                    >

                </div>

            </div>


            <div class="flex gap-3 mt-4">

                <button
                    type="submit"
                    class="bg-black text-white rounded-xl px-5 py-2.5 font-semibold hover:bg-gray-800"
                >
                    Search
                </button>

                <a
                    href="{{ route('admin.payments.index') }}"
                    class="border rounded-xl px-5 py-2.5 font-semibold"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Payments table --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-5 py-4">
                            Customer
                        </th>

                        <th class="text-left px-5 py-4">
                            Amount
                        </th>

                        <th class="text-left px-5 py-4">
                            Provider
                        </th>

                        <th class="text-left px-5 py-4">
                            Reference
                        </th>

                        <th class="text-left px-5 py-4">
                            Status
                        </th>

                        <th class="text-left px-5 py-4">
                            Date
                        </th>

                        <th class="text-right px-5 py-4">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse ($payments as $payment)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4">

                                <p class="font-semibold">
                                    {{ $payment->user?->name ?? 'Unknown user' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $payment->user?->email }}
                                </p>

                            </td>


                            <td class="px-5 py-4">

                                <span class="font-semibold">
                                    {{ $payment->currency }}
                                    {{ number_format($payment->amount, 2) }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <span class="capitalize">
                                    {{ $payment->provider ?? '—' }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <p class="font-mono text-xs">
                                    {{ $payment->transaction_reference }}
                                </p>

                                @if ($payment->payment_reference)
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $payment->payment_reference }}
                                    </p>
                                @endif

                            </td>


                            <td class="px-5 py-4">

                                @php
                                    $status = $payment->status instanceof \BackedEnum
                                        ? $payment->status->value
                                        : $payment->status;
                                @endphp

                                @if ($status === 'successful')

                                    <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                        Successful
                                    </span>

                                @elseif ($status === 'pending')

                                    <span class="px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        Pending
                                    </span>

                                @elseif ($status === 'failed')

                                    <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        Failed
                                    </span>

                                @elseif ($status === 'refunded')

                                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                        Refunded
                                    </span>

                                @else

                                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                        {{ ucfirst($status) }}
                                    </span>

                                @endif

                            </td>


                            <td class="px-5 py-4 text-gray-500">

                                {{ $payment->created_at?->format('d M Y') }}

                                <div class="text-xs">
                                    {{ $payment->created_at?->format('H:i') }}
                                </div>

                            </td>


                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('admin.payments.show', $payment) }}"
                                    class="font-semibold hover:underline"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-5 py-12 text-center text-gray-500"
                            >
                                No payments found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if ($payments->hasPages())

            <div class="px-5 py-4 border-t">
                {{ $payments->links() }}
            </div>

        @endif

    </div>

</main>

@endsection