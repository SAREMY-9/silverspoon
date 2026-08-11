@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="mb-8">

        <a
            href="{{ route('admin.users.show', $user) }}"
            class="text-sm text-gray-500 hover:text-gray-900"
        >
            ← Back to user
        </a>

        <h1 class="text-2xl font-bold text-gray-900 mt-3">
            Edit User
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Update account information and role.
        </p>

    </div>


    @if(session('error'))

        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
            {{ session('error') }}
        </div>

    @endif


    <div class="bg-white border rounded-xl p-6">

        <form
            method="POST"
            action="{{ route('admin.users.update', $user) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-lg border-gray-300"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-lg border-gray-300"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Role
                </label>

                <select
                    name="role"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option
                        value="customer"
                        @selected(old('role', $user->role) === 'customer')
                    >
                        Customer
                    </option>

                    <option
                        value="staff"
                        @selected(old('role', $user->role) === 'staff')
                    >
                        Staff
                    </option>

                    <option
                        value="admin"
                        @selected(old('role', $user->role) === 'admin')
                    >
                        Administrator
                    </option>

                </select>
            </div>


            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">

                <div>
                    <strong>User ID:</strong>
                    {{ $user->id }}
                </div>

                <div class="mt-1">
                    <strong>Registered:</strong>
                    {{ $user->created_at?->format('d M Y H:i') }}
                </div>

                <div class="mt-1">
                    <strong>Last updated:</strong>
                    {{ $user->updated_at?->format('d M Y H:i') }}
                </div>

            </div>


            <div class="flex justify-end gap-3 pt-4 border-t">

                <a
                    href="{{ route('admin.users.show', $user) }}"
                    class="px-4 py-2.5 border rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

@endsection