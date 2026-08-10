<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Meal Plan - Silver Spoon</title>

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
            Create Meal Plan
        </h1>

        <p class="text-gray-500 mt-2">
            Define the subscription package customers can purchase.
        </p>

    </div>


    @if($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-5 mb-6">

            <p class="font-bold mb-2">
                Please fix the following:
            </p>

            <ul class="list-disc ml-5 text-sm space-y-1">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.meal-plans.store') }}"
        class="bg-white border rounded-2xl p-6 space-y-6"
    >

        @csrf


        <div>

            <label class="block text-sm font-semibold mb-2">
                Plan Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="e.g. Premium Plan"
                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
            >

        </div>


        <div>

            <label class="block text-sm font-semibold mb-2">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                placeholder="Describe what customers receive with this plan..."
                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
            >{{ old('description') }}</textarea>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Price (KES)
                </label>

                <input
                    type="number"
                    name="price"
                    value="{{ old('price') }}"
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
                    value="{{ old('meal_limit', 1) }}"
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
                    value="{{ old('duration_days', 30) }}"
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
                checked
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
                class="flex-1 bg-black text-white rounded-xl px-5 py-3 font-semibold hover:bg-gray-800"
            >
                Create Meal Plan
            </button>

        </div>

    </form>

</main>

</body>
</html>