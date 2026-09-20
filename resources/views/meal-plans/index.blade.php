<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meal Plans — Silver Spoon</title>

    <meta
        name="description"
        content="Choose a Silver Spoon meal plan and make your meals easier to manage."
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'ui-sans-serif', 'system-ui'],
                        display: ['Playfair Display', 'Georgia', 'serif'],
                    },

                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },

                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-8px)'
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
            background: rgba(255,255,255,.76);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(15,23,42,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,23,42,.035) 1px, transparent 1px);

            background-size: 44px 44px;
        }

        .plan-shadow {
            box-shadow:
                0 30px 80px rgba(15,23,42,.08),
                0 10px 30px rgba(15,23,42,.04);
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


<body class="min-h-screen bg-[#fafaf8] text-slate-900 antialiased">


<!-- =========================================================
     NAVIGATION
========================================================= -->

<nav class="fixed inset-x-0 top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">

        <div
            class="glass border border-white/80
                   rounded-2xl h-16 px-4 sm:px-6
                   flex items-center justify-between
                   shadow-lg shadow-slate-900/5"
        >

            <!-- Brand -->

            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3"
            >

                <div
                    class="w-10 h-10 rounded-xl
                           bg-slate-950 text-white
                           flex items-center justify-center
                           font-bold text-lg"
                >
                    S
                </div>

                <div>

                    <div class="font-bold text-lg tracking-tight leading-none">
                        Silver Spoon
                    </div>

                    <div
                        class="hidden sm:block
                               text-[10px] uppercase
                               tracking-[.22em]
                               text-slate-400 mt-1"
                    >
                        Meals made simple
                    </div>

                </div>

            </a>


            <!-- Navigation -->

            <div
                class="hidden md:flex items-center
                       gap-8 text-sm font-medium
                       text-slate-500"
            >

                <a
                    href="{{ route('home') }}"
                    class="hover:text-slate-950 transition"
                >
                    Home
                </a>

                <a
                    href="#plans"
                    class="text-slate-950"
                >
                    Meal plans
                </a>

                <a
                    href="{{ route('home') }}#how-it-works"
                    class="hover:text-slate-950 transition"
                >
                    How it works
                </a>

            </div>


            <!-- Actions -->

            <div class="flex items-center gap-2">

                @auth

                    <a
                        href="{{ route('home') }}"
                        class="hidden sm:inline-flex
                               px-4 py-2
                               text-sm font-semibold
                               text-slate-600
                               hover:text-slate-950"
                    >
                        Dashboard
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="hidden sm:inline-flex
                               px-4 py-2
                               text-sm font-semibold
                               text-slate-600
                               hover:text-slate-950"
                    >
                        Sign in
                    </a>

                @endauth

            </div>

        </div>

    </div>

</nav>


<!-- =========================================================
     HERO
========================================================= -->

<section
    class="relative overflow-hidden
           pt-36 sm:pt-40 pb-16
           hero-grid"
>

    <!-- Background -->

    <div
        class="absolute -top-40 -right-40
               w-[500px] h-[500px]
               rounded-full
               bg-amber-200/30
               blur-3xl"
    ></div>

    <div
        class="absolute -bottom-40 -left-40
               w-[500px] h-[500px]
               rounded-full
               bg-orange-100/40
               blur-3xl"
    ></div>


    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">

        <div class="max-w-3xl">

            <div
                class="inline-flex items-center gap-2
                       rounded-full
                       border border-slate-200
                       bg-white
                       px-3 py-1.5
                       text-xs font-semibold
                       text-slate-500
                       shadow-sm"
            >

                <span
                    class="w-2 h-2 rounded-full bg-emerald-500"
                ></span>

                Plans made for real schedules

            </div>


            <h1
                class="display-font
                       text-5xl sm:text-6xl lg:text-7xl
                       leading-[.98]
                       tracking-tight
                       mt-7"
            >

                Choose a week
                <span class="italic text-slate-400">
                    worth eating.
                </span>

            </h1>


            <p
                class="text-lg sm:text-xl
                       leading-8
                       text-slate-600
                       max-w-2xl
                       mt-6"
            >
                Pick a ready-made meal plan and let Silver Spoon
                handle the structure. Want more control? Customize
                your meals when you get inside your plan.
            </p>


            <!-- Small value indicators -->

            <div
                class="flex flex-wrap
                       gap-x-6 gap-y-3
                       mt-8
                       text-sm text-slate-500"
            >

                <div class="flex items-center gap-2">
                    <span class="text-emerald-600">✓</span>
                    Planned meals
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-emerald-600">✓</span>
                    Flexible schedules
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-emerald-600">✓</span>
                    Easy checkout
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     PLANS
========================================================= -->

<main
    id="plans"
    class="max-w-7xl mx-auto
           px-6 lg:px-8
           pb-24 lg:pb-32"
>

    @if ($mealPlans->isEmpty())

        <!-- Empty state -->

        <div
            class="bg-white
                   rounded-[2rem]
                   border border-dashed
                   border-slate-300
                   p-14 sm:p-20
                   text-center
                   plan-shadow"
        >

            <div class="text-6xl">
                🍽️
            </div>

            <h2
                class="display-font
                       text-3xl
                       mt-6"
            >
                Something delicious is coming.
            </h2>

            <p
                class="text-slate-500
                       max-w-md
                       mx-auto
                       mt-3"
            >
                We don't have any active meal plans available
                right now. Please check back shortly.
            </p>

            <a
                href="{{ route('home') }}"
                class="inline-flex
                       items-center gap-2
                       mt-7
                       bg-slate-950
                       text-white
                       px-6 py-3.5
                       rounded-xl
                       font-semibold"
            >
                Back home →
            </a>

        </div>

    @else


        <!-- =================================================
             PLAN INTRO BAR
        ================================================== -->

        <div
            class="flex flex-col
                   sm:flex-row
                   sm:items-center
                   justify-between
                   gap-5
                   mb-8"
        >

            <div>

                <div
                    class="text-xs uppercase
                           tracking-[.22em]
                           text-slate-400
                           font-semibold"
                >
                    Available plans
                </div>

                <div class="text-sm text-slate-500 mt-1">
                    {{ $mealPlans->count() }}
                    {{ $mealPlans->count() === 1 ? 'plan' : 'plans' }}
                    available
                </div>

            </div>


            <div
                class="flex items-center gap-2
                       text-sm text-slate-500"
            >

                <span
                    class="w-2 h-2
                           rounded-full
                           bg-emerald-500"
                ></span>

                Currently accepting subscriptions

            </div>

        </div>


        <!-- =================================================
             PLAN CARDS
        ================================================== -->

        <div
            class="grid
                   md:grid-cols-2
                   xl:grid-cols-3
                   gap-6"
        >

            @foreach ($mealPlans as $index => $plan)

                @php

                    $isFirst = $index === 0;

                    $duration = (int) $plan->duration_days;

                    $mealCount = $plan->meals->count();

                    $dailyMeals = $duration > 0
                        ? round($mealCount / $duration, 1)
                        : 0;

                @endphp


                <article
                    class="group relative
                           bg-white
                           rounded-[2rem]
                           border
                           {{ $isFirst
                                ? 'border-slate-950 ring-1 ring-slate-950/5'
                                : 'border-slate-200' }}
                           overflow-hidden
                           transition duration-300
                           hover:-translate-y-1
                           hover:shadow-2xl
                           hover:shadow-slate-900/10"
                >


                    <!-- Recommended -->

                    @if($isFirst)

                        <div
                            class="absolute
                                   top-5 right-5
                                   z-20
                                   bg-slate-950
                                   text-white
                                   rounded-full
                                   px-3 py-1.5
                                   text-[10px]
                                   uppercase
                                   tracking-[.15em]
                                   font-bold"
                        >
                            Popular
                        </div>

                    @endif


                    <!-- Plan top -->

                    <div class="p-7 sm:p-8">


                        <!-- Plan number -->

                        <div
                            class="flex items-center
                                   justify-between
                                   mb-7"
                        >

                            <div
                                class="w-11 h-11
                                       rounded-2xl
                                       {{ $isFirst
                                            ? 'bg-slate-950 text-white'
                                            : 'bg-slate-100 text-slate-700' }}
                                       flex items-center
                                       justify-center
                                       font-bold"
                            >
                                {{ sprintf('%02d', $index + 1) }}
                            </div>


                            @if($plan->is_active)

                                <div
                                    class="inline-flex
                                           items-center gap-2
                                           text-xs
                                           font-semibold
                                           text-emerald-700"
                                >

                                    <span
                                        class="w-2 h-2
                                               rounded-full
                                               bg-emerald-500"
                                    ></span>

                                    Available

                                </div>

                            @endif

                        </div>


                        <!-- Name -->

                        <h2
                            class="text-2xl
                                   sm:text-3xl
                                   font-bold
                                   tracking-tight"
                        >
                            {{ $plan->name }}
                        </h2>


                        <!-- Description -->

                        <p
                            class="text-slate-500
                                   leading-7
                                   mt-3
                                   min-h-[56px]"
                        >
                            {{ $plan->description }}
                        </p>


                        <!-- Price -->

                        <div class="mt-8">

                            <div
                                class="text-xs
                                       uppercase
                                       tracking-[.18em]
                                       text-slate-400"
                            >
                                Plan price
                            </div>

                            <div
                                class="flex items-baseline
                                       gap-2 mt-1"
                            >

                                <span
                                    class="text-4xl
                                           sm:text-5xl
                                           font-bold
                                           tracking-tight"
                                >
                                    KES {{ number_format($plan->price, 0) }}
                                </span>

                            </div>

                        </div>


                        <!-- Quick stats -->

                        <div
                            class="grid
                                   grid-cols-2
                                   gap-3
                                   mt-8"
                        >

                            <div
                                class="rounded-2xl
                                       bg-[#fafaf8]
                                       border border-slate-100
                                       p-4"
                            >

                                <div
                                    class="text-[11px]
                                           uppercase
                                           tracking-wider
                                           text-slate-400"
                                >
                                    Duration
                                </div>

                                <div
                                    class="font-bold
                                           mt-1"
                                >
                                    {{ $plan->duration_days }} days
                                </div>

                            </div>


                            <div
                                class="rounded-2xl
                                       bg-[#fafaf8]
                                       border border-slate-100
                                       p-4"
                            >

                                <div
                                    class="text-[11px]
                                           uppercase
                                           tracking-wider
                                           text-slate-400"
                                >
                                    Meal limit
                                </div>

                                <div
                                    class="font-bold
                                           mt-1"
                                >
                                    {{ $plan->meal_limit }}
                                </div>

                            </div>

                        </div>


                        <!-- What's included -->

                        <div
                            class="border-t
                                   border-slate-100
                                   mt-7 pt-7"
                        >

                            <div
                                class="text-xs
                                       uppercase
                                       tracking-[.18em]
                                       text-slate-400
                                       font-semibold
                                       mb-4"
                            >
                                What's included
                            </div>


                            <div class="space-y-3">


                                <div
                                    class="flex
                                           items-center
                                           justify-between
                                           text-sm"
                                >

                                    <span
                                        class="text-slate-500"
                                    >
                                        Scheduled meals
                                    </span>

                                    <span
                                        class="font-semibold"
                                    >
                                        {{ $mealCount }}
                                    </span>

                                </div>


                                <div
                                    class="flex
                                           items-center
                                           justify-between
                                           text-sm"
                                >

                                    <span
                                        class="text-slate-500"
                                    >
                                        Meal types
                                    </span>

                                    <span
                                        class="font-semibold"
                                    >
                                        Breakfast · Lunch · Supper
                                    </span>

                                </div>


                                <div
                                    class="flex
                                           items-center
                                           justify-between
                                           text-sm"
                                >

                                    <span
                                        class="text-slate-500"
                                    >
                                        Customization
                                    </span>

                                    <span
                                        class="inline-flex
                                               items-center gap-1.5
                                               font-semibold"
                                    >

                                        <span
                                            class="text-emerald-600"
                                        >
                                            ✓
                                        </span>

                                        Available

                                    </span>

                                </div>

                            </div>

                        </div>


                        <!-- CTA -->

                        <a
                            href="{{ route('meal-plans.show', $plan) }}"
                            class="mt-8
                                   w-full
                                   flex
                                   items-center
                                   justify-center
                                   gap-3
                                   rounded-2xl
                                   py-4
                                   px-5
                                   font-semibold
                                   transition
                                   {{ $isFirst
                                        ? 'bg-slate-950 text-white hover:bg-slate-800'
                                        : 'bg-slate-100 text-slate-950 hover:bg-slate-200' }}"
                        >

                            Explore {{ $plan->name }}

                            <span
                                class="group-hover:translate-x-1
                                       transition"
                            >
                                →
                            </span>

                        </a>


                        <!-- Reassurance -->

                        <div
                            class="text-center
                                   text-xs
                                   text-slate-400
                                   mt-4"
                        >
                            View menu · customize · subscribe
                        </div>

                    </div>

                </article>

            @endforeach

        </div>


        <!-- =================================================
             CUSTOMIZATION EXPLAINER
        ================================================== -->

        <section
            class="mt-20
                   rounded-[2.5rem]
                   bg-slate-950
                   text-white
                   overflow-hidden"
        >

            <div
                class="grid
                       lg:grid-cols-2
                       items-center"
            >

                <!-- Text -->

                <div class="p-8 sm:p-12 lg:p-16">

                    <div
                        class="inline-flex
                               items-center gap-2
                               rounded-full
                               bg-white/5
                               border border-white/10
                               px-3 py-1.5
                               text-xs
                               font-semibold
                               text-white/70"
                    >

                        <span>✦</span>

                        Want more control?

                    </div>


                    <h2
                        class="display-font
                               text-4xl
                               sm:text-5xl
                               leading-tight
                               mt-6"
                    >

                        Your plan doesn't
                        have to look like
                        <span class="italic text-white/40">
                            everyone else's.
                        </span>

                    </h2>


                    <p
                        class="text-white/60
                               leading-8
                               text-lg
                               mt-6
                               max-w-xl"
                    >
                        Choose a base plan, then select the exact
                        breakfasts, lunches and suppers you want.
                        Your selections determine your customized meal
                        schedule.
                    </p>


                    <div
                        class="grid
                               grid-cols-2
                               gap-3
                               mt-8
                               max-w-md"
                    >

                        <div
                            class="rounded-2xl
                                   bg-white/5
                                   border border-white/10
                                   p-4"
                        >

                            <div class="text-xl">
                                📅
                            </div>

                            <div
                                class="font-semibold
                                       text-sm
                                       mt-3"
                            >
                                Pick your days
                            </div>

                        </div>


                        <div
                            class="rounded-2xl
                                   bg-white/5
                                   border border-white/10
                                   p-4"
                        >

                            <div class="text-xl">
                                🍽️
                            </div>

                            <div
                                class="font-semibold
                                       text-sm
                                       mt-3"
                            >
                                Pick your meals
                            </div>

                        </div>

                    </div>

                </div>


                <!-- Visual -->

                <div
                    class="p-6 sm:p-10
                           lg:pr-12"
                >

                    <div
                        class="bg-white
                               rounded-[2rem]
                               text-slate-950
                               p-5 sm:p-7
                               shadow-2xl"
                    >

                        <div
                            class="flex
                                   items-center
                                   justify-between
                                   mb-6"
                        >

                            <div>

                                <div
                                    class="text-[10px]
                                           uppercase
                                           tracking-[.2em]
                                           text-slate-400"
                                >
                                    Custom week
                                </div>

                                <div
                                    class="font-bold
                                           text-xl
                                           mt-1"
                                >
                                    Your selections
                                </div>

                            </div>


                            <div
                                class="w-10 h-10
                                       rounded-xl
                                       bg-emerald-50
                                       text-emerald-600
                                       flex
                                       items-center
                                       justify-center"
                            >
                                ✓
                            </div>

                        </div>


                        @foreach([
                            ['Mon', 'Supper', '✓'],
                            ['Tue', 'Breakfast', '✓'],
                            ['Tue', 'Supper', '✓'],
                            ['Wed', 'Lunch', '✓'],
                            ['Fri', 'Supper', '✓'],
                        ] as $selection)

                            <div
                                class="flex
                                       items-center
                                       gap-3
                                       py-3
                                       border-b
                                       border-slate-100
                                       last:border-0"
                            >

                                <div
                                    class="w-10 h-10
                                           rounded-xl
                                           bg-slate-100
                                           flex
                                           items-center
                                           justify-center
                                           text-xs
                                           font-bold"
                                >
                                    {{ $selection[0] }}
                                </div>


                                <div class="flex-1">

                                    <div
                                        class="text-xs
                                               text-slate-400"
                                    >
                                        {{ $selection[1] }}
                                    </div>

                                    <div
                                        class="font-semibold
                                               text-sm
                                               mt-0.5"
                                    >
                                        Selected meal
                                    </div>

                                </div>


                                <span
                                    class="text-emerald-600
                                           font-bold"
                                >
                                    {{ $selection[2] }}
                                </span>

                            </div>

                        @endforeach


                        <div
                            class="mt-5
                                   bg-slate-950
                                   text-white
                                   rounded-2xl
                                   p-4
                                   flex
                                   items-center
                                   justify-between"
                        >

                            <div>

                                <div
                                    class="text-[10px]
                                           uppercase
                                           tracking-wider
                                           text-white/40"
                                >
                                    Selected meals
                                </div>

                                <div
                                    class="font-semibold
                                           mt-1"
                                >
                                    5 meals this week
                                </div>

                            </div>


                            <span
                                class="font-bold"
                            >
                                Customize →
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =================================================
             HOW TO CHOOSE
        ================================================== -->

        <section class="mt-24">

            <div
                class="text-center
                       max-w-2xl
                       mx-auto"
            >

                <div
                    class="text-xs
                           uppercase
                           tracking-[.25em]
                           text-slate-400
                           font-semibold"
                >
                    Not sure where to start?
                </div>

                <h2
                    class="display-font
                           text-4xl
                           sm:text-5xl
                           mt-4"
                >
                    Keep it simple.
                </h2>

            </div>


            <div
                class="grid
                       md:grid-cols-3
                       gap-5
                       mt-12"
            >

                <div
                    class="bg-white
                           border border-slate-200
                           rounded-[2rem]
                           p-7"
                >

                    <div
                        class="w-11 h-11
                               rounded-2xl
                               bg-slate-100
                               flex items-center
                               justify-center"
                    >
                        01
                    </div>

                    <h3
                        class="font-bold
                               text-lg
                               mt-6"
                    >
                        Pick your duration
                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               leading-6
                               mt-2"
                    >
                        Choose the plan duration that fits
                        your routine and budget.
                    </p>

                </div>


                <div
                    class="bg-white
                           border border-slate-200
                           rounded-[2rem]
                           p-7"
                >

                    <div
                        class="w-11 h-11
                               rounded-2xl
                               bg-slate-100
                               flex items-center
                               justify-center"
                    >
                        02
                    </div>

                    <h3
                        class="font-bold
                               text-lg
                               mt-6"
                    >
                        See what's on the menu
                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               leading-6
                               mt-2"
                    >
                        Explore the meals included in your
                        selected plan before committing.
                    </p>

                </div>


                <div
                    class="bg-white
                           border border-slate-200
                           rounded-[2rem]
                           p-7"
                >

                    <div
                        class="w-11 h-11
                               rounded-2xl
                               bg-slate-100
                               flex items-center
                               justify-center"
                    >
                        03
                    </div>

                    <h3
                        class="font-bold
                               text-lg
                               mt-6"
                    >
                        Customize if you want
                    </h3>

                    <p
                        class="text-sm
                               text-slate-500
                               leading-6
                               mt-2"
                    >
                        Keep the standard plan or create a
                        meal schedule around your preferences.
                    </p>

                </div>

            </div>

        </section>


    @endif

</main>


<!-- =========================================================
     FINAL CTA
========================================================= -->

<section class="px-6 pb-10">

    <div
        class="max-w-7xl mx-auto
               rounded-[2.5rem]
               bg-slate-950
               text-white
               overflow-hidden
               relative"
    >

        <div
            class="absolute
                   -top-40
                   -right-40
                   w-[450px]
                   h-[450px]
                   rounded-full
                   bg-white/5
                   blur-3xl"
        ></div>


        <div
            class="relative
                   px-7 py-14
                   sm:px-12
                   lg:px-20
                   lg:py-20"
        >

            <div class="max-w-3xl">

                <div
                    class="text-xs
                           uppercase
                           tracking-[.25em]
                           text-white/40
                           font-semibold"
                >
                    Make your week easier
                </div>


                <h2
                    class="display-font
                           text-4xl
                           sm:text-5xl
                           lg:text-6xl
                           leading-tight
                           mt-5"
                >

                    Choose once.
                    <span class="italic text-white/40">
                        Eat better all week.
                    </span>

                </h2>


                <p
                    class="text-white/60
                           text-lg
                           leading-8
                           mt-6
                           max-w-xl"
                >
                    Pick your Silver Spoon plan and take the daily
                    meal decision off your plate.
                </p>


                <a
                    href="#plans"
                    class="inline-flex
                           items-center
                           gap-3
                           mt-8
                           bg-white
                           text-slate-950
                           rounded-2xl
                           px-6 py-4
                           font-semibold
                           hover:bg-slate-100
                           transition"
                >
                    Choose a plan

                    <span>
                        ↑
                    </span>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer
    class="bg-[#fafaf8]
           border-t border-slate-200"
>

    <div
        class="max-w-7xl mx-auto
               px-6 lg:px-8
               py-12"
    >

        <div
            class="flex
                   flex-col
                   sm:flex-row
                   items-start
                   sm:items-center
                   justify-between
                   gap-8"
        >

            <div>

                <a
                    href="{{ route('home') }}"
                    class="font-bold text-xl"
                >
                    Silver Spoon
                </a>

                <p
                    class="text-sm
                           text-slate-500
                           mt-2"
                >
                    Meals made simple.
                </p>

            </div>


            <div
                class="flex
                       flex-wrap
                       gap-6
                       text-sm
                       text-slate-500"
            >

                <a
                    href="{{ route('home') }}"
                    class="hover:text-slate-950 transition"
                >
                    Home
                </a>

                <a
                    href="#plans"
                    class="hover:text-slate-950 transition"
                >
                    Meal Plans
                </a>

                <a
                    href="{{ route('home') }}#how-it-works"
                    class="hover:text-slate-950 transition"
                >
                    How it works
                </a>

                @auth

                    <a
                        href="{{ route('home') }}"
                        class="hover:text-slate-950 transition"
                    >
                        Dashboard
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="hover:text-slate-950 transition"
                    >
                        Sign in
                    </a>

                @endauth

            </div>

        </div>


        <div
            class="border-t
                   border-slate-200
                   mt-10 pt-6
                   flex
                   flex-col
                   sm:flex-row
                   justify-between
                   gap-3
                   text-xs
                   text-slate-400"
        >

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

<div
    class="md:hidden
           fixed
           bottom-0
           inset-x-0
           z-50
           p-3
           bg-white/90
           backdrop-blur-xl
           border-t border-slate-200"
>

    <div class="flex items-center gap-3">

        <div class="flex-1 pl-2">

            <div
                class="text-[10px]
                       uppercase
                       tracking-wider
                       text-slate-400"
            >
                Silver Spoon
            </div>

            <div class="font-semibold text-sm">
                Choose your meal plan
            </div>

        </div>


        <a
            href="#plans"
            class="bg-slate-950
                   text-white
                   rounded-xl
                   px-5 py-3
                   font-semibold
                   text-sm"
        >
            Choose →
        </a>

    </div>

</div>
</body>
</html>