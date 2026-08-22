<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X · All Games</title>


    <!-- ========================================================= -->
    <!-- TAILWIND CSS -->
    <!-- ========================================================= -->

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
    tailwind.config = {

        theme: {

            extend: {

                colors: {

                    gx: {
                        bg: '#000205',
                        panel: '#070612',
                        card: '#0f0d1a',
                        border: '#26223D',
                        purple: '#A726DD',
                        darkPurple: '#5207A1'
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

                }

            }

        }

    };
    </script>


    <!-- ========================================================= -->
    <!-- GOOGLE FONTS -->
    <!-- ========================================================= -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">


    <!-- ========================================================= -->
    <!-- FONT AWESOME -->
    <!-- ========================================================= -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    <!-- ========================================================= -->
    <!-- CUSTOM STYLE -->
    <!-- ========================================================= -->

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #000205;
        color: #fff;
    }

    h1,
    h2,
    h3,
    .heading-font {
        font-family: 'Rajdhani', sans-serif;
    }

    input[type="range"] {
        accent-color: #A726DD;
    }
    </style>

</head>


<body>

    <div class="bg-[#000205] text-white min-h-screen">


        <!-- ========================================================= -->
        <!-- NAVBAR -->
        <!-- ========================================================= -->

        <?php include 'components/nav.php'; ?>


        <!-- ========================================================= -->
        <!-- GAMES SECTION -->
        <!-- ========================================================= -->

        <section class="p-10">


            <!-- ========================================================= -->
            <!-- HEADER -->
            <!-- ========================================================= -->

            <div class="mt-4 pt-2">

                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-wide mb-1">

                    ALL

                    <span class="text-[#A726DD]">
                        GAMES
                    </span>

                </h1>


                <p class="text-gray-400 text-sm md:text-base">

                    Explore premium games worth playing and instantly redeemable keys.

                </p>


                <!-- ===================================================== -->
                <!-- CATEGORY FILTERS -->
                <!-- ===================================================== -->

                <div id="categoryButtons" class="flex flex-wrap items-center gap-2 md:gap-3 mt-4">

                    <button type="button"
                        class="category-btn active-category bg-[#A726DD] text-white px-5 py-2 rounded-full font-semibold shadow-lg text-sm cursor-pointer transition"
                        data-category="all">
                        All Games
                    </button>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- MAIN CONTENT -->
            <!-- ========================================================= -->

            <div class="flex flex-col lg:flex-row gap-6 mt-6">


                <!-- ===================================================== -->
                <!-- GAMES GRID -->
                <!-- ===================================================== -->

                <div class="flex-1">


                    <!-- ================================================= -->
                    <!-- SORT -->
                    <!-- ================================================= -->

                    <div class="flex justify-end mb-4">

                        <div class="relative">

                            <select id="sortSelect"
                                class="bg-[#15121f] hover:bg-[#221c33] border border-[#26223D] text-white px-4 py-2 rounded-full text-sm cursor-pointer outline-none appearance-none pr-10">

                                <option value="popularity">
                                    Sort by: Popularity
                                </option>

                                <option value="price-low">
                                    Price: Low to High
                                </option>

                                <option value="price-high">
                                    Price: High to Low
                                </option>

                                <option value="rating">
                                    Rating
                                </option>

                                <option value="newest">
                                    Newest
                                </option>

                            </select>


                            <i
                                class="fa-solid fa-chevron-down text-xs absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- LOADING -->
                    <!-- ================================================= -->

                    <div id="loadingState" class="border border-[#26223D] bg-[#0f0d1a] rounded-xl p-10 text-center">

                        <i class="fa-solid fa-spinner fa-spin text-[#A726DD] text-4xl mb-4"></i>

                        <p class="text-gray-400">
                            Loading games...
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- GAMES GRID -->
                    <!-- ================================================= -->

                    <div id="gamesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    </div>


                    <!-- ================================================= -->
                    <!-- EMPTY STATE -->
                    <!-- ================================================= -->

                    <div id="emptyState"
                        class="hidden border border-[#26223D] bg-[#0f0d1a] rounded-xl p-10 text-center">

                        <i class="fa-solid fa-gamepad text-[#A726DD] text-5xl mb-4"></i>

                        <h3 class="text-2xl font-bold mb-2">
                            No Games Found
                        </h3>

                        <p class="text-gray-400">
                            There are no games matching your filters.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- PAGINATION -->
                    <!-- ================================================= -->

                    <div id="pagination" class="flex items-center justify-center gap-2 mt-10">

                        <button type="button" id="prevPage"
                            class="w-10 h-10 rounded-lg bg-[#15121f] hover:bg-[#A726DD] border border-[#26223D] duration-300 flex items-center justify-center cursor-pointer">

                            <i class="fa-solid fa-chevron-left text-sm"></i>

                        </button>


                        <div id="pageNumbers" class="flex gap-2"></div>


                        <button type="button" id="nextPage"
                            class="w-10 h-10 rounded-lg bg-[#15121f] hover:bg-[#A726DD] border border-[#26223D] duration-300 flex items-center justify-center cursor-pointer">

                            <i class="fa-solid fa-chevron-right text-sm"></i>

                        </button>

                    </div>

                </div>


                <!-- ===================================================== -->
                <!-- FILTER SIDEBAR -->
                <!-- ===================================================== -->

                <aside class="w-full lg:w-72 shrink-0 bg-[#0f0d1a] border border-[#26223D] rounded-xl p-5 h-fit">


                    <!-- ================================================= -->
                    <!-- FILTER HEADER -->
                    <!-- ================================================= -->

                    <div class="flex items-center justify-between mb-5 border-b border-[#26223D] pb-3">

                        <h3 class="font-bold text-lg flex items-center gap-2">

                            <i class="fa-solid fa-layer-group text-[#A726DD]"></i>

                            FILTERS

                        </h3>


                        <i class="fa-solid fa-sliders text-gray-400"></i>

                    </div>


                    <!-- ================================================= -->
                    <!-- PRICE -->
                    <!-- ================================================= -->

                    <div class="mb-6">

                        <h4 class="font-semibold mb-3 text-sm text-gray-200">
                            Price Range
                        </h4>


                        <input id="priceRange" type="range" min="0" max="100" value="100"
                            class="w-full accent-[#A726DD] cursor-pointer">


                        <div class="flex justify-between text-xs text-gray-400 mt-1 font-mono">

                            <span>$0</span>

                            <span id="priceValue">
                                $100
                            </span>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- GENRES -->
                    <!-- ================================================= -->

                    <div class="mb-6">

                        <h4 class="font-semibold mb-3 text-sm text-gray-200">
                            Genres
                        </h4>


                        <div id="genreFilters" class="flex flex-col gap-2.5 text-gray-300 text-sm"></div>

                    </div>


                    <!-- ================================================= -->
                    <!-- PLATFORMS -->
                    <!-- ================================================= -->

                    <div class="mb-6">

                        <h4 class="font-semibold mb-3 text-sm text-gray-200">
                            Platforms
                        </h4>


                        <div id="platformFilters" class="flex flex-col gap-2.5 text-gray-300 text-sm"></div>

                    </div>


                    <!-- ================================================= -->
                    <!-- RATINGS -->
                    <!-- ================================================= -->

                    <div class="mb-6">

                        <h4 class="font-semibold mb-3 text-sm text-gray-200">
                            Ratings
                        </h4>


                        <div class="flex flex-col gap-2 text-sm">


                            <label class="flex items-center gap-2 cursor-pointer">

                                <input type="checkbox" class="rating-filter accent-[#A726DD] rounded" value="5">

                                <span class="text-[#A726DD]">
                                    ★★★★★
                                </span>

                            </label>


                            <label class="flex items-center gap-2 cursor-pointer">

                                <input type="checkbox" class="rating-filter accent-[#A726DD] rounded" value="4">

                                <span class="text-[#A726DD]">

                                    ★★★★

                                    <span class="text-gray-600">
                                        ★
                                    </span>

                                </span>

                            </label>


                            <label class="flex items-center gap-2 cursor-pointer">

                                <input type="checkbox" class="rating-filter accent-[#A726DD] rounded" value="3">

                                <span class="text-[#A726DD]">

                                    ★★★

                                    <span class="text-gray-600">
                                        ★★
                                    </span>

                                </span>

                            </label>


                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- CLEAR FILTERS -->
                    <!-- ================================================= -->

                    <button type="button" id="clearFilters"
                        class="w-full py-2 border border-[#A726DD]/40 text-[#A726DD] hover:bg-[#A726DD] hover:text-white duration-300 text-xs rounded-lg flex items-center justify-center gap-2 font-semibold cursor-pointer">

                        <i class="fa-solid fa-rotate-left"></i>

                        Clear Filters

                    </button>

                </aside>

            </div>

        </section>

    </div>


    <!-- ========================================================= -->
    <!-- JAVASCRIPT -->
    <!-- ========================================================= -->

    <script src="./js/games.js"></script>

    <script src="./js/favorites.js"></script>

    <script src="./js/cart.js"></script>


</body>

</html>