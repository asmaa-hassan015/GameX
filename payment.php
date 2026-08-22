<?php

session_start();

// =========================================================
// CHECK LOGIN
// =========================================================

if (!isset($_SESSION['user_id'])) {

    header("Location: Login.php");
    exit;

}

$userId = (int) $_SESSION['user_id'];

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Payment</title>


    <!-- =====================================================
         TAILWIND
    ====================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- =====================================================
         TAILWIND CONFIG
    ====================================================== -->

    <script>
    tailwind.config = {

        theme: {

            extend: {

                colors: {

                    gx: {

                        bg: '#070612',
                        panel: '#111022',
                        line: '#26223D',
                        purple: '#7C2CFF',
                        neon: '#A855F7',
                        text: '#F5F3FF',
                        muted: '#A5A1B5'

                    }

                },

                fontFamily: {

                    rajdhani: [
                        'Rajdhani',
                        'sans-serif'
                    ],

                    poppins: [
                        'Poppins',
                        'sans-serif'
                    ]

                },

                boxShadow: {

                    neon: '0 0 15px rgba(124,44,255,.35)'

                }

            }

        }

    };
    </script>


    <!-- =====================================================
         GOOGLE FONTS
    ====================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- =====================================================
         MAIN CSS
    ====================================================== -->

    <link rel="stylesheet" href="./css/style.css">

</head>


<body class="min-h-screen bg-[#070612] text-[#F5F3FF] font-[Poppins]" data-user-id="<?= $userId ?>">


    <!-- =====================================================
         NAVBAR
    ====================================================== -->

    <?php include 'components/nav.php'; ?>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="w-full max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-10 pt-8 lg:pt-10 pb-20">


        <!-- =================================================
             BREADCRUMB
        ================================================== -->

        <div class="text-sm text-[#A5A1B5] mb-4">

            <a href="index.php" class="hover:text-[#A855F7] transition-colors duration-200">
                Home
            </a>

            <span class="mx-1 text-[#6B687A]">
                ›
            </span>

            <a href="./Cart.php" class="hover:text-[#A855F7] transition-colors duration-200">
                Cart
            </a>

            <span class="mx-1 text-[#6B687A]">
                ›
            </span>

            <span class="text-[#A855F7] font-medium">
                Payment
            </span>

        </div>


        <!-- =================================================
             PAGE HEADER
        ================================================== -->

        <h1 class="font-[Rajdhani] font-bold text-[36px] sm:text-[44px] leading-none tracking-[0.5px] mb-2">
            Payment
        </h1>

        <p class="text-[#A5A1B5] text-sm sm:text-[15px] mb-8">

            Complete your payment to place your order.

        </p>


        <!-- =================================================
             MAIN GRID
        ================================================== -->

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.35fr_1fr] gap-6 items-start">


            <!-- =================================================
                 PAYMENT METHOD
            ================================================== -->

            <section class="bg-[#111022] border border-[#26223D] rounded-2xl p-5 sm:p-6">

                <div class="font-[Rajdhani] font-semibold text-[13px] tracking-[1.5px] text-[#A5A1B5] mb-[18px]">
                    PAYMENT METHOD
                </div>


                <div id="paymentMethods" class="flex flex-col gap-3">


                    <!-- Credit / Debit Card -->

                    <div class="method-option selected flex items-center gap-3 px-4 py-3.5 border-[1.5px] border-[#7C2CFF] rounded-xl cursor-pointer bg-[#7C2CFF]/[0.06] shadow-[0_0_0_1px_rgba(124,44,255,0.15),0_0_24px_rgba(124,38,255,0.12)] transition-all hover:border-[#4C1D95]"
                        data-method="card" id="methodCard">

                        <span
                            class="radio-dot w-[18px] h-[18px] rounded-full border-2 border-[#A855F7] flex items-center justify-center shrink-0">

                            <span class="w-2 h-2 rounded-full bg-[#A855F7] shadow-[0_0_8px_#B026FF]">
                            </span>

                        </span>

                        <span class="flex-1 text-[15px] font-medium text-[#F5F3FF]">
                            Credit / Debit Card
                        </span>

                    </div>


                    <!-- =================================================
                         CARD BRANDS
                    ================================================== -->

                    <div id="brandsWrap" class="mt-1">

                        <div class="flex gap-2">


                            <!-- VISA -->

                            <div
                                class="w-11 h-7 rounded-[5px] flex items-center justify-center text-[9px] font-extrabold tracking-[0.3px] text-white bg-gradient-to-br from-[#1A1F71] to-[#2E4CC4]">
                                VISA
                            </div>


                            <!-- MASTERCARD -->

                            <div id="mcBrand"
                                class="w-11 h-7 rounded-[5px] flex items-center justify-center bg-[#151515]">
                            </div>


                            <!-- AMEX -->

                            <div
                                class="w-11 h-7 rounded-[5px] flex items-center justify-center text-[9px] font-extrabold tracking-[0.3px] text-white bg-[#1F72CD]">
                                AMEX
                            </div>


                            <!-- DISCOVER -->

                            <div
                                class="w-11 h-7 rounded-[5px] flex items-center justify-center text-[9px] font-extrabold tracking-[0.3px] text-[#111] bg-[#f5f5f5]">
                                DISC
                            </div>


                            <!-- JCB -->

                            <div
                                class="w-11 h-7 rounded-[5px] flex items-center justify-center text-[9px] font-extrabold tracking-[0.3px] text-white bg-gradient-to-r from-[#0B4EA2] via-[#00A650] to-[#E4032E]">
                                JCB
                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         WALLET
                    ================================================== -->

                    <div class="method-option flex items-center gap-3 px-4 py-3.5 border-[1.5px] border-[#26223D] rounded-xl cursor-pointer bg-transparent transition-all hover:border-[#4C1D95]"
                        data-method="wallet">

                        <span class="radio-dot w-[18px] h-[18px] rounded-full border-2 border-[#26223D] shrink-0">
                        </span>

                        <span class="flex-1 text-[15px] font-medium text-[#F5F3FF]">
                            Wallet
                        </span>

                        <svg class="w-5 h-5 text-[#A5A1B5] shrink-0" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">

                            <rect x="3" y="6.5" width="18" height="12" rx="2" />

                            <path d="M3 10h18" />

                            <path d="M15.5 14.2a1.3 1.3 0 1 0 0-2.6 1.3 1.3 0 0 0 0 2.6z" />

                        </svg>

                    </div>

                </div>

            </section>


            <!-- =================================================
                 ORDER INFORMATION
            ================================================== -->

            <section class="bg-[#111022] border border-[#26223D] rounded-2xl p-5 sm:p-6">

                <div class="font-[Rajdhani] font-semibold text-[13px] tracking-[1.5px] text-[#A5A1B5] mb-[18px]">
                    ORDER INFORMATION
                </div>


                <form id="paymentForm" novalidate>


                    <!-- =================================================
                         CARD FORM
                    ================================================== -->

                    <div id="cardFormSection">


                        <!-- Card Number -->

                        <div class="mb-5">

                            <label for="cardNumber" class="block text-[13px] text-[#A5A1B5] mb-2">
                                Card Number
                            </label>


                            <div class="relative">

                                <input id="cardNumber" name="card_number" type="text" inputmode="numeric"
                                    autocomplete="cc-number" placeholder="Card Number" maxlength="19"
                                    class="w-full bg-[#0D0B1A] border border-[#26223D] rounded-[10px] px-4 py-3.5 pr-11 text-[#F5F3FF] text-[15px] tracking-[0.5px] outline-none focus:border-[#6D28D9] focus:ring-[3px] focus:ring-[#6D28D9]/25 transition">

                                <span id="cardIcon"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#A855F7] text-base pointer-events-none">
                                    💳
                                </span>

                            </div>


                            <div id="cardNumberError" class="hidden text-red-500 text-xs mt-1.5">
                                Enter a valid card number.
                            </div>

                        </div>


                        <!-- Cardholder Name -->

                        <div class="mb-5">

                            <label for="cardName" class="block text-[13px] text-[#A5A1B5] mb-2">
                                Cardholder Name
                            </label>


                            <input id="cardName" name="card_name" type="text" autocomplete="cc-name"
                                placeholder="Name on card"
                                class="w-full bg-[#0D0B1A] border border-[#26223D] rounded-[10px] px-4 py-3.5 text-[#F5F3FF] text-[15px] tracking-[0.5px] outline-none focus:border-[#6D28D9] focus:ring-[3px] focus:ring-[#6D28D9]/25 transition">


                            <div id="cardNameError" class="hidden text-red-500 text-xs mt-1.5">
                                Enter the cardholder's name.
                            </div>

                        </div>


                        <!-- Expiry + CVV -->

                        <div class="grid grid-cols-2 gap-4">


                            <!-- Expiry -->

                            <div class="mb-5">

                                <label for="expiry" class="block text-[13px] text-[#A5A1B5] mb-2">
                                    Expiry Date
                                </label>


                                <input id="expiry" name="expiry" type="text" inputmode="numeric" autocomplete="cc-exp"
                                    placeholder="MM / YY" maxlength="7"
                                    class="w-full bg-[#0D0B1A] border border-[#26223D] rounded-[10px] px-4 py-3.5 text-[#F5F3FF] text-[15px] tracking-[0.5px] outline-none focus:border-[#6D28D9] focus:ring-[3px] focus:ring-[#6D28D9]/25 transition">


                                <div id="expiryError" class="hidden text-red-500 text-xs mt-1.5">
                                    Enter a valid future expiry date.
                                </div>

                            </div>


                            <!-- CVV -->

                            <div class="mb-5">

                                <label for="cvv" class="block text-[13px] text-[#A5A1B5] mb-2">
                                    CVV
                                </label>


                                <input id="cvv" name="cvv" type="password" inputmode="numeric" autocomplete="cc-csc"
                                    placeholder="CVV" maxlength="4"
                                    class="w-full bg-[#0D0B1A] border border-[#26223D] rounded-[10px] px-4 py-3.5 text-[#F5F3FF] text-[15px] tracking-[0.5px] outline-none focus:border-[#6D28D9] focus:ring-[3px] focus:ring-[#6D28D9]/25 transition">


                                <div id="cvvError" class="hidden text-red-500 text-xs mt-1.5">
                                    Enter a valid CVV.
                                </div>

                            </div>

                        </div>


                        <!-- Save Card -->

                        <label class="flex items-center gap-2.5 mt-1 text-sm text-[#A5A1B5] cursor-pointer">

                            <span id="saveCardBox"
                                class="w-[18px] h-[18px] rounded-[5px] bg-[#7C2CFF] flex items-center justify-center text-white text-xs shrink-0">
                                ✓
                            </span>

                            <input type="checkbox" id="saveCard" name="save_card" checked class="hidden">

                            Save card information for faster checkout

                        </label>

                    </div>


                    <!-- =================================================
                         WALLET INFORMATION
                    ================================================== -->

                    <div id="walletInfo" class="hidden flex-col items-center text-center gap-3.5 py-10 px-2.5">

                        <div
                            class="w-14 h-14 rounded-full bg-[#7C2CFF]/10 border border-[#6D28D9] flex items-center justify-center">

                            <svg class="w-[26px] h-[26px] text-[#A855F7]" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">

                                <rect x="3" y="6.5" width="18" height="12" rx="2" />

                                <path d="M3 10h18" />

                                <path d="M15.5 14.2a1.3 1.3 0 1 0 0-2.6 1.3 1.3 0 0 0 0 2.6z" />

                            </svg>

                        </div>


                        <p class="text-[#A5A1B5] text-sm leading-[1.6] max-w-[280px]">
                            Your wallet balance will be used to cover your order.
                        </p>

                    </div>

                </form>

            </section>


            <!-- =================================================
                 ORDER SUMMARY
            ================================================== -->

            <section class="bg-[#111022] border border-[#26223D] rounded-2xl p-5 sm:p-6">

                <div class="font-[Rajdhani] font-semibold text-[13px] tracking-[1.5px] text-[#A5A1B5] mb-[18px]">
                    ORDER SUMMARY
                </div>


                <!-- Order Items -->

                <div id="orderItems" class="flex flex-col gap-4">
                </div>


                <!-- Empty Message -->

                <div id="emptyOrderMessage" class="py-8 text-center text-sm text-[#6B687A]">
                    No order items available.
                </div>


                <div class="h-px bg-[#26223D] my-[18px]">
                </div>


                <!-- Subtotal -->

                <div class="flex justify-between text-sm mb-3">

                    <span class="text-[#A5A1B5]">
                        Subtotal
                    </span>

                    <span id="subtotal" class="text-[#F5F3FF] font-medium">
                        --
                    </span>

                </div>


                <!-- Discount -->

                <div class="flex justify-between text-sm mb-3">

                    <span class="text-[#A5A1B5]">
                        Discount
                    </span>

                    <span id="discount" class="text-green-500 font-semibold">
                        --
                    </span>

                </div>


                <!-- Tax -->

                <div class="flex justify-between text-sm mb-3">

                    <span class="text-[#A5A1B5]">
                        Tax
                    </span>

                    <span id="tax" class="text-[#F5F3FF] font-medium">
                        --
                    </span>

                </div>


                <div class="h-px bg-[#26223D] my-[18px]">
                </div>


                <!-- Total -->

                <div class="flex justify-between items-center mt-[22px] mb-[22px]">

                    <span class="font-[Rajdhani] font-semibold text-xl">
                        Total
                    </span>

                    <span id="total"
                        class="font-[Rajdhani] font-bold text-[30px] text-[#A855F7] drop-shadow-[0_0_18px_rgba(176,38,255,0.45)]">
                        --
                    </span>

                </div>


                <!-- Pay Button -->

                <button id="payBtn" type="button"
                    class="w-full py-4 rounded-xl bg-gradient-to-r from-[#7C2CFF] to-[#B026FF] text-white font-[Rajdhani] font-bold text-lg tracking-[0.5px] flex items-center justify-center gap-2.5 cursor-pointer shadow-[0_8px_30px_rgba(124,44,255,0.45)] transition-all hover:-translate-y-px hover:shadow-[0_10px_36px_rgba(176,38,255,0.55)] disabled:opacity-75 disabled:cursor-not-allowed">

                    <span id="paySpinner"
                        class="hidden w-[18px] h-[18px] rounded-full border-[2.5px] border-white/35 border-t-white">
                    </span>

                    <span id="payBtnLabel">
                        🔒 Pay
                    </span>

                </button>


                <!-- Security Message -->

                <div class="flex items-center justify-center gap-2 mt-4 text-[12.5px] text-[#6B687A]">
                    🔒 Secure payment: Your data is protected.
                </div>

            </section>

        </div>

    </main>


    <!-- =====================================================
         TOAST
    ====================================================== -->

    <div id="toast"
        class="fixed top-6 left-1/2 -translate-x-1/2 -translate-y-5 bg-[#111022] border border-green-500 text-[#F5F3FF] px-[22px] py-3.5 rounded-xl text-sm font-medium flex items-center gap-2.5 opacity-0 pointer-events-none transition-all duration-300 z-[100]">

        <span
            class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center text-[#07120b] text-xs font-extrabold shrink-0">
            ✓
        </span>

        <span id="toastMsg">
            Payment successful — your order has been placed.
        </span>

    </div>


    <!-- =====================================================
         GLOBAL USER ID
    ====================================================== -->

    <script>
    window.CURRENT_USER_ID = <?= $userId ?>;
    </script>


    <!-- =====================================================
         MAIN JAVASCRIPT
    ====================================================== -->

    <script src="./js/main.js"></script>


    <!-- =====================================================
         PAYMENT JAVASCRIPT
    ====================================================== -->

    <script src="./js/payment.js"></script>


</body>

</html>