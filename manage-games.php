<?php

session_start();

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Manage Games</title>


    <!-- ========================================================= -->
    <!-- TAILWIND -->
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

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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

        <section
            class="w-full max-w-[1100px] mx-auto bg-[#111022] border border-[#26223D] rounded-xl sm:rounded-2xl overflow-hidden">


            <!-- ================================================= -->
            <!-- HEADER -->
            <!-- ================================================= -->

            <div class="flex items-center justify-between gap-3 px-4 pt-4 pb-4 sm:px-5 sm:pt-5 sm:pb-5">

                <div class="min-w-0">

                    <h1 class="font-['Rajdhani',sans-serif] text-lg sm:text-2xl font-bold">

                        Manage Games

                    </h1>

                    <p class="text-[#A5A1B5] text-xs sm:text-sm mt-1">

                        View and manage all games.

                    </p>

                </div>


                <!-- ================================================= -->
                <!-- ADD GAME BUTTON -->
                <!-- ================================================= -->

                <button type="button" onclick="openAddPage()"
                    class="shrink-0 flex items-center justify-center gap-2 bg-gradient-to-br from-[#7C2CFF] to-[#B026FF] text-white font-['Rajdhani',sans-serif] font-semibold text-sm sm:text-[15px] px-3 sm:px-5 py-2.5 rounded-xl transition duration-200 hover:shadow-[0_0_24px_rgba(176,38,255,0.45)] active:scale-[0.97]">

                    <i class="fa-solid fa-plus"></i>

                    <span>
                        Add New Game
                    </span>

                </button>

            </div>


            <!-- ================================================= -->
            <!-- TABLE HEADER -->
            <!-- ================================================= -->

            <div
                class="grid grid-cols-[minmax(0,1fr)_70px_60px] sm:grid-cols-[minmax(0,1fr)_90px_75px] md:grid-cols-[minmax(0,1fr)_140px_90px_100px_90px] items-center px-3 sm:px-5 py-3 border-t border-b border-[#26223D] text-[#A5A1B5] font-medium text-xs sm:text-sm">

                <span>
                    Game
                </span>

                <span class="hidden md:block">
                    Category
                </span>

                <span>
                    Price
                </span>

                <span class="hidden md:block text-center">
                    Status
                </span>

                <span class="text-right">
                    Actions
                </span>

            </div>


            <!-- ================================================= -->
            <!-- GAMES LIST -->
            <!-- ================================================= -->

            <div id="gamesList">

                <div class="py-10 text-center text-[#A5A1B5]">

                    Loading games...

                </div>

            </div>


            <!-- ================================================= -->
            <!-- PAGINATION -->
            <!-- ================================================= -->

            <div id="gamesPagination"
                class="flex items-center justify-center gap-1.5 sm:gap-2 py-4 sm:py-5 border-t border-[#26223D]">

            </div>

        </section>

    </main>


    <!-- ========================================================= -->
    <!-- ADD / EDIT GAME MODAL -->
    <!-- ========================================================= -->

    <div id="addPage"
        class="fixed inset-0 hidden items-start justify-center bg-[#070612]/95 backdrop-blur-sm px-3 sm:px-4 py-5 sm:py-6 overflow-y-auto z-[50]">

        <div class="w-full max-w-[720px] bg-[#111022] border border-[#26223D] rounded-2xl p-5 sm:p-7 mt-2 sm:mt-5 mb-5">


            <!-- ================================================= -->
            <!-- FORM HEADER -->
            <!-- ================================================= -->

            <div class="flex items-center gap-3 mb-5 sm:mb-6">

                <button type="button" onclick="closeAddPage()"
                    class="w-9 h-9 shrink-0 rounded-[10px] bg-purple-500/10 border border-[#26223D] text-[#A5A1B5] flex items-center justify-center transition hover:border-[#7C2CFF] hover:text-[#A855F7]">

                    <i class="fa-solid fa-arrow-left text-sm"></i>

                </button>

                <h2 id="formTitle" class="font-['Rajdhani',sans-serif] text-lg sm:text-[19px] font-semibold">

                    Add New Game

                </h2>

            </div>


            <!-- ================================================= -->
            <!-- GAME FORM -->
            <!-- ================================================= -->

            <form id="gameForm" enctype="multipart/form-data" novalidate>


                <!-- ================================================= -->
                <!-- GAME ID -->
                <!-- ================================================= -->

                <input type="hidden" id="gameId" name="id" value="">


                <!-- ================================================= -->
                <!-- GAME NAME -->
                <!-- ================================================= -->

                <div class="mb-4">

                    <label for="gameName"
                        class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                        Game Name

                    </label>

                    <input type="text" id="gameName" name="title" placeholder="e.g. Elden Ring" required
                        class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none transition focus:border-[#7C2CFF] focus:ring-[3px] focus:ring-purple-500/15">

                    <div id="gameNameError" class="hidden text-[#EF4444] text-xs mt-1.5">

                        Game name must be at least 2 characters.

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- DEVELOPER / PUBLISHER -->
                <!-- ================================================= -->

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

                    <div>

                        <label for="gameDeveloper"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Developer

                        </label>

                        <input type="text" id="gameDeveloper" name="developer" placeholder="e.g. FromSoftware"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                    </div>


                    <div>

                        <label for="gamePublisher"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Publisher

                        </label>

                        <input type="text" id="gamePublisher" name="publisher" placeholder="e.g. Bandai Namco"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- CATEGORY / PLATFORMS -->
                <!-- ================================================= -->

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

                    <div>

                        <label for="gameCategory"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Category

                        </label>

                        <select id="gameCategory" name="category"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                            <option value="Action">
                                Action
                            </option>

                            <option value="RPG">
                                RPG
                            </option>

                            <option value="Racing">
                                Racing
                            </option>

                            <option value="Adventure">
                                Adventure
                            </option>

                            <option value="Sports">
                                Sports
                            </option>

                            <option value="Shooter">
                                Shooter
                            </option>

                            <option value="Strategy">
                                Strategy
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>


                    <div>

                        <label for="gamePlatforms"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Platforms

                        </label>

                        <input type="text" id="gamePlatforms" name="platforms" placeholder="e.g. PC, Linux"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- PRICE / OLD PRICE -->
                <!-- ================================================= -->

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

                    <div>

                        <label for="gamePrice"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Price ($)

                        </label>

                        <input type="number" id="gamePrice" name="price" step="0.01" min="0" placeholder="e.g. 39.99"
                            required
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                        <div id="gamePriceError" class="hidden text-[#EF4444] text-xs mt-1.5">

                            Please enter a valid price.

                        </div>

                    </div>


                    <div>

                        <label for="gameOldPrice"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Old Price ($)

                        </label>

                        <input type="number" id="gameOldPrice" name="old_price" step="0.01" min="0"
                            placeholder="Optional"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- DISCOUNT / RELEASE DATE -->
                <!-- ================================================= -->

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

                    <div>

                        <label for="gameDiscount"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Discount (%)

                        </label>

                        <input type="number" id="gameDiscount" name="discount" step="0.01" min="0" max="100" value="0"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                    </div>


                    <div>

                        <label for="gameReleaseDate"
                            class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                            Release Date

                        </label>

                        <input type="date" id="gameReleaseDate" name="release_date"
                            class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF]">

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- DESCRIPTION -->
                <!-- ================================================= -->

                <div class="mb-4">

                    <label for="gameDescription"
                        class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                        Description

                    </label>

                    <textarea id="gameDescription" name="description" rows="4" placeholder="Game description..."
                        class="w-full bg-[#0D0B1A] border border-[#26223D] text-white text-sm px-3.5 py-[11px] rounded-[10px] outline-none focus:border-[#7C2CFF] resize-none"></textarea>

                </div>


                <!-- ================================================= -->
                <!-- COVER IMAGE -->
                <!-- ================================================= -->

                <div class="mb-4">

                    <label for="gameCover"
                        class="block text-xs sm:text-[13px] text-[#A5A1B5] mb-2 font-['Rajdhani',sans-serif]">

                        Cover Image

                    </label>

                    <input type="file" id="gameCover" name="image" accept="image/*" onchange="handleCoverUpload(event)"
                        class="w-full bg-[#0D0B1A] border border-[#26223D] text-[#A5A1B5] cursor-pointer text-sm px-3.5 py-[9px] rounded-[10px] file:bg-[#4C1D95] file:text-white file:border-0 file:px-3 file:py-1.5 file:rounded-lg">

                    <div id="coverPreviewWrap" class="mt-2.5 hidden">

                        <img id="coverPreview" src="" alt="Cover Preview"
                            class="w-16 h-16 rounded-[10px] object-cover border border-[#26223D]">

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- GAME STATUS -->
                <!-- ================================================= -->

                <div class="mb-4">

                    <div
                        class="flex items-center justify-between bg-[#0D0B1A] border border-[#26223D] rounded-[10px] px-3.5 py-[11px]">

                        <span id="statusLabel" class="text-sm">

                            Active

                        </span>

                        <label class="relative w-11 h-6">

                            <input type="checkbox" id="gameStatus" name="status" value="active" checked
                                class="peer opacity-0 w-0 h-0">

                            <span
                                class="absolute inset-0 bg-[#26223D] rounded-full cursor-pointer peer-checked:bg-[#7C2CFF] transition"></span>

                            <span
                                class="absolute w-[18px] h-[18px] left-[3px] top-[3px] bg-white rounded-full transition peer-checked:translate-x-5 pointer-events-none"></span>

                        </label>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- FORM BUTTONS -->
                <!-- ================================================= -->

                <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 mt-6">

                    <button type="button" onclick="closeAddPage()"
                        class="w-full sm:flex-1 bg-transparent border border-[#26223D] text-[#A5A1B5] font-['Rajdhani',sans-serif] font-semibold py-[11px] rounded-xl hover:border-[#A5A1B5] hover:text-white">

                        Cancel

                    </button>


                    <button type="submit" id="submitBtn"
                        class="w-full sm:flex-[2] bg-gradient-to-br from-[#7C2CFF] to-[#B026FF] text-white font-['Rajdhani',sans-serif] font-semibold py-[11px] rounded-xl hover:shadow-[0_0_24px_rgba(176,38,255,0.45)]">

                        Add Game

                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- GAME DETAILS MODAL -->
    <!-- ========================================================= -->

    <div id="detailsPage"
        class="fixed inset-0 hidden items-start justify-center bg-[#070612]/95 backdrop-blur-sm px-3 sm:px-4 py-5 sm:py-6 overflow-y-auto z-[50]">

        <div class="w-full max-w-[560px] bg-[#111022] border border-[#26223D] rounded-2xl p-5 sm:p-7 mt-2 sm:mt-5 mb-5">


            <!-- ================================================= -->
            <!-- DETAILS HEADER -->
            <!-- ================================================= -->

            <div class="flex items-center gap-3 mb-5 sm:mb-6">

                <button type="button" onclick="closeDetailsPage()"
                    class="w-9 h-9 shrink-0 rounded-[10px] bg-purple-500/10 border border-[#26223D] text-[#A5A1B5] flex items-center justify-center transition hover:border-[#7C2CFF] hover:text-[#A855F7]">

                    <i class="fa-solid fa-arrow-left text-sm"></i>

                </button>

                <h2 class="font-['Rajdhani',sans-serif] text-lg sm:text-[19px] font-semibold">

                    Game Details

                </h2>

            </div>


            <!-- ================================================= -->
            <!-- COVER + TITLE -->
            <!-- ================================================= -->

            <div class="flex items-center gap-4 mb-5">

                <img id="detailsImage" src="" alt="Game cover"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover border border-[#26223D] shrink-0"
                    onerror="this.onerror=null; this.src='src/Images/games/default.png'">

                <div class="min-w-0">

                    <p id="detailsTitle" class="text-white text-base sm:text-lg font-semibold truncate">

                    </p>

                    <span id="detailsStatus"
                        class="inline-flex items-center px-2.5 py-1 mt-1.5 rounded-full border text-xs font-medium">

                    </span>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- GAME INFORMATION -->
            <!-- ================================================= -->

            <div class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm mb-5">


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Developer
                    </p>

                    <p id="detailsDeveloper" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Publisher
                    </p>

                    <p id="detailsPublisher" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Category
                    </p>

                    <p id="detailsCategory" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Release Date
                    </p>

                    <p id="detailsReleaseDate" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Price
                    </p>

                    <p id="detailsPrice" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Old Price
                    </p>

                    <p id="detailsOldPrice" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Discount
                    </p>

                    <p id="detailsDiscount" class="text-white truncate">

                    </p>

                </div>


                <div>

                    <p class="text-[#6B687A] text-[11px] mb-1">
                        Rating
                    </p>

                    <p id="detailsRating" class="text-white truncate">

                    </p>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- DESCRIPTION -->
            <!-- ================================================= -->

            <div class="mb-6">

                <p class="text-[#6B687A] text-[11px] mb-1">
                    Description
                </p>

                <p id="detailsDescription" class="text-[#A5A1B5] text-sm leading-relaxed">

                </p>

            </div>


            <!-- ================================================= -->
            <!-- DETAILS BUTTONS -->
            <!-- ================================================= -->

            <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3">

                <button type="button" onclick="closeDetailsPage()"
                    class="w-full sm:flex-1 bg-transparent border border-[#26223D] text-[#A5A1B5] font-['Rajdhani',sans-serif] font-semibold py-[11px] rounded-xl hover:border-[#A5A1B5] hover:text-white">

                    Close

                </button>


                <button type="button" id="detailsEditBtn"
                    class="w-full sm:flex-[2] bg-gradient-to-br from-[#7C2CFF] to-[#B026FF] text-white font-['Rajdhani',sans-serif] font-semibold py-[11px] rounded-xl hover:shadow-[0_0_24px_rgba(176,38,255,0.45)]">

                    Edit Game

                </button>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- JAVASCRIPT -->
    <!-- ========================================================= -->

    <script src="./js/main.js"></script>

    <script src="./js/manage-games.js"></script>


</body>

</html>