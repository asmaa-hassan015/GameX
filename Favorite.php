<?php
session_start();
?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - My Wishlist</title>

    <!-- ======================== Tailwind ======================== -->

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ======================== Tailwind Config ======================== -->

    <script>
    tailwind.config = {

        theme: {

            extend: {

                colors: {

                    gx: {

                        bg: '#03040b',

                        panel: '#090b16',

                        line: '#24213a',

                        purple: '#7c2cff',

                        neon: '#a855f7'

                    }

                },

                fontFamily: {

                    rajdhani: ['Rajdhani', 'sans-serif'],

                    poppins: ['Poppins', 'sans-serif']

                },

                boxShadow: {

                    neon: '0 0 15px rgba(124,44,255,.35)'

                }

            }

        }

    };
    </script>

    <!-- ======================== Fonts ======================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- ======================== Font Awesome ======================== -->

    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- ======================== Custom CSS ======================== -->

    <link rel="stylesheet" href="src/output.css">

    <!-- ======================== Page Style ======================== -->

    <style>
    body {

        font-family: Poppins, sans-serif;

        background: #03040b;

        color: #fff;

    }

    .heading {

        font-family: Rajdhani, sans-serif;

    }

    .muted {

        color: #9b9aad;

    }

    .glass {

        background: linear-gradient(145deg,
                rgba(13, 16, 32, .94),
                rgba(5, 6, 14, .98));

        border: 1px solid #24213a;

    }

    .neon {

        border: 1px solid rgba(124, 44, 255, .65);

        box-shadow: 0 0 15px rgba(124, 44, 255, .2);

    }

    .purple {

        background: linear-gradient(135deg,
                #7c2cff,
                #a855f7);

    }
    </style>

</head>

<body class="font-poppins bg-[#03040b] text-white">

    <!-- =========================================================
         NAVBAR
    ========================================================= -->

    <?php include 'components/nav.php'; ?>

    <!-- =========================================================
         MAIN PAGE
    ========================================================= -->

    <div class="min-h-screen max-w-[1450px] mx-auto p-4 md:p-7">

        <!-- =====================================================
             WISHLIST HEADER
        ===================================================== -->

        <section class="py-9 border-b border-[#24213a] flex flex-col md:flex-row justify-between gap-5">

            <div>

                <h1 class="heading text-5xl font-bold">

                    My Wishlist

                    (<span id="wishlist-count">0</span>)

                </h1>

                <p class="muted mt-4 max-w-xl">

                    Save your favorite games and easily add them
                    to your cart.

                </p>

            </div>

            <a href="#" id="move-all-cart" class="text-[#a855f7] font-semibold text-sm hover:underline self-start">

                Move All to Cart →

            </a>

        </section>

        <!-- =====================================================
             WISHLIST ITEMS
        ===================================================== -->

        <section class="py-10">

            <div id="wishlist-container" class="flex flex-col gap-3">

                <p class="muted text-center py-10">
                    Loading...
                </p>

            </div>

        </section>

        <!-- =====================================================
             CONTACT US
        ===================================================== -->

        <br>
        <br>

        <hr class="border-[#24213a]">

        <br>

        <section id="contactus" class="mb-10 mt-5">

            <div class="text-center">

                <span class="text-[#A726DD] font-semibold tracking-wide">

                    CONTACT US

                </span>

                <h1 class="heading text-4xl md:text-5xl font-bold mt-2">

                    We're Here to

                    <span class="text-[#A726DD]">
                        Help!
                    </span>

                </h1>

                <p class="muted text-lg mt-3">

                    Have a question, issue, or feedback?
                    Our team is ready to assist you.

                </p>

            </div>

        </section>

        <!-- =====================================================
             CONTACT CONTENT
        ===================================================== -->

        <div class="flex flex-col lg:flex-row gap-6 mt-10">

            <!-- =================================================
                 CONTACT INFO
            ================================================= -->

            <div class="flex-1 flex flex-col gap-4">

                <!-- Email -->

                <div class="glass rounded-xl p-5 flex items-center gap-4">

                    <div class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

                        <i class="fa-regular fa-envelope text-xl text-[#A726DD]">
                        </i>

                    </div>

                    <div>

                        <h3 class="font-bold text-lg">
                            Email Us
                        </h3>

                        <a href="mailto:support@gamex.com" class="text-[#A726DD] hover:text-[#c96bf0] duration-300">

                            support@gamex.com

                        </a>

                        <p class="muted text-sm">

                            We usually reply within 24 hours.

                        </p>

                    </div>

                </div>

                <!-- Live Chat -->

                <div class="glass rounded-xl p-5 flex items-center justify-between gap-4">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

                            <i class="fa-solid fa-headset text-xl text-[#A726DD]">
                            </i>

                        </div>

                        <div>

                            <h3 class="font-bold text-lg flex items-center gap-2">

                                Live Chat

                                <span class="flex items-center gap-1 text-sm text-green-400 font-normal">

                                    <span class="w-2 h-2 rounded-full bg-green-400">
                                    </span>

                                    Online

                                </span>

                            </h3>

                            <p class="muted text-sm">

                                Chat with our support team
                                in real-time.

                            </p>

                        </div>

                    </div>

                    <button
                        class="border border-[#A726DD] text-[#A726DD] hover:bg-[#A726DD] hover:text-white duration-300 px-4 py-2 rounded-lg font-semibold whitespace-nowrap">

                        Start Chat

                    </button>

                </div>

                <!-- Call Us -->

                <div class="glass rounded-xl p-5 flex items-center gap-4">

                    <div class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

                        <i class="fa-solid fa-phone text-xl text-[#A726DD]">
                        </i>

                    </div>

                    <div>

                        <h3 class="font-bold text-lg">
                            Call Us
                        </h3>

                        <a href="tel:+201234567890" class="text-[#A726DD] hover:text-[#c96bf0] duration-300">

                            +20 123 456 7890

                        </a>

                        <p class="muted text-sm">

                            Available daily from
                            10AM - 10PM (GMT+2)

                        </p>

                    </div>

                </div>

                <!-- Office -->

                <div class="glass rounded-xl p-5 flex items-center gap-4">

                    <div class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

                        <i class="fa-solid fa-location-dot text-xl text-[#A726DD]">
                        </i>

                    </div>

                    <div>

                        <h3 class="font-bold text-lg">
                            Our Office
                        </h3>

                        <p class="text-[#A726DD]">
                            GameX Headquarters
                        </p>

                        <p class="muted text-sm">
                            123 Gaming Street, Level 5
                        </p>

                        <p class="muted text-sm">
                            Cairo, Egypt
                        </p>

                    </div>

                </div>

            </div>

            <!-- =================================================
                 CONTACT FORM
            ================================================= -->

            <div class="flex-1 glass rounded-xl p-6">

                <h3 class="font-bold text-xl">
                    Send Us a Message
                </h3>

                <p class="muted text-sm mt-1 mb-5">

                    Fill out the form below and
                    we'll get back to you.

                </p>

                <!-- Alert -->

                <div id="formAlert" class="hidden">
                </div>

                <form id="contactForm" class="flex flex-col gap-4" novalidate>

                    <!-- Name + Email -->

                    <div class="flex flex-col md:flex-row gap-4">

                        <!-- Name -->

                        <div
                            class="flex-1 flex items-center gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                            <i class="fa-regular fa-user text-gray-400">
                            </i>

                            <input type="text" id="name" name="name" placeholder="Your Name"
                                class="bg-transparent outline-none w-full text-white placeholder-gray-500">

                        </div>

                        <!-- Email -->

                        <div
                            class="flex-1 flex items-center gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                            <i class="fa-regular fa-envelope text-gray-400">
                            </i>

                            <input type="email" id="email" name="email" placeholder="Your Email"
                                class="bg-transparent outline-none w-full text-white placeholder-gray-500">

                        </div>

                    </div>

                    <!-- Subject -->

                    <div class="flex items-center gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                        <i class="fa-solid fa-tag text-gray-400">
                        </i>

                        <select id="subject" name="subject" class="bg-transparent outline-none w-full text-gray-400">

                            <option class="bg-[#15121f]">
                                Subject
                            </option>

                            <option class="bg-[#15121f]">
                                Order Issue
                            </option>

                            <option class="bg-[#15121f]">
                                Refund Request
                            </option>

                            <option class="bg-[#15121f]">
                                Bug Report
                            </option>

                            <option class="bg-[#15121f]">
                                Other
                            </option>

                        </select>

                    </div>

                    <!-- Message -->

                    <div class="flex items-start gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                        <i class="fa-solid fa-pen text-gray-400 mt-1">
                        </i>

                        <textarea rows="4" id="message" name="message" placeholder="How can we help you?"
                            class="bg-transparent outline-none w-full text-white placeholder-gray-500 resize-none"></textarea>

                    </div>

                    <!-- Submit -->

                    <button type="submit" id="submitBtn"
                        class="purple rounded-xl py-3 font-semibold flex items-center justify-center gap-2 shadow-neon">

                        <i class="fa-solid fa-paper-plane"></i>

                        Send Message

                    </button>

                    <p class="text-center muted text-sm">

                        By sending this message,
                        you agree to our

                        <a href="#" class="text-[#A726DD] hover:text-[#c96bf0] duration-300">

                            Privacy Policy

                        </a>.

                    </p>

                </form>

            </div>

        </div>

        <!-- =====================================================
             FAQ
        ===================================================== -->

        <div id="faq-section" class="glass rounded-xl p-6 mt-10">

            <div class="flex justify-between items-center mb-5">

                <h3 class="font-bold text-xl">
                    Frequently Asked Questions
                </h3>

                <a href="#"
                    class="text-[#A726DD] hover:text-[#c96bf0] duration-300 font-semibold flex items-center gap-1">

                    View All FAQs

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- FAQ 1 -->

                <div>

                    <button
                        class="faq-btn w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                        <span class="flex items-center gap-3">

                            <i class="fa-regular fa-circle-question text-[#A726DD]">
                            </i>

                            How do I reset my password?

                        </span>

                        <i class="fa-solid fa-chevron-down text-gray-400 duration-300">
                        </i>

                    </button>

                    <div class="faq-answer overflow-hidden duration-300" style="max-height: 0">

                        <p class="muted text-sm px-4 py-3">

                            Go to Settings > Account >
                            Reset Password, and follow the
                            instructions sent to your email.

                        </p>

                    </div>

                </div>

                <!-- FAQ 2 -->

                <div>

                    <button
                        class="faq-btn w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                        <span class="flex items-center gap-3">

                            <i class="fa-regular fa-circle-question text-[#A726DD]">
                            </i>

                            How can I request a refund?

                        </span>

                        <i class="fa-solid fa-chevron-down text-gray-400">
                        </i>

                    </button>

                    <div class="faq-answer overflow-hidden" style="max-height: 0">

                        <p class="muted text-sm px-4 py-3">

                            Contact our support team within
                            14 days of purchase with your
                            order number.

                        </p>

                    </div>

                </div>

                <!-- FAQ 3 -->

                <div>

                    <button
                        class="faq-btn w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                        <span class="flex items-center gap-3">

                            <i class="fa-regular fa-circle-question text-[#A726DD]">
                            </i>

                            What payment methods do you accept?

                        </span>

                        <i class="fa-solid fa-chevron-down text-gray-400">
                        </i>

                    </button>

                    <div class="faq-answer overflow-hidden" style="max-height: 0">

                        <p class="muted text-sm px-4 py-3">

                            We accept Visa, Mastercard,
                            PayPal, and Fawry.

                        </p>

                    </div>

                </div>

                <!-- FAQ 4 -->

                <div>

                    <button
                        class="faq-btn w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                        <span class="flex items-center gap-3">

                            <i class="fa-regular fa-circle-question text-[#A726DD]">
                            </i>

                            How do I report a bug or issue?

                        </span>

                        <i class="fa-solid fa-chevron-down text-gray-400">
                        </i>

                    </button>

                    <div class="faq-answer overflow-hidden" style="max-height: 0">

                        <p class="muted text-sm px-4 py-3">

                            Use the "Report a Bug" option
                            in your account settings, or
                            email support@gamex.com.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================================================
         JAVASCRIPT
    ========================================================= -->

    <script src="./js/contact-form.js"></script>

    <script src="./js/favorites.js"></script>

    <script src="./js/cart.js"></script>

</body>

</html>