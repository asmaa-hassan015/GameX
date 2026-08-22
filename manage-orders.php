<?php

session_start();

?>

<!doctype html>

<html lang="en" dir="ltr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Manage Orders</title>


    <!-- ========================================================= -->
    <!-- TAILWIND CSS -->
    <!-- ========================================================= -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- ========================================================= -->
    <!-- PROJECT CSS -->
    <!-- ========================================================= -->

    <link rel="stylesheet" href="./css/style.css">


    <!-- ========================================================= -->
    <!-- FONT AWESOME -->
    <!-- ========================================================= -->

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- ========================================================= -->
    <!-- GOOGLE FONTS -->
    <!-- ========================================================= -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap">

</head>


<body class="min-h-screen bg-[#070612] text-[#F5F3FF] font-['Poppins',sans-serif]">


    <!-- ========================================================= -->
    <!-- ADMIN NAVBAR -->
    <!-- ========================================================= -->

    <?php include 'components/admin-nav.php'; ?>


    <!-- ========================================================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================================================= -->

    <main class="w-full px-3 sm:px-4 pt-28 sm:pt-32 pb-8">


        <!-- ===================================================== -->
        <!-- ORDERS SECTION -->
        <!-- ===================================================== -->

        <section
            class="w-full max-w-[1100px] mx-auto bg-[#111022] border border-[#26223D] rounded-xl sm:rounded-2xl overflow-hidden">


            <!-- ================================================= -->
            <!-- HEADER -->
            <!-- ================================================= -->

            <div class="flex items-center justify-between gap-3 px-4 pt-4 pb-4 sm:px-5 sm:pt-5 sm:pb-5">


                <!-- ================================================= -->
                <!-- PAGE TITLE -->
                <!-- ================================================= -->

                <div class="min-w-0">

                    <h1 class="font-['Rajdhani',sans-serif] text-lg sm:text-2xl font-bold tracking-[0.3px]">

                        Manage Orders

                    </h1>


                    <p class="text-[#A5A1B5] text-xs sm:text-sm mt-1">

                        View and manage all orders.

                    </p>

                </div>


                <!-- ================================================= -->
                <!-- ORDER COUNT -->
                <!-- ================================================= -->

                <div
                    class="hidden sm:flex items-center gap-3 bg-[#0D0B1A] border border-[#26223D] rounded-xl px-3 py-2">


                    <!-- Order Icon -->

                    <div class="w-9 h-9 rounded-lg bg-[#7C2CFF]/15 text-[#A855F7] flex items-center justify-center">

                        <i class="fa-solid fa-cart-shopping"></i>

                    </div>


                    <!-- Order Count -->

                    <div>

                        <p class="text-[10px] text-[#A5A1B5]">

                            Total Orders

                        </p>


                        <p id="ordersCount" class="text-lg font-bold">

                            0

                        </p>

                    </div>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- TABLE HEADER -->
            <!-- ========================================================= -->

            <div class="grid
                       grid-cols-[75px_minmax(120px,1.3fr)_70px_90px_85px_45px]
                       sm:grid-cols-[100px_minmax(160px,1.3fr)_90px_110px_110px_60px]
                       md:grid-cols-[110px_minmax(180px,1.3fr)_100px_120px_120px_70px]
                       items-center
                       gap-2
                       px-3 sm:px-5
                       py-3 sm:py-3.5
                       border-t border-b
                       border-[#26223D]
                       text-[#A5A1B5]
                       font-medium
                       text-xs sm:text-sm">


                <!-- Order ID -->

                <span>

                    Order ID

                </span>


                <!-- Player -->

                <span>

                    Player

                </span>


                <!-- Total -->

                <span>

                    Total

                </span>


                <!-- Status -->

                <span>

                    Status

                </span>


                <!-- Date -->

                <span>

                    Date

                </span>


                <!-- Action -->

                <span class="text-right">

                    Action

                </span>

            </div>


            <!-- ========================================================= -->
            <!-- ORDERS LIST -->
            <!-- ========================================================= -->

            <div id="ordersList">


                <!-- ===================================================== -->
                <!-- LOADING STATE -->
                <!-- ===================================================== -->

                <div
                    class="flex flex-col items-center justify-center py-12 sm:py-16 px-5 text-[#A5A1B5] text-sm text-center">


                    <!-- Loading Icon -->

                    <i class="fa-solid fa-spinner fa-spin text-2xl sm:text-3xl mb-3">
                    </i>


                    <!-- Loading Message -->

                    <span>

                        Loading orders...

                    </span>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- PAGINATION -->
            <!-- ========================================================= -->

            <div id="ordersPagination"
                class="flex items-center justify-center gap-1.5 sm:gap-2 py-4 sm:py-5 border-t border-[#26223D]">

            </div>

        </section>

    </main>


    <!-- ========================================================= -->
    <!-- MAIN JAVASCRIPT -->
    <!-- ========================================================= -->

    <script src="./js/main.js"></script>


    <!-- ========================================================= -->
    <!-- MANAGE ORDERS JAVASCRIPT -->
    <!-- ========================================================= -->

    <script src="./js/manage-orders.js"></script>


</body>

</html>