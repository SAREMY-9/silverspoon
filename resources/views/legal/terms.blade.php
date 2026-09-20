<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Terms & Conditions — Silver Spoon</title>

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
                        soft: '0 20px 60px rgba(38, 34, 28, 0.08)',
                        card: '0 30px 90px rgba(38, 34, 28, 0.10)',
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .022;
            z-index: 50;

            background-image:
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.7'/%3E%3C/svg%3E");
        }

        .legal-content h2 {
            scroll-margin-top: 120px;
        }

        .legal-content p {
            line-height: 1.85;
        }

        .legal-content li {
            line-height: 1.75;
        }

        .toc-link {
            transition: all .2s ease;
        }

        .toc-link:hover {
            transform: translateX(4px);
        }

        .floating-orb {
            animation: float 9s ease-in-out infinite;
        }

        .fade-up {
            animation: fadeUp .7s ease-out both;
        }

        .fade-up-delay {
            animation: fadeUp .7s .12s ease-out both;
        }

        @keyframes float {
            0%, 100% {
                transform: translate3d(0, 0, 0);
            }

            50% {
                transform: translate3d(0, -15px, 0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

</head>


<body class="bg-[#f7f5f0] text-[#211e19]">

<div class="grain"></div>


<!-- =========================================================
     AMBIENT BACKGROUND
========================================================= -->

<div class="fixed inset-0 overflow-hidden pointer-events-none">

    <div
        class="floating-orb absolute -top-40 -right-32 w-[30rem] h-[30rem] rounded-full bg-[#e8dfcf] blur-3xl opacity-60">
    </div>

    <div
        class="absolute -bottom-48 -left-40 w-[32rem] h-[32rem] rounded-full bg-[#e1e7dd] blur-3xl opacity-50">
    </div>

</div>


<!-- =========================================================
     NAVIGATION
========================================================= -->

<header class="sticky top-0 z-40 border-b border-black/5 bg-[#f7f5f0]/85 backdrop-blur-xl">

    <div class="max-w-7xl mx-auto px-5 sm:px-8">

        <div class="h-20 flex items-center justify-between">

            <!-- Brand -->

            <a href="/" class="flex items-center gap-3 group">

                <div
                    class="w-10 h-10 rounded-xl bg-[#211e19] text-white flex items-center justify-center shadow-lg group-hover:rotate-3 transition duration-300">

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none">

                        <path
                            d="M7 3V10"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                        <path
                            d="M4.5 3V10C4.5 11.38 5.62 12.5 7 12.5C8.38 12.5 9.5 11.38 9.5 10V3"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                        <path
                            d="M7 12.5V21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                        <path
                            d="M16.5 3C14.84 4.66 14 6.77 14 9V21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                        <path
                            d="M14 9H19"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </div>

                <div>

                    <span class="block font-bold tracking-tight">
                        Silver Spoon
                    </span>

                    <span
                        class="hidden sm:block text-[9px] uppercase tracking-[.2em] text-gray-400 font-semibold">
                        Better meals. Better days.
                    </span>

                </div>

            </a>


            <!-- Navigation -->

            <div class="flex items-center gap-2 sm:gap-3">

                <a
                    href="{{ route('login') }}"
                    class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold text-gray-600 hover:text-[#211e19] transition">
                    Sign in
                </a>

                <a
                    href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#211e19] text-white px-4 py-2.5 text-sm font-semibold hover:bg-[#342f28] transition shadow-sm">

                    Create account

                    <svg
                        class="w-3.5 h-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5 12h14M13 6l6 6-6 6"/>

                    </svg>

                </a>

            </div>

        </div>

    </div>

</header>


<!-- =========================================================
     HERO
========================================================= -->

<section class="relative">

    <div class="max-w-5xl mx-auto px-5 sm:px-8 pt-16 sm:pt-24 pb-14 sm:pb-20">

        <div class="max-w-3xl fade-up">

            <div class="inline-flex items-center gap-2 mb-7">

                <span class="w-8 h-px bg-[#211e19]"></span>

                <span
                    class="text-[10px] uppercase tracking-[.28em] font-bold text-gray-400">
                    Legal
                </span>

            </div>


            <h1
                class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[1.02] tracking-tight">

                Terms &

                <span class="italic text-[#8b7d68]">
                    Conditions
                </span>

            </h1>


            <p
                class="mt-7 max-w-2xl text-base sm:text-lg text-gray-500 leading-8 fade-up-delay">

                Please take a moment to understand the terms that govern
                your use of Silver Spoon, our platform, services and
                company-issued identification cards.

            </p>


            <div class="mt-8 flex flex-wrap items-center gap-3">

                <div
                    class="inline-flex items-center gap-2 rounded-full bg-white border border-black/5 px-4 py-2 shadow-sm">

                    <span class="w-2 h-2 rounded-full bg-green-500"></span>

                    <span class="text-xs font-semibold text-gray-600">
                        Effective {{ date('F j, Y') }}
                    </span>

                </div>


                <div
                    class="inline-flex items-center gap-2 rounded-full bg-white border border-black/5 px-4 py-2 shadow-sm">

                    <svg
                        class="w-3.5 h-3.5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                            stroke="currentColor"
                            stroke-width="1.6"/>

                        <path
                            d="M12 7v5l3 2"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"/>

                    </svg>

                    <span class="text-xs font-semibold text-gray-600">
                        Please read carefully
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     DOCUMENT
========================================================= -->

<main class="max-w-5xl mx-auto px-5 sm:px-8 pb-24">

    <div class="grid lg:grid-cols-[220px_1fr] gap-10 lg:gap-16">


        <!-- =================================================
             TABLE OF CONTENTS
        ================================================== -->

        <aside class="hidden lg:block">

            <div class="sticky top-28">

                <p
                    class="text-[10px] uppercase tracking-[.2em] font-bold text-gray-400 mb-5">
                    On this page
                </p>


                <nav class="space-y-1">

                    <a href="#acceptance"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        01 &nbsp; Acceptance
                    </a>

                    <a href="#service"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        02 &nbsp; Our Service
                    </a>

                    <a href="#accounts"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        03 &nbsp; Accounts
                    </a>

                    <a href="#meal-plans"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        04 &nbsp; Meal Plans
                    </a>

                    <a href="#payments"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        05 &nbsp; Payments
                    </a>

                    <a href="#intellectual"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        06 &nbsp; Intellectual Property
                    </a>

                    <a href="#acceptable-use"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        07 &nbsp; Acceptable Use
                    </a>

                    <a href="#liability"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        08 &nbsp; Liability
                    </a>

                    <a href="#termination"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        09 &nbsp; Termination
                    </a>

                    <a href="#changes"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        10 &nbsp; Changes
                    </a>

                    <a href="#contact"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        11 &nbsp; Contact
                    </a>

                    <a href="#company-cards"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        12 &nbsp; Identification Cards
                    </a>

                </nav>

            </div>

        </aside>


        <!-- =================================================
             LEGAL DOCUMENT
        ================================================== -->

        <article class="legal-content">

            <div
                class="bg-white rounded-[2rem] border border-black/5 shadow-card overflow-hidden">


                <!-- INTRO -->

                <div class="p-7 sm:p-10 lg:p-14 pb-10">

                    <div class="max-w-2xl">

                        <p class="text-gray-600">
                            Welcome to Silver Spoon. These Terms & Conditions
                            govern your access to and use of the Silver Spoon
                            website, platform, meal-planning services and
                            related products and services.
                        </p>

                        <p class="mt-5 text-gray-600">
                            By creating an account, accessing our platform or
                            using our services, you acknowledge that you have
                            read, understood and agree to be bound by these
                            Terms & Conditions.
                        </p>

                    </div>

                </div>


                <div class="border-t border-gray-100"></div>


                <!-- =================================================
                     01
                ================================================== -->

                <section id="acceptance" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            01
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Acceptance of Terms
                            </h2>

                            <p class="mt-5 text-gray-600">
                                By accessing or using Silver Spoon, you agree
                                to comply with these Terms & Conditions and
                                any applicable laws and regulations.
                            </p>

                            <p class="mt-4 text-gray-600">
                                If you do not agree with these terms, please
                                do not use the platform or create an account.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     02
                ================================================== -->

                <section id="service" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            02
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Our Service
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon provides meal-planning,
                                food-related information and related digital
                                services designed to help users plan and
                                organize meals.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Features and services may change over time.
                                We may introduce, modify, suspend or
                                discontinue certain features where reasonably
                                necessary.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     03
                ================================================== -->

                <section id="accounts" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            03
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                User Accounts
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Some Silver Spoon features require you to
                                create an account.
                            </p>

                            <ul class="mt-5 space-y-3 text-gray-600 list-disc pl-5">

                                <li>
                                    You must provide accurate and current
                                    information when creating your account.
                                </li>

                                <li>
                                    You are responsible for keeping your
                                    login credentials secure.
                                </li>

                                <li>
                                    You should notify us if you believe your
                                    account has been accessed without your
                                    authorization.
                                </li>

                                <li>
                                    You are responsible for activity carried
                                    out through your account.
                                </li>

                            </ul>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     04
                ================================================== -->

                <section id="meal-plans" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            04
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Meal Plans & Food Information
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon may provide meal plans, recipes,
                                nutritional information, recommendations and
                                other food-related content.
                            </p>


                            <div class="mt-6 rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                <div class="flex gap-3">

                                    <svg
                                        class="w-5 h-5 shrink-0 text-[#8b7d68] mt-0.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.6"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>

                                    </svg>

                                    <p class="text-sm text-gray-600 leading-6">
                                        Information provided through Silver Spoon
                                        is for general informational purposes and
                                        should not be treated as medical or
                                        professional dietary advice.
                                    </p>

                                </div>

                            </div>

                            <p class="mt-5 text-gray-600">
                                If you have allergies, dietary restrictions,
                                medical conditions or specific nutritional
                                requirements, you should obtain appropriate
                                professional advice before relying on
                                information provided through the platform.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     05
                ================================================== -->

                <section id="payments" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            05
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Payments & Purchases
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Where Silver Spoon offers paid products or
                                services, applicable prices and payment terms
                                will be presented before a transaction is
                                completed.
                            </p>

                            <p class="mt-4 text-gray-600">
                                You agree to provide accurate payment
                                information and authorize the applicable
                                payment provider to process transactions
                                associated with your purchases.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     06
                ================================================== -->

                <section id="intellectual" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            06
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Intellectual Property
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon and its associated content,
                                branding, designs, graphics, text, software,
                                logos and other materials are protected by
                                applicable intellectual property laws.
                            </p>

                            <p class="mt-4 text-gray-600">
                                You may use the platform for its intended
                                personal or authorized purposes. You may not
                                reproduce, distribute, modify or commercially
                                exploit our content without appropriate
                                authorization.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     07
                ================================================== -->

                <section id="acceptable-use" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            07
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Acceptable Use
                            </h2>

                            <p class="mt-5 text-gray-600">
                                You agree not to misuse Silver Spoon or
                                interfere with the operation or security
                                of the platform.
                            </p>

                            <ul class="mt-5 space-y-3 text-gray-600 list-disc pl-5">

                                <li>
                                    Attempt to gain unauthorized access to
                                    accounts or systems.
                                </li>

                                <li>
                                    Use the platform for unlawful purposes.
                                </li>

                                <li>
                                    Introduce malicious software or harmful
                                    code.
                                </li>

                                <li>
                                    Attempt to disrupt or compromise the
                                    platform or its infrastructure.
                                </li>

                                <li>
                                    Misrepresent your identity or provide
                                    fraudulent information.
                                </li>

                            </ul>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     08
                ================================================== -->

                <section id="liability" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            08
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Disclaimer & Liability
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon aims to provide reliable and
                                useful services, but we do not guarantee that
                                the platform will always be uninterrupted,
                                error-free or available at all times.
                            </p>

                            <p class="mt-4 text-gray-600">
                                To the extent permitted by applicable law,
                                Silver Spoon will not be responsible for
                                losses arising from circumstances outside
                                our reasonable control.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     09
                ================================================== -->

                <section id="termination" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            09
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Account Suspension & Termination
                            </h2>

                            <p class="mt-5 text-gray-600">
                                We may suspend or terminate access to an
                                account where we reasonably believe that a
                                user has violated these terms, applicable
                                laws or the security of the platform.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Users may also choose to stop using Silver
                                Spoon and may contact us regarding account
                                closure.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     10
                ================================================== -->

                <section id="changes" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            10
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Changes to These Terms
                            </h2>

                            <p class="mt-5 text-gray-600">
                                We may update these Terms & Conditions from
                                time to time to reflect changes to our
                                services, legal requirements or business
                                operations.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Updated terms will be published on this page.
                                Your continued use of Silver Spoon after
                                changes take effect constitutes acceptance
                                of the updated terms.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     11
                ================================================== -->

                <section id="contact" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            11
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Contact Us
                            </h2>

                            <p class="mt-5 text-gray-600">
                                If you have questions about these Terms &
                                Conditions, please contact Silver Spoon
                                through the contact information provided
                                on our platform.
                            </p>

                            <a
                                href="/"
                                class="inline-flex items-center gap-2 mt-6 text-sm font-semibold text-[#211e19] hover:underline">

                                Return to Silver Spoon

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M5 12h14M13 6l6 6-6 6"/>

                                </svg>

                            </a>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     12 — COMPANY ID CARDS
                ================================================== -->

                <section id="company-cards" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            12
                        </span>


                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="font-display text-2xl sm:text-3xl">
                                    Company-Issued Identification Cards
                                </h2>

                                <span
                                    class="inline-flex items-center rounded-full bg-[#f3f0e9] px-3 py-1 text-[10px] uppercase tracking-[.15em] font-bold text-[#8b7d68]">
                                    Company Property
                                </span>

                            </div>


                            <p class="mt-5 text-gray-600">
                                Silver Spoon may issue identification cards to
                                users, members, employees, partners or other
                                authorized individuals for identification,
                                verification, access, membership or operational
                                purposes.
                            </p>


                            <!-- 12.1 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.1
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            Ownership of Cards
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            All identification cards issued by
                                            Silver Spoon remain the sole property
                                            of Silver Spoon at all times.
                                            Issuing a card to an individual does
                                            not transfer ownership of the card
                                            to that individual.
                                        </p>

                                        <p class="mt-3 text-gray-600">
                                            The card is provided to the designated
                                            holder for authorized use only and
                                            must be returned to Silver Spoon when
                                            requested or when the holder's
                                            relationship or authorization with
                                            Silver Spoon ends.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- 12.2 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.2
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            Care & Responsibility
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            The card holder is responsible for
                                            keeping the identification card safe
                                            and in reasonable condition.
                                        </p>

                                        <p class="mt-3 text-gray-600">
                                            The card must not be intentionally
                                            damaged, altered, defaced,
                                            duplicated or otherwise modified.
                                            Where applicable, the QR code and
                                            other identification features must
                                            remain intact and readable.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- 12.3 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.3
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            QR Codes & Verification
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            Identification cards may contain a
                                            QR code or other technology that
                                            allows Silver Spoon or authorized
                                            personnel to verify the card, the
                                            holder, membership status or
                                            information associated with the card.
                                        </p>

                                        <p class="mt-3 text-gray-600">
                                            A QR code must not be copied,
                                            reproduced, manipulated, altered or
                                            used for unauthorized purposes.
                                        </p>

                                        <p class="mt-3 text-gray-600">
                                            Silver Spoon may deactivate or
                                            replace a QR code or identification
                                            card where reasonably necessary for
                                            security, fraud prevention,
                                            administrative or operational
                                            purposes.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- 12.4 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.4
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            Lost, Stolen or Damaged Cards
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            A card holder must notify Silver
                                            Spoon as soon as reasonably possible
                                            if their identification card is:
                                        </p>

                                        <ul class="mt-4 space-y-2 text-gray-600 list-disc pl-5">

                                            <li>Lost.</li>
                                            <li>Stolen.</li>
                                            <li>Damaged.</li>
                                            <li>Destroyed.</li>
                                            <li>Rendered unreadable or unusable.</li>
                                            <li>
                                                Suspected to have been copied
                                                or misused.
                                            </li>

                                        </ul>

                                        <p class="mt-4 text-gray-600">
                                            For security purposes, Silver Spoon
                                            may deactivate a card or its
                                            associated QR code after receiving
                                            a report of loss, theft or suspected
                                            misuse.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- 12.5 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.5
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            Replacement Cards
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            Where a card is lost, stolen,
                                            damaged, destroyed or otherwise
                                            requires replacement due to the
                                            actions, negligence or circumstances
                                            attributable to the holder, Silver
                                            Spoon may charge a reasonable
                                            replacement fee.
                                        </p>

                                        <div
                                            class="mt-5 rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                            <div class="flex gap-3">

                                                <svg
                                                    class="w-5 h-5 shrink-0 text-[#8b7d68] mt-0.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.6"
                                                        d="M12 9v4m0 4h.01M10.3 3.5h3.4L21 17.2a1.5 1.5 0 01-1.3 2.3H4.3A1.5 1.5 0 013 17.2L10.3 3.5z"/>

                                                </svg>

                                                <p class="text-sm text-gray-600 leading-6">
                                                    Any applicable replacement
                                                    fee will be communicated to
                                                    the card holder before the
                                                    replacement card is issued.
                                                </p>

                                            </div>

                                        </div>

                                        <p class="mt-4 text-gray-600">
                                            Silver Spoon may waive or reduce a
                                            replacement fee at its discretion
                                            where circumstances reasonably
                                            justify doing so.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- 12.6 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.6
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            Prohibited Use
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            A Silver Spoon identification card
                                            is personal to the individual to
                                            whom it is issued and must not be:
                                        </p>

                                        <ul class="mt-4 space-y-2 text-gray-600 list-disc pl-5">

                                            <li>
                                                Lent or transferred to another
                                                person.
                                            </li>

                                            <li>
                                                Sold or exchanged.
                                            </li>

                                            <li>
                                                Altered or duplicated.
                                            </li>

                                            <li>
                                                Used to impersonate another
                                                person.
                                            </li>

                                            <li>
                                                Used to obtain unauthorized
                                                access or benefits.
                                            </li>

                                            <li>
                                                Used for any unlawful or
                                                fraudulent purpose.
                                            </li>

                                        </ul>

                                        <p class="mt-4 text-gray-600">
                                            Misuse of an identification card or
                                            QR code may result in deactivation
                                            of the card, suspension or
                                            termination of the relevant account
                                            or authorization, and any other
                                            action available to Silver Spoon
                                            under these Terms or applicable law.
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <!-- 12.7 -->

                            <div class="mt-8">

                                <div class="flex gap-3">

                                    <span
                                        class="shrink-0 w-7 h-7 rounded-lg bg-[#faf9f6] border border-[#eeeae1] flex items-center justify-center text-[10px] font-bold text-[#8b7d68]">
                                        12.7
                                    </span>

                                    <div>

                                        <h3 class="font-semibold text-[#211e19]">
                                            Return of Cards
                                        </h3>

                                        <p class="mt-3 text-gray-600">
                                            Silver Spoon may require an
                                            identification card to be returned
                                            at any time where reasonably
                                            necessary.
                                        </p>

                                        <p class="mt-3 text-gray-600">
                                            Upon termination of a user's
                                            membership, employment, partnership,
                                            authorization or other relationship
                                            with Silver Spoon, the card holder
                                            may be required to return the
                                            identification card to Silver Spoon.
                                        </p>

                                        <p class="mt-3 text-gray-600">
                                            Failure to return company property
                                            when requested may result in
                                            appropriate action in accordance
                                            with these Terms and applicable law.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- FINAL ACKNOWLEDGEMENT -->

                <div class="p-7 sm:p-10 lg:p-14 pt-2">

                    <div
                        class="rounded-[1.5rem] bg-[#211e19] text-white p-6 sm:p-8">

                        <div class="flex flex-col sm:flex-row sm:items-center gap-5">

                            <div
                                class="w-12 h-12 shrink-0 rounded-xl bg-white/10 flex items-center justify-center">

                                <svg
                                    class="w-5 h-5 text-[#d7c6a8]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.6"
                                        d="M9 12l2 2 4-4m5.5 2a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>

                                </svg>

                            </div>


                            <div>

                                <p class="font-semibold">
                                    Thank you for choosing Silver Spoon.
                                </p>

                                <p class="mt-1 text-sm text-white/50">
                                    We're here to make better meals easier.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </article>

    </div>

</main>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="border-t border-black/5 bg-white/50">

    <div class="max-w-7xl mx-auto px-5 sm:px-8 py-8">

        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-4">

            <p class="text-xs text-gray-400">
                © {{ date('Y') }} Silver Spoon. All rights reserved.
            </p>


            <div class="flex items-center gap-5">

                <a
                    href="{{ route('terms') }}"
                    class="text-xs font-semibold text-[#211e19]">
                    Terms
                </a>

                <a
                    href="/privacy"
                    class="text-xs text-gray-400 hover:text-[#211e19] transition">
                    Privacy
                </a>

                <a
                    href="/"
                    class="text-xs text-gray-400 hover:text-[#211e19] transition">
                    Home
                </a>

            </div>

        </div>

    </div>

</footer>


</body>
</html>