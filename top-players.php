<?php

session_start();

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Top Players</title>


    <!-- =========================================================
         TAILWIND
    ========================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- =========================================================
         MAIN CSS
    ========================================================== -->

    <link rel="stylesheet" href="./css/style.css">


    <!-- =========================================================
         FONT AWESOME
    ========================================================== -->

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- =========================================================
         GOOGLE FONTS
    ========================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

</head>


<body class="bg-[#070612] text-[#F5F3FF] min-h-screen">


    <!-- =========================================================
         NAVBAR
    ========================================================== -->

    <?php include 'components/nav.php'; ?>


    <!-- =========================================================
         TOP PLAYERS HEADER
    ========================================================== -->

    <header class="top-players-hero relative overflow-hidden">


        <!-- =====================================================
             BACKGROUND EFFECT
        ====================================================== -->

        <div class="absolute inset-0 pointer-events-none opacity-40" style="
                background-image:
                    radial-gradient(
                        1px 1px at 20px 30px,
                        #892acd 40%,
                        transparent 41%
                    ),
                    radial-gradient(
                        1px 1px at 120px 80px,
                        #ad30e0 40%,
                        transparent 41%
                    ),
                    radial-gradient(
                        1.5px 1.5px at 200px 40px,
                        #f0ae2d 40%,
                        transparent 41%
                    );
                background-size: 260px 180px;
            ">
        </div>


        <!-- =====================================================
             PAGE TITLE
        ====================================================== -->

        <div class="relative max-w-[1450px] pt-20 mx-auto sm:px-6 pb-5">

            <h1 class="font-[Poppins] font-bold text-4xl leading-tight">

                <span class="text-white">
                    TOP
                </span>

                <span
                    class="bg-gradient-to-r from-purple-500 via-fuchsia-500 to-purple-700 bg-clip-text text-transparent">
                    PLAYERS
                </span>

            </h1>


            <p class="font-[Rajdhani] text-[#A5A1B5] mt-1.5 text-xs sm:text-sm tracking-wide">
                Discover. Choose. Play.
            </p>


            <!-- =================================================
                 RANKING PERIOD
            ================================================== -->

            <div id="tabs"
                class="mt-4 inline-flex items-center gap-1 bg-[#0D0B1A] border border-[#26223D] rounded-full p-1">

                <!-- Global -->

                <button type="button" data-period="global"
                    class="tab-btn font-[Rajdhani] font-semibold text-xs sm:text-sm px-4 py-1.5 rounded-full bg-gradient-to-r from-[#3D1398] to-[#AD30E0] text-white shadow-[0_0_20px_rgba(173,48,224,0.5)] transition-all duration-300">
                    Global
                </button>


                <!-- Weekly -->

                <button type="button" data-period="weekly"
                    class="tab-btn font-[Rajdhani] font-semibold text-xs sm:text-sm px-4 py-1.5 rounded-full text-[#A5A1B5] hover:text-white transition-all duration-300">
                    Weekly
                </button>


                <!-- Monthly -->

                <button type="button" data-period="monthly"
                    class="tab-btn font-[Rajdhani] font-semibold text-xs sm:text-sm px-4 py-1.5 rounded-full text-[#A5A1B5] hover:text-white transition-all duration-300">
                    Monthly
                </button>

            </div>

        </div>


        <!-- =========================================================
             TOP 3 PLAYERS
        ========================================================== -->

        <section id="podium"
            class="relative max-w-4xl mx-auto px-4 sm:px-6 grid grid-cols-3 gap-2 sm:gap-4 items-end pt-8 pb-6">
        </section>

    </header>



    <!-- =========================================================
         PLAYERS TABLE
    ========================================================== -->

    <main class="max-w-4xl mx-auto px-4 sm:px-6">

        <section class="pb-10">


            <!-- =====================================================
                 TABLE CONTAINER
            ====================================================== -->

            <div class="rounded-2xl border border-[#26223D] bg-[#111022]/60 overflow-hidden card-glow">

                <div class="overflow-x-auto scrollbar-thin">


                    <!-- =================================================
                         PLAYERS TABLE
                    ================================================== -->

                    <table class="w-full min-w-[520px] text-left">


                        <!-- =================================================
                             TABLE HEADER
                        ================================================== -->

                        <thead>

                            <tr
                                class="font-[Rajdhani] text-[#AD30E0] text-xs uppercase tracking-wider border-b border-[#26223D]">

                                <!-- Rank -->

                                <th class="py-3 px-3 sm:px-5 font-semibold">
                                    #
                                </th>


                                <!-- Player -->

                                <th class="py-3 px-3 sm:px-5 font-semibold">
                                    Player
                                </th>


                                <!-- Game -->

                                <th class="py-3 px-3 sm:px-5 font-semibold">
                                    Game
                                </th>


                                <!-- Genre -->

                                <th class="py-3 px-3 sm:px-5 font-semibold">
                                    Genre
                                </th>


                                <!-- Price -->

                                <th class="py-3 px-3 sm:px-5 font-semibold">
                                    Price
                                </th>


                                <!-- Actions -->

                                <th class="py-3 px-3 sm:px-5 font-semibold">
                                </th>

                            </tr>

                        </thead>


                        <!-- =================================================
                             TABLE BODY
                        ================================================== -->

                        <tbody id="tableBody">
                        </tbody>

                    </table>

                </div>


                <!-- =====================================================
                     PAGINATION
                ====================================================== -->

                <div id="pagination" class="flex items-center justify-center gap-2 py-5 border-t border-[#26223D]">
                </div>

            </div>

        </section>

    </main>



    <!-- =========================================================
         PLAYER DETAILS MODAL
    ========================================================== -->

    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-md bg-black/60 px-4">


        <!-- =====================================================
             MODAL CONTAINER
        ====================================================== -->

        <div class="w-full max-w-sm bg-[#111022] border border-[#26223D] rounded-2xl p-6 card-glow relative">


            <!-- =================================================
                 CLOSE BUTTON
            ================================================== -->

            <button id="closeModal" type="button"
                class="absolute top-4 right-4 text-[#A5A1B5] hover:text-white transition" aria-label="Close">

                <i class="fa-solid fa-xmark text-lg"></i>

            </button>



            <!-- =================================================
                 GAME INFORMATION
            ================================================== -->

            <div class="flex items-center gap-4">


                <!-- Game Image -->

                <img id="modalGameImg" src="" alt="" class="w-20 h-28 object-cover rounded-lg border border-[#26223D]">


                <!-- Game Details -->

                <div>

                    <p id="modalGameTitle" class="font-[Poppins] font-bold text-lg leading-tight">
                    </p>


                    <p id="modalGenre" class="font-[Rajdhani] text-[#AD30E0] text-sm mt-1">
                    </p>


                    <p id="modalPrice" class="font-[Rajdhani] text-[#F0AE2D] font-semibold mt-1">
                    </p>

                </div>

            </div>



            <!-- =================================================
                 PLAYER INFORMATION
            ================================================== -->

            <div class="mt-5 pt-5 border-t border-[#26223D] flex items-center gap-3">


                <!-- Player Avatar -->

                <img id="modalAvatar" src="" alt="" class="w-10 h-10 rounded-full object-cover">


                <!-- Player Name -->

                <div>

                    <p class="text-xs text-[#A5A1B5] font-[Rajdhani]">
                        Purchased by
                    </p>


                    <p id="modalPlayer" class="font-semibold text-sm">
                    </p>

                </div>

            </div>



            <!-- =================================================
                 PLAYER STATISTICS
            ================================================== -->

            <div class="mt-5 grid grid-cols-2 gap-3">


                <!-- =================================================
                     GAMES PURCHASED
                ================================================== -->

                <div class="bg-[#0D0B1A] rounded-xl p-3 text-center border border-[#26223D]">

                    <p class="text-xs text-[#A5A1B5] font-[Rajdhani]">
                        Games Purchased
                    </p>


                    <p id="modalCount" class="font-[Poppins] font-bold text-lg mt-1">
                    </p>

                </div>



                <!-- =================================================
                     TOTAL SPENT
                ================================================== -->

                <div class="bg-[#0D0B1A] rounded-xl p-3 text-center border border-[#26223D]">

                    <p class="text-xs text-[#A5A1B5] font-[Rajdhani]">
                        Total Spent
                    </p>


                    <p id="modalSpent" class="font-[Poppins] font-bold text-lg mt-1 text-[#F0AE2D]">
                    </p>

                </div>

            </div>

        </div>

    </div>



    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <script src="./js/main.js"></script>

    <script src="./js/top-players.js"></script>
    <script src="./js/cart.js"></script>
    <script src="./js/favorites.js"></script>


</body>

</html>