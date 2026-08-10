<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $mealPlan->name }} - Silver Spoon</title>

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

            @endauth

        </div>

    </div>

</nav>


<main class="max-w-5xl mx-auto px-6 py-14">


    <a
        href="{{ route('meal-plans.index') }}"
        class="text-sm text-gray-500 hover:text-black"
    >
        ← Back to meal plans
    </a>


    <div class="bg-white rounded-2xl shadow-sm border mt-6 overflow-hidden">

        <div class="p-8 md:p-10">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-8">

                <div>

                    <h1 class="text-4xl font-bold">
                        {{ $mealPlan->name }}
                    </h1>

                    <p class="text-gray-500 text-lg mt-4 max-w-2xl">
                        {{ $mealPlan->description }}
                    </p>

                </div>


                <div class="md:text-right">

                    <p class="text-4xl font-bold">
                        KES {{ number_format($mealPlan->price, 0) }}
                    </p>

                    <p class="text-gray-500 mt-1">
                        {{ $mealPlan->duration_days }} days
                    </p>

                </div>

            </div>


            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-10">

                <div class="bg-gray-50 rounded-xl p-5">

                    <p class="text-sm text-gray-500">
                        Duration
                    </p>

                    <p class="text-xl font-semibold mt-1">
                        {{ $mealPlan->duration_days }} days
                    </p>

                </div>


                <div class="bg-gray-50 rounded-xl p-5">

                    <p class="text-sm text-gray-500">
                        Meal allowance
                    </p>

                    <p class="text-xl font-semibold mt-1">
                        {{ $mealPlan->meal_limit }}
                    </p>

                </div>


                <div class="bg-gray-50 rounded-xl p-5">

                    <p class="text-sm text-gray-500">
                        Scheduled meals
                    </p>

                    <p class="text-xl font-semibold mt-1">
                        {{ $mealPlan->meals->count() }}
                    </p>

                </div>

            </div>

        </div>


        <div class="border-t p-8 md:p-10">

            <h2 class="text-2xl font-bold">
                What's included
            </h2>


            <div class="mt-6 space-y-4">

                @forelse ($mealPlan->meals->groupBy('day_of_week') as $day => $meals)

                    <div class="border rounded-xl overflow-hidden">

                        <div class="bg-gray-50 px-5 py-3 font-semibold">
                            Day {{ $day }}
                        </div>


                        <div class="divide-y">

                            @foreach ($meals as $meal)

                                <div class="px-5 py-4 flex items-center justify-between">

                                    <div>

                                        <p class="font-semibold">
                                            {{ $meal->name }}
                                        </p>

                                        @if ($meal->description)

                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $meal->description }}
                                            </p>

                                        @endif

                                    </div>


                                    <span class="text-xs uppercase font-semibold text-gray-500">
                                        {{ $meal->meal_type }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <p class="text-gray-500">
                        No meals have been configured for this plan yet.
                    </p>

                @endforelse

            </div>


            <div class="mt-10">

                @auth

                    <a
                        href="{{ route('checkout.show', $mealPlan) }}"
                        class="block text-center w-full bg-black text-white rounded-xl py-4 font-semibold text-lg hover:bg-gray-800 transition"
                    >
                        Subscribe for KES {{ number_format($mealPlan->price, 0) }}
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="block text-center w-full bg-black text-white rounded-xl py-4 font-semibold text-lg hover:bg-gray-800 transition"
                    >
                        Login to Subscribe
                    </a>

                    <p class="text-center text-sm text-gray-500 mt-3">
                        Don't have an account?

                        <a
                            href="{{ route('register') }}"
                            class="font-semibold text-black hover:underline"
                        >
                            Create one
                        </a>
                    </p>

                @endauth
            </div>

        </div>

    </div>

</main>

</body>

</html>