<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account - Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Silver Spoon
            </h1>

            <p class="text-gray-500 mt-2">
                Create your account
            </p>
        </div>


        @if ($errors->any())
            <div class="bg-red-50 text-red-700 rounded-lg p-4 mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('register') }}" class="space-y-5">

            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full rounded-lg border-gray-300 border px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="John Doe"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full rounded-lg border-gray-300 border px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="you@example.com"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full rounded-lg border-gray-300 border px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="0712345678"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border-gray-300 border px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="••••••••"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-lg border-gray-300 border px-4 py-3 focus:ring-2 focus:ring-black focus:outline-none"
                    placeholder="••••••••"
                >
            </div>


            <button
                type="submit"
                class="w-full bg-black text-white rounded-lg py-3 font-semibold hover:bg-gray-800 transition"
            >
                Create Account
            </button>

        </form>


        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?

            <a
                href="{{ route('login') }}"
                class="text-black font-semibold hover:underline"
            >
                Sign in
            </a>
        </p>

    </div>

</div>

</body>
</html>