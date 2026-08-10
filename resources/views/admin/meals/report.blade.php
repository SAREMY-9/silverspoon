<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meal Service Report - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen">

<nav class="bg-black text-white">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <div>

            <h1 class="font-bold text-lg">
                Silver Spoon
            </h1>

            <p class="text-xs text-gray-400">
                Meal Service Reports
            </p>

        </div>

        <div class="text-sm">
            {{ auth()->user()->name }}
        </div>

    </div>

</nav>


<main class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h2 class="text-3xl font-bold">
                Meal Service Report
            </h2>

            <p class="text-gray-500 mt-2">
                Track exactly who served each meal, to whom, and when.
            </p>

        </div>

        <a
            href="{{ route('admin.meals.report.export', request()->query()) }}"
            class="bg-black text-white px-5 py-3 rounded-xl font-semibold text-center hover:bg-gray-800"
        >
            Export CSV
        </a>

    </div>


    {{-- Filters --}}

    <div class="bg-white border rounded-2xl p-6 mb-8">

        <form
            method="GET"
            action="{{ route('admin.meals.report') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ $date }}"
                    class="w-full border rounded-xl px-4 py-3"
                >

            </div>


            <div>

                <label class="block text-sm font-semibold mb-2">
                    Staff Member
                </label>

                <select
                    name="staff_id"
                    class="w-full border rounded-xl px-4 py-3"
                >

                    <option value="">
                        All staff
                    </option>

                    @foreach($staff as $member)

                        <option
                            value="{{ $member->id }}"
                            @selected($staffId == $member->id)
                        >
                            {{ $member->name }}
                            ({{ ucfirst($member->role) }})
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="block text-sm font-semibold mb-2">
                    Customer
                </label>

                <input
                    type="text"
                    name="customer"
                    value="{{ $customer }}"
                    placeholder="Name or email"
                    class="w-full border rounded-xl px-4 py-3"
                >

            </div>


            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full bg-black text-white px-5 py-3 rounded-xl font-semibold hover:bg-gray-800"
                >
                    Apply Filters
                </button>

            </div>

        </form>

    </div>


    {{-- Summary --}}

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Total Served
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $totalServed }}
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Breakfast
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $breakfast }}
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Lunch
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $lunch }}
            </p>

        </div>


        <div class="bg-white border rounded-2xl p-5">

            <p class="text-sm text-gray-500">
                Supper
            </p>

            <p class="text-3xl font-bold mt-2">
                {{ $supper }}
            </p>

        </div>

    </div>


    {{-- Staff performance --}}

    <div class="bg-white border rounded-2xl p-6 mb-8">

        <div class="flex items-center justify-between mb-5">

            <div>

                <h3 class="text-xl font-bold">
                    Staff Performance
                </h3>

                <p class="text-sm text-gray-500">
                    Meals served by each staff member.
                </p>

            </div>

        </div>


        @if($staffSummary->isEmpty())

            <div class="text-gray-500 py-6 text-center">
                No meals were served on this date.
            </div>

        @else

            <div class="space-y-3">

                @foreach($staffSummary as $member)

                    <div class="flex items-center justify-between border rounded-xl p-4">

                        <div>

                            <p class="font-semibold">
                                {{ $member->staff_name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $member->meals_served }}
                                {{ $member->meals_served == 1 ? 'meal' : 'meals' }}
                            </p>

                        </div>

                        <div class="text-2xl font-bold">
                            {{ $member->meals_served }}
                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>


    {{-- Meal breakdown --}}

    <div class="bg-white border rounded-2xl p-6 mb-8">

        <h3 class="text-xl font-bold">
            Meal Breakdown
        </h3>

        <p class="text-sm text-gray-500 mb-5">
            Distribution of meals served.
        </p>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @foreach($mealSummary as $meal)

                <div class="border rounded-xl p-5">

                    <p class="text-sm text-gray-500">
                        {{ ucfirst($meal->meal_type) }}
                    </p>

                    <p class="text-3xl font-bold mt-2">
                        {{ $meal->meals_served }}
                    </p>

                </div>

            @endforeach

        </div>

    </div>


    {{-- Redemption history --}}

    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-6 border-b">

            <h3 class="text-xl font-bold">
                Service History
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Every meal redemption recorded for
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
            </p>

        </div>


        @if($redemptions->isEmpty())

            <div class="p-10 text-center text-gray-500">
                No meal service records found.
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="text-left px-6 py-4">
                                Time
                            </th>

                            <th class="text-left px-6 py-4">
                                Customer
                            </th>

                            <th class="text-left px-6 py-4">
                                Meal
                            </th>

                            <th class="text-left px-6 py-4">
                                Type
                            </th>

                            <th class="text-left px-6 py-4">
                                Served By
                            </th>

                            <th class="text-left px-6 py-4">
                                Reference
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($redemptions as $redemption)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($redemption->redeemed_at)->format('H:i:s') }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-semibold">
                                        {{ $redemption->customer_name }}
                                    </p>

                                    @if($redemption->customer_email)

                                        <p class="text-gray-500 text-xs">
                                            {{ $redemption->customer_email }}
                                        </p>

                                    @endif

                                </td>


                                <td class="px-6 py-4 font-medium">

                                    {{ $redemption->meal_name }}

                                </td>


                                <td class="px-6 py-4">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">

                                        {{ ucfirst($redemption->meal_type) }}

                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    @if($redemption->staff_name)

                                        <span class="font-medium">
                                            {{ $redemption->staff_name }}
                                        </span>

                                    @else

                                        <span class="text-gray-400">
                                            Customer
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">

                                        {{ $redemption->reference }}

                                    </code>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="p-6 border-t">

                {{ $redemptions->links() }}

            </div>

        @endif

    </div>

</main>

</body>
</html>