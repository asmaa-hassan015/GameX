<?php

session_start();

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Reviews</title>

    <!-- =========================================================
         TAILWIND
    ========================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- =========================================================
         TAILWIND CONFIG
    ========================================================== -->

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

    <!-- =========================================================
         GOOGLE FONTS
    ========================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- =========================================================
         FONT AWESOME
    ========================================================== -->

    <link rel="stylesheet" href="./assets/css/all.min.css">

    <!-- =========================================================
         MAIN CSS
    ========================================================== -->

    <link rel="stylesheet" href="./css/style2.css">

</head>

<body>

    <!-- =========================================================
         NAVBAR
    ========================================================== -->

    <?php include 'components/nav.php'; ?>


    <!-- =========================================================
         MAIN PAGE WRAPPER
    ========================================================== -->

    <div class="min-h-screen max-w-[1450px] mx-auto p-4 md:p-7">


        <!-- =====================================================
             REVIEWS HEADER
        ====================================================== -->

        <section class="py-9 border-b border-[#24213a] flex flex-col md:flex-row justify-between gap-5">

            <!-- =================================================
                 HEADER CONTENT
            ================================================== -->

            <div>

                <h1 class="heading text-5xl font-bold">
                    Reviews
                </h1>

                <p class="muted mt-4 max-w-xl">
                    Read honest reviews from our gaming community<br>
                    and share your own experience.
                </p>

            </div>


            <!-- =================================================
                 WRITE REVIEW BUTTON
            ================================================== -->

            <button
                id="openWriteReview"
                type="button"
                class="neon rounded-xl px-7 py-3 text-[#a855f7] self-start hover:bg-[#7c2cff]/10 transition cursor-pointer">

                <i class="fa-solid fa-pen"></i>

                Write a Review

            </button>

        </section>


        <!-- =====================================================
             CUSTOMER REVIEWS
        ====================================================== -->

        <section class="py-10">


            <!-- =================================================
                 REVIEWS SECTION HEADER
            ================================================== -->

            <div class="flex justify-between mb-7 items-center">

                <h2 class="heading text-3xl flex items-center">

                    <i class="fa-regular fa-comments text-[#a855f7] mr-3"></i>

                    Customer Reviews

                </h2>


                <!-- =================================================
                     VIEW ALL REVIEWS
                ================================================== -->

                <a
                    href="#"
                    id="viewAllBtn"
                    class="text-[#a855f7] hover:underline">
                    View All Reviews →
                </a>

            </div>


            <!-- =================================================
                 REVIEWS GRID
            ================================================== -->

            <div
                id="reviewsGrid"
                class="grid md:grid-cols-2 gap-6">
            </div>


            <!-- =================================================
                 PAGINATION
            ================================================== -->

            <div
                id="pagination"
                class="flex justify-center flex-wrap gap-3 mt-9">
            </div>

        </section>

    </div>


    <!-- =========================================================
         WRITE REVIEW MODAL
    ========================================================== -->

    <div
        id="reviewModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">

        <div class="glass neon rounded-2xl w-full max-w-lg p-8 relative">


            <!-- =================================================
                 CLOSE BUTTON
            ================================================== -->

            <button
                id="closeWriteReview"
                type="button"
                class="absolute top-4 right-5 text-2xl muted hover:text-white cursor-pointer">
                &times;
            </button>


            <!-- =================================================
                 MODAL TITLE
            ================================================== -->

            <h3 class="heading text-3xl mb-6">

                <i class="fa-solid fa-pen text-[#a855f7] mr-2"></i>

                Write a Review

            </h3>


            <!-- =================================================
                 REVIEW FORM
            ================================================== -->

            <form id="reviewForm" class="space-y-5">


                <!-- =================================================
                     GAME
                ================================================== -->

                <div>

                    <label
                        class="block muted mb-2 text-sm"
                        for="reviewGame">
                        Game
                    </label>

                    <select
                        id="reviewGame"
                        required
                        class="w-full bg-[#0d0f1c] border border-[#24213a] rounded-lg px-4 py-3 outline-none focus:border-[#7c2cff]">

                        <option value="">
                            Loading games...
                        </option>

                    </select>

                </div>


                <!-- =================================================
                     RATING
                ================================================== -->

                <div>

                    <label class="block muted mb-2 text-sm">
                        Your Rating
                    </label>

                    <div
                        id="starPicker"
                        class="star-pick text-3xl text-gray-600 flex gap-2">

                        <i class="fa-solid fa-star cursor-pointer" data-value="1"></i>

                        <i class="fa-solid fa-star cursor-pointer" data-value="2"></i>

                        <i class="fa-solid fa-star cursor-pointer" data-value="3"></i>

                        <i class="fa-solid fa-star cursor-pointer" data-value="4"></i>

                        <i class="fa-solid fa-star cursor-pointer" data-value="5"></i>

                    </div>

                    <input
                        type="hidden"
                        id="ratingValue"
                        value="5">

                </div>


                <!-- =================================================
                     REVIEW TEXT
                ================================================== -->

                <div>

                    <label
                        class="block muted mb-2 text-sm"
                        for="reviewText">
                        Your Review
                    </label>

                    <textarea
                        required
                        id="reviewText"
                        rows="4"
                        placeholder="Tell us about your experience..."
                        class="w-full bg-[#0d0f1c] border border-[#24213a] rounded-lg px-4 py-3 outline-none focus:border-[#7c2cff] resize-none"></textarea>

                </div>


                <!-- =================================================
                     FORM MESSAGE
                ================================================== -->

                <p
                    id="reviewMessage"
                    class="hidden text-sm">
                </p>


                <!-- =================================================
                     SUBMIT BUTTON
                ================================================== -->

                <button
                    type="submit"
                    id="submitReviewBtn"
                    class="purple w-full rounded-xl px-5 py-3 font-semibold shadow-neon cursor-pointer">
                    Submit Review
                </button>

            </form>

        </div>

    </div>


    <!-- =========================================================
         REVIEW DETAILS MODAL
    ========================================================== -->

    <div
        id="detailsModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">

        <div class="glass neon rounded-2xl w-full max-w-lg p-8 relative">


            <!-- =================================================
                 CLOSE DETAILS BUTTON
            ================================================== -->

            <button
                id="closeDetails"
                type="button"
                class="absolute top-4 right-5 text-2xl muted hover:text-white cursor-pointer">
                &times;
            </button>


            <!-- =================================================
                 REVIEWER INFORMATION
            ================================================== -->

            <div class="flex items-center gap-4 mb-4">


                <!-- =================================================
                     AVATAR
                ================================================== -->

                <div
                    class="h-14 w-14 rounded-full border border-[#7c2cff] bg-[#17121f] grid place-items-center overflow-hidden">

                    <img
                        id="detailsAvatar"
                        class="hidden h-full w-full object-cover"
                        alt="Avatar">

                    <i
                        id="detailsAvatarIcon"
                        class="fa-solid fa-user text-xl text-[#a855f7]"></i>

                </div>


                <!-- =================================================
                     NAME + RATING
                ================================================== -->

                <div>

                    <h3
                        id="detailsName"
                        class="text-xl font-bold"></h3>

                    <div
                        id="detailsStars"
                        class="text-yellow-400 text-sm mt-1"></div>

                </div>

            </div>


            <!-- =================================================
                 GAME
            ================================================== -->

            <p
                id="detailsGame"
                class="text-[#a855f7] text-sm mb-2"></p>


            <!-- =================================================
                 DATE
            ================================================== -->

            <p
                id="detailsDate"
                class="muted text-sm mb-4"></p>


            <!-- =================================================
                 REVIEW CONTENT
            ================================================== -->

            <p
                id="detailsText"
                class="text-gray-300 leading-7"></p>

        </div>

    </div>


    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <script src="./js/Review.js"></script>

</body>

</html>