<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meal Plans - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900">

<nav class="bg-white border-b">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a
            href="{{ route('home') }}"
            class="text-2xl font-bold"
        >
            Silver Spoon
        </a>

        <div class="flex items-center gap-6">

            @auth

                <a
                    href="{{ route('dashboard') }}"
                    class="text-gray-600 hover:text-black"
                >
                    Dashboard
                </a>

            @else

                <a
                    href="{{ route('login') }}"
                    class="text-gray-600 hover:text-black"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="bg-black text-white px-5 py-2 rounded-lg"
                >
                    Get Started
                </a>

            @endauth

        </div>

    </div>

</nav>


<main class="max-w-7xl mx-auto px-6 py-16">

    <div class="text-center max-w-2xl mx-auto mb-14">

        <p class="text-sm font-semibold uppercase tracking-widest text-gray-500">
            Silver Spoon
        </p>

        <h1 class="text-4xl md:text-5xl font-bold mt-3">
            Choose Your Meal Plan
        </h1>

        <p class="text-gray-500 text-lg mt-4">
            Get your meals sorted without the daily hassle.
        </p>

    </div>


    @if ($mealPlans->isEmpty())

        <div class="bg-white rounded-2xl p-12 text-center shadow-sm">

            <h2 class="text-xl font-semibold">
                No meal plans available
            </h2>

            <p class="text-gray-500 mt-2">
                Please check back later.
            </p>

        </div>

    @else

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach ($mealPlans as $plan)

                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                    <div class="p-7">

                        <h2 class="text-2xl font-bold">
                            {{ $plan->name }}
                        </h2>

                        <p class="text-gray-500 mt-3 min-h-[48px]">
                            {{ $plan->description }}
                        </p>


                        <div class="mt-7">

                            <span class="text-4xl font-bold">
                                KES {{ number_format($plan->price, 0) }}
                            </span>

                        </div>


                        <div class="border-t mt-7 pt-6 space-y-3 text-sm">

                            <div class="flex justify-between">

                                <span class="text-gray-500">
                                    Duration
                                </span>

                                <span class="font-medium">
                                    {{ $plan->duration_days }} days
                                </span>

                            </div>


                            <div class="flex justify-between">

                                <span class="text-gray-500">
                                    Meals
                                </span>

                                <span class="font-medium">
                                    {{ $plan->meal_limit }}
                                </span>

                            </div>


                            <div class="flex justify-between">

                                <span class="text-gray-500">
                                    Scheduled meals
                                </span>

                                <span class="font-medium">
                                    {{ $plan->meals->count() }}
                                </span>

                            </div>

                        </div>


                        <a
                            href="{{ route('meal-plans.show', $plan) }}"
                            class="block text-center bg-black text-white rounded-lg py-3 mt-7 font-semibold hover:bg-gray-800 transition"
                        >
                            View Plan
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</main>

</body>

</html>