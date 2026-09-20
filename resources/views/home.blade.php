<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Silver Spoon — Meals Made Simple</title>

    <meta
        name="description"
        content="Plan your meals, choose your favourites and enjoy delicious food without the daily hassle."
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                        display: ['Playfair Display', 'Georgia', 'serif'],
                    },

                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'fade-up': 'fadeUp .7s ease-out forwards',
                    },

                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-12px)' },
                        },

                        fadeUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
        }

        .display-font {
            font-family: 'Playfair Display', serif;
        }

        .glass {
            background: rgba(255,255,255,.72);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(15,23,42,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,23,42,.035) 1px, transparent 1px);
            background-size: 44px 44px;
        }

        .soft-shadow {
            box-shadow:
                0 30px 80px rgba(15, 23, 42, .10),
                0 10px 30px rgba(15, 23, 42, .05);
        }

        .food-shadow {
            box-shadow: 0 30px 60px rgba(0,0,0,.18);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#fafaf8] text-slate-900 antialiased">

<!-- =========================================================
     NAVIGATION
========================================================= -->

<nav class="fixed inset-x-0 top-0 z-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-4">

        <div class="glass border border-white/70 shadow-lg shadow-slate-900/5
                    rounded-2xl px-4 sm:px-6 h-16 flex items-center justify-between">

            <!-- Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-slate-950 text-white
                            flex items-center justify-center font-bold text-lg">
                    S
                </div>

                <div>
                    <div class="font-bold tracking-tight text-lg leading-none">
                        Silver Spoon
                    </div>

                    <div class="text-[10px] uppercase tracking-[.22em]
                                text-slate-400 mt-1 hidden sm:block">
                        Meals made simple
                    </div>
                </div>

            </a>

            <!-- Desktop navigation -->
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">

                <a href="#how-it-works"
                   class="hover:text-slate-950 transition">
                    How it works
                </a>

                <a href="#plans"
                   class="hover:text-slate-950 transition">
                    Meal plans
                </a>

                <a href="#menu"
                   class="hover:text-slate-950 transition">
                    Menu
                </a>

            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">

                @auth

                    <a href="{{ route('dashboard') }}"
                       class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold
                              text-slate-600 hover:text-slate-950">
                        Dashboard
                    </a>

                    <a href="{{ route('meal-plans.index') }}"
                       class="inline-flex items-center gap-2
                              bg-slate-950 text-white px-4 sm:px-5 py-2.5
                              rounded-xl text-sm font-semibold
                              hover:bg-slate-800 transition">
                        Explore plans
                        <span>→</span>
                    </a>

                @else

                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold
                              text-slate-600 hover:text-slate-950">
                        Sign in
                    </a>

                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2
                              bg-slate-950 text-white px-4 sm:px-5 py-2.5
                              rounded-xl text-sm font-semibold
                              hover:bg-slate-800 transition">
                        Get started
                        <span>→</span>
                    </a>

                @endauth

            </div>

        </div>
    </div>
</nav>


<!-- =========================================================
     HERO
========================================================= -->

<section class="relative overflow-hidden pt-32 sm:pt-40 pb-20 lg:pb-28 hero-grid">

    <!-- Decorative background -->
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px]
                bg-amber-200/30 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 -left-40 w-[450px] h-[450px]
                bg-orange-100/40 rounded-full blur-3xl"></div>


    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">

        <div class="grid lg:grid-cols-[1.05fr_.95fr] gap-14 lg:gap-20 items-center">

            <!-- LEFT -->
            <div class="max-w-2xl">

                <div class="inline-flex items-center gap-2
                            bg-white border border-slate-200
                            rounded-full px-3 py-1.5
                            text-xs font-semibold text-slate-600
                            shadow-sm mb-7">

                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                    Meal plans now available

                </div>


                <h1 class="display-font text-5xl sm:text-6xl lg:text-7xl
                           leading-[.98] tracking-tight text-slate-950">

                    Stop wondering
                    <span class="italic text-slate-500">
                        what to eat.
                    </span>

                </h1>


                <p class="mt-7 text-lg sm:text-xl leading-8
                          text-slate-600 max-w-xl">

                    Your meals are planned, prepared and ready for you.
                    Choose a plan that fits your routine — or build your
                    own weekly menu.

                </p>


                <!-- CTAs -->

                <div class="flex flex-col sm:flex-row gap-3 mt-9">

                    <a href="{{ route('meal-plans.index') }}"
                       class="group inline-flex items-center justify-center
                              gap-3 px-6 py-4 rounded-2xl
                              bg-slate-950 text-white
                              font-semibold
                              shadow-xl shadow-slate-950/15
                              hover:bg-slate-800 hover:-translate-y-0.5
                              transition">

                        Explore meal plans

                        <span class="group-hover:translate-x-1 transition">
                            →
                        </span>

                    </a>


                    <a href="#how-it-works"
                       class="inline-flex items-center justify-center
                              px-6 py-4 rounded-2xl
                              bg-white border border-slate-200
                              text-slate-800 font-semibold
                              hover:border-slate-400
                              transition">

                        See how it works

                    </a>

                </div>


                <!-- Trust -->
                <div class="flex flex-wrap gap-x-6 gap-y-3 mt-9
                            text-sm text-slate-500">

                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600">✓</span>
                        Flexible meal plans
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600">✓</span>
                        Weekly variety
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600">✓</span>
                        Simple checkout
                    </div>

                </div>

            </div>


            <!-- RIGHT VISUAL -->

            <div class="relative min-h-[500px] flex items-center justify-center">

                @php
                    $heroMeal = $featuredMeals->first();
                @endphp


                @if($heroMeal)

                    <!-- Main food image -->

                    <div class="relative z-10 w-[290px] sm:w-[370px]
                                aspect-square rounded-[2.5rem]
                                overflow-hidden
                                bg-slate-200
                                rotate-[-3deg]
                                food-shadow animate-float">

                        <img
                            src="{{ $heroMeal->image
                                ? asset('storage/' . $heroMeal->image)
                                : 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=85'
                            }}"
                            alt="{{ $heroMeal->name }}"
                            class="w-full h-full object-cover"
                        >

                        <div class="absolute inset-0 bg-gradient-to-t
                                    from-black/50 via-transparent to-transparent"></div>

                        <div class="absolute left-6 right-6 bottom-6 text-white">

                            <div class="text-xs uppercase tracking-[.2em]
                                        text-white/70 mb-1">
                                Featured today
                            </div>

                            <div class="text-2xl font-semibold">
                                {{ $heroMeal->name }}
                            </div>

                        </div>

                    </div>


                    <!-- Floating plan card -->

                    <div class="absolute z-20 top-8 right-0 sm:right-4
                                glass rounded-2xl border border-white
                                shadow-2xl p-4 w-48
                                animate-float-slow">

                        <div class="flex items-center justify-between mb-3">

                            <span class="text-xs font-semibold text-slate-500">
                                YOUR WEEK
                            </span>

                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>

                        </div>

                        <div class="space-y-2">

                            <div class="flex items-center gap-2 text-xs">
                                <span class="w-6 h-6 rounded-lg bg-orange-100
                                             flex items-center justify-center">
                                    ☀
                                </span>
                                Breakfast
                            </div>

                            <div class="flex items-center gap-2 text-xs">
                                <span class="w-6 h-6 rounded-lg bg-amber-100
                                             flex items-center justify-center">
                                    ◐
                                </span>
                                Lunch
                            </div>

                            <div class="flex items-center gap-2 text-xs">
                                <span class="w-6 h-6 rounded-lg bg-indigo-100
                                             flex items-center justify-center">
                                    ◑
                                </span>
                                Supper
                            </div>

                        </div>

                    </div>


                    <!-- Price badge -->

                    <div class="absolute bottom-6 left-0 sm:left-4
                                z-20 bg-slate-950 text-white
                                rounded-2xl px-5 py-4 shadow-2xl">

                        <div class="text-[10px] uppercase tracking-[.2em]
                                    text-white/50">
                            Starting from
                        </div>

                        <div class="text-2xl font-bold mt-1">
                            KSh {{ number_format($mealPlans->min('price')) }}
                        </div>

                    </div>


                    <!-- Decorative circle -->

                    <div class="absolute w-[390px] h-[390px]
                                sm:w-[470px] sm:h-[470px]
                                rounded-full
                                border border-slate-200
                                -z-0"></div>

                @else

                    <div class="w-full max-w-md aspect-square rounded-[2.5rem]
                                bg-slate-100 flex items-center justify-center">

                        <div class="text-center p-8">

                            <div class="text-5xl mb-4">🍽️</div>

                            <div class="font-semibold text-slate-800">
                                Delicious meals are coming.
                            </div>

                            <div class="text-sm text-slate-500 mt-2">
                                Explore our plans to get started.
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     QUICK VALUE STRIP
========================================================= -->

<section class="border-y border-slate-200 bg-white">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="grid sm:grid-cols-3 divide-y sm:divide-y-0
                    sm:divide-x divide-slate-200">

            <div class="py-7 sm:px-8 flex gap-4">

                <div class="text-2xl">🍽️</div>

                <div>
                    <div class="font-semibold">Meals planned</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Stop making the same food decisions every day.
                    </div>
                </div>

            </div>


            <div class="py-7 sm:px-8 flex gap-4">

                <div class="text-2xl">📅</div>

                <div>
                    <div class="font-semibold">Built around your week</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Choose a ready-made plan or customize your meals.
                    </div>
                </div>

            </div>


            <div class="py-7 sm:px-8 flex gap-4">

                <div class="text-2xl">✨</div>

                <div>
                    <div class="font-semibold">Less daily hassle</div>
                    <div class="text-sm text-slate-500 mt-1">
                        One decision today. Better meals all week.
                    </div>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     HOW IT WORKS
========================================================= -->

<section id="how-it-works" class="py-24 lg:py-32 bg-[#fafaf8]">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="max-w-2xl mb-14">

            <div class="text-xs uppercase tracking-[.25em]
                        font-semibold text-slate-400">
                The Silver Spoon way
            </div>

            <h2 class="display-font text-4xl sm:text-5xl
                       mt-4 tracking-tight">
                From hungry to sorted.
            </h2>

            <p class="mt-5 text-slate-600 text-lg leading-8">
                Silver Spoon removes the daily friction around meals.
                Pick your plan, make it yours and let your schedule do the rest.
            </p>

        </div>


        <div class="grid md:grid-cols-3 gap-6">

            <!-- Step -->

            <div class="group bg-white rounded-[2rem] border border-slate-200
                        p-7 lg:p-9 hover:-translate-y-1
                        hover:shadow-xl hover:shadow-slate-900/5
                        transition">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-bold text-slate-400">
                        01
                    </span>

                    <span class="w-12 h-12 rounded-2xl bg-slate-950
                                 text-white flex items-center justify-center
                                 text-xl">
                        ◎
                    </span>

                </div>

                <h3 class="text-xl font-bold mt-8">
                    Choose your plan
                </h3>

                <p class="text-slate-500 leading-7 mt-3">
                    Pick a meal plan based on how often and how much you
                    want to eat.
                </p>

            </div>


            <div class="group bg-white rounded-[2rem] border border-slate-200
                        p-7 lg:p-9 hover:-translate-y-1
                        hover:shadow-xl hover:shadow-slate-900/5
                        transition">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-bold text-slate-400">
                        02
                    </span>

                    <span class="w-12 h-12 rounded-2xl bg-amber-100
                                 flex items-center justify-center text-xl">
                        ✦
                    </span>

                </div>

                <h3 class="text-xl font-bold mt-8">
                    Make it yours
                </h3>

                <p class="text-slate-500 leading-7 mt-3">
                    Keep the standard menu or select the exact meals you
                    want throughout your week.
                </p>

            </div>


            <div class="group bg-white rounded-[2rem] border border-slate-200
                        p-7 lg:p-9 hover:-translate-y-1
                        hover:shadow-xl hover:shadow-slate-900/5
                        transition">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-bold text-slate-400">
                        03
                    </span>

                    <span class="w-12 h-12 rounded-2xl bg-emerald-100
                                 flex items-center justify-center text-xl">
                        ✓
                    </span>

                </div>

                <h3 class="text-xl font-bold mt-8">
                    Enjoy your meals
                </h3>

                <p class="text-slate-500 leading-7 mt-3">
                    Your schedule is set. Your meals are waiting.
                    No daily planning required.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     PLANS
========================================================= -->

<section id="plans" class="py-24 lg:py-32 bg-white">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="flex flex-col md:flex-row
                    md:items-end justify-between gap-6 mb-14">

            <div>

                <div class="text-xs uppercase tracking-[.25em]
                            font-semibold text-slate-400">
                    Meal plans
                </div>

                <h2 class="display-font text-4xl sm:text-5xl
                           mt-4 tracking-tight">
                    Find your rhythm.
                </h2>

                <p class="text-slate-500 mt-4 max-w-xl">
                    Start simple with a ready-made plan.
                    You can customize your meals when you want more control.
                </p>

            </div>

            <a href="{{ route('meal-plans.index') }}"
               class="font-semibold text-slate-950
                      border-b-2 border-slate-950 pb-1
                      hover:border-slate-400 transition">
                View all plans →
            </a>

        </div>


        @if($mealPlans->count())

            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

                @foreach($mealPlans as $plan)

                    <article
                        class="relative group rounded-[2rem]
                               border border-slate-200 bg-[#fafaf8]
                               overflow-hidden
                               hover:-translate-y-1
                               hover:shadow-2xl hover:shadow-slate-900/10
                               transition duration-300">

                        @if($plan->is_active)

                            <div class="absolute top-5 right-5 z-10
                                        px-3 py-1.5 rounded-full
                                        bg-emerald-50 text-emerald-700
                                        text-xs font-bold">
                                Available
                            </div>

                        @endif


                        <div class="p-7 lg:p-8">

                            <div class="flex items-start justify-between">

                                <div>

                                    <div class="text-xs uppercase
                                                tracking-[.2em]
                                                text-slate-400">
                                        {{ $plan->duration_days }} days
                                    </div>

                                    <h3 class="text-2xl font-bold mt-2">
                                        {{ $plan->name }}
                                    </h3>

                                </div>

                            </div>


                            <p class="text-slate-500 leading-7 mt-5 min-h-[56px]">
                                {{ $plan->description }}
                            </p>


                            <div class="mt-7">

                                <div class="text-xs text-slate-400">
                                    Starting at
                                </div>

                                <div class="flex items-end gap-2 mt-1">

                                    <span class="text-4xl font-bold tracking-tight">
                                        KSh {{ number_format($plan->price) }}
                                    </span>

                                    <span class="text-sm text-slate-400 pb-1">
                                        / plan
                                    </span>

                                </div>

                            </div>


                            <div class="grid grid-cols-2 gap-3 mt-7">

                                <div class="rounded-2xl bg-white border
                                            border-slate-200 p-4">

                                    <div class="text-xs text-slate-400">
                                        Duration
                                    </div>

                                    <div class="font-semibold mt-1">
                                        {{ $plan->duration_days }} days
                                    </div>

                                </div>


                                <div class="rounded-2xl bg-white border
                                            border-slate-200 p-4">

                                    <div class="text-xs text-slate-400">
                                        Meal allowance
                                    </div>

                                    <div class="font-semibold mt-1">
                                        {{ $plan->meal_limit }}
                                    </div>

                                </div>

                            </div>


                            <a href="{{ route('meal-plans.show', $plan) }}"
                               class="mt-7 w-full flex items-center
                                      justify-center gap-2
                                      bg-slate-950 text-white
                                      rounded-2xl py-4
                                      font-semibold
                                      group-hover:bg-slate-800
                                      transition">

                                Explore {{ $plan->name }}

                                <span class="group-hover:translate-x-1 transition">
                                    →
                                </span>

                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="rounded-[2rem] border border-dashed
                        border-slate-300 p-14 text-center">

                <div class="text-5xl">🍽️</div>

                <h3 class="font-bold text-xl mt-5">
                    Meal plans are coming soon.
                </h3>

                <p class="text-slate-500 mt-2">
                    Check back shortly for our available plans.
                </p>

            </div>

        @endif

    </div>

</section>


<!-- =========================================================
     CUSTOMIZATION FEATURE
========================================================= -->

<section class="py-24 lg:py-32 bg-slate-950 text-white overflow-hidden">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- Text -->

            <div>

                <div class="inline-flex items-center gap-2
                            rounded-full border border-white/10
                            bg-white/5 px-4 py-2
                            text-xs font-semibold text-white/70">

                    <span>✦</span>
                    Your meals, your rules

                </div>


                <h2 class="display-font text-5xl sm:text-6xl
                           leading-tight mt-7">

                    Don't like the
                    <span class="italic text-white/50">
                        standard menu?
                    </span>

                </h2>


                <p class="text-white/60 text-lg leading-8 mt-6 max-w-xl">

                    Build your own weekly meal schedule.
                    Pick breakfast, lunch or supper for the exact days
                    you want them.

                </p>


                <a href="{{ route('meal-plans.index') }}"
                   class="inline-flex items-center gap-3 mt-9
                          bg-white text-slate-950
                          rounded-2xl px-6 py-4
                          font-semibold
                          hover:bg-slate-100 transition">

                    Pick your meal plan

                    <span>→</span>

                </a>

            </div>


            <!-- Weekly schedule visual -->

            <div class="relative">

                <div class="rounded-[2rem] bg-white text-slate-950
                            p-5 sm:p-7 shadow-2xl">

                    <div class="flex items-center justify-between mb-6">

                        <div>
                            <div class="text-xs text-slate-400 uppercase
                                        tracking-[.2em]">
                                Your week
                            </div>

                            <div class="font-bold text-xl mt-1">
                                Custom schedule
                            </div>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-slate-100
                                    flex items-center justify-center">
                            ✓
                        </div>

                    </div>


                    <div class="space-y-3">

                        @foreach([
                            ['Monday', 'Supper', 'Chicken & Rice'],
                            ['Tuesday', 'Lunch', 'Beef Pilau'],
                            ['Tuesday', 'Supper', 'Vegetable Curry'],
                            ['Wednesday', 'Breakfast', 'Eggs & Toast'],
                            ['Thursday', 'Supper', 'Ugali & Fish'],
                            ['Friday', 'Lunch', 'Chicken Biryani'],
                        ] as $meal)

                            <div class="flex items-center gap-4
                                        rounded-2xl border border-slate-100
                                        p-3 hover:bg-slate-50 transition">

                                <div class="w-11 h-11 rounded-xl
                                            bg-slate-100
                                            flex items-center justify-center
                                            text-xs font-bold">

                                    {{ substr($meal[0], 0, 1) }}

                                </div>

                                <div class="flex-1">

                                    <div class="text-xs text-slate-400">
                                        {{ $meal[0] }} · {{ $meal[1] }}
                                    </div>

                                    <div class="font-semibold text-sm mt-1">
                                        {{ $meal[2] }}
                                    </div>

                                </div>

                                <span class="text-slate-300">
                                    ›
                                </span>

                            </div>

                        @endforeach

                    </div>

                    <div class="mt-5 rounded-2xl bg-slate-950
                                text-white p-4 flex items-center
                                justify-between">

                        <div>
                            <div class="text-xs text-white/50">
                                Your selection
                            </div>

                            <div class="font-semibold mt-1">
                                Weekly meals
                            </div>
                        </div>

                        <div class="font-bold">
                            6 meals
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     MENU
========================================================= -->

<section id="menu" class="py-24 lg:py-32 bg-[#fafaf8]">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="flex flex-col md:flex-row
                    md:items-end justify-between gap-6 mb-14">

            <div>

                <div class="text-xs uppercase tracking-[.25em]
                            font-semibold text-slate-400">
                    From the kitchen
                </div>

                <h2 class="display-font text-4xl sm:text-5xl
                           mt-4">
                    A menu worth planning around.
                </h2>

            </div>

        </div>


        @if($featuredMeals->count())

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($featuredMeals as $meal)

                    <article class="group">

                        <div class="relative aspect-[4/3]
                                    rounded-[2rem]
                                    overflow-hidden
                                    bg-slate-100">

                            @if($meal->image)

                                <img
                                    src="{{ asset('storage/' . $meal->image) }}"
                                    alt="{{ $meal->name }}"
                                    class="w-full h-full object-cover
                                           group-hover:scale-105
                                           transition duration-700"
                                >

                            @else

                                <div class="w-full h-full flex items-center
                                            justify-center text-5xl">
                                    🍽️
                                </div>

                            @endif


                            <div class="absolute top-4 left-4
                                        px-3 py-1.5 rounded-full
                                        bg-white/90 backdrop-blur
                                        text-xs font-semibold">

                                {{ ucfirst($meal->meal_type) }}

                            </div>

                        </div>


                        <div class="px-1 pt-5">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <h3 class="text-xl font-bold">
                                        {{ $meal->name }}
                                    </h3>

                                    <p class="text-sm text-slate-400 mt-1">
                                        {{ $meal->mealPlan->name ?? 'Silver Spoon Menu' }}
                                    </p>

                                </div>

                                <div class="text-xs font-semibold
                                            bg-slate-100 px-3 py-1.5
                                            rounded-full whitespace-nowrap">

                                    Day {{ $meal->day_of_week }}

                                </div>

                            </div>


                            @if($meal->description)

                                <p class="text-sm leading-6
                                          text-slate-500 mt-3">

                                    {{ Str::limit($meal->description, 100) }}

                                </p>

                            @endif

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="rounded-[2rem] border border-dashed
                        border-slate-300 p-14 text-center">

                <div class="text-5xl">🥘</div>

                <h3 class="font-bold text-xl mt-5">
                    Our menu is being prepared.
                </h3>

            </div>

        @endif

    </div>

</section>


<!-- =========================================================
     TRUST / EXPERIENCE
========================================================= -->

<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="grid lg:grid-cols-[.8fr_1.2fr] gap-16 items-start">

            <div>

                <div class="text-xs uppercase tracking-[.25em]
                            font-semibold text-slate-400">
                    Designed around you
                </div>

                <h2 class="display-font text-4xl sm:text-5xl mt-4">
                    Food should fit your life.
                </h2>

                <p class="text-slate-500 leading-8 mt-6">
                    Silver Spoon is designed to remove the repetitive
                    decisions that come with figuring out breakfast,
                    lunch and supper every day.
                </p>

            </div>


            <div class="grid sm:grid-cols-2 gap-4">

                <div class="rounded-[1.75rem] bg-[#fafaf8]
                            border border-slate-200 p-7">

                    <div class="text-2xl">🧠</div>

                    <h3 class="font-bold text-lg mt-6">
                        Less decision fatigue
                    </h3>

                    <p class="text-sm text-slate-500 leading-6 mt-2">
                        Your meals are planned ahead instead of becoming
                        another daily decision.
                    </p>

                </div>


                <div class="rounded-[1.75rem] bg-[#fafaf8]
                            border border-slate-200 p-7">

                    <div class="text-2xl">📆</div>

                    <h3 class="font-bold text-lg mt-6">
                        Built around schedules
                    </h3>

                    <p class="text-sm text-slate-500 leading-6 mt-2">
                        Choose meals around the days and times that
                        actually work for you.
                    </p>

                </div>


                <div class="rounded-[1.75rem] bg-[#fafaf8]
                            border border-slate-200 p-7">

                    <div class="text-2xl">🍛</div>

                    <h3 class="font-bold text-lg mt-6">
                        Variety without effort
                    </h3>

                    <p class="text-sm text-slate-500 leading-6 mt-2">
                        Discover different meals without having to
                        plan an entire menu yourself.
                    </p>

                </div>


                <div class="rounded-[1.75rem] bg-[#fafaf8]
                            border border-slate-200 p-7">

                    <div class="text-2xl">⚡</div>

                    <h3 class="font-bold text-lg mt-6">
                        Simple experience
                    </h3>

                    <p class="text-sm text-slate-500 leading-6 mt-2">
                        Select your meals, check out and get on with
                        your day.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>





<!-- =========================================================
     FINAL CTA
========================================================= -->

<section class="px-6 pb-10">

    <div class="max-w-7xl mx-auto">

        <div class="relative overflow-hidden
                    rounded-[2.5rem]
                    bg-slate-950 text-white
                    px-7 py-16 sm:px-12 lg:px-20 lg:py-20">

            <div class="absolute -top-32 -right-32
                        w-96 h-96 rounded-full
                        bg-white/5 blur-3xl"></div>

            <div class="absolute -bottom-32 -left-32
                        w-96 h-96 rounded-full
                        bg-amber-500/10 blur-3xl"></div>


            <div class="relative max-w-3xl">

                <div class="text-xs uppercase tracking-[.25em]
                            text-white/40 font-semibold">
                    Ready when you are
                </div>

                <h2 class="display-font text-4xl sm:text-5xl
                           lg:text-6xl mt-5 leading-tight">

                    Your next meal
                    <span class="italic text-white/50">
                        is already planned.
                    </span>

                </h2>

                <p class="text-white/60 text-lg leading-8 mt-6 max-w-xl">
                    Choose a plan and make your week easier.
                </p>


                <a href="{{ route('meal-plans.index') }}"
                   class="inline-flex items-center gap-3
                          mt-8 bg-white text-slate-950
                          px-6 py-4 rounded-2xl
                          font-semibold
                          hover:bg-slate-100 transition">

                    Explore meal plans

                    <span>→</span>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="bg-[#fafaf8] border-t border-slate-200">

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <div class="flex flex-col sm:flex-row
                    items-start sm:items-center
                    justify-between gap-8">

            <div>

                <a href="{{ route('home') }}"
                   class="font-bold text-xl">
                    Silver Spoon
                </a>

                <p class="text-sm text-slate-500 mt-2">
                    Meals made simple.
                </p>

            </div>


            <div class="flex flex-wrap gap-6 text-sm text-slate-500">


            
                                <a
                                    href="{{ route('terms') }}"
                                    class="hover:text-slate-950 transition"
                                >
                                    Terms
                                </a>

                          
                                <a
                                        href="{{ route('privacy') }}"
                                        class="hover:text-slate-950 transition"
                                    >
                                        Privacy Policy
                                </a>.

                                <a href="{{ route('meal-plans.index') }}"
                                class="hover:text-slate-950 transition">
                                    Meal Plans
                                </a>

                                <a href="#how-it-works"
                                class="hover:text-slate-950 transition">
                                    How it works
                                </a>


                                @auth

                                    <a href="{{ route('home') }}"
                                    class="hover:text-slate-950 transition">
                                        Dashboard
                                    </a>

                                @else

                                    <a href="{{ route('login') }}"
                                    class="hover:text-slate-950 transition">
                                        Sign in
                                    </a>

                                @endauth

            </div>

        </div>


        <div class="border-t border-slate-200 mt-10 pt-6
                    flex flex-col sm:flex-row
                    justify-between gap-3
                    text-xs text-slate-400">

            <div>
                © {{ date('Y') }} Silver Spoon. All rights reserved.
            </div>

            <div>
                Good food. Less thinking.
            </div>

        </div>

    </div>

</footer>


<!-- =========================================================
     MOBILE STICKY CTA
========================================================= -->

<div class="md:hidden fixed bottom-0 inset-x-0 z-50 p-3
            bg-white/90 backdrop-blur-xl
            border-t border-slate-200">

    <div class="flex items-center gap-3">

        <div class="flex-1 pl-2">

            <div class="text-[10px] uppercase tracking-wider
                        text-slate-400">
                Ready to eat?
            </div>

            <div class="font-semibold text-sm">
                Choose your meal plan
            </div>

        </div>

        <a href="{{ route('meal-plans.index') }}"
           class="bg-slate-950 text-white
                  rounded-xl px-5 py-3
                  font-semibold text-sm">
            Get started →
        </a>

    </div>

</div>

</body>
</html>