@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="mb-8">

        <a
            href="{{ route('admin.users.index') }}"
            class="text-sm text-gray-500 hover:text-gray-900"
        >
            ← Back to users
        </a>

        <h1 class="text-2xl font-bold text-gray-900 mt-3">
            Create User
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Create a customer, staff member or administrator.
        </p>

    </div>


    <div class="bg-white border rounded-xl p-6">

        @if($errors->any())

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">

                <ul class="list-disc ml-5 text-sm">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('admin.users.store') }}"
            class="space-y-6"
        >

            @csrf


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
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
                    value="{{ old('email') }}"
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
                    value="{{ old('phone') }}"
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
                    <option value="customer" @selected(old('role', 'customer') === 'customer')>
                        Customer
                    </option>

                    <option value="staff" @selected(old('role') === 'staff')>
                        Staff
                    </option>

                    <option value="admin" @selected(old('role') === 'admin')>
                        Administrator
                    </option>
                </select>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
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
                        class="w-full rounded-lg border-gray-300"
                    >
                </div>

            </div>


            <div class="flex items-center gap-2">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    checked
                    class="rounded border-gray-300"
                >

                <label class="text-sm text-gray-700">
                    Account is active
                </label>

            </div>


            <div class="flex justify-end gap-3 pt-4 border-t">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="px-4 py-2.5 border rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
                >
                    Create User
                </button>

            </div>

        </form>

    </div>

</div>

@endsection