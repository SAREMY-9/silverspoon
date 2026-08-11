@extends('layouts.app')

@section('title', 'Meal Service Report - Silver Spoon')

@section('content')

    {{-- HEADER --}}
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h2 class="text-3xl font-bold text-slate-900">
                Meal Service Report
            </h2>

            <p class="mt-2 text-slate-500">
                Track exactly who served each meal, to whom, and when.
            </p>

        </div>

        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('admin.meals.dashboard', request()->query()) }}"
                class="rounded-xl bg-slate-900 px-5 py-3 text-center font-semibold text-white hover:bg-slate-800"
            >
                Back To Dashboard
            </a>

            <a
                href="{{ route('admin.meals.report.export', request()->query()) }}"
                class="rounded-xl bg-slate-900 px-5 py-3 text-center font-semibold text-white hover:bg-slate-800"
            >
                Export CSV
            </a>

        </div>

    </div>


    {{-- FILTERS --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">

        <form
            method="GET"
            action="{{ route('admin.meals.report') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-4"
        >

            <div>

                <label class="mb-2 block text-sm font-semibold">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ $date }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-semibold">
                    Staff Member
                </label>

                <select
                    name="staff_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
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

                <label class="mb-2 block text-sm font-semibold">
                    Customer
                </label>

                <input
                    type="text"
                    name="customer"
                    value="{{ $customer }}"
                    placeholder="Name or email"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900"
                >

            </div>


            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white hover:bg-slate-800"
                >
                    Apply Filters
                </button>

            </div>

        </form>

    </div>


    {{-- SUMMARY --}}
    <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Total Served
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $totalServed }}
            </p>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Breakfast
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $breakfast }}
            </p>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Lunch
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $lunch }}
            </p>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Supper
            </p>

            <p class="mt-2 text-3xl font-bold">
                {{ $supper }}
            </p>

        </div>

    </div>


    {{-- STAFF PERFORMANCE --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">

        <div class="mb-5">

            <h3 class="text-xl font-bold">
                Staff Performance
            </h3>

            <p class="text-sm text-slate-500">
                Meals served by each staff member.
            </p>

        </div>


        @if($staffSummary->isEmpty())

            <div class="py-6 text-center text-slate-500">
                No meals were served on this date.
            </div>

        @else

            <div class="space-y-3">

                @foreach($staffSummary as $member)

                    <div class="flex items-center justify-between rounded-xl border border-slate-200 p-4">

                        <div>

                            <p class="font-semibold">
                                {{ $member->staff_name }}
                            </p>

                            <p class="text-sm text-slate-500">
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


    {{-- MEAL BREAKDOWN --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6">

        <h3 class="text-xl font-bold">
            Meal Breakdown
        </h3>

        <p class="mb-5 text-sm text-slate-500">
            Distribution of meals served.
        </p>


        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            @foreach($mealSummary as $meal)

                <div class="rounded-xl border border-slate-200 p-5">

                    <p class="text-sm text-slate-500">
                        {{ ucfirst($meal->meal_type) }}
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $meal->meals_served }}
                    </p>

                </div>

            @endforeach

        </div>

    </div>


    {{-- REDEMPTION HISTORY --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 p-6">

            <h3 class="text-xl font-bold">
                Service History
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Every meal redemption recorded for
                {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
            </p>

        </div>


        @if($redemptions->isEmpty())

            <div class="p-10 text-center text-slate-500">
                No meal service records found.
            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-left">
                                Time
                            </th>

                            <th class="px-6 py-4 text-left">
                                Customer
                            </th>

                            <th class="px-6 py-4 text-left">
                                Meal
                            </th>

                            <th class="px-6 py-4 text-left">
                                Type
                            </th>

                            <th class="px-6 py-4 text-left">
                                Served By
                            </th>

                            <th class="px-6 py-4 text-left">
                                Reference
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y">

                        @foreach($redemptions as $redemption)

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($redemption->redeemed_at)->format('H:i:s') }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-semibold">
                                        {{ $redemption->customer_name }}
                                    </p>

                                    @if($redemption->customer_email)

                                        <p class="text-xs text-slate-500">
                                            {{ $redemption->customer_email }}
                                        </p>

                                    @endif

                                </td>


                                <td class="px-6 py-4 font-medium">
                                    {{ $redemption->meal_name }}
                                </td>


                                <td class="px-6 py-4">

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ ucfirst($redemption->meal_type) }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    @if($redemption->staff_name)

                                        <span class="font-medium">
                                            {{ $redemption->staff_name }}
                                        </span>

                                    @else

                                        <span class="text-slate-400">
                                            Customer
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <code class="rounded bg-slate-100 px-2 py-1 text-xs">
                                        {{ $redemption->reference }}
                                    </code>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="border-t border-slate-200 p-6">
                {{ $redemptions->links() }}
            </div>

        @endif

    </div>

@endsection