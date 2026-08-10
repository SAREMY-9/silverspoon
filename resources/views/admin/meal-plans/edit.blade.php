<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Meal Plan - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<nav class="bg-black text-white">

    <div class="max-w-5xl mx-auto px-6 py-4">

        <a
            href="{{ route('admin.meal-plans.index') }}"
            class="text-sm text-gray-300 hover:text-white"
        >
            ← Back to Meal Plans
        </a>

    </div>

</nav>


<main class="max-w-3xl mx-auto px-6 py-8">

    <div class="mb-8">

        <p class="text-sm text-gray-500 uppercase font-semibold">
            Meal Management
        </p>

        <h1 class="text-3xl font-bold mt-1">
            Edit Meal Plan
        </h1>

        <p class="text-gray-500 mt-2">
            Update {{ $mealPlan->name }}.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-5 mb-6">

            <ul class="list-disc ml-5 text-sm space-y-1">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.meal-plans.update', $mealPlan) }}"
        class="bg-white border rounded-2xl p-6 space-y-6"
    >

        @csrf
        @method('PUT')


        <div>

            <label class="block text-sm font-semibold mb-2">
                Plan Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $mealPlan->name) }}"
                required
                class="w-full border rounded-xl px-4 py-3"
            >

        </div>


        <div>

            <label class="block text-sm font-semibold mb-2">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full border rounded-xl px-4 py-3"
            >{{ old('description', $mealPlan->description) }}</textarea>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Price (KES)
                </label>

                <input
                    type="number"
                    name="price"
                    value="{{ old('price', $mealPlan->price) }}"
                    min="0"
                    step="0.01"
                    required
                    class="w-full border rounded-xl px-4 py-3"
                >

            </div>


            <div>

                <label class="block text-sm font-semibold mb-2">
                    Meal Limit
                </label>

                <input
                    type="number"
                    name="meal_limit"
                    value="{{ old('meal_limit', $mealPlan->meal_limit) }}"
                    min="1"
                    required
                    class="w-full border rounded-xl px-4 py-3"
                >

            </div>


            <div>

                <label class="block text-sm font-semibold mb-2">
                    Duration (Days)
                </label>

                <input
                    type="number"
                    name="duration_days"
                    value="{{ old('duration_days', $mealPlan->duration_days) }}"
                    min="1"
                    required
                    class="w-full border rounded-xl px-4 py-3"
                >

            </div>

        </div>


        <label class="flex items-center gap-3 cursor-pointer">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $mealPlan->is_active))
                class="w-5 h-5"
            >

            <span>

                <span class="font-semibold block">
                    Active plan
                </span>

                <span class="text-sm text-gray-500">
                    Customers can purchase this plan.
                </span>

            </span>

        </label>


        <div class="flex gap-3 pt-4">

            <a
                href="{{ route('admin.meal-plans.index') }}"
                class="flex-1 text-center border rounded-xl px-5 py-3 font-semibold"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="flex-1 bg-black text-white rounded-xl px-5 py-3 font-semibold"
            >
                Save Changes
            </button>

        </div>

    </form>

</main>

</body>
</html>