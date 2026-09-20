<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Dashboard — Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    },

                    colors: {
                        spoon: {
                            50: '#faf9f6',
                            100: '#f3f0e9',
                            200: '#e6e0d4',
                            300: '#d3c9b8',
                            500: '#8b7d68',
                            700: '#514838',
                            900: '#211e19',
                        }
                    },

                    boxShadow: {
                        soft: '0 20px 60px rgba(38,34,28,.08)',
                        card: '0 30px 90px rgba(38,34,28,.10)',
                    },

                    animation: {
                        'fade-up': 'fadeUp .7s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-soft': 'pulseSoft 3s ease-in-out infinite',
                    },

                    keyframes: {
                        fadeUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(18px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },

                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-8px)'
                            }
                        },

                        pulseSoft: {
                            '0%, 100%': {
                                opacity: '.45'
                            },
                            '50%': {
                                opacity: '1'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f7f5f0;
            color: #211e19;
        }

        .display-font {
            font-family: 'Playfair Display', serif;
        }

        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 40;
            opacity: .035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.45'/%3E%3C/svg%3E");
        }

        .ambient {
            position: fixed;
            border-radius: 9999px;
            filter: blur(90px);
            pointer-events: none;
            z-index: -1;
        }

        .ambient-one {
            width: 420px;
            height: 420px;
            background: rgba(211, 201, 184, .24);
            top: -180px;
            right: -120px;
        }

        .ambient-two {
            width: 360px;
            height: 360px;
            background: rgba(139, 125, 104, .10);
            bottom: 5%;
            left: -180px;
        }

        .dashboard-card {
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .meal-card {
            transition:
                transform .3s ease,
                box-shadow .3s ease,
                border-color .3s ease;
        }

        .meal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 65px rgba(38,34,28,.10);
            border-color: #d3c9b8;
        }

        .qr-glow {
            box-shadow:
                0 0 0 1px rgba(255,255,255,.15),
                0 20px 60px rgba(0,0,0,.22);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 9999px;
            display: inline-block;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

    </style>

</head>


<body>

<div class="grain"></div>

<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>


{{-- ========================================================= --}}
{{-- NAVIGATION --}}
{{-- ========================================================= --}}

<nav class="sticky top-0 z-30 border-b border-spoon-200/70 bg-[#f7f5f0]/85 backdrop-blur-xl">

    <div class="max-w-7xl mx-auto px-5 sm:px-8">

        <div class="h-[76px] flex items-center justify-between">

            {{-- Brand --}}

            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3 group"
            >

                <div
                    class="w-10 h-10 rounded-2xl bg-spoon-900 text-white flex items-center justify-center shadow-sm group-hover:rotate-[-5deg] transition"
                >

                    <svg
                        width="21"
                        height="21"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >

                        <path
                            d="M8 3V11"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                        <path
                            d="M5 3V8"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                        <path
                            d="M11 3V8"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                        <path
                            d="M5 8C5 10 6.2 11 8 11C9.8 11 11 10 11 8"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                        <path
                            d="M8 11V21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                        <path
                            d="M16 3C13.8 5.3 13 8 13 11C13 13.1 14.2 14.2 16 14.2V21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                    </svg>

                </div>


                <div class="leading-none">

                    <div class="font-bold tracking-tight">
                        Silver Spoon
                    </div>

                    <div class="text-[9px] uppercase tracking-[.25em] text-spoon-500 mt-1">
                        Better meals. Better days.
                    </div>

                </div>

            </a>


            {{-- User Navigation --}}

            <div class="flex items-center gap-3 sm:gap-5">

                <div class="hidden sm:block text-right">

                    <p class="text-sm font-semibold">
                        {{ $user->name }}
                    </p>

                    <p class="text-[11px] text-spoon-500">
                        Member
                    </p>

                </div>


                <div
                    class="w-10 h-10 rounded-full bg-spoon-200 flex items-center justify-center font-bold text-sm"
                >
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="hidden sm:inline-flex items-center gap-2 text-sm text-spoon-500 hover:text-red-600 transition"
                    >

                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >

                            <path
                                d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                            />

                            <path d="M16 17l5-5-5-5" />

                            <path d="M21 12H9" />

                        </svg>

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>


{{-- ========================================================= --}}
{{-- MAIN --}}
{{-- ========================================================= --}}

<main class="max-w-7xl mx-auto px-5 sm:px-8 py-10 md:py-14">


    {{-- ===================================================== --}}
    {{-- WELCOME --}}
    {{-- ===================================================== --}}

    <section class="mb-10 animate-fade-up">

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

            <div>

                <div class="flex items-center gap-2 text-xs uppercase tracking-[.2em] text-spoon-500 font-semibold">

                    <span class="status-dot bg-green-500"></span>

                    Member dashboard

                </div>


                <h1 class="display-font text-4xl sm:text-5xl md:text-6xl leading-[1.05] mt-3">

                    Good day,
                    <span class="italic text-spoon-500">
                        {{ $user->name }}
                    </span>.

                </h1>


                <p class="text-spoon-500 mt-4 max-w-xl leading-relaxed">

                    Everything you need for a better meal day —
                    your pass, today's meals and what's coming next.

                </p>

            </div>


            <div class="lg:text-right">

                <p class="text-sm font-semibold">
                    {{ now()->format('l') }}
                </p>

                <p class="text-sm text-spoon-500 mt-1">
                    {{ now()->format('d F Y') }}
                </p>

            </div>

        </div>

    </section>



    @if ($activeSubscription)


        {{-- ================================================= --}}
        {{-- SUBSCRIPTION HERO --}}
        {{-- ================================================= --}}

        <section class="mb-12">

            <div
                class="relative overflow-hidden rounded-[2rem] bg-spoon-900 text-white shadow-card"
            >

                {{-- Decorative circles --}}

                <div class="absolute -right-24 -top-24 w-72 h-72 rounded-full border border-white/10"></div>

                <div class="absolute -right-10 -bottom-32 w-80 h-80 rounded-full border border-white/5"></div>

                <div class="absolute left-1/2 top-0 w-64 h-64 rounded-full bg-white/[.025] blur-3xl"></div>


                <div class="relative p-7 sm:p-9 lg:p-10">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10">


                        {{-- Plan --}}

                        <div>

                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>

                                <span class="text-xs uppercase tracking-[.2em] text-white/50 font-semibold">
                                    Active meal plan
                                </span>

                            </div>


                            <h2 class="display-font text-3xl sm:text-4xl md:text-5xl">
                                {{ $activeSubscription->mealPlan->name }}
                            </h2>


                            <p class="text-white/50 mt-3">
                                {{ $activeSubscription->starts_at?->format('d M Y') }}
                                <span class="mx-2">→</span>
                                {{ $activeSubscription->ends_at?->format('d M Y') }}
                            </p>

                        </div>


                        {{-- Stats --}}

                        <div class="grid grid-cols-2 gap-3 min-w-[280px]">

                            <div class="rounded-2xl bg-white/[.07] border border-white/10 p-5">

                                <p class="text-[11px] uppercase tracking-[.15em] text-white/40">
                                    Days left
                                </p>

                                <p class="text-4xl font-semibold mt-2">
                                    {{ $daysRemaining }}
                                </p>

                            </div>


                            <div class="rounded-2xl bg-white/[.07] border border-white/10 p-5">

                                <p class="text-[11px] uppercase tracking-[.15em] text-white/40">
                                    Meal allowance
                                </p>

                                <p class="text-4xl font-semibold mt-2">
                                    {{ $activeSubscription->mealPlan->meal_limit }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        {{-- ================================================= --}}
        {{-- QR PASS --}}
        {{-- ================================================= --}}

        <section class="mb-14">

            <div class="grid lg:grid-cols-[1fr_380px] gap-6">


                {{-- Explanation --}}

                <div class="dashboard-card border border-spoon-200 rounded-[2rem] p-7 sm:p-9 shadow-soft">

                    <div class="max-w-xl">

                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-spoon-100 text-spoon-700 text-xs font-semibold">

                            <svg
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <rect
                                    x="4"
                                    y="4"
                                    width="16"
                                    height="16"
                                    rx="2"
                                />

                                <path d="M8 8h3v3H8z" />
                                <path d="M13 8h3v3h-3z" />
                                <path d="M8 13h3v3H8z" />
                                <path d="M14 14h2v2h-2z" />
                            </svg>

                            Your digital meal pass

                        </span>


                        <h2 class="display-font text-3xl sm:text-4xl mt-6">
                            Your meals are
                            <span class="italic text-spoon-500">
                                one scan away.
                            </span>
                        </h2>


                        <p class="text-spoon-500 leading-relaxed mt-4 max-w-lg">

                            Show your Silver Spoon QR code whenever you collect
                            a meal. Our team scans it to verify your active plan
                            and confirm your entitlement.

                        </p>


                        <div class="mt-8 grid sm:grid-cols-2 gap-4">

                            <div class="rounded-2xl bg-spoon-50 border border-spoon-200 p-4">

                                <div class="w-9 h-9 rounded-xl bg-white border border-spoon-200 flex items-center justify-center mb-3">

                                    <svg
                                        width="17"
                                        height="17"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <path d="M12 3v18" />
                                        <path d="M3 12h18" />

                                    </svg>

                                </div>

                                <p class="font-semibold text-sm">
                                    Keep it handy
                                </p>

                                <p class="text-xs text-spoon-500 mt-1 leading-relaxed">
                                    Your pass is available whenever you need it.
                                </p>

                            </div>


                            <div class="rounded-2xl bg-spoon-50 border border-spoon-200 p-4">

                                <div class="w-9 h-9 rounded-xl bg-white border border-spoon-200 flex items-center justify-center mb-3">

                                    <svg
                                        width="17"
                                        height="17"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <path d="M12 3l7 4v5c0 4.5-3 7.7-7 9-4-1.3-7-4.5-7-9V7l7-4z" />

                                        <path d="M9 12l2 2 4-4" />

                                    </svg>

                                </div>

                                <p class="font-semibold text-sm">
                                    Secure verification
                                </p>

                                <p class="text-xs text-spoon-500 mt-1 leading-relaxed">
                                    Your pass helps verify your meal entitlement.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- QR Card --}}

                <div
                    class="relative overflow-hidden rounded-[2rem] bg-white border border-spoon-200 shadow-soft p-6"
                >

                    <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-spoon-100"></div>


                    <div class="relative">

                        <div class="flex items-center justify-between mb-5">

                            <div>

                                <p class="text-xs uppercase tracking-[.18em] text-spoon-500 font-semibold">
                                    Silver Spoon Pass
                                </p>

                                <p class="text-sm font-semibold mt-1">
                                    Scan to verify
                                </p>

                            </div>


                            <div class="w-9 h-9 rounded-full bg-green-50 text-green-600 flex items-center justify-center">

                                <span class="w-2 h-2 rounded-full bg-green-500"></span>

                            </div>

                        </div>


                        <div class="bg-spoon-50 rounded-[1.5rem] p-5 flex items-center justify-center">

                            <div
                                id="customer-qr"
                                class="bg-white p-4 rounded-2xl shadow-sm"
                            ></div>

                        </div>


                        <div class="text-center mt-5">

                            <p class="text-sm font-semibold">
                                {{ $user->name }}
                            </p>

                            <p class="text-xs text-spoon-500 mt-1">
                                Active member
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        {{-- ================================================= --}}
        {{-- TODAY'S MEALS --}}
        {{-- ================================================= --}}

        <section class="mb-16">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-7">

                <div>

                    <p class="text-xs uppercase tracking-[.2em] text-spoon-500 font-semibold">
                        For today
                    </p>

                    <h2 class="display-font text-3xl sm:text-4xl mt-2">
                        Today's meals
                    </h2>

                </div>


                @if ($todayEntitlements->isNotEmpty())

                    <p class="text-sm text-spoon-500">

                        {{ $todayEntitlements->count() }}
                        {{ Str::plural('meal', $todayEntitlements->count()) }}
                        available

                    </p>

                @endif

            </div>


            @if ($todayEntitlements->isNotEmpty())

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($todayEntitlements as $entitlement)

                        <article
                            class="meal-card bg-white border border-spoon-200 rounded-[1.75rem] overflow-hidden shadow-sm"
                        >

                            {{-- Image --}}

                            <div class="relative">

                                @if ($entitlement->meal->image)

                                    <img
                                        src="{{ asset('storage/' . $entitlement->meal->image) }}"
                                        alt="{{ $entitlement->meal->name }}"
                                        class="w-full h-52 object-cover"
                                    >

                                @else

                                    <div class="w-full h-52 bg-spoon-100 flex items-center justify-center">

                                        <svg
                                            width="38"
                                            height="38"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="#8b7d68"
                                            stroke-width="1.4"
                                        >

                                            <path d="M4 19V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14" />

                                            <path d="M4 19h16" />

                                            <circle cx="9" cy="9" r="1.5" />

                                            <path d="M20 15l-4-4-6 6" />

                                        </svg>

                                    </div>

                                @endif


                                {{-- Type badge --}}

                                <div class="absolute top-4 left-4">

                                    <span class="px-3 py-1.5 rounded-full bg-white/90 backdrop-blur text-[10px] uppercase tracking-[.12em] font-bold shadow-sm">

                                        {{ ucfirst($entitlement->meal->meal_type) }}

                                    </span>

                                </div>


                                {{-- Status --}}

                                <div class="absolute top-4 right-4">

                                    @if ($entitlement->status->value === 'available')

                                        <span class="px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-[10px] uppercase tracking-[.1em] font-bold border border-green-100">

                                            Available

                                        </span>

                                    @elseif ($entitlement->status->value === 'redeemed')

                                        <span class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-[10px] uppercase tracking-[.1em] font-bold border border-blue-100">

                                            Redeemed

                                        </span>

                                    @else

                                        <span class="px-3 py-1.5 rounded-full bg-white/90 text-spoon-500 text-[10px] uppercase tracking-[.1em] font-bold">

                                            Expired

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Content --}}

                            <div class="p-6">

                                <h3 class="display-font text-2xl leading-tight">
                                    {{ $entitlement->meal->name }}
                                </h3>


                                @if ($entitlement->meal->description)

                                    <p class="text-sm text-spoon-500 mt-3 leading-relaxed line-clamp-2">

                                        {{ $entitlement->meal->description }}

                                    </p>

                                @endif


                                @if ($entitlement->status->value === 'available')

                                    <button
                                        type="button"
                                        class="redeem-button w-full mt-6 bg-spoon-900 text-white rounded-xl py-3.5 font-semibold hover:bg-spoon-700 transition flex items-center justify-center gap-2"
                                        data-url="{{ route('dashboard.meals.redeem', $entitlement) }}"
                                        data-meal="{{ $entitlement->meal->name }}"
                                        data-type="{{ ucfirst($entitlement->meal->meal_type) }}"
                                    >

                                        Redeem Meal

                                        <svg
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >

                                            <path d="M5 12h14" />
                                            <path d="M13 6l6 6-6 6" />

                                        </svg>

                                    </button>

                                @elseif ($entitlement->status->value === 'redeemed')

                                    @php
                                        $redemption = $entitlement->redemption ?? null;
                                    @endphp

                                    <div class="mt-6 rounded-xl bg-green-50 border border-green-100 p-4">

                                        <div class="flex items-center gap-2 text-green-700 font-semibold text-sm">

                                            <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                                ✓
                                            </span>

                                            Meal redeemed

                                        </div>


                                        @if ($redemption)

                                            <p class="text-[11px] text-green-700/70 mt-2">

                                                Reference:
                                                <span class="font-semibold">
                                                    {{ $redemption->reference }}
                                                </span>

                                            </p>

                                        @endif

                                    </div>

                                @else

                                    <div class="mt-6 rounded-xl bg-spoon-50 border border-spoon-200 p-4 text-center">

                                        <p class="text-sm text-spoon-500 font-semibold">
                                            Meal expired
                                        </p>

                                    </div>

                                @endif

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div class="bg-white border border-spoon-200 rounded-[2rem] p-12 text-center shadow-soft">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-spoon-100 flex items-center justify-center">

                        <svg
                            width="27"
                            height="27"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#8b7d68"
                            stroke-width="1.5"
                        >

                            <path d="M7 3v8" />
                            <path d="M4.5 3v5.5A2.5 2.5 0 0 0 7 11a2.5 2.5 0 0 0 2.5-2.5V3" />
                            <path d="M7 11v10" />
                            <path d="M16 3c-2 2.3-3 4.8-3 7.5 0 1.9 1.1 3 3 3v7.5" />

                        </svg>

                    </div>

                    <h3 class="display-font text-2xl mt-5">
                        Nothing on the menu today
                    </h3>

                    <p class="text-sm text-spoon-500 mt-2">
                        Check back soon for your next scheduled meal.
                    </p>

                </div>

            @endif

        </section>



        {{-- ================================================= --}}
        {{-- UPCOMING MEALS --}}
        {{-- ================================================= --}}

        <section class="mb-16">

            <div class="mb-7">

                <p class="text-xs uppercase tracking-[.2em] text-spoon-500 font-semibold">
                    Coming up
                </p>

                <h2 class="display-font text-3xl sm:text-4xl mt-2">
                    Upcoming meals
                </h2>

                <p class="text-sm text-spoon-500 mt-2">
                    A quick look at what's waiting for you.
                </p>

            </div>


            @if ($upcomingEntitlements->isNotEmpty())

                <div class="bg-white border border-spoon-200 rounded-[2rem] overflow-hidden shadow-sm">

                    <div class="divide-y divide-spoon-100">

                        @foreach ($upcomingEntitlements as $entitlement)

                            <div class="px-6 sm:px-8 py-5 flex items-center justify-between gap-5 hover:bg-spoon-50/60 transition">

                                <div class="flex items-center gap-4">

                                    <div class="w-12 h-12 rounded-2xl bg-spoon-100 flex items-center justify-center shrink-0">

                                        <svg
                                            width="20"
                                            height="20"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="#514838"
                                            stroke-width="1.5"
                                        >

                                            <rect
                                                x="3"
                                                y="4"
                                                width="18"
                                                height="17"
                                                rx="2"
                                            />

                                            <path d="M8 2v4" />
                                            <path d="M16 2v4" />
                                            <path d="M3 10h18" />

                                        </svg>

                                    </div>


                                    <div>

                                        <p class="text-xs uppercase tracking-[.12em] text-spoon-500 font-semibold">

                                            {{ $entitlement->scheduled_for->format('l, d M') }}

                                        </p>

                                        <p class="font-semibold mt-1">
                                            {{ $entitlement->meal->name }}
                                        </p>

                                    </div>

                                </div>


                                <span class="hidden sm:inline-flex px-3 py-1.5 rounded-full bg-spoon-100 text-spoon-700 text-[10px] uppercase tracking-[.1em] font-bold">

                                    {{ $entitlement->meal->meal_type }}

                                </span>

                            </div>

                        @endforeach

                    </div>

                </div>

            @else

                <div class="bg-white border border-spoon-200 rounded-[2rem] p-9 text-center">

                    <p class="text-sm text-spoon-500">
                        No upcoming meals scheduled yet.
                    </p>

                </div>

            @endif

        </section>


    @else


        {{-- ================================================= --}}
        {{-- NO ACTIVE PLAN --}}
        {{-- ================================================= --}}

        <section class="mb-14">

            <div class="relative overflow-hidden rounded-[2rem] bg-spoon-900 text-white shadow-card">

                <div class="absolute -right-20 -top-20 w-72 h-72 rounded-full border border-white/10"></div>

                <div class="absolute -left-32 -bottom-40 w-96 h-96 rounded-full bg-white/[.025]"></div>


                <div class="relative px-7 py-14 sm:px-12 sm:py-16 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center mb-7">

                        <svg
                            width="28"
                            height="28"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >

                            <path d="M7 3v8" />
                            <path d="M4.5 3v5.5A2.5 2.5 0 0 0 7 11a2.5 2.5 0 0 0 2.5-2.5V3" />
                            <path d="M7 11v10" />
                            <path d="M16 3c-2 2.3-3 4.8-3 7.5 0 1.9 1.1 3 3 3v7.5" />

                        </svg>

                    </div>


                    <p class="text-xs uppercase tracking-[.22em] text-white/40 font-semibold">
                        Your next chapter
                    </p>


                    <h2 class="display-font text-4xl sm:text-5xl mt-3">
                        Better meals start here.
                    </h2>


                    <p class="text-white/55 max-w-xl mx-auto mt-4 leading-relaxed">

                        Choose a meal plan designed to make eating well
                        simpler, more convenient and more enjoyable.

                    </p>


                    <a
                        href="{{ route('meal-plans.index') }}"
                        class="inline-flex items-center gap-2 mt-8 bg-white text-spoon-900 px-6 py-3.5 rounded-xl font-semibold hover:bg-spoon-100 transition"
                    >

                        Explore meal plans

                        <svg
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >

                            <path d="M5 12h14" />
                            <path d="M13 6l6 6-6 6" />

                        </svg>

                    </a>

                </div>

            </div>

        </section>

    @endif



    {{-- ========================================================= --}}
    {{-- SUBSCRIPTION HISTORY --}}
    {{-- ========================================================= --}}

    <section class="pb-10">

        <div class="flex items-end justify-between mb-7">

            <div>

                <p class="text-xs uppercase tracking-[.2em] text-spoon-500 font-semibold">
                    Your journey
                </p>

                <h2 class="display-font text-3xl sm:text-4xl mt-2">
                    Subscription history
                </h2>

            </div>

        </div>


        @forelse ($subscriptions as $subscription)

            <div class="bg-white border border-spoon-200 rounded-2xl p-5 sm:p-6 mb-3 hover:border-spoon-300 transition">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div class="flex items-center gap-4">

                        <div class="w-11 h-11 rounded-xl bg-spoon-100 flex items-center justify-center shrink-0">

                            <svg
                                width="19"
                                height="19"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#514838"
                                stroke-width="1.5"
                            >

                                <path d="M4 5h16" />
                                <path d="M4 12h16" />
                                <path d="M4 19h16" />

                            </svg>

                        </div>


                        <div>

                            <h3 class="font-semibold">
                                {{ $subscription->mealPlan->name }}
                            </h3>

                            <p class="text-xs text-spoon-500 mt-1">

                                {{ $subscription->starts_at?->format('d M Y') }}

                                <span class="mx-1">→</span>

                                {{ $subscription->ends_at?->format('d M Y') }}

                            </p>

                        </div>

                    </div>


                    <span class="inline-flex w-fit px-3 py-1.5 rounded-full bg-spoon-100 text-spoon-700 text-[10px] uppercase tracking-[.1em] font-bold">

                        {{ ucfirst($subscription->status->value) }}

                    </span>

                </div>

            </div>

        @empty

            <div class="bg-white border border-spoon-200 rounded-2xl p-9 text-center">

                <p class="text-sm text-spoon-500">
                    Your subscription history will appear here.
                </p>

            </div>

        @endforelse

    </section>

</main>



{{-- ========================================================= --}}
{{-- REDEMPTION MODAL --}}
{{-- ========================================================= --}}

<div
    id="redeemModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-spoon-900/60 backdrop-blur-sm px-4"
>

    <div
        class="w-full max-w-md bg-white rounded-[2rem] shadow-card p-7 sm:p-8 transform transition"
        id="redeemModalContent"
    >

        <div class="flex items-start justify-between mb-6">

            <div>

                <div class="w-11 h-11 rounded-xl bg-spoon-100 flex items-center justify-center mb-4">

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#514838"
                        stroke-width="1.5"
                    >

                        <path d="M4 3h16v18H4z" />

                        <path d="M8 7h8" />
                        <path d="M8 11h8" />
                        <path d="M8 15h5" />

                    </svg>

                </div>


                <p class="text-xs uppercase tracking-[.18em] text-spoon-500 font-semibold">
                    Meal redemption
                </p>

                <h2 class="display-font text-3xl mt-1">
                    Redeem this meal?
                </h2>

            </div>


            <button
                type="button"
                id="closeRedeemModal"
                class="w-9 h-9 rounded-full bg-spoon-100 text-spoon-500 hover:bg-spoon-200 hover:text-spoon-900 transition flex items-center justify-center text-xl"
            >
                &times;
            </button>

        </div>


        <div class="bg-spoon-50 border border-spoon-200 rounded-2xl p-5 mb-5">

            <p
                id="modalMealName"
                class="font-semibold text-lg"
            ></p>

            <p
                id="modalMealType"
                class="text-sm text-spoon-500 mt-1"
            ></p>

        </div>


        <p class="text-sm text-spoon-500 leading-relaxed mb-7">

            Once redeemed, this meal will be marked as used and
            cannot be redeemed again.

        </p>


        <div class="flex gap-3">

            <button
                type="button"
                id="cancelRedeem"
                class="flex-1 border border-spoon-200 text-spoon-700 rounded-xl py-3.5 font-semibold hover:bg-spoon-50 transition"
            >
                Not yet
            </button>


            <button
                type="button"
                id="confirmRedeem"
                class="flex-1 bg-spoon-900 text-white rounded-xl py-3.5 font-semibold hover:bg-spoon-700 transition"
            >
                Confirm redemption
            </button>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- REDEMPTION SCRIPT --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('redeemModal');

    const closeModal =
        document.getElementById('closeRedeemModal');

    const cancelRedeem =
        document.getElementById('cancelRedeem');

    const confirmRedeem =
        document.getElementById('confirmRedeem');

    const modalMealName =
        document.getElementById('modalMealName');

    const modalMealType =
        document.getElementById('modalMealType');


    let selectedButton = null;
    let selectedUrl = null;


    function openModal(button) {

        selectedButton = button;
        selectedUrl = button.dataset.url;

        modalMealName.textContent =
            button.dataset.meal;

        modalMealType.textContent =
            button.dataset.type;


        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }


    function closeRedemptionModal() {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

        selectedButton = null;
        selectedUrl = null;

        confirmRedeem.disabled = false;
        confirmRedeem.textContent =
            'Confirm redemption';

    }


    document
        .querySelectorAll('.redeem-button')
        .forEach(button => {

            button.addEventListener('click', () => {
                openModal(button);
            });

        });


    closeModal.addEventListener(
        'click',
        closeRedemptionModal
    );


    cancelRedeem.addEventListener(
        'click',
        closeRedemptionModal
    );


    modal.addEventListener('click', event => {

        if (event.target === modal) {
            closeRedemptionModal();
        }

    });


    document.addEventListener('keydown', event => {

        if (
            event.key === 'Escape' &&
            !modal.classList.contains('hidden')
        ) {
            closeRedemptionModal();
        }

    });


    confirmRedeem.addEventListener(
        'click',
        async () => {

            if (!selectedButton || !selectedUrl) {
                return;
            }


            const button = selectedButton;


            confirmRedeem.disabled = true;

            confirmRedeem.textContent =
                'Redeeming...';


            try {

                const response = await fetch(
                    selectedUrl,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute(
                                        'content'
                                    )
                        }
                    }
                );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Unable to redeem meal.'
                    );

                }


                closeRedemptionModal();


                const container =
                    button.parentElement;


                container.innerHTML = `

                    <div class="mt-6 rounded-xl bg-green-50 border border-green-100 p-4">

                        <div class="flex items-center gap-2 text-green-700 font-semibold text-sm">

                            <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                                ✓
                            </span>

                            Meal redeemed

                        </div>

                        <p class="text-[11px] text-green-700/70 mt-2">

                            Reference:
                            <span class="font-semibold">
                                ${data.reference}
                            </span>

                        </p>

                    </div>

                `;


            } catch (error) {

                alert(error.message);

                confirmRedeem.disabled = false;

                confirmRedeem.textContent =
                    'Confirm redemption';

            }

        }
    );

});

</script>



{{-- ========================================================= --}}
{{-- QR CODE --}}
{{-- ========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>


@if($activeSubscription)

<script>

document.addEventListener('DOMContentLoaded', () => {

    const qrContainer =
        document.getElementById('customer-qr');


    if (!qrContainer) {
        return;
    }


    new QRCode(
        qrContainer,
        {
            text:
                "{{ route('staff.meals.scan') }}?token={{ $activeSubscription->qr_token }}",

            width: 220,

            height: 220,

            correctLevel:
                QRCode.CorrectLevel.H
        }
    );

});

</script>

@endif


</body>
</html>
