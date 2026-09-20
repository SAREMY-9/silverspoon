<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account — Silver Spoon</title>

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

        .password-strength {
            transition: all .25s ease;
        }

        @media (max-width: 640px) {
            .mobile-card {
                box-shadow: none;
                border: 0;
                background: transparent;
            }
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

            <div class="grid lg:grid-cols-[.85fr_1.15fr] gap-0 lg:min-h-[760px] rounded-[2rem] overflow-hidden bg-white/70 backdrop-blur-xl border border-white/80 shadow-card">

                <!-- LEFT / BRAND PANEL -->
                <section class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-[#211e19] text-white p-12 xl:p-16">

                    <!-- Decorative circles -->
                    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full border border-white/10"></div>
                    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full border border-white/10"></div>

                    <div class="absolute -bottom-48 -right-48 w-[32rem] h-[32rem] rounded-full bg-[#776b59]/20 blur-3xl"></div>

                    <!-- Brand -->
                    <div class="relative z-10">

                        <a href="/" class="inline-flex items-center gap-3 group">

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


                    <!-- Hero copy -->
                    <div class="relative z-10 max-w-md">

                        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 mb-7">

                            <span class="w-1.5 h-1.5 rounded-full bg-[#d7c6a8]"></span>

                            <span class="text-[11px] uppercase tracking-[.2em] text-white/60 font-semibold">
                                Eat better. Live better.
                            </span>

                        </div>

                        <h2 class="font-display text-5xl xl:text-6xl leading-[1.05] tracking-tight">
                            Good food
                            <span class="text-[#cfc1a9] italic">
                                starts
                            </span>
                            with a good plan.
                        </h2>

                        <p class="mt-7 text-white/55 leading-7 text-sm xl:text-base max-w-sm">
                            Create your Silver Spoon account and make delicious,
                            balanced meals a simple part of your everyday life.
                        </p>

                        <!-- Mini benefits -->
                        <div class="mt-10 space-y-4">

                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#d7c6a8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-medium">Personalized meal plans</p>
                                    <p class="text-xs text-white/40 mt-0.5">Plans made around your lifestyle.</p>
                                </div>

                            </div>


                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#d7c6a8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-medium">Less thinking, more eating</p>
                                    <p class="text-xs text-white/40 mt-0.5">Take the stress out of meal planning.</p>
                                </div>

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


                <!-- RIGHT / FORM -->
                <section class="flex items-center justify-center bg-white/90 p-6 sm:p-10 lg:p-14 xl:p-16">

                    <div class="w-full max-w-lg">

                        <!-- Mobile logo -->
                        <div class="lg:hidden flex items-center justify-between mb-10">

                            <a href="/" class="flex items-center gap-3">

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
                                href="{{ route('login') }}"
                                class="text-sm font-semibold text-[#211e19]"
                            >
                                Sign in
                            </a>

                        </div>


                        <!-- Heading -->
                        <div class="mb-8">

                            <div class="flex items-center gap-2 mb-4">

                                <span class="w-8 h-px bg-[#211e19]"></span>

                                <span class="text-[10px] font-bold uppercase tracking-[.22em] text-gray-400">
                                    Welcome
                                </span>

                            </div>

                            <h1 class="font-display text-4xl sm:text-5xl font-semibold tracking-tight text-[#211e19]">
                                Create your account.
                            </h1>

                            <p class="mt-3 text-sm leading-6 text-gray-500 max-w-md">
                                Join Silver Spoon and start making every meal a little easier,
                                healthier and more enjoyable.
                            </p>

                        </div>


                        <!-- Progress -->
                        <div class="flex items-center gap-3 mb-8">

                            <div class="h-1.5 flex-1 rounded-full bg-[#211e19]"></div>

                            <div class="h-1.5 flex-1 rounded-full bg-[#e9e5dc]"></div>

                            <div class="h-1.5 flex-1 rounded-full bg-[#e9e5dc]"></div>

                            <span class="ml-2 text-[10px] uppercase tracking-widest font-semibold text-gray-400">
                                01 / 03
                            </span>

                        </div>


                        <!-- Validation errors -->
                        @if ($errors->any())

                            <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4">

                                <div class="flex gap-3">

                                    <div class="shrink-0 w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center">

                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86l-8.3 14a2 2 0 001.72 3h16.58a2 2 0 001.72-3l-8.3-14a2 2 0 00-3.42 0z"/>
                                        </svg>

                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-red-800">
                                            Something needs your attention
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


                        <!-- Form -->
                        <form method="POST" action="{{ route('register') }}" class="space-y-5">

                            @csrf


                            <!-- Name -->
                            <div>

                                <label
                                    for="name"
                                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2"
                                >
                                    Full name
                                </label>

                                <div class="input-shell relative flex items-center rounded-2xl border border-gray-200 bg-[#faf9f6]">

                                    <div class="input-icon pl-4 text-gray-400">

                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm12 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                        </svg>

                                    </div>

                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="John Doe"
                                        class="w-full bg-transparent border-0 outline-none focus:ring-0 px-4 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                    >

                                </div>

                            </div>


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

                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 7l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>

                                    </div>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                        class="w-full bg-transparent border-0 outline-none focus:ring-0 px-4 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                    >

                                </div>

                            </div>


                            <!-- Phone -->
                            <div>

                                <label
                                    for="phone"
                                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2"
                                >
                                    Phone number
                                    <span class="normal-case tracking-normal font-medium text-gray-400 ml-1">
                                        (optional)
                                    </span>
                                </label>

                                <div class="input-shell relative flex items-center rounded-2xl border border-gray-200 bg-[#faf9f6]">

                                    <div class="input-icon pl-4 text-gray-400">

                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 4h3l2 5-2 1.5a16 16 0 007.5 7.5L17 16l5 2v3a2 2 0 01-2 2C10.61 23 1 13.39 1 4a2 2 0 012-2h2z"/>
                                        </svg>

                                    </div>

                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        autocomplete="tel"
                                        placeholder="0712 345 678"
                                        class="w-full bg-transparent border-0 outline-none focus:ring-0 px-4 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                    >

                                </div>

                            </div>


                            <!-- Password grid -->
                            <div class="grid sm:grid-cols-2 gap-5">

                                <!-- Password -->
                                <div>

                                    <label
                                        for="password"
                                        class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2"
                                    >
                                        Password
                                    </label>

                                    <div class="input-shell relative flex items-center rounded-2xl border border-gray-200 bg-[#faf9f6]">

                                        <div class="input-icon pl-4 text-gray-400">

                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 10V7a5 5 0 0110 0v3M6 10h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2z"/>
                                            </svg>

                                        </div>

                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                            placeholder="••••••••"
                                            class="password-input w-full min-w-0 bg-transparent border-0 outline-none focus:ring-0 px-3 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                        >

                                        <button
                                            type="button"
                                            onclick="togglePassword('password', this)"
                                            class="mr-2 p-2 text-gray-400 hover:text-gray-900 transition"
                                            aria-label="Show password"
                                        >
                                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/>
                                                <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.6"/>
                                            </svg>

                                            <svg class="eye-closed hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 3l18 18M10.58 10.58a2 2 0 002.84 2.84M9.88 5.09A9.87 9.87 0 0112 4.86c6.5 0 10 6 10 6a17.18 17.18 0 01-3.18 3.83M6.61 6.61C3.5 8.35 2 10.86 2 10.86s3.5 6 10 6c1.36 0 2.59-.27 3.69-.7"/>
                                            </svg>
                                        </button>

                                    </div>

                                </div>


                                <!-- Confirm -->
                                <div>

                                    <label
                                        for="password_confirmation"
                                        class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2"
                                    >
                                        Confirm
                                    </label>

                                    <div class="input-shell relative flex items-center rounded-2xl border border-gray-200 bg-[#faf9f6]">

                                        <div class="input-icon pl-4 text-gray-400">

                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                                            </svg>

                                        </div>

                                        <input
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                            placeholder="••••••••"
                                            class="w-full min-w-0 bg-transparent border-0 outline-none focus:ring-0 px-3 py-4 text-sm text-gray-900 placeholder:text-gray-400"
                                        >

                                    </div>

                                </div>

                            </div>


                            <!-- Password hint -->
                            <div class="flex items-center gap-2 text-[11px] text-gray-400">

                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                                </svg>

                                <span>
                                    Use at least 8 characters for a stronger password.
                                </span>

                            </div>


                            <!-- Terms -->
                            <label class="flex items-start gap-3 cursor-pointer group pt-1">

                                <input
                                    type="checkbox"
                                    required
                                    class="mt-0.5 w-4 h-4 rounded border-gray-300 text-[#211e19] focus:ring-[#211e19]"
                                >

                                <span class="text-xs leading-5 text-gray-500">
                                    I agree to Silver Spoon's
                                                            
                                <a
                                    href="{{ route('terms') }}"
                                    class="font-semibold text-[#211e19] hover:underline"
                                >
                                    Terms
                                </a>

                                    and


                                    <a
                                        href="{{ route('privacy') }}"
                                        class="font-semibold text-[#211e19] hover:underline"
                                    >
                                        Privacy Policy
                                    </a>.
                                    
                                </span>

                            </label>


                            <!-- Submit -->
                            <button
                                type="submit"
                                class="button-shine group relative w-full flex items-center justify-center gap-3 rounded-2xl bg-[#211e19] text-white py-4 px-6 font-semibold text-sm shadow-lg shadow-black/10 hover:bg-[#342f28] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                            >

                                <span>
                                    Create my account
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


                        <!-- Login -->
                        <div class="relative my-8">

                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-100"></div>
                            </div>

                            <div class="relative flex justify-center">
                                <span class="bg-white px-4 text-[10px] uppercase tracking-[.2em] text-gray-300 font-semibold">
                                    Already a member?
                                </span>
                            </div>

                        </div>


                        <a
                            href="{{ route('login') }}"
                            class="group w-full flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-[#211e19] hover:border-gray-400 hover:bg-gray-50 transition"
                        >
                            Sign in to Silver Spoon

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