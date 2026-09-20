<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Privacy Policy — Silver Spoon</title>

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
                    Privacy
                </span>

            </div>


            <h1
                class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[1.02] tracking-tight">

                Your privacy,

                <span class="italic text-[#8b7d68]">
                    handled carefully.
                </span>

            </h1>


            <p
                class="mt-7 max-w-2xl text-base sm:text-lg text-gray-500 leading-8 fade-up-delay">

                This Privacy Policy explains what information Silver Spoon
                collects, how we use it, how we protect it and the choices
                available to you when you use our platform and services.

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

                        <path
                            d="M12 3l7 4v5c0 4.8-3 7.8-7 9-4-1.2-7-4.2-7-9V7l7-4z"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linejoin="round"/>

                    </svg>

                    <span class="text-xs font-semibold text-gray-600">
                        Privacy matters
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CONTENT
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

                    <a href="#introduction"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        01 &nbsp; Introduction
                    </a>

                    <a href="#information"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        02 &nbsp; Information We Collect
                    </a>

                    <a href="#how-we-use"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        03 &nbsp; How We Use Information
                    </a>

                    <a href="#accounts"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        04 &nbsp; Accounts
                    </a>

                    <a href="#id-cards"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        05 &nbsp; ID Cards & QR Codes
                    </a>

                    <a href="#payments"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        06 &nbsp; Payments
                    </a>

                    <a href="#cookies"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        07 &nbsp; Cookies
                    </a>

                    <a href="#sharing"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        08 &nbsp; Information Sharing
                    </a>

                    <a href="#retention"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        09 &nbsp; Data Retention
                    </a>

                    <a href="#security"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        10 &nbsp; Data Security
                    </a>

                    <a href="#rights"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        11 &nbsp; Your Choices & Rights
                    </a>

                    <a href="#changes"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        12 &nbsp; Policy Changes
                    </a>

                    <a href="#contact"
                       class="toc-link block py-2 text-xs text-gray-500 hover:text-[#211e19]">
                        13 &nbsp; Contact
                    </a>

                </nav>

            </div>

        </aside>


        <!-- =================================================
             PRIVACY DOCUMENT
        ================================================== -->

        <article class="legal-content">

            <div
                class="bg-white rounded-[2rem] border border-black/5 shadow-card overflow-hidden">


                <!-- =================================================
                     INTRODUCTION
                ================================================== -->

                <section id="introduction" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            01
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Introduction
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon respects your privacy and is
                                committed to handling your personal information
                                responsibly.
                            </p>

                            <p class="mt-4 text-gray-600">
                                This Privacy Policy explains how information
                                relating to you may be collected, used,
                                stored, disclosed and protected when you
                                access or use Silver Spoon's website,
                                platform, meal-planning services and related
                                products and services.
                            </p>

                            <p class="mt-4 text-gray-600">
                                By using Silver Spoon, you acknowledge that you
                                have read and understood this Privacy Policy.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     INFORMATION WE COLLECT
                ================================================== -->

                <section id="information" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            02
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Information We Collect
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Depending on how you interact with Silver Spoon,
                                we may collect different categories of
                                information.
                            </p>


                            <div class="mt-7 grid sm:grid-cols-2 gap-4">

                                <div
                                    class="rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                    <h3 class="font-semibold">
                                        Account Information
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-500 leading-6">
                                        Information such as your name, email
                                        address, phone number and other details
                                        you provide when creating or managing
                                        your account.
                                    </p>

                                </div>


                                <div
                                    class="rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                    <h3 class="font-semibold">
                                        Usage Information
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-500 leading-6">
                                        Information about how you interact
                                        with our website, platform, features
                                        and services.
                                    </p>

                                </div>


                                <div
                                    class="rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                    <h3 class="font-semibold">
                                        Transaction Information
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-500 leading-6">
                                        Information associated with purchases,
                                        meal plans, subscriptions or other
                                        transactions made through our services.
                                    </p>

                                </div>


                                <div
                                    class="rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                    <h3 class="font-semibold">
                                        Device & Technical Data
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-500 leading-6">
                                        Technical information such as browser
                                        type, device information, IP address,
                                        operating system and related usage data.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     HOW WE USE INFORMATION
                ================================================== -->

                <section id="how-we-use" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            03
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                How We Use Your Information
                            </h2>

                            <p class="mt-5 text-gray-600">
                                We may use information we collect for purposes
                                including:
                            </p>

                            <ul class="mt-5 space-y-3 text-gray-600 list-disc pl-5">

                                <li>
                                    Creating and managing your account.
                                </li>

                                <li>
                                    Providing and improving Silver Spoon's
                                    services.
                                </li>

                                <li>
                                    Processing purchases and transactions.
                                </li>

                                <li>
                                    Providing meal plans, recommendations and
                                    related services.
                                </li>

                                <li>
                                    Communicating with you about your account,
                                    transactions, services or support requests.
                                </li>

                                <li>
                                    Maintaining the security and integrity of
                                    our platform.
                                </li>

                                <li>
                                    Detecting, preventing and investigating
                                    fraud, misuse or unauthorized activity.
                                </li>

                                <li>
                                    Complying with applicable legal or regulatory
                                    requirements.
                                </li>

                            </ul>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     ACCOUNTS
                ================================================== -->

                <section id="accounts" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            04
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Account Information
                            </h2>

                            <p class="mt-5 text-gray-600">
                                When you create an account, you are responsible
                                for providing information that is accurate and
                                reasonably up to date.
                            </p>

                            <p class="mt-4 text-gray-600">
                                You should also take reasonable steps to protect
                                your password and other account credentials.
                                Silver Spoon will not ask you to disclose your
                                password through unsolicited communications.
                            </p>

                            <div
                                class="mt-6 rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                <div class="flex gap-3">

                                    <svg
                                        class="w-5 h-5 shrink-0 text-[#8b7d68]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            d="M12 15a3 3 0 100-6 3 3 0 000 6z"
                                            stroke="currentColor"
                                            stroke-width="1.6"/>

                                        <path
                                            d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06-1.8 1.8-.06-.06a1.7 1.7 0 00-1.88-.34 1.7 1.7 0 00-1.03 1.56V20h-2.55v-.1a1.7 1.7 0 00-1.03-1.56 1.7 1.7 0 00-1.88.34l-.06.06-1.8-1.8.06-.06A1.7 1.7 0 008.1 15a1.7 1.7 0 00-1.56-1.03H6v-2.54h.1A1.7 1.7 0 007.66 10a1.7 1.7 0 00-.34-1.88l-.06-.06 1.8-1.8.06.06A1.7 1.7 0 0011 6a1.7 1.7 0 001.03-1.56V4h2.55v.1A1.7 1.7 0 0015.6 6a1.7 1.7 0 001.88.34l.06-.06 1.8 1.8-.06.06A1.7 1.7 0 0019 10a1.7 1.7 0 001.56 1.03h.1v2.54h-.1A1.7 1.7 0 0019.4 15z"
                                            stroke="currentColor"
                                            stroke-width="1.2"
                                            stroke-linejoin="round"/>

                                    </svg>

                                    <p class="text-sm text-gray-600 leading-6">
                                        If you believe your account has been
                                        accessed without authorization, please
                                        contact Silver Spoon as soon as possible.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     ID CARDS
                ================================================== -->

                <section id="id-cards" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            05
                        </span>

                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="font-display text-2xl sm:text-3xl">
                                    Identification Cards & QR Codes
                                </h2>

                                <span
                                    class="inline-flex items-center rounded-full bg-[#f3f0e9] px-3 py-1 text-[10px] uppercase tracking-[.15em] font-bold text-[#8b7d68]">
                                    Security
                                </span>

                            </div>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon may issue identification cards
                                containing information and/or QR codes
                                associated with a user, member, employee,
                                partner or other authorized individual.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Information associated with an identification
                                card or QR code may be used for identity
                                verification, membership verification,
                                access control, operational purposes,
                                security and fraud prevention.
                            </p>

                            <p class="mt-4 text-gray-600">
                                QR codes should not be copied, altered,
                                reproduced or used for unauthorized purposes.
                                Silver Spoon may deactivate an identification
                                card or QR code where reasonably necessary
                                for security or operational reasons.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     PAYMENTS
                ================================================== -->

                <section id="payments" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            06
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Payments & Transactions
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Where payments are made through Silver Spoon,
                                transaction-related information may be
                                processed to complete purchases, subscriptions
                                or other services.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Payments may be processed through third-party
                                payment providers. Payment providers may
                                process information in accordance with their
                                own privacy policies and terms.
                            </p>

                            <div
                                class="mt-6 rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                <p class="text-sm text-gray-600 leading-6">
                                    Silver Spoon does not need to receive or
                                    store your full payment card credentials
                                    where payment processing is handled
                                    directly by an authorized payment provider.
                                </p>

                            </div>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     COOKIES
                ================================================== -->

                <section id="cookies" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            07
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Cookies & Similar Technologies
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon may use cookies and similar
                                technologies to help operate the platform,
                                remember preferences, understand usage and
                                improve the user experience.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Some cookies may be essential for features such
                                as authentication, account sessions and security.
                            </p>

                            <p class="mt-4 text-gray-600">
                                Depending on the technologies used on the
                                platform, you may be able to manage certain
                                cookie preferences through your browser or
                                device settings.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     SHARING
                ================================================== -->

                <section id="sharing" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            08
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                When We Share Information
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon does not treat your personal
                                information as something to be freely sold or
                                distributed. We may share information where
                                reasonably necessary to operate our services.
                            </p>

                            <p class="mt-5 text-gray-600">
                                This may include sharing information with:
                            </p>

                            <ul class="mt-5 space-y-3 text-gray-600 list-disc pl-5">

                                <li>
                                    Payment and transaction service providers.
                                </li>

                                <li>
                                    Technology, hosting and infrastructure
                                    providers.
                                </li>

                                <li>
                                    Service providers supporting the operation
                                    of Silver Spoon.
                                </li>

                                <li>
                                    Professional advisers where reasonably
                                    necessary.
                                </li>

                                <li>
                                    Authorities or other parties where
                                    disclosure is required or permitted by law.
                                </li>

                            </ul>

                            <p class="mt-5 text-gray-600">
                                Where third parties process information on our
                                behalf, we aim to use appropriate contractual
                                and operational safeguards.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     RETENTION
                ================================================== -->

                <section id="retention" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            09
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Data Retention
                            </h2>

                            <p class="mt-5 text-gray-600">
                                We retain personal information for as long as
                                reasonably necessary for the purposes described
                                in this Privacy Policy, including to provide
                                services, maintain records, resolve disputes,
                                enforce agreements and meet applicable legal
                                or regulatory requirements.
                            </p>

                            <p class="mt-4 text-gray-600">
                                When information is no longer reasonably
                                required, we may delete, anonymize or otherwise
                                securely dispose of it, subject to applicable
                                obligations.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     SECURITY
                ================================================== -->

                <section id="security" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            10
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Data Security
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Silver Spoon takes reasonable technical,
                                administrative and organizational measures
                                designed to protect personal information
                                against unauthorized access, misuse,
                                alteration, disclosure or destruction.
                            </p>

                            <p class="mt-4 text-gray-600">
                                However, no internet-based service or method
                                of electronic storage can be guaranteed to
                                be completely secure.
                            </p>

                            <div
                                class="mt-6 rounded-2xl bg-[#211e19] text-white p-6">

                                <div class="flex gap-4">

                                    <div
                                        class="w-10 h-10 shrink-0 rounded-xl bg-white/10 flex items-center justify-center">

                                        <svg
                                            class="w-5 h-5 text-[#d7c6a8]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                d="M12 3l7 4v5c0 4.8-3 7.8-7 9-4-1.2-7-4.2-7-9V7l7-4z"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                                stroke-linejoin="round"/>

                                            <path
                                                d="M9 12l2 2 4-4"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"/>

                                        </svg>

                                    </div>

                                    <div>

                                        <p class="font-semibold">
                                            Security is a shared responsibility.
                                        </p>

                                        <p class="mt-1 text-sm text-white/55 leading-6">
                                            Please protect your password,
                                            device and identification card,
                                            and notify us promptly if you
                                            suspect unauthorized access.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     RIGHTS
                ================================================== -->

                <section id="rights" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            11
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Your Choices & Privacy Rights
                            </h2>

                            <p class="mt-5 text-gray-600">
                                Depending on applicable law and the nature of
                                the information involved, you may have rights
                                relating to your personal information.
                            </p>

                            <ul class="mt-5 space-y-3 text-gray-600 list-disc pl-5">

                                <li>
                                    Request access to personal information we
                                    hold about you.
                                </li>

                                <li>
                                    Request correction of inaccurate or
                                    incomplete information.
                                </li>

                                <li>
                                    Request deletion of information where
                                    applicable.
                                </li>

                                <li>
                                    Object to or request restriction of certain
                                    processing where applicable.
                                </li>

                                <li>
                                    Withdraw consent where processing is based
                                    on consent, subject to applicable
                                    limitations.
                                </li>

                            </ul>

                            <p class="mt-5 text-gray-600">
                                To exercise a privacy-related request, please
                                contact Silver Spoon using the contact
                                information provided on our platform.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     POLICY CHANGES
                ================================================== -->

                <section id="changes" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            12
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Changes to This Privacy Policy
                            </h2>

                            <p class="mt-5 text-gray-600">
                                We may update this Privacy Policy from time to
                                time to reflect changes in our services,
                                technology, business operations or applicable
                                requirements.
                            </p>

                            <p class="mt-4 text-gray-600">
                                The updated version will be published on this
                                page with a revised effective date where
                                appropriate.
                            </p>

                            <p class="mt-4 text-gray-600">
                                We encourage you to review this page periodically
                                so that you remain informed about how Silver
                                Spoon handles personal information.
                            </p>

                        </div>

                    </div>

                </section>


                <div class="mx-7 sm:mx-10 lg:mx-14 border-t border-gray-100"></div>


                <!-- =================================================
                     CONTACT
                ================================================== -->

                <section id="contact" class="p-7 sm:p-10 lg:p-14">

                    <div class="flex gap-5">

                        <span
                            class="shrink-0 text-xs font-bold text-[#8b7d68] pt-1">
                            13
                        </span>

                        <div>

                            <h2 class="font-display text-2xl sm:text-3xl">
                                Contact Us
                            </h2>

                            <p class="mt-5 text-gray-600">
                                If you have questions about this Privacy Policy,
                                want to make a privacy-related request or have
                                concerns about how your information is handled,
                                please contact Silver Spoon through the contact
                                information provided on our platform.
                            </p>

                            <div
                                class="mt-6 rounded-2xl bg-[#faf9f6] border border-[#eeeae1] p-5">

                                <p class="text-xs uppercase tracking-[.18em] font-bold text-[#8b7d68]">
                                    Privacy enquiries
                                </p>

                                <p class="mt-2 text-sm text-gray-600">
                                    Please include enough information for us to
                                    understand and respond to your request.
                                </p>

                            </div>

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


                <!-- =================================================
                     FINAL MESSAGE
                ================================================== -->

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
                                        d="M12 3l7 4v5c0 4.8-3 7.8-7 9-4-1.2-7-4.2-7-9V7l7-4z"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                        stroke-linejoin="round"/>

                                </svg>

                            </div>


                            <div>

                                <p class="font-semibold">
                                    Your trust matters to us.
                                </p>

                                <p class="mt-1 text-sm text-white/50">
                                    Silver Spoon is committed to treating your
                                    information with care and responsibility.
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
                    class="text-xs text-gray-400 hover:text-[#211e19] transition">
                    Terms
                </a>

                <a
                    href="{{ route('privacy') }}"
                    class="text-xs font-semibold text-[#211e19]">
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