<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In — Silver Spoon</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Playfair Display', 'Georgia', 'serif'],
                    },
                    colors: {
                        spoon: {
                            50: '#faf9f6',
                            100: '#f3f0e9',
                            200: '#e6e0d4',
                            300: '#d3c9b8',
                            500: '#8b7d68',
                            700: '#514838',
                            900: '#211e19',
                        }
                    },
                    boxShadow: {
                        'soft': '0 20px 60px rgba(38, 34, 28, 0.08)',
                        'card': '0 30px 90px rgba(38, 34, 28, 0.12)',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .grain {
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .035;
            background-image:
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.7'/%3E%3C/svg%3E");
        }

        .input-shell {
            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background-color .2s ease,
                transform .2s ease;
        }

        .input-shell:focus-within {
            border-color: #211e19;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(33, 30, 25, .06);
        }

        .input-shell:focus-within .input-icon {
            color: #211e19;
            transform: scale(1.05);
        }

        .input-icon {
            transition: all .2s ease;
        }

        .floating-orb {
            animation: float 8s ease-in-out infinite;
        }

        .floating-orb-delay {
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translate3d(0, 0, 0);
            }

            50% {
                transform: translate3d(0, -18px, 0);
            }
        }

        .button-shine {
            position: relative;
            overflow: hidden;
        }

        .button-shine::after {
            content: "";
            position: absolute;
            top: 0;
            left: -120%;
            width: 70%;
            height: 100%;
            transform: skewX(-20deg);
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,.18),
                transparent
            );
            transition: left .6s ease;
        }

        .button-shine:hover::after {
            left: 140%;
        }

        .checkmark {
            transition: all .2s ease;
        }

        input[type="checkbox"]:checked + .custom-checkbox {
            background: #211e19;
            border-color: #211e19;
        }

        input[type="checkbox"]:checked + .custom-checkbox .checkmark {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>


<body class="min-h-screen bg-[#f7f5f0] text-spoon-900">

    <!-- Ambient background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-[#e8dfcf] blur-3xl opacity-60 floating-orb"></div>

        <div class="absolute -bottom-40 -left-32 h-[28rem] w-[28rem] rounded-full bg-[#e1e7dd] blur-3xl opacity-60 floating-orb-delay"></div>

        <div class="absolute top-1/2 left-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white blur-3xl opacity-70"></div>

        <div class="grain"></div>

    </div>


    <main class="relative min-h-screen flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">

        <div class="w-full max-w-6xl">

            <div class="grid lg:grid-cols-[1.15fr_.85fr] lg:min-h-[680px] rounded-[2rem] overflow-hidden bg-white/70 backdrop-blur-xl border border-white/80 shadow-card">


                <!-- LEFT BRAND PANEL -->
                <section class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-[#211e19] text-white p-12 xl:p-16">

                    <!-- Decorative rings -->
                    <div class="absolute -top-32 -right-32 w-[30rem] h-[30rem] rounded-full border border-white/10"></div>

                    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full border border-white/10"></div>

                    <div class="absolute -bottom-48 -left-48 w-[32rem] h-[32rem] rounded-full bg-[#776b59]/20 blur-3xl"></div>


                    <!-- Brand -->
                    <div class="relative z-10">

                        <a
                            href="/"
                            class="inline-flex items-center gap-3 group"
                        >

                            <div class="h-11 w-11 rounded-2xl bg-white flex items-center justify-center text-[#211e19] shadow-lg group-hover:rotate-3 transition-transform duration-300">

                                <svg
                                    width="22"
                                    height="22"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M7 3V10"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M4.5 3V10C4.5 11.38 5.62 12.5 7 12.5C8.38 12.5 9.5 11.38 9.5 10V3"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M7 12.5V21"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M16.5 3C14.84 4.66 14 6.77 14 9V21"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M14 9H19"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                </svg>

                            </div>

                            <span class="font-semibold tracking-tight text-lg">
                                Silver Spoon
                            </span>

                        </a>

                    </div>


                    <!-- Main brand message -->
                    <div class="relative z-10 max-w-lg">

                        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 mb-7">

                            <span class="w-1.5 h-1.5 rounded-full bg-[#d7c6a8]"></span>

                            <span class="text-[11px] uppercase tracking-[.2em] text-white/60 font-semibold">
                                Welcome back
                            </span>

                        </div>


                        <h2 class="font-display text-5xl xl:text-6xl leading-[1.04] tracking-tight">

                            Your next
                            <span class="text-[#cfc1a9] italic">
                                great meal
                            </span>
                            starts here.

                        </h2>


                        <p class="mt-7 text-white/55 leading-7 text-sm xl:text-base max-w-md">

                            Pick up where you left off. Your meal plans,
                            preferences and Silver Spoon experience are waiting for you.

                        </p>


                        <!-- Feature cards -->
                        <div class="grid grid-cols-2 gap-3 mt-10">

                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">

                                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center mb-4">

                                    <svg
                                        class="w-4 h-4 text-[#d7c6a8]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.6"
                                            d="M12 6v12M6 12h12"
                                        />
                                    </svg>

                                </div>

                                <p class="text-sm font-semibold">
                                    Your plans
                                </p>

                                <p class="text-xs text-white/40 mt-1 leading-5">
                                    Everything organized in one place.
                                </p>

                            </div>


                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">

                                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center mb-4">

                                    <svg
                                        class="w-4 h-4 text-[#d7c6a8]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.6"
                                            d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"
                                        />
                                    </svg>

                                </div>

                                <p class="text-sm font-semibold">
                                    Your preferences
                                </p>

                                <p class="text-xs text-white/40 mt-1 leading-5">
                                    Food choices that stay yours.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Footer -->
                    <div class="relative z-10 flex items-center justify-between text-xs text-white/30">

                        <span>
                            © {{ date('Y') }} Silver Spoon
                        </span>

                        <span>
                            Made for better days.
                        </span>

                    </div>

                </section>


                <!-- RIGHT LOGIN PANEL -->
                <section class="flex items-center justify-center bg-white/90 p-6 sm:p-10 lg:p-14 xl:p-16">

                    <div class="w-full max-w-md">


                        <!-- Mobile header -->
                        <div class="lg:hidden flex items-center justify-between mb-12">

                            <a
                                href="/"
                                class="flex items-center gap-3"
                            >

                                <div class="h-10 w-10 rounded-xl bg-[#211e19] text-white flex items-center justify-center">

                                    <svg
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path d="M7 3V10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M4.5 3V10C4.5 11.38 5.62 12.5 7 12.5C8.38 12.5 9.5 11.38 9.5 10V3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M7 12.5V21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M16.5 3C14.84 4.66 14 6.77 14 9V21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M14 9H19" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>

                                </div>

                                <span class="font-bold tracking-tight">
                                    Silver Spoon
                                </span>

                            </a>


                            <a
                                href="{{ route('register') }}"
                                class="text-sm font-semibold text-[#211e19]"
                            >
                                Create account
                            </a>

                        </div>


                        <!-- Heading -->
                        <div class="mb-9">

                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-8 h-px bg-[#211e19]"></span>

                                <span class="text-[10px] font-bold uppercase tracking-[.22em] text-gray-400">
                                    Member access
                                </span>

                            </div>


                            <h1 class="font-display text-4xl sm:text-5xl font-semibold tracking-tight text-[#211e19]">

                                Welcome
                                <span class="italic">
                                    back.
                                </span>

                            </h1>


                            <p class="mt-3 text-sm leading-6 text-gray-500">

                                Sign in to continue your Silver Spoon journey.

                            </p>

                        </div>


                        <!-- Error message -->
                        @if ($errors->any())

                            <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4">

                                <div class="flex gap-3">

                                    <div class="shrink-0 w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center">

                                        <svg
                                            class="w-4 h-4 text-red-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4m0 4h.01M10.29 3.86l-8.3 14a2 2 0 001.72 3h16.58a2 2 0 001.72-3l-8.3-14a2 2 0 00-3.42 0z"
                                            />
                                        </svg>

                                    </div>


                                    <div>

                                        <p class="text-sm font-semibold text-red-800">
                                            We couldn't sign you in
                                        </p>

                                        <ul class="mt-1.5 space-y-1 text-xs text-red-600">

                                            @foreach ($errors->all() as $error)

                                                <li>{{ $error }}</li>

                                            @endforeach

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        @endif


                        <!-- Login form -->
                        <form
                            method="POST"
                            action="{{ route('login') }}"
                            class="space-y-5"
                        >

                            @csrf


                            <!-- Email -->
                            <div>

                                <label
                                    for="email"
                                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2"
                                >
                                    Email address
                                </label>


                                <div class="input-shell relative flex items-center rounded-2xl border border-gray-200 bg-[#faf9f6]">

                                    <div class="input-icon pl-4 text-gray-400">

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.6"
                                                d="M3 7l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>

                                    </div>


                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                        class="w-full bg-transparent border-0 outline-none focus:ring-0 px-4 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                    >

                                </div>

                            </div>


                            <!-- Password -->
                            <div>

                                <div class="flex items-center justify-between mb-2">

                                    <label
                                        for="password"
                                        class="block text-xs font-bold uppercase tracking-wider text-gray-500"
                                    >
                                        Password
                                    </label>

                                    @if (Route::has('password.request'))

                                        <a
                                            href="{{ route('password.request') }}"
                                            class="text-xs font-semibold text-[#211e19] hover:underline"
                                        >
                                            Forgot password?
                                        </a>

                                    @endif

                                </div>


                                <div class="input-shell relative flex items-center rounded-2xl border border-gray-200 bg-[#faf9f6]">

                                    <div class="input-icon pl-4 text-gray-400">

                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.6"
                                                d="M7 10V7a5 5 0 0110 0v3M6 10h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2z"
                                            />
                                        </svg>

                                    </div>


                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="••••••••"
                                        class="w-full min-w-0 bg-transparent border-0 outline-none focus:ring-0 px-3 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                    >


                                    <button
                                        type="button"
                                        onclick="togglePassword('password', this)"
                                        class="mr-2 p-2 text-gray-400 hover:text-gray-900 transition"
                                        aria-label="Show password"
                                    >

                                        <svg
                                            class="eye-open w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.6"
                                                d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="2.5"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                            />

                                        </svg>


                                        <svg
                                            class="eye-closed hidden w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.6"
                                                d="M3 3l18 18M10.58 10.58a2 2 0 002.84 2.84M9.88 5.09A9.87 9.87 0 0112 4.86c6.5 0 10 6 10 6a17.18 17.18 0 01-3.18 3.83M6.61 6.61C3.5 8.35 2 10.86 2 10.86s3.5 6 10 6c1.36 0 2.59-.27 3.69-.7"
                                            />

                                        </svg>

                                    </button>

                                </div>

                            </div>


                            <!-- Remember me -->
                            <div class="flex items-center justify-between pt-1">

                                <label class="inline-flex items-center gap-3 cursor-pointer select-none">

                                    <input
                                        type="checkbox"
                                        name="remember"
                                        value="1"
                                        class="sr-only"
                                    >

                                    <span class="custom-checkbox relative w-5 h-5 rounded-md border border-gray-300 bg-white flex items-center justify-center transition-colors">

                                        <svg
                                            class="checkmark w-3 h-3 text-white opacity-0 scale-75"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="3"
                                                d="M5 12l4 4L19 7"
                                            />
                                        </svg>

                                    </span>


                                    <span class="text-sm text-gray-500">
                                        Remember me
                                    </span>

                                </label>


                                <div class="flex items-center gap-1.5">

                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>

                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">
                                        Secure login
                                    </span>

                                </div>

                            </div>


                            <!-- Submit -->
                            <button
                                type="submit"
                                class="button-shine group relative w-full flex items-center justify-center gap-3 rounded-2xl bg-[#211e19] text-white py-4 px-6 font-semibold text-sm shadow-lg shadow-black/10 hover:bg-[#342f28] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                            >

                                <span>
                                    Sign in
                                </span>

                                <svg
                                    class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M5 12h14M13 6l6 6-6 6"
                                    />
                                </svg>

                            </button>

                        </form>


                        <!-- Divider -->
                        <div class="relative my-8">

                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-100"></div>
                            </div>

                            <div class="relative flex justify-center">

                                <span class="bg-white px-4 text-[10px] uppercase tracking-[.2em] text-gray-300 font-semibold">
                                    New to Silver Spoon?
                                </span>

                            </div>

                        </div>


                        <!-- Create account -->
                        <a
                            href="{{ route('register') }}"
                            class="group w-full flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-[#211e19] hover:border-gray-400 hover:bg-gray-50 transition"
                        >

                            Create your account

                            <svg
                                class="w-4 h-4 transition-transform group-hover:translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M5 12h14M13 6l6 6-6 6"
                                />
                            </svg>

                        </a>


                        <!-- Security note -->
                        <div class="flex items-center justify-center gap-2 mt-7">

                            <svg
                                class="w-3.5 h-3.5 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.6"
                                    d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                                />
                            </svg>

                            <p class="text-[10px] text-gray-400">
                                Your information is protected and kept private.
                            </p>

                        </div>


                        <!-- Mobile footer -->
                        <p class="lg:hidden text-center text-[10px] text-gray-400 mt-8">
                            © {{ date('Y') }} Silver Spoon · Made for better days.
                        </p>

                    </div>

                </section>

            </div>

        </div>

    </main>


    <script>

        function togglePassword(inputId, button) {

            const input = document.getElementById(inputId);

            const openEye = button.querySelector('.eye-open');

            const closedEye = button.querySelector('.eye-closed');


            if (input.type === 'password') {

                input.type = 'text';

                openEye.classList.add('hidden');

                closedEye.classList.remove('hidden');

                button.setAttribute('aria-label', 'Hide password');

            } else {

                input.type = 'password';

                openEye.classList.remove('hidden');

                closedEye.classList.add('hidden');

                button.setAttribute('aria-label', 'Show password');

            }

        }

    </script>

</body>
</html>