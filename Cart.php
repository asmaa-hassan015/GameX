<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Your Cart</title>

    <!-- ======================== Tailwind ======================== -->

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ======================== CSS ======================== -->

    <link rel="stylesheet" href="./css/style.css">

    <!-- ======================== Font Awesome ======================== -->

    <link rel="stylesheet" href="./assets/css/all.min.css">

    <!-- ======================== Fonts ======================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

</head>

<body class="font-display text-textmain bg-[#000205] min-h-screen">

    <?php include 'components/nav.php'; ?>

    <!-- =========================================================
         MAIN WRAPPER
    ========================================================= -->

    <div class="max-w-[1360px] mx-auto px-6 pt-10 pb-20 lg:px-10">

        <!-- =========================================================
             BREADCRUMB
        ========================================================= -->

        <div class="text-sm text-gray-400 mb-4">

            <a href="index.php" class="hover:text-[#A726DD]">
                Home
            </a>

            <span class="mx-1">›</span>

            <span class="text-[#A726DD] font-medium">

                <a href="./Cart.php">
                    Cart
                </a>

            </span>

        </div>

        <!-- =========================================================
             PAGE HEADER
        ========================================================= -->

        <h1
            class="font-tech font-bold text-[44px] tracking-[0.5px] mb-2 max-[980px]:text-[34px] flex items-center gap-3">

            <i class="fa-solid fa-cart-shopping text-accent"></i>

            Your Cart

        </h1>

        <p class="text-textsecond text-[15px] mb-8">

            Review your items and proceed to checkout.

        </p>

        <!-- =========================================================
             MAIN GRID
        ========================================================= -->

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.35fr_1fr] gap-6 items-start">

            <!-- =====================================================
                 CART ITEMS SECTION
            ===================================================== -->

            <div class="lg:col-span-2 bg-card border border-borderc rounded-2xl p-6">

                <div class="font-tech font-semibold text-[13px] tracking-[1.5px] text-textsecond mb-[18px]">

                    CART ITEMS

                </div>

                <!-- Cart Items -->

                <div id="cart-items" class="flex flex-col gap-4">

                    <p class="text-muted text-center py-10">
                        Loading...
                    </p>

                </div>

                <!-- Continue Shopping -->

                <div class="mt-6">

                    <a href="Games.php" class="text-accent text-sm hover:underline inline-flex items-center gap-1.5">

                        <i class="fa-solid fa-arrow-left"></i>

                        Continue Shopping

                    </a>

                </div>

            </div>

            <!-- =====================================================
                 ORDER SUMMARY
            ===================================================== -->

            <div class="bg-card border border-borderc rounded-2xl p-6">

                <div class="font-tech font-semibold text-[13px] tracking-[1.5px] text-textsecond mb-[18px]">

                    ORDER SUMMARY

                </div>

                <!-- Subtotal -->

                <div class="flex justify-between text-sm mb-3">

                    <span class="text-textsecond">
                        Subtotal
                    </span>

                    <span id="subtotal" class="text-textmain font-medium">

                        --

                    </span>

                </div>

                <!-- Discount -->

                <div class="flex justify-between text-sm mb-3">

                    <span class="text-textsecond">
                        Discount
                    </span>

                    <span id="discount" class="text-success font-semibold">

                        --

                    </span>

                </div>

                <!-- Tax -->

                <div class="flex justify-between text-sm mb-3">

                    <span class="text-textsecond">
                        Tax
                    </span>

                    <span id="tax" class="text-textmain font-medium">

                        --

                    </span>

                </div>

                <!-- Divider -->

                <div class="h-px bg-borderc my-[18px]"></div>

                <!-- Total -->

                <div class="flex justify-between items-center mt-[22px] mb-[22px]">

                    <span class="font-tech font-semibold text-xl">
                        Total
                    </span>

                    <span id="total"
                        class="font-tech font-bold text-[30px] text-accent drop-shadow-[0_0_18px_rgba(176,38,255,0.45)]">

                        --

                    </span>

                </div>

                <!-- =====================================================
                     CHECKOUT BUTTON
                ===================================================== -->

                <button id="checkout-btn"
                    class="w-full py-4 border-0 rounded-xl bg-gradient-to-r from-primary to-glow text-white font-tech font-bold text-lg tracking-[0.5px] flex items-center justify-center gap-2.5 cursor-pointer shadow-[0_8px_30px_rgba(124,44,255,0.45)] transition-all hover:-translate-y-px hover:shadow-[0_10px_36px_rgba(176,38,255,0.55)] disabled:opacity-75 disabled:cursor-not-allowed"
                    type="button" disabled>

                    <i class="fa-solid fa-lock text-sm"></i>

                    Proceed to Checkout

                    <i class="fa-solid fa-arrow-right text-sm"></i>

                </button>

                <!-- Secure Checkout -->

                <div class="flex items-center justify-center gap-2 mt-4 text-[12.5px] text-muted">

                    🔒 Secure checkout: Your data is protected.

                </div>

            </div>

        </div>

    </div>

    <!-- ======================== JavaScript ======================== -->

    <script src="./js/main.js"></script>

    <script src="./js/cart.js"></script>

</body>

</html>