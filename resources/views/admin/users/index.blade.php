@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                User Management
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Manage customers, staff and administrators.
            </p>
        </div>

        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition"
        >
            + Add User
        </a>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">
                Total Users
            </p>

            <p class="text-2xl font-bold mt-1">
                {{ number_format($totalUsers) }}
            </p>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">
                Active Users
            </p>

            <p class="text-2xl font-bold text-green-600 mt-1">
                {{ number_format($activeUsers) }}
            </p>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">
                Inactive Users
            </p>

            <p class="text-2xl font-bold text-red-600 mt-1">
                {{ number_format($inactiveUsers) }}
            </p>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">
                Administrators
            </p>

            <p class="text-2xl font-bold text-purple-600 mt-1">
                {{ number_format($adminCount) }}
            </p>
        </div>

    </div>


    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Validation --}}
    @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
            <ul class="list-disc ml-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Filters --}}
    <div class="bg-white border rounded-xl p-5 mb-6">

        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name, email or phone"
                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Role
                </label>

                <select
                    name="role"
                    class="w-full rounded-lg border-gray-300"
                >
                    <option value="">All roles</option>

                    <option
                        value="customer"
                        @selected(request('role') === 'customer')
                    >
                        Customer
                    </option>

                    <option
                        value="staff"
                        @selected(request('role') === 'staff')
                    >
                        Staff
                    </option>

                    <option
                        value="admin"
                        @selected(request('role') === 'admin')
                    >
                        Admin
                    </option>
                </select>
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border-gray-300"
                >
                    <option value="">All statuses</option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        @selected(request('status') === 'inactive')
                    >
                        Inactive
                    </option>
                </select>
            </div>


            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                >
                    Filter
                </button>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="px-4 py-2.5 border rounded-lg hover:bg-gray-50"
                >
                    Clear
                </a>

            </div>

        </form>

    </div>


    {{-- Users table --}}
    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            User
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            Role
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            Subscriptions
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            Payments
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($users as $user)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $user->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </div>

                                @if($user->phone)
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $user->phone }}
                                    </div>
                                @endif

                            </td>


                            <td class="px-6 py-4">

                                @php
                                    $roleClasses = [
                                        'admin' => 'bg-purple-100 text-purple-700',
                                        'staff' => 'bg-blue-100 text-blue-700',
                                        'customer' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClasses[$user->role] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>

                            </td>


                            <td class="px-6 py-4">

                                @if($user->is_active)

                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $user->subscriptions_count }}
                            </td>


                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $user->payments_count }}
                            </td>


                            <td class="px-6 py-4">

                                <div class="flex justify-end items-center gap-2">

                                    <a
                                        href="{{ route('admin.users.show', $user) }}"
                                        class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50"
                                    >
                                        Edit
                                    </a>

                                    @if(auth()->id() !== $user->id)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.toggle', $user) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 text-sm rounded-lg {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}"
                                            >
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-500"
                            >
                                No users found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($users->hasPages())

            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>

@endsection