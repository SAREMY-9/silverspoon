<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', config('app.name', 'Silver Spoon'))
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('head')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    {{-- ========================================================= --}}
    {{-- TOP NAVIGATION --}}
    {{-- ========================================================= --}}

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

            {{-- BRAND + NAVIGATION --}}

            <div class="flex items-center gap-8">

                {{-- BRAND --}}

                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-2"
                >
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white">
                        SS
                    </div>

                    <div class="hidden sm:block">
                        <div class="text-sm font-bold text-slate-900">
                            Silver Spoon
                        </div>

                        <div class="text-xs text-slate-500">
                            Meal Subscription
                        </div>
                    </div>
                </a>


                {{-- DESKTOP NAVIGATION --}}

                @auth
                    @if(auth()->user()->role === 'admin')

                        <nav class="hidden items-center gap-1 md:flex">

                            {{-- Dashboard --}}

                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('dashboard')
                                    ? 'bg-slate-100 text-slate-900'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Dashboard
                            </a>


                            {{-- Users --}}

                            <a
                                href="{{ route('admin.users.index') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('admin.users.*')
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Users
                            </a>


                            {{-- Meal Plans --}}

                            <a
                                href="{{ route('admin.meal-plans.index') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('admin.meal-plans.*')
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Meal Plans
                            </a>


                            {{-- Meals --}}

                            <a
                                href="{{ route('admin.meals.index') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('admin.meals.index')
                                    || request()->routeIs('admin.meals.create')
                                    || request()->routeIs('admin.meals.edit')
                                    || request()->routeIs('admin.meals.show')
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Meals
                            </a>


                            {{-- Operations --}}

                            <a
                                href="{{ route('admin.meals.dashboard') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('admin.meals.dashboard')
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Operations
                            </a>


                            {{-- Reports --}}

                            <a
                                href="{{ route('admin.meals.report') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('admin.meals.report')
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Reports
                            </a>


                            {{-- Scanner --}}

                            <a
                                href="{{ route('staff.meals.scan') }}"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition
                                {{ request()->routeIs('staff.meals.scan')
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            >
                                Scanner
                            </a>

                        </nav>

                    @endif
                @endauth

            </div>


            {{-- ================================================= --}}
            {{-- USER MENU --}}
            {{-- ================================================= --}}

            @auth

                <div class="flex items-center gap-3">

                    {{-- NAME + ROLE --}}

                    <div class="hidden text-right sm:block">

                        <div class="text-sm font-semibold text-slate-900">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="text-xs capitalize text-slate-500">
                            {{ auth()->user()->role }}
                        </div>

                    </div>


                    {{-- AVATAR --}}

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>


                    {{-- LOGOUT --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900"
                        >
                            Logout
                        </button>
                    </form>

                </div>

            @endauth

        </div>
    </header>


    {{-- ========================================================= --}}
    {{-- MOBILE NAVIGATION --}}
    {{-- ========================================================= --}}

    @auth

        @if(auth()->user()->role === 'admin')

            <div class="border-b border-slate-200 bg-white md:hidden">

                <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-4 py-3">

                    {{-- Dashboard --}}

                    <a
                        href="{{ route('home') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('dashboard')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Dashboard
                    </a>


                    {{-- Users --}}

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('admin.users.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Users
                    </a>


                    {{-- Meal Plans --}}

                    <a
                        href="{{ route('admin.meal-plans.index') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('admin.meal-plans.*')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Meal Plans
                    </a>


                    {{-- Meals --}}

                    <a
                        href="{{ route('admin.meals.index') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('admin.meals.index')
                            || request()->routeIs('admin.meals.create')
                            || request()->routeIs('admin.meals.edit')
                            || request()->routeIs('admin.meals.show')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Meals
                    </a>


                    {{-- Operations --}}

                    <a
                        href="{{ route('admin.meals.dashboard') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('admin.meals.dashboard')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Operations
                    </a>


                    {{-- Reports --}}

                    <a
                        href="{{ route('admin.meals.report') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('admin.meals.report')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Reports
                    </a>


                    {{-- Scanner --}}

                    <a
                        href="{{ route('staff.meals.scan') }}"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium
                        {{ request()->routeIs('staff.meals.scan')
                            ? 'bg-slate-900 text-white'
                            : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        Scanner
                    </a>

                </div>

            </div>

        @endif

    @endauth


    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- SUCCESS --}}

        @if(session('success'))

            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>

        @endif


        {{-- ERROR --}}

        @if(session('error'))

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>

        @endif


        {{-- VALIDATION ERRORS --}}

        @if($errors->any())

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3">

                <div class="font-semibold text-red-800">
                    Please correct the following:
                </div>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-700">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- PAGE CONTENT --}}

        @yield('content')

    </main>


    @stack('scripts')

</body>
</html>