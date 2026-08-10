<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="max-w-5xl mx-auto px-6 py-10">

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
                Choose your payment method to activate your meal plan.
            </p>
        </div>


        <div class="grid lg:grid-cols-3 gap-8">

            {{-- PLAN SUMMARY --}}
            <div class="lg:col-span-1">

                <div class="bg-white rounded-2xl shadow-sm border p-6 sticky top-6">

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

                        <span class="font-medium">
                            {{ $mealPlan->duration_days }} days
                        </span>
                    </div>

                    <div class="flex justify-between mb-5">
                        <span class="text-gray-500">
                            Meal limit
                        </span>

                        <span class="font-medium">
                            {{ $mealPlan->meal_limit }} meals
                        </span>
                    </div>

                    <div class="border-t pt-5">

                        <div class="text-sm text-gray-500">
                            Total
                        </div>

                        <div class="text-3xl font-bold text-gray-900">
                            KES {{ number_format($mealPlan->price, 2) }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- PAYMENT --}}
            <div class="lg:col-span-2">

                <div class="bg-white rounded-2xl shadow-sm border p-8">

                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        Payment method
                    </h2>


                    {{-- ERROR --}}
                    <div
                        id="payment-error"
                        class="hidden mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3"
                    ></div>


                    {{-- SUCCESS --}}
                    <div
                        id="payment-success"
                        class="hidden mb-6 rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3"
                    ></div>


                    <form
                        id="checkout-form"
                        method="POST"
                        action="{{ route('checkout.initiate', $mealPlan) }}"
                    >

                        @csrf


                        {{-- PAYMENT METHOD --}}
                        <div class="space-y-4">

                            <label class="block cursor-pointer">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="mpesa"
                                    class="peer hidden"
                                    checked
                                >

                                <div class="border rounded-xl p-5 peer-checked:border-green-600 peer-checked:bg-green-50 transition">

                                    <div class="flex items-center justify-between">

                                        <div>
                                            <div class="font-semibold text-gray-900">
                                                M-Pesa
                                            </div>

                                            <div class="text-sm text-gray-500">
                                                Pay directly using your Safaricom M-Pesa.
                                            </div>
                                        </div>

                                        <div class="text-green-600 font-bold">
                                            M-Pesa
                                        </div>

                                    </div>

                                </div>
                            </label>


                            <label class="block cursor-pointer">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="paystack"
                                    class="peer hidden"
                                >

                                <div class="border rounded-xl p-5 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition">

                                    <div class="flex items-center justify-between">

                                        <div>
                                            <div class="font-semibold text-gray-900">
                                                Paystack
                                            </div>

                                            <div class="text-sm text-gray-500">
                                                Pay securely using Paystack.
                                            </div>
                                        </div>

                                        <div class="text-blue-600 font-bold">
                                            Paystack
                                        </div>

                                    </div>

                                </div>
                            </label>

                        </div>


                        {{-- PHONE --}}
                        <div
                            id="mpesa-phone"
                            class="mt-6"
                        >

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                M-Pesa phone number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                placeholder="07XXXXXXXX"
                                value="{{ auth()->user()->phone }}"
                                class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >

                            <p class="text-xs text-gray-500 mt-2">
                                You will receive an M-Pesa STK Push on this number.
                            </p>

                        </div>


                        {{-- PAY --}}
                        <button
                            id="pay-button"
                            type="submit"
                            class="w-full mt-8 bg-gray-900 text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition"
                        >
                            Pay KES {{ number_format($mealPlan->price, 2) }}
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


<script>

const form = document.getElementById('checkout-form');
const payButton = document.getElementById('pay-button');

const errorBox = document.getElementById('payment-error');
const successBox = document.getElementById('payment-success');

const phoneSection = document.getElementById('mpesa-phone');

const paymentMethods =
    document.querySelectorAll('input[name="payment_method"]');


paymentMethods.forEach(method => {

    method.addEventListener('change', function () {

        if (this.value === 'mpesa') {
            phoneSection.classList.remove('hidden');
        } else {
            phoneSection.classList.add('hidden');
        }

    });

});


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


        const data = await response.json();


        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                'Unable to process payment.'
            );

        }


        /*
         * PAYSTACK
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
         * M-PESA
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


    } catch (error) {

        errorBox.innerText =
            error.message ||
            'Something went wrong. Please try again.';

        errorBox.classList.remove('hidden');

        payButton.disabled = false;

        payButton.innerText =
            'Pay KES {{ number_format($mealPlan->price, 2) }}';

    }

});

</script>

</body>
</html>