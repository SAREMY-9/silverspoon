@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}

    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-8">

        <div>

            <a
                href="{{ route('admin.users.index') }}"
                class="text-sm text-gray-500 hover:text-gray-900"
            >
                ← Back to users
            </a>

            <h1 class="text-2xl font-bold text-gray-900 mt-3">
                {{ $user->name }}
            </h1>

            <p class="text-sm text-gray-500">
                {{ $user->email }}
            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('admin.users.edit', $user) }}"
                class="px-4 py-2.5 border rounded-lg hover:bg-gray-50"
            >
                Edit User
            </a>


            @if(auth()->id() !== $user->id)

                <form
                    method="POST"
                    action="{{ route('admin.users.toggle', $user) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="px-4 py-2.5 rounded-lg {{ $user->is_active ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}"
                    >
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>

            @endif

        </div>

    </div>


    {{-- Messages --}}

    @if(session('success'))

        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-lg p-4">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
            {{ session('error') }}
        </div>

    @endif


    {{-- User summary --}}

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Role
            </p>

            <p class="font-semibold mt-1">
                {{ ucfirst($user->role) }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Account Status
            </p>

            <p class="font-semibold mt-1 {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Subscriptions
            </p>

            <p class="text-2xl font-bold mt-1">
                {{ $user->subscriptions->count() }}
            </p>

        </div>


        <div class="bg-white border rounded-xl p-5">

            <p class="text-sm text-gray-500">
                Payments
            </p>

            <p class="text-2xl font-bold mt-1">
                {{ $user->payments->count() }}
            </p>

        </div>

    </div>


    {{-- Contact details --}}

    <div class="bg-white border rounded-xl p-6 mb-8">

        <h2 class="text-lg font-semibold text-gray-900 mb-5">
            Customer Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <p class="text-xs uppercase text-gray-400">
                    Name
                </p>

                <p class="font-medium mt-1">
                    {{ $user->name }}
                </p>
            </div>


            <div>
                <p class="text-xs uppercase text-gray-400">
                    Email
                </p>

                <p class="font-medium mt-1">
                    {{ $user->email }}
                </p>
            </div>


            <div>
                <p class="text-xs uppercase text-gray-400">
                    Phone
                </p>

                <p class="font-medium mt-1">
                    {{ $user->phone ?: '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Subscriptions --}}

    <div class="bg-white border rounded-xl mb-8 overflow-hidden">

        <div class="px-6 py-5 border-b">

            <h2 class="text-lg font-semibold">
                Subscriptions
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Plan
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Starts
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Ends
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Access Code
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($user->subscriptions as $subscription)

                        <tr>

                            <td class="px-6 py-4">
                                {{ $subscription->mealPlan?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ ucfirst($subscription->status->value ?? $subscription->status) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $subscription->starts_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $subscription->ends_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                            <td class="px-6 py-4 font-mono text-sm">
                                {{ $subscription->access_code ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No subscriptions found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Payments --}}

    <div class="bg-white border rounded-xl mb-8 overflow-hidden">

        <div class="px-6 py-5 border-b">

            <h2 class="text-lg font-semibold">
                Payments
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Transaction
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Amount
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Provider
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Paid At
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($user->payments as $payment)

                        <tr>

                            <td class="px-6 py-4">

                                <div class="font-mono text-sm">
                                    {{ $payment->transaction_reference }}
                                </div>

                                @if($payment->payment_reference)

                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $payment->payment_reference }}
                                    </div>

                                @endif

                            </td>


                            <td class="px-6 py-4">
                                {{ $payment->currency }}
                                {{ number_format((float) $payment->amount, 2) }}
                            </td>


                            <td class="px-6 py-4">
                                {{ $payment->provider ?? '—' }}
                            </td>


                            <td class="px-6 py-4">
                                {{ ucfirst($payment->status->value ?? $payment->status) }}
                            </td>


                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $payment->paid_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No payments found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Entitlements --}}

    <div class="bg-white border rounded-xl mb-8 overflow-hidden">

        <div class="px-6 py-5 border-b">

            <h2 class="text-lg font-semibold">
                Meal Entitlements
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Meal
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs uppercase text-gray-500">
                            Expires
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($entitlements as $entitlement)

                        <tr>

                            <td class="px-6 py-4">
                                {{ $entitlement->scheduled_for?->format('d M Y') ?? '—' }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="font-medium">
                                    {{ $entitlement->meal?->name ?? '—' }}
                                </div>

                                @if($entitlement->meal)
                                    <div class="text-xs text-gray-400">
                                        {{ ucfirst($entitlement->meal->meal_type) }}
                                    </div>
                                @endif

                            </td>

                            <td class="px-6 py-4">
                                {{ ucfirst($entitlement->status->value ?? $entitlement->status) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $entitlement->expires_at?->format('d M Y H:i') ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-gray-500"
                            >
                                No meal entitlements found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($entitlements->hasPages())

            <div class="px-6 py-4 border-t">
                {{ $entitlements->links() }}
            </div>

        @endif

    </div>


    {{-- Password reset --}}

    <div class="bg-white border rounded-xl p-6 mb-8">

        <h2 class="text-lg font-semibold text-gray-900">
            Reset Password
        </h2>

        <p class="text-sm text-gray-500 mt-1 mb-5">
            Set a new password for this account.
        </p>


        <form
            method="POST"
            action="{{ route('admin.users.reset-password', $user) }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end"
        >

            @csrf

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    minlength="8"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    minlength="8"
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            <button
                type="submit"
                class="px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
            >
                Reset Password
            </button>

        </form>

    </div>


    {{-- Delete --}}

    @if(auth()->id() !== $user->id)

        <div class="bg-red-50 border border-red-200 rounded-xl p-6">

            <h2 class="text-lg font-semibold text-red-800">
                Danger Zone
            </h2>

            <p class="text-sm text-red-700 mt-1 mb-4">
                Users with subscriptions, payments or meal redemption
                history cannot be deleted.
            </p>

            <form
                method="POST"
                action="{{ route('admin.users.destroy', $user) }}"
                onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
                >
                    Delete User
                </button>

            </form>

        </div>

    @endif

</div>

@endsection