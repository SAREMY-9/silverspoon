<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meal Scanner - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="bg-black text-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">

            <div>
                <h1 class="font-bold text-lg">
                    Silver Spoon
                </h1>

                <p class="text-xs text-gray-400">
                    Staff Meal Service
                </p>
            </div>


            <a
    href="{{ route('home') }}"
    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900"
>
    <svg
        class="h-4 w-4"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M3 12l9-9 9 9M5 10v10h14V10"
        />
    </svg>

    Dashboard
</a>


            <div class="text-sm">
                {{ auth()->user()->name }}
            </div>

        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-8">


    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Today's Meals
            </p>

            <p
                id="summary-total"
                class="text-3xl font-bold mt-2"
            >
                —
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Served
            </p>

            <p
                id="summary-served"
                class="text-3xl font-bold mt-2 text-green-600"
            >
                —
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Available
            </p>

            <p
                id="summary-available"
                class="text-3xl font-bold mt-2"
            >
                —
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <p class="text-sm text-gray-500">
                Expired
            </p>

            <p
                id="summary-expired"
                class="text-3xl font-bold mt-2 text-red-600"
            >
                —
            </p>
        </div>

    </div>


        <div class="mb-8">

            <h2 class="text-3xl font-bold">
                Scan Customer QR
            </h2>

            <p class="text-gray-500 mt-2">
                Scan the customer's Silver Spoon QR code to validate their meal entitlement.
            </p>

        </div>


        {{-- Scanner --}}

        <div
            id="scanner-section"
            class="bg-white rounded-2xl border shadow-sm p-6"
        >

            <div
                id="reader"
                class="w-full max-w-md mx-auto"
            ></div>

            <p
                id="scanner-status"
                class="text-center text-sm text-gray-500 mt-4"
            >
                Point the camera at the customer's QR code.
            </p>

        </div>


        {{-- Manual token fallback --}}

      <div class="bg-white rounded-2xl border shadow-sm p-6 mt-6">

            <h3 class="font-bold text-lg">
                Manual QR Token Test
            </h3>

            <p class="text-sm text-gray-500 mt-1 mb-4">
                Paste the customer's QR token here.
            </p>

            <div class="flex flex-col sm:flex-row gap-3">

                <input
                    id="manual-token"
                    type="text"
                    placeholder="Paste QR token"
                    class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
                >

                <button
                    id="manual-submit"
                    type="button"
                    class="bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-800"
                >
                    Validate
                </button>

            </div>

            <p id="manual-status" class="text-sm mt-3 text-gray-500"></p>

        </div>

        {{-- Validation result --}}

        <div
            id="result-section"
            class="hidden mt-6"
        >

            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

                <div class="bg-black text-white p-6">

                    <p class="text-sm text-gray-400">
                        Customer
                    </p>

                    <h3
                        id="customer-name"
                        class="text-2xl font-bold mt-1"
                    ></h3>

                    <p
                        id="subscription-plan"
                        class="text-gray-400 mt-2"
                    ></p>

                </div>


                <div class="p-6">

                    <div
                        id="subscription-status"
                        class="mb-6"
                    ></div>


                    <h4 class="font-bold text-lg mb-4">
                        Today's Meals
                    </h4>


                    <div
                        id="meals-list"
                        class="space-y-4"
                    ></div>

                </div>

            </div>



            <div class="mt-8 pt-6 border-t">

                <button
                    id="scan-next-button"
                    type="button"
                    class="w-full bg-black text-white py-4 rounded-xl font-bold text-lg hover:bg-gray-800 transition"
                >
                    Scan Next Customer
                </button>

            </div>

        </div>


        {{-- Error --}}

        <div
            id="error-section"
            class="hidden mt-6 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-5"
        ></div>

    </main>


<script>

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');

const scannerStatus =
    document.getElementById('scanner-status');

const resultSection =
    document.getElementById('result-section');

const errorSection =
    document.getElementById('error-section');

let scanner = null;
let scannerLocked = false;


/*
|--------------------------------------------------------------------------
| Validate QR token
|--------------------------------------------------------------------------
*/

async function validateToken(token)
{
    if (!token) {
        showError('No QR token was provided.');
        return;
    }

    if (scannerLocked) {
        return;
    }

    scannerLocked = true;

    scannerStatus.innerText = 'Validating QR code...';

    errorSection.classList.add('hidden');

    try {

        const response = await fetch(
            "{{ route('staff.meals.validate') }}",
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: JSON.stringify({
                    token: token
                })
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || 'Invalid QR code.'
            );
        }

        displayCustomer(data);

    } catch (error) {

        showError(error.message);

        } finally {

        scannerStatus.innerText =
            'Customer verified. Serve the appropriate meal, then scan the next customer.';
    }
}


/*
|--------------------------------------------------------------------------
| Display customer
|--------------------------------------------------------------------------
*/

function displayCustomer(data)
{
    document.getElementById('customer-name').innerText =
        data.customer.name;

    document.getElementById('subscription-plan').innerText =
        `${data.subscription.plan} · Valid until ${formatDate(data.subscription.ends_at)}`;

    document.getElementById('subscription-status').innerHTML = `
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Active subscription
        </span>
    `;

    const mealsList =
        document.getElementById('meals-list');

    mealsList.innerHTML = '';

    if (!data.meals.length) {

        mealsList.innerHTML = `
            <div class="bg-gray-50 rounded-xl p-5 text-center text-gray-500">
                No meals are scheduled for this customer today.
            </div>
        `;

    } else {

        data.meals.forEach(meal => {

            const available =
                meal.status === 'available';

            const redeemed =
                meal.status === 'redeemed';

            const expired =
                meal.status === 'expired';

            let action = '';

            if (available) {

                action = `
                    <button
                        type="button"
                        onclick="serveMeal(${meal.id}, this)"
                        class="serve-button bg-black text-white px-5 py-3 rounded-xl font-semibold hover:bg-gray-800 transition"
                    >
                        Serve Meal
                    </button>
                `;

            } else if (redeemed) {

                action = `
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-100 text-blue-700 font-semibold">
                        ✓ Already Served
                    </span>
                `;

            } else if (expired) {

                action = `
                    <span class="px-4 py-2 rounded-xl bg-red-100 text-red-700 font-semibold">
                        Expired
                    </span>
                `;

            } else {

                action = `
                    <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-500 font-semibold">
                        Unavailable
                    </span>
                `;
            }

            mealsList.innerHTML += `
                <div class="border rounded-2xl p-5 flex items-center justify-between gap-4">

                    <div>

                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
                            ${capitalize(meal.type)}
                        </p>

                        <h5 class="font-bold text-lg mt-1">
                            ${escapeHtml(meal.name)}
                        </h5>

                    </div>

                    <div>
                        ${action}
                    </div>

                </div>
            `;
        });
    }

    resultSection.classList.remove('hidden');

    resultSection.scrollIntoView({
        behavior: 'smooth'
    });
}

/*
|--------------------------------------------------------------------------
| Serve meal
|--------------------------------------------------------------------------
*/

async function serveMeal(entitlementId, button)
{
    const confirmed = confirm(
        'Confirm that this meal has been physically served to the customer?'
    );

    if (!confirmed) {
        return;
    }

    const originalText = button.innerText;

    button.disabled = true;
    button.innerText = 'Serving...';

    try {

        const response = await fetch(
            `/staff/meals/${entitlementId}/serve`,
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || 'Unable to serve meal.'
            );
        }

        button.outerHTML = `
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-700 font-semibold">
                ✓ Served
            </span>
        `;

        await loadSummary();

        alert(
            `Meal served successfully!\n\nReference: ${data.reference}`
        );

    } catch (error) {

        alert(error.message);

        button.disabled = false;
        button.innerText = originalText;
    }
}
/*
|--------------------------------------------------------------------------
| QR scanner
|--------------------------------------------------------------------------
*/

function onScanSuccess(decodedText)
{
    let token = decodedText;

    try {

        const url = new URL(decodedText);

        const qrToken = url.searchParams.get('token');

        if (qrToken) {
            token = qrToken;
        }

    } catch (e) {
        // QR contains the token directly.
    }

    validateToken(token);
}

/*
|--------------------------------------------------------------------------
| Start scanner
|--------------------------------------------------------------------------
*/

scanner = new Html5Qrcode("reader");

scanner.start(
    {
        facingMode: "environment"
    },
    {
        fps: 10,
        qrbox: {
            width: 250,
            height: 250
        }
    },
    onScanSuccess,
    () => {}
).catch(error => {

    scannerStatus.innerText =
        'Camera unavailable. Use the manual token field below.';

});


/*
|--------------------------------------------------------------------------
| Manual validation
|--------------------------------------------------------------------------
*/
document
    .getElementById('manual-submit')
    .addEventListener('click', async function () {

        const input = document.getElementById('manual-token');
        const status = document.getElementById('manual-status');

        const token = input.value.trim();

        console.log('Manual token:', token);

        if (!token) {
            status.innerText = 'Please enter a token.';
            status.className = 'text-sm mt-3 text-red-600';
            return;
        }

        status.innerText = 'Sending validation request...';
        status.className = 'text-sm mt-3 text-gray-500';

        try {

            const response = await fetch(
                "{{ route('staff.meals.validate') }}",
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },

                    body: JSON.stringify({
                        token: token
                    })
                }
            );

            console.log('HTTP status:', response.status);

            const data = await response.json();

            console.log('Response:', data);

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || 'Validation failed.'
                );
            }

            status.innerText = '✓ Token validated successfully.';
            status.className = 'text-sm mt-3 text-green-600 font-semibold';

            displayCustomer(data);

        } catch (error) {

            console.error('Validation error:', error);

            status.innerText = error.message;
            status.className = 'text-sm mt-3 text-red-600 font-semibold';

        }
    });


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function capitalize(value)
{
    if (!value) return '';

    return value.charAt(0).toUpperCase() +
        value.slice(1);
}


function formatDate(value)
{
    if (!value) return '';

    return new Date(value)
        .toLocaleDateString(
            'en-GB',
            {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }
        );
}


function escapeHtml(value)
{
    const div = document.createElement('div');

    div.textContent = value;

    return div.innerHTML;
}


function showError(message)
{
    errorSection.innerText = message;

    errorSection.classList.remove('hidden');

    resultSection.classList.add('hidden');
}



async function loadSummary()
{
    try {

        const response = await fetch(
            "{{ route('staff.meals.summary') }}",
            {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            return;
        }

        document.getElementById('summary-total').innerText =
            data.total;

        document.getElementById('summary-served').innerText =
            data.served;

        document.getElementById('summary-available').innerText =
            data.available;

        document.getElementById('summary-expired').innerText =
            data.expired;

    } catch (error) {

        console.error(
            'Unable to load service summary:',
            error
        );
    }
}




document
    .getElementById('scan-next-button')
    .addEventListener('click', function () {

        resultSection.classList.add('hidden');

        errorSection.classList.add('hidden');

        document.getElementById('customer-name').innerText = '';

        document.getElementById('subscription-plan').innerText = '';

        document.getElementById('subscription-status').innerHTML = '';

        document.getElementById('meals-list').innerHTML = '';

        document.getElementById('manual-token').value = '';

        scannerLocked = false;

        scannerStatus.innerText =
            'Point the camera at the customer\'s QR code.';

        document
            .getElementById('scanner-section')
            .scrollIntoView({
                behavior: 'smooth'
            });

        loadSummary();
    });


</script>




</body>
</html>