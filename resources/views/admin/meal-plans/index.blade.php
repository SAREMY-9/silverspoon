<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meal Plans - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen text-gray-900">

<nav class="bg-black text-white">
    <div class="max-w-7xl mx-auto px-6 py-4">

        <div class="flex items-center justify-between gap-6">

            <div>
                <h1 class="text-xl font-bold tracking-tight">
                    Silver Spoon
                </h1>

                <p class="text-xs text-gray-400 mt-0.5">
                    Administration
                </p>
            </div>

            <div class="flex items-center gap-4">

                <a
                    href="{{ route('admin.meals.dashboard') }}"
                    class="text-sm text-gray-300 hover:text-white"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.meals.report') }}"
                    class="text-sm text-gray-300 hover:text-white"
                >
                    Reports
                </a>

                <div class="border-l border-gray-700 pl-4 text-sm">
                    {{ auth()->user()->name }}
                </div>

            </div>

        </div>

    </div>
</nav>


<main class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">

        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">
                Menu Management
            </p>

            <h2 class="text-3xl font-bold mt-1">
                Meal Plans
            </h2>

            <p class="text-gray-500 mt-2">
                Create and manage the subscription plans offered to customers.
            </p>
        </div>

        <a
            href="{{ route('admin.meal-plans.create') }}"
            class="inline-flex items-center justify-center bg-black text-white px-5 py-3 rounded-xl font-semibold hover:bg-gray-800 transition"
        >
            + Create Meal Plan
        </a>

    </div>


    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">
            <div class="font-semibold">
                Success
            </div>

            <div class="text-sm mt-1">
                {{ session('success') }}
            </div>
        </div>
    @endif


    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
            <div class="font-semibold">
                Action unavailable
            </div>

            <div class="text-sm mt-1">
                {{ session('error') }}
            </div>
        </div>
    @endif


    {{-- Overview --}}
    @php
        $totalPlans = $mealPlans->total();

        $activePlans = $mealPlans->getCollection()
            ->where('is_active', true)
            ->count();

        $totalMeals = $mealPlans->getCollection()
            ->sum('meals_count');

        $totalSubscribers = $mealPlans->getCollection()
            ->sum('subscriptions_count');
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Total Plans
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $totalPlans }}
            </p>
        </div>


        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Active Plans
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $activePlans }}
            </p>
        </div>


        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Meals
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $totalMeals }}
            </p>
        </div>


        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Subscribers
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $totalSubscribers }}
            </p>
        </div>

    </div>


    {{-- Meal Plans --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-bold">
                        All Meal Plans
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Manage your subscription packages and their menus.
                    </p>
                </div>

            </div>

        </div>


        @if($mealPlans->isEmpty())

            <div class="py-16 px-6 text-center">

                <div class="text-4xl mb-4">
                    🍽️
                </div>

                <h3 class="text-lg font-bold">
                    No meal plans yet
                </h3>

                <p class="text-gray-500 text-sm mt-2 mb-6">
                    Create your first subscription meal plan to get started.
                </p>

                <a
                    href="{{ route('admin.meal-plans.create') }}"
                    class="inline-flex bg-black text-white px-5 py-3 rounded-xl font-semibold hover:bg-gray-800"
                >
                    Create Meal Plan
                </a>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Meal Plan
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Price
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Duration
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Meals
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Subscribers
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="text-right px-6 py-4 font-semibold text-gray-600">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($mealPlans as $mealPlan)

                            <tr class="hover:bg-gray-50 transition">

                                {{-- Plan --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center text-xl">
                                            🍽️
                                        </div>

                                        <div>

                                            <div class="font-bold">
                                                {{ $mealPlan->name }}
                                            </div>

                                            @if($mealPlan->description)

                                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                                    {{ $mealPlan->description }}
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Price --}}
                                <td class="px-6 py-5 whitespace-nowrap">

                                    <span class="font-bold">
                                        KES {{ number_format($mealPlan->price, 2) }}
                                    </span>

                                </td>


                                {{-- Duration --}}
                                <td class="px-6 py-5 whitespace-nowrap">

                                    <span>
                                        {{ $mealPlan->duration_days }}
                                        {{ $mealPlan->duration_days == 1 ? 'day' : 'days' }}
                                    </span>

                                </td>


                                {{-- Meals --}}
                                <td class="px-6 py-5">

                                    <a
                                        href="{{ route('admin.meals.index', ['meal_plan_id' => $mealPlan->id]) }}"
                                        class="font-semibold hover:underline"
                                    >
                                        {{ $mealPlan->meals_count }}
                                    </a>

                                    <span class="text-gray-400 text-xs">
                                        / {{ $mealPlan->meal_limit }}
                                    </span>

                                </td>


                                {{-- Subscribers --}}
                                <td class="px-6 py-5">

                                    <span class="font-semibold">
                                        {{ $mealPlan->subscriptions_count }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-5">

                                    @if($mealPlan->is_active)

                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                            Active

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center justify-end gap-2">

                                        <a
                                            href="{{ route('admin.meal-plans.show', $mealPlan) }}"
                                            class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 font-medium"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('admin.meal-plans.edit', $mealPlan) }}"
                                            class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 font-medium"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.meal-plans.toggle', $mealPlan) }}"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 font-medium"
                                            >
                                                {{ $mealPlan->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>

                                        </form>

                                        @if($mealPlan->subscriptions_count === 0 && $mealPlan->meals_count === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.meal-plans.destroy', $mealPlan) }}"
                                                onsubmit="return confirm('Delete this meal plan permanently?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-medium"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="px-6 py-5 border-t border-gray-200">
                {{ $mealPlans->links() }}
            </div>

        @endif

    </div>

</main>

</body>
</html>