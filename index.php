<?php

session_start();

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Game X</title>

    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" />

    <link rel="stylesheet" href="src/output.css" />
</head>

<body>

    <div class="bg-[#000205] text-white min-h-screen">

        <!-- Main Container -->
        <div class="w-full max-w-[1920px] m-auto bg-[#070612] border-solid border-[#4d1d9577] rounded-xl">

            <!-- Navbar -->
            <?php include 'components/nav.php'; ?>


            <!-- =========================================================
           PAGE CONTENT
      ========================================================== -->

            <div class="px-4 sm:px-6 lg:px-10">


                <!-- =========================================================
             Hero Section
        ========================================================== -->

                <section>

                    <div class="h-140 bg-[url(Images/backgroundHome.png)] bg-cover bg-top-right flex items-end">

                        <div class="w-1/2 p-8">

                            <p class="text-7xl font-bold">
                                LEVEL UP <br />
                                <span class="text-[#5101A4]">YOUR GAME</span>
                            </p>

                            <p class="text-gray-400 text-2xl font-ligh my-8">
                                Discover , buy and enjoy your <br />
                                favorite games.
                            </p>

                            <div>

                                <a id="viewDetails"
                                    class="m-2 bg-[#5101A4] p-3 rounded-xl font-bold hover:translate-0.5 duration-300 hover:cursor-pointer inline-block"
                                    href="game-details.php">

                                    View Details
                                    <i class="fa-solid fa-angle-right"></i>

                                </a>

                                <a href="Games.php" id="Explore"
                                    class="m-2 font-bold border border-[#4C1D95] p-3 rounded-xl hover:bg-[#4d1d9558] hover:cursor-pointer hover:translate-0.5 duration-300 inline-block">

                                    Explore Games
                                    <i class="fa-solid fa-angle-right"></i>

                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- =====================================================
               Best Sellers
          ====================================================== -->

                    <div class="pt-15">

                        <!-- Header -->
                        <div class="flex justify-between items-center text-xl">

                            <span>
                                🔥 Best Sellers
                            </span>

                            <a href="Games.php" id="ViewMore"
                                class="text-[#A527C8] hover:translate-x-0.5 duration-300 hover:cursor-pointer flex items-center gap-2">

                                View More

                                <!-- Arrow Circle -->
                                <span
                                    class="w-9 h-9 rounded-full border border-[#A527C8] flex items-center justify-center">

                                    <i class="fa-solid fa-arrow-right text-sm"></i>

                                </span>

                            </a>

                        </div>


                        <!-- Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 pt-12">


                            <!-- Card 1 -->
                            <div
                                class="border-2 border-[#26223D] rounded-xl overflow-hidden hover:scale-105 duration-300 group">

                                <img src="src/Images/gamepic.png" width="100%" alt="God of War" />

                                <div class="p-3">

                                    <span class="text-3xl font-bold group-hover:text-[#5101A4] duration-300">

                                        God of war ragnarok

                                    </span>

                                    <div class="flex justify-between pt-3 text-xl font-light">

                                        <span>
                                            $49.99
                                        </span>

                                        <span>
                                            ⭐4.9
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- Card 2 -->
                            <div
                                class="border-2 border-[#26223D] rounded-xl overflow-hidden hover:scale-105 duration-300 group">

                                <img src="src/Images/gamepic.png" width="100%" alt="God of War" />

                                <div class="p-3">

                                    <span class="text-3xl font-bold group-hover:text-[#5101A4] duration-300">

                                        God of war ragnarok

                                    </span>

                                    <div class="flex justify-between pt-3 text-xl font-light">

                                        <span>
                                            $49.99
                                        </span>

                                        <span>
                                            ⭐4.9
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- Card 3 -->
                            <div
                                class="border-2 border-[#26223D] rounded-xl overflow-hidden hover:scale-105 duration-300 group">

                                <img src="src/Images/gamepic.png" width="100%" alt="God of War" />

                                <div class="p-3">

                                    <span class="text-3xl font-bold group-hover:text-[#5101A4] duration-300">

                                        God of war ragnarok

                                    </span>

                                    <div class="flex justify-between pt-3 text-xl font-light">

                                        <span>
                                            $49.99
                                        </span>

                                        <span>
                                            ⭐4.9
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- Card 4 -->
                            <div
                                class="border-2 border-[#26223D] rounded-xl overflow-hidden hover:scale-105 duration-300 group">

                                <img src="src/Images/gamepic.png" width="100%" alt="God of War" />

                                <div class="p-3">

                                    <span class="text-3xl font-bold group-hover:text-[#5101A4] duration-300">

                                        God of war ragnarok

                                    </span>

                                    <div class="flex justify-between pt-3 text-xl font-light">

                                        <span>
                                            $49.99
                                        </span>

                                        <span>
                                            ⭐4.9
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =====================================================
               Customer Reviews
          ====================================================== -->

                    <div class="pt-16">

                        <!-- Header -->
                        <div class="flex justify-between items-center mb-6">

                            <h4 class="text-2xl font-bold flex items-center gap-2">

                                <i class="fa-regular fa-comment-dots"></i>

                                Customer Reviews

                            </h4>


                            <!-- View All Reviews -->
                            <a id="AllReview" href="Review.php"
                                class="text-[#A726DD] font-semibold flex items-center gap-2 hover:translate-x-0.5 duration-300 hover:cursor-pointer">

                                View All Reviews

                                <!-- Arrow Circle -->
                                <span
                                    class="w-9 h-9 rounded-full border border-[#A726DD] flex items-center justify-center">

                                    <i class="fa-solid fa-arrow-right text-sm"></i>

                                </span>

                            </a>

                        </div>


                        <!-- Reviews Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                            <!-- Review Card 1 -->
                            <div class="border border-[#4d1d9577] rounded-xl p-4 bg-[#0f0d1a]">

                                <div class="flex justify-between items-start mb-2">

                                    <div class="flex items-center gap-3">

                                        <img src="src/Images/girl.png"
                                            class="w-14 h-14 rounded-full object-cover border-2 border-[#A726DD]"
                                            alt="Ahmed Mostafa" />

                                        <div>

                                            <div class="font-bold flex items-center gap-1">

                                                Ahmed Mostafa

                                                <i class="fa-solid fa-circle-check text-[#A726DD] text-sm">
                                                </i>

                                            </div>

                                            <div class="text-yellow-400 text-sm">

                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="text-right">

                                        <div class="text-[#A726DD] font-bold text-2xl">

                                            99

                                        </div>

                                        <span class="text-gray-400 text-sm">

                                            May 12, 2024

                                        </span>

                                    </div>

                                </div>


                                <p class="text-gray-400 mt-3 mb-4">

                                    Amazing experience! The best prices and super fast delivery.
                                    Highly recommended!

                                </p>


                                <a href="Review.php"
                                    class="inline-block border border-[#A726DD] text-white rounded-xl px-5 py-2 hover:bg-[#A726DD] duration-300">

                                    View Details

                                    <i class="fa-solid fa-chevron-right ml-1">
                                    </i>

                                </a>

                            </div>


                            <!-- Review Card 2 -->
                            <div class="border border-[#4d1d9577] rounded-xl p-4 bg-[#0f0d1a]">

                                <div class="flex justify-between items-start mb-2">

                                    <div class="flex items-center gap-3">

                                        <img src="src/Images/girl.png"
                                            class="w-14 h-14 rounded-full object-cover border-2 border-[#A726DD]"
                                            alt="Ahmed Mostafa" />

                                        <div>

                                            <div class="font-bold flex items-center gap-1">

                                                Ahmed Mostafa

                                                <i class="fa-solid fa-circle-check text-[#A726DD] text-sm">
                                                </i>

                                            </div>

                                            <div class="text-yellow-400 text-sm">

                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="text-right">

                                        <div class="text-[#A726DD] font-bold text-2xl">

                                            99

                                        </div>

                                        <span class="text-gray-400 text-sm">

                                            May 12, 2024

                                        </span>

                                    </div>

                                </div>


                                <p class="text-gray-400 mt-3 mb-4">

                                    Amazing experience! The best prices and super fast delivery.
                                    Highly recommended!

                                </p>


                                <a href="Review.php"
                                    class="inline-block border border-[#A726DD] text-white rounded-xl px-5 py-2 hover:bg-[#A726DD] duration-300">

                                    View Details

                                    <i class="fa-solid fa-chevron-right ml-1">
                                    </i>

                                </a>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- =====================================================
             Mega Deals Banner
        ====================================================== -->

                <section
                    class="p-20 relative w-full rounded-xl overflow-hidden border border-solid border-[#6D28D9] mt-5 md:mt-10">

                    <div class="absolute inset-0 bg-[url('Images/megaDealsBg.png')] bg-cover bg-center">
                    </div>

                    <div class="absolute inset-0 bg-linear-to-r from-black/70 via-black/40 to-transparent">
                    </div>

                    <div class="relative z-10 flex flex-col justify-center gap-3 p-8 md:p-10 min-h-55 md:min-h-65">

                        <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-wide">

                            MEGA DEALS

                        </h2>

                        <p class="text-lg md:text-xl text-gray-300">

                            Up to

                            <span class="text-[#A726DD] font-bold">

                                70% OFF

                            </span>

                            on selected games

                        </p>


                        <a href="Games.php"
                            class="w-fit mt-2 bg-[#5207A1] hover:bg-[#5107a18f] duration-300 text-white text-lg font-semibold px-6 py-3 rounded-xl flex items-center gap-2 hover:cursor-pointer">

                            Shop Now

                            <i class="fa-solid fa-chevron-right"></i>

                        </a>

                    </div>

                </section>


                <!-- =====================================================
             Contact Us
        ====================================================== -->

                <section id="contactus" class="mb-10 mt-5">

                    <div class="text-center">

                        <span class="text-[#A726DD] font-semibold tracking-wide">

                            CONTACT US

                        </span>

                        <h1 class="text-4xl md:text-5xl font-extrabold mt-2">

                            We're Here to

                            <span class="text-[#A726DD]">
                                Help!
                            </span>

                        </h1>

                        <p class="text-gray-400 text-lg mt-3">

                            Have a question, issue, or feedback?
                            Our team is ready to assist you.

                        </p>

                    </div>

                </section>


                <!-- Contact Content -->
                <div class="flex flex-col lg:flex-row gap-6 mt-10">


                    <!-- Contact Info -->
                    <div class="flex-1 flex flex-col gap-4">


                        <!-- Email Us -->
                        <div class="bg-[#0f0d1a] border border-[#26223D] rounded-xl p-5 flex items-center gap-4">

                            <div
                                class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

                                <i class="fa-regular fa-envelope text-xl text-[#A726DD]">
                                </i>

                            </div>

                            <div>

                                <h3 class="font-bold text-lg">
                                    Email Us
                                </h3>

                                <a href="mailto:support@gamex.com"
                                    class="text-[#A726DD] hover:text-[#c96bf0] duration-300">

                                    support@gamex.com

                                </a>

                                <p class="text-gray-400 text-sm">

                                    We usually reply within 24 hours.

                                </p>

                            </div>

                        </div>


                        <!-- Live Chat -->
                        <div
                            class="bg-[#0f0d1a] border border-[#26223D] rounded-xl p-5 flex items-center justify-between gap-4">

                            <div class="flex items-center gap-4">

                                <div
                                    class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

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

                                    <p class="text-gray-400 text-sm">

                                        Chat with our support team in real-time.

                                    </p>

                                </div>

                            </div>


                            <button
                                class="border border-[#A726DD] text-[#A726DD] hover:bg-[#A726DD] hover:text-white duration-300 px-4 py-2 rounded-lg font-semibold whitespace-nowrap">

                                Start Chat

                            </button>

                        </div>


                        <!-- Call Us -->
                        <div class="bg-[#0f0d1a] border border-[#26223D] rounded-xl p-5 flex items-center gap-4">

                            <div
                                class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

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

                                <p class="text-gray-400 text-sm">

                                    Available daily from 10AM - 10PM (GMT+2)

                                </p>

                            </div>

                        </div>


                        <!-- Our Office -->
                        <div class="bg-[#0f0d1a] border border-[#26223D] rounded-xl p-5 flex items-center gap-4">

                            <div
                                class="w-14 h-14 rounded-full bg-[#5207A1]/30 flex items-center justify-center shrink-0">

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

                                <p class="text-gray-400 text-sm">
                                    123 Gaming Street, Level 5
                                </p>

                                <p class="text-gray-400 text-sm">
                                    Cairo, Egypt
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Contact Form -->
                    <div class="flex-1 bg-[#0f0d1a] border border-[#26223D] rounded-xl p-6">

                        <h3 class="font-bold text-xl">
                            Send Us a Message
                        </h3>

                        <p class="text-gray-400 text-sm mt-1 mb-5">

                            Fill out the form below and we'll get back to you.

                        </p>


                        <div id="formAlert" class="hidden">
                        </div>


                        <form id="contactForm" class="flex flex-col gap-4" novalidate>


                            <div class="flex flex-col md:flex-row gap-4">

                                <div
                                    class="flex-1 field-wrapper flex items-center gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                                    <i class="fa-regular fa-user text-gray-400">
                                    </i>

                                    <input type="text" id="name" name="name" placeholder="Your Name"
                                        class="bg-transparent outline-none w-full text-white placeholder-gray-500" />

                                </div>


                                <div
                                    class="flex-1 field-wrapper flex items-center gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                                    <i class="fa-regular fa-envelope text-gray-400">
                                    </i>

                                    <input type="email" id="email" name="email" placeholder="Your Email"
                                        class="bg-transparent outline-none w-full text-white placeholder-gray-500" />

                                </div>

                            </div>


                            <div
                                class="field-wrapper flex items-center gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                                <i class="fa-regular fa-tag text-gray-400">
                                </i>

                                <select id="subject" name="subject"
                                    class="bg-transparent outline-none w-full text-gray-400">

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


                            <div
                                class="field-wrapper flex items-start gap-2 bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3">

                                <i class="fa-solid fa-pen text-gray-400 mt-1">
                                </i>

                                <textarea rows="4" id="message" name="message" placeholder="How can we help you?"
                                    class="bg-transparent outline-none w-full text-white placeholder-gray-500 resize-none"></textarea>

                            </div>


                            <button type="submit" id="submitBtn"
                                class="bg-[#5207A1] hover:bg-[#5107a18f] duration-300 rounded-xl py-3 font-semibold flex items-center justify-center gap-2 cursor-pointer">

                                <i class="fa-solid fa-paper-plane"></i>

                                Send Message

                            </button>


                            <p class="text-center text-gray-400 text-sm">

                                By sending this message, you agree to our

                                <a href="#" class="text-[#A726DD] hover:text-[#c96bf0] duration-300">

                                    Privacy Policy

                                </a>.

                            </p>

                        </form>

                    </div>

                </div>


                <!-- =====================================================
             FAQ Section
        ====================================================== -->

                <div id="faq-section" class="bg-[#0f0d1a] border border-[#26223D] rounded-xl p-6 mt-10">

                    <div class="flex justify-between items-center mb-5">

                        <h3 class="font-bold text-xl">

                            Frequently Asked Questions

                        </h3>

                        <a href="#"
                            class="text-[#A726DD] hover:text-[#c96bf0] duration-300 font-semibold flex items-center gap-2">

                            View All FAQs

                            <span class="w-9 h-9 rounded-full border border-[#A726DD] flex items-center justify-center">

                                <i class="fa-solid fa-arrow-right text-sm"></i>

                            </span>

                        </a>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                        <!-- FAQ 1 -->
                        <div>

                            <button
                                class="w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                                <span class="flex items-center gap-3">

                                    <i class="fa-regular fa-circle-question text-[#A726DD]">
                                    </i>

                                    How do I reset my password?

                                </span>

                                <i class="fa-solid fa-chevron-down text-gray-400 duration-300">
                                </i>

                            </button>


                            <div class="overflow-hidden duration-300" style="max-height: 0">

                                <p class="text-gray-400 text-sm px-4 py-3">

                                    Go to Settings > Account > Reset Password,
                                    and follow the instructions sent to your email.

                                </p>

                            </div>

                        </div>


                        <!-- FAQ 2 -->
                        <div>

                            <button
                                class="w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                                <span class="flex items-center gap-3">

                                    <i class="fa-regular fa-circle-question text-[#A726DD]">
                                    </i>

                                    How can I request a refund?

                                </span>

                                <i class="fa-solid fa-chevron-down text-gray-400 duration-300">
                                </i>

                            </button>


                            <div class="overflow-hidden duration-300" style="max-height: 0">

                                <p class="text-gray-400 text-sm px-4 py-3">

                                    Contact our support team within 14 days of purchase
                                    with your order number.

                                </p>

                            </div>

                        </div>


                        <!-- FAQ 3 -->
                        <div>

                            <button
                                class="w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                                <span class="flex items-center gap-3">

                                    <i class="fa-regular fa-circle-question text-[#A726DD]">
                                    </i>

                                    What payment methods do you accept?

                                </span>

                                <i class="fa-solid fa-chevron-down text-gray-400 duration-300">
                                </i>

                            </button>


                            <div class="overflow-hidden duration-300" style="max-height: 0">

                                <p class="text-gray-400 text-sm px-4 py-3">

                                    We accept Visa, Mastercard, PayPal, and Fawry.

                                </p>

                            </div>

                        </div>


                        <!-- FAQ 4 -->
                        <div>

                            <button
                                class="w-full flex justify-between items-center bg-[#15121f] border border-[#26223D] rounded-lg px-4 py-3 text-left hover:border-[#A726DD] duration-300">

                                <span class="flex items-center gap-3">

                                    <i class="fa-regular fa-circle-question text-[#A726DD]">
                                    </i>

                                    How do I report a bug or issue?

                                </span>

                                <i class="fa-solid fa-chevron-down text-gray-400 duration-300">
                                </i>

                            </button>


                            <div class="overflow-hidden duration-300" style="max-height: 0">

                                <p class="text-gray-400 text-sm px-4 py-3">

                                    Use the "Report a Bug" option in your account settings,
                                    or email support@gamex.com.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


            </div>
            <!-- End Page Content -->


            <!-- Scripts -->
            <script src="./js/contact-form.js"></script>
            <script src="./js/cart.js"></script>
            <script src="./js/favorites.js"></script>

        </div>
        <!-- End Main Container -->

    </div>

</body>

</html>