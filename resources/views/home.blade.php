<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <nav class="bg-black text-white">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <a
                href="{{ route('home') }}"
                class="text-xl font-bold"
            >
                Silver Spoon
            </a>

            <div class="flex items-center gap-6">

                <a
                    href="{{ route('meal-plans.index') }}"
                    class="text-sm text-gray-300 hover:text-white"
                >
                    Meal Plans
                </a>

                @auth

                    <a
                        href="{{ route('dashboard') }}"
                        class="text-sm text-gray-300 hover:text-white"
                    >
                        Dashboard
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="text-sm text-gray-300 hover:text-white"
                    >
                        Login
                    </a>

                @endauth

            </div>

        </div>
    </nav>


    <main class="max-w-7xl mx-auto px-6 py-12">

        {{-- HERO --}}

        <section class="py-16">

            <div class="max-w-3xl">

                <p class="text-sm font-semibold uppercase tracking-widest text-gray-500">
                    Silver Spoon
                </p>

                <h1 class="text-5xl md:text-6xl font-bold tracking-tight mt-4">
                    Your meals.
                    <br>
                    Planned for you.
                </h1>

                <p class="text-xl text-gray-500 mt-6 max-w-2xl">
                    Choose a meal plan, get your weekly menu,
                    and enjoy your meals without the daily hassle.
                </p>

                <div class="flex flex-wrap gap-4 mt-8">

                    <a
                        href="{{ route('meal-plans.index') }}"
                        class="px-6 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800"
                    >
                        View Meal Plans
                    </a>

                    @guest

                        <a
                            href="{{ route('register') }}"
                            class="px-6 py-3 rounded-xl border border-gray-300 bg-white font-semibold hover:bg-gray-50"
                        >
                            Get Started
                        </a>

                    @endguest

                </div>

            </div>

        </section>


        {{-- MEAL PLANS --}}

        <section class="mb-16">

            <div class="mb-6">

                <h2 class="text-3xl font-bold">
                    Choose Your Meal Plan
                </h2>

                <p class="text-gray-500 mt-2">
                    Simple plans designed around your meals.
                </p>

            </div>


            @if($mealPlans->isEmpty())

                <div class="bg-white border rounded-2xl p-10 text-center">

                    <p class="text-gray-500">
                        Meal plans are coming soon.
                    </p>

                </div>

            @else

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($mealPlans as $plan)

                        <div class="bg-white border rounded-2xl p-6">

                            <div class="flex items-start justify-between gap-4">

                                <h3 class="text-xl font-bold">
                                    {{ $plan->name }}
                                </h3>

                                <span class="text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>

                            </div>


                            @if($plan->description)

                                <p class="text-gray-500 text-sm mt-3">
                                    {{ $plan->description }}
                                </p>

                            @endif


                            <div class="mt-6">

                                <p class="text-3xl font-bold">
                                    KES {{ number_format($plan->price, 0) }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $plan->duration_days }} days
                                </p>

                            </div>


                            <div class="mt-5 pt-5 border-t">

                                <p class="text-sm text-gray-600">
                                    {{ $plan->meal_limit }} meal allowance
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $plan->meals_count }} active meals available
                                </p>

                            </div>


                            <a
                                href="{{ route('meal-plans.show', $plan) }}"
                                class="block mt-6 text-center px-4 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800"
                            >
                                View Plan
                            </a>

                        </div>

                    @endforeach

                </div>

            @endif

        </section>


        {{-- MEAL PREVIEW --}}

        <section>

            <div class="mb-6">

                <h2 class="text-3xl font-bold">
                    What's on the Menu?
                </h2>

                <p class="text-gray-500 mt-2">
                    A preview of meals available through Silver Spoon.
                </p>

            </div>


            @if($featuredMeals->isEmpty())

                <div class="bg-white border rounded-2xl p-10 text-center">

                    <p class="text-gray-500">
                        No meals have been added yet.
                    </p>

                </div>

            @else

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($featuredMeals as $meal)

                        <div class="bg-white border rounded-2xl overflow-hidden">

                            @if($meal->image)

                                <img
                                    src="{{ asset('storage/' . $meal->image) }}"
                                    alt="{{ $meal->name }}"
                                    class="w-full h-48 object-cover"
                                >

                            @else

                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">

                                    <span class="text-gray-400">
                                        No image
                                    </span>

                                </div>

                            @endif


                            <div class="p-5">

                                <div class="flex items-center justify-between">

                                    <span class="text-xs uppercase tracking-wide font-semibold text-gray-500">
                                        {{ $meal->meal_type }}
                                    </span>

                                    <span class="text-xs text-gray-400">
                                        Day {{ $meal->day_of_week }}
                                    </span>

                                </div>


                                <h3 class="text-xl font-bold mt-3">
                                    {{ $meal->name }}
                                </h3>


                                @if($meal->description)

                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                        {{ $meal->description }}
                                    </p>

                                @endif


                                <p class="text-xs text-gray-400 mt-4">
                                    {{ $meal->mealPlan->name }}
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </section>

    </main>


    <footer class="border-t bg-white mt-16">

        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <p class="font-bold">
                        Silver Spoon
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Simple meals. Better planning.
                    </p>

                </div>


                
                <div class="flex gap-5 text-sm text-gray-500">

                    <a
                        href="{{ route('meal-plans.index') }}"
                        class="hover:text-black"
                    >
                        Meal Plans
                    </a>

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="hover:text-black"
                        >
                            Dashboard
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="hover:text-black"
                        >
                            Login
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </footer>

</body>
</html>