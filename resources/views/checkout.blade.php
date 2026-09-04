<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

<div class="max-w-6xl mx-auto px-6 py-10">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <a
            href="{{ route('meal-plans.show', $mealPlan) }}"
            class="text-sm text-gray-500 hover:text-gray-900"
        >
            ← Back to {{ $mealPlan->name }}
        </a>

        <h1 class="text-3xl font-bold text-gray-900 mt-4">
            Complete your subscription
        </h1>

        <p class="text-gray-500 mt-2">
            Review your meal schedule and choose your payment method.
        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- CALCULATE CUSTOM PLAN INFORMATION --}}
    {{-- ========================================================= --}}

    @php

        /*
         * Number of recurring weeks.
         *
         * 7 days  = 1 week
         * 30 days = 4 weeks
         * 90 days = 12 weeks
         */
        $weeks = max(
            1,
            intdiv($mealPlan->duration_days, 7)
        );

        $weeklyMealCount = $subscription->mealSelections->count();

        $totalMealOccurrences = $weeklyMealCount * $weeks;

        $displayTotal = $isCustom
            ? $customTotal
            : $mealPlan->price;

        $dayNames = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

    @endphp


    <div class="grid lg:grid-cols-3 gap-8">


        {{-- ===================================================== --}}
        {{-- ORDER SUMMARY --}}
        {{-- ===================================================== --}}

        <div class="lg:col-span-1">

            <div class="bg-white rounded-2xl shadow-sm border p-6 sticky top-6">

                @if($isCustom)

                    {{-- ================================================= --}}
                    {{-- CUSTOM ORDER --}}
                    {{-- ================================================= --}}

                    <div class="flex items-center justify-between mb-2">

                        <h2 class="text-xl font-bold text-gray-900">
                            Custom Meal Plan
                        </h2>

                        <span class="
                            text-xs font-semibold
                            bg-purple-100 text-purple-700
                            px-3 py-1 rounded-full
                        ">
                            CUSTOM
                        </span>

                    </div>


                    <p class="text-sm text-gray-500 mb-6">
                        Your selected meals will repeat weekly
                        throughout your subscription.
                    </p>


                    {{-- ================================================= --}}
                    {{-- PLAN DURATION --}}
                    {{-- ================================================= --}}

                    <div class="
                        bg-gray-50
                        border
                        rounded-xl
                        p-4
                        mb-6
                    ">

                        <div class="flex justify-between items-center">

                            <span class="text-sm text-gray-500">
                                Subscription
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $mealPlan->name }}
                            </span>

                        </div>


                        <div class="flex justify-between items-center mt-2">

                            <span class="text-sm text-gray-500">
                                Duration
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $mealPlan->duration_days }} days
                            </span>

                        </div>


                        <div class="flex justify-between items-center mt-2">

                            <span class="text-sm text-gray-500">
                                Recurring weeks
                            </span>

                            <span class="font-semibold text-gray-900">
                                {{ $weeks }}
                            </span>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SELECTED MEALS --}}
                    {{-- ================================================= --}}

                    <div class="space-y-4">

                        @foreach(
                            $subscription->mealSelections
                                ->sortBy([
                                    ['day_of_week', 'asc'],
                                    ['meal_type', 'asc'],
                                ])
                            as $selection
                        )

                            <div class="
                                border
                                rounded-xl
                                p-4
                                hover:border-gray-300
                                transition
                            ">

                                <div class="flex justify-between items-start">

                                    <div>

                                        <div class="
                                            font-semibold
                                            text-gray-900
                                        ">

                                            {{ $dayNames[$selection->day_of_week] }}

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                            {{ ucfirst($selection->meal_type) }}

                                        </div>


                                        <div class="
                                            text-sm
                                            text-gray-600
                                            mt-1
                                        ">
                                            {{ $selection->meal->name }}
                                        </div>

                                    </div>

                                </div>


                                {{-- PRICE --}}

                                <div class="
                                    flex
                                    justify-between
                                    items-center
                                    mt-3
                                    pt-3
                                    border-t
                                ">

                                    <span class="text-sm text-gray-500">

                                        KES
                                        {{ number_format($selection->unit_price, 2) }}

                                        × {{ $weeks }}

                                    </span>


                                    <span class="
                                        font-semibold
                                        text-gray-900
                                    ">

                                        KES
                                        {{ number_format(
                                            $selection->unit_price * $weeks,
                                            2
                                        ) }}

                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    {{-- ================================================= --}}
                    {{-- MEAL COUNTS --}}
                    {{-- ================================================= --}}

                    <div class="border-t my-6"></div>


                    <div class="flex justify-between mb-3">

                        <span class="text-gray-500">
                            Meals per week
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ $weeklyMealCount }}
                        </span>

                    </div>


                    <div class="flex justify-between mb-5">

                        <span class="text-gray-500">
                            Total meals
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ $totalMealOccurrences }}
                        </span>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TOTAL --}}
                    {{-- ================================================= --}}

                    <div class="border-t pt-5">

                        <div class="text-sm text-gray-500">
                            Total
                        </div>


                        <div class="
                            text-3xl
                            font-bold
                            text-gray-900
                            mt-1
                        ">

                            KES {{ number_format($customTotal, 2) }}

                        </div>


                        <div class="
                            text-xs
                            text-gray-500
                            mt-2
                        ">

                            {{ $weeklyMealCount }} selected meal(s)
                            × {{ $weeks }} week(s)

                        </div>

                    </div>


                @else

                    {{-- ================================================= --}}
                    {{-- STANDARD PLAN --}}
                    {{-- ================================================= --}}

                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $mealPlan->name }}
                    </h2>


                    <p class="text-gray-500 mt-2">
                        {{ $mealPlan->description }}
                    </p>


                    <div class="border-t my-5"></div>


                    <div class="flex justify-between mb-3">

                        <span class="text-gray-500">
                            Duration
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ $mealPlan->duration_days }} days
                        </span>

                    </div>


                    <div class="flex justify-between mb-5">

                        <span class="text-gray-500">
                            Meal limit
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ $mealPlan->meal_limit }} meals
                        </span>

                    </div>


                    <div class="border-t pt-5">

                        <div class="text-sm text-gray-500">
                            Total
                        </div>


                        <div class="
                            text-3xl
                            font-bold
                            text-gray-900
                            mt-1
                        ">

                            KES {{ number_format($mealPlan->price, 2) }}

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PAYMENT --}}
        {{-- ========================================================= --}}

        <div class="lg:col-span-2">

            <div class="
                bg-white
                rounded-2xl
                shadow-sm
                border
                p-8
            ">

                <h2 class="
                    text-xl
                    font-bold
                    text-gray-900
                    mb-6
                ">
                    Payment method
                </h2>


                {{-- ================================================= --}}
                {{-- ERROR --}}
                {{-- ================================================= --}}

                <div
                    id="payment-error"
                    class="
                        hidden
                        mb-6
                        rounded-xl
                        bg-red-50
                        border
                        border-red-200
                        text-red-700
                        px-4
                        py-3
                    "
                ></div>


                {{-- ================================================= --}}
                {{-- SUCCESS --}}
                {{-- ================================================= --}}

                <div
                    id="payment-success"
                    class="
                        hidden
                        mb-6
                        rounded-xl
                        bg-green-50
                        border
                        border-green-200
                        text-green-700
                        px-4
                        py-3
                    "
                ></div>


                {{-- ================================================= --}}
                {{-- CHECKOUT FORM --}}
                {{-- ================================================= --}}

                <form
                    id="checkout-form"
                    method="POST"
                    action="{{ route('checkout.initiate', $mealPlan) }}"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- PAYMENT METHOD --}}
                    {{-- ================================================= --}}

                    <div class="space-y-4">

                        <label class="block cursor-pointer">

                            <input
                                type="radio"
                                name="payment_method"
                                value="paystack"
                                class="peer hidden"
                                checked
                            >


                            <div class="
                                border
                                rounded-xl
                                p-5
                                peer-checked:border-blue-600
                                peer-checked:bg-blue-50
                                transition
                            ">

                                <div class="
                                    flex
                                    items-center
                                    justify-between
                                ">

                                    <div>

                                        <div class="
                                            font-semibold
                                            text-gray-900
                                        ">
                                            Pay with Mobile Money or Card
                                        </div>


                                        <div class="
                                            text-sm
                                            text-gray-500
                                            mt-1
                                        ">
                                            Pay securely using
                                            M-Pesa, Airtel Money or Card.
                                        </div>

                                    </div>


                                    <div class="
                                        text-blue-600
                                        font-bold
                                        text-sm
                                    ">
                                        PAYSTACK
                                    </div>

                                </div>

                            </div>

                        </label>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PAYMENT SUMMARY --}}
                    {{-- ================================================= --}}

                    <div class="
                        mt-8
                        bg-gray-50
                        border
                        rounded-xl
                        p-5
                    ">

                        <div class="
                            flex
                            justify-between
                            items-center
                        ">

                            <span class="text-gray-500">
                                Amount to pay
                            </span>

                            <span class="
                                text-2xl
                                font-bold
                                text-gray-900
                            ">

                                KES {{ number_format($displayTotal, 2) }}

                            </span>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PAY BUTTON --}}
                    {{-- ================================================= --}}

                    <button
                        id="pay-button"
                        type="submit"
                        class="
                            w-full
                            mt-6
                            bg-gray-900
                            text-white
                            py-4
                            rounded-xl
                            font-semibold
                            hover:bg-gray-800
                            disabled:opacity-50
                            disabled:cursor-not-allowed
                            transition
                        "
                    >

                        Pay KES {{ number_format($displayTotal, 2) }}

                    </button>


                    <p class="
                        text-center
                        text-xs
                        text-gray-400
                        mt-4
                    ">
                        You will be redirected to our secure payment
                        provider to complete your payment.
                    </p>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>

const form = document.getElementById('checkout-form');

const payButton = document.getElementById('pay-button');

const errorBox = document.getElementById('payment-error');

const successBox = document.getElementById('payment-success');


/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

form.addEventListener('submit', async function (event) {

    event.preventDefault();

    errorBox.classList.add('hidden');

    successBox.classList.add('hidden');

    payButton.disabled = true;

    payButton.innerText = 'Processing...';


    try {

        const formData = new FormData(form);


        const response = await fetch(
            form.action,
            {
                method: 'POST',

                headers: {

                    'Accept': 'application/json',

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'input[name="_token"]'
                        ).value

                },

                body: formData
            }
        );


        /*
        |--------------------------------------------------------------------------
        | HANDLE NON-JSON RESPONSES
        |--------------------------------------------------------------------------
        */

        const contentType =
            response.headers.get('content-type') || '';


        if (!contentType.includes('application/json')) {

            throw new Error(
                'The server returned an unexpected response. Please try again.'
            );

        }


        const data = await response.json();


        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */

        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                'Unable to process payment.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PAYSTACK
        |--------------------------------------------------------------------------
        */

        if (
            data.provider === 'paystack' &&
            data.authorization_url
        ) {

            window.location.href =
                data.authorization_url;

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | M-PESA
        |--------------------------------------------------------------------------
        */

        if (data.provider === 'mpesa') {

            successBox.innerText =
                data.message ||
                'Payment request sent. Check your phone for the M-Pesa prompt.';

            successBox.classList.remove('hidden');

            payButton.innerText =
                'Waiting for payment...';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | UNKNOWN RESPONSE
        |--------------------------------------------------------------------------
        */

        throw new Error(
            'Payment provider returned an unexpected response.'
        );


    } catch (error) {

        console.error(error);


        errorBox.innerText =
            error.message ||
            'Something went wrong. Please try again.';

        errorBox.classList.remove('hidden');


        payButton.disabled = false;

        payButton.innerText =
            'Pay KES {{ number_format($displayTotal, 2) }}';

    }

});

</script>

</body>
</html>