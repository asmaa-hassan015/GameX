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

    <title>GAME X · Profile</title>


    <!-- =====================================================
         TAILWIND
    ====================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>

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
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- =====================================================
         MAIN CSS
    ====================================================== -->

    <link rel="stylesheet" href="./css/style2.css">

</head>


<body data-user-id="<?= $userId ?>">


    <!-- =====================================================
         NAVBAR
    ====================================================== -->

    <?php include 'components/nav.php'; ?>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="min-h-screen max-w-[1450px] mx-auto p-4 md:p-7">


        <!-- =====================================================
             PROFILE HEADER
        ====================================================== -->

        <section class="profile-scene neon rounded-2xl p-7 md:p-10 mt-7 relative overflow-hidden">


            <!-- =================================================
                 PROFILE BACKGROUND
            ================================================== -->

            <svg class="scene-layer" viewBox="0 0 1400 400" preserveAspectRatio="xMaxYMid slice"
                xmlns="http://www.w3.org/2000/svg">

                <!-- Dot grid -->

                <g fill="#a855f7" opacity=".15">
                    <?php
                    for ($row = 0; $row < 10; $row++) {
                        for ($col = 0; $col < 20; $col++) {
                            $cx = 700 + $col * 32;
                            $cy = 40 + $row * 32;
                            echo "<circle cx=\"$cx\" cy=\"$cy\" r=\"1.6\" />";
                        }
                    }
                    ?>
                </g>

                <!-- Controller silhouette -->

                <g transform="translate(1080,140)" fill="none" stroke="#7c2cff" stroke-width="3" opacity=".35">
                    <path d="M40 60 Q40 20 90 20 H210 Q260 20 260 60 L270 130 Q275 165 245 165 Q220 165 210 140 L200 120
                           H100 L90 140 Q80 165 55 165 Q25 165 30 130 Z" />
                    <circle cx="105" cy="75" r="10" />
                    <circle cx="195" cy="75" r="10" />
                    <line x1="60" y1="105" x2="60" y2="135" />
                    <line x1="45" y1="120" x2="75" y2="120" />
                </g>

            </svg>


            <!-- =================================================
                 PROFILE CONTENT
            ================================================== -->

            <div class="relative flex flex-col md:flex-row items-center md:items-start gap-8">


                <!-- =================================================
                     AVATAR
                ================================================== -->

                <div
                    class="avatar-ring h-36 w-36 rounded-full border-2 border-[#7c2cff] bg-[#0a0614] grid place-items-center shrink-0 overflow-hidden">

                    <img id="playerAvatar" src="" alt="Player Avatar" class="hidden h-full w-full object-cover">

                    <div id="defaultAvatar" class="h-full w-full grid place-items-center">

                        <i class="fa-solid fa-user text-6xl text-[#a855f7]"></i>

                    </div>

                </div>


                <!-- =================================================
                     PLAYER INFO
                ================================================== -->

                <div class="flex-1 w-full">

                    <div class="flex justify-between items-start gap-4 flex-wrap">

                        <div>

                            <h1 id="playerName" class="heading text-4xl font-bold">
                                Loading...
                            </h1>

                            <p id="memberSince" class="text-gray-400 mt-2">
                                &nbsp;
                            </p>

                            <p class="text-gray-400 mt-3 max-w-md">
                                Manage your orders, wishlist and account details.
                            </p>

                        </div>


                        <!-- =================================================
                             EDIT PROFILE
                        ================================================== -->

                        <button id="openEditProfile" type="button"
                            class="neon rounded-lg px-5 py-3 hover:bg-[#7c2cff]/10 transition shrink-0">

                            <i class="fa-solid fa-pen mr-2"></i>

                            Edit Profile

                        </button>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             PROFILE STATS
        ====================================================== -->

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mt-8">


            <!-- =================================================
                 TOTAL ORDERS
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <div class="w-14 h-14 mx-auto rounded-full bg-[#7c2cff]/15 grid place-items-center">
                    <i class="fa-solid fa-bag-shopping text-2xl text-[#a855f7]"></i>
                </div>

                <p class="muted mt-4">
                    Total Orders
                </p>

                <b id="statOrders" class="heading text-4xl block">
                    0
                </b>

                <a href="Orders.php" class="text-[#a855f7] hover:underline text-sm inline-flex items-center gap-1 mt-2">
                    View all orders <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

            </div>


            <!-- =================================================
                 GAMES PURCHASED
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <div class="w-14 h-14 mx-auto rounded-full bg-[#7c2cff]/15 grid place-items-center">
                    <i class="fa-solid fa-gamepad text-2xl text-[#a855f7]"></i>
                </div>

                <p class="muted mt-4">
                    Games Purchased
                </p>

                <b id="statGames" class="heading text-4xl block">
                    0
                </b>

                <a href="Games.php" class="text-[#a855f7] hover:underline text-sm inline-flex items-center gap-1 mt-2">
                    View all games <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

            </div>


            <!-- =================================================
                 WISHLIST
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <div class="w-14 h-14 mx-auto rounded-full bg-[#7c2cff]/15 grid place-items-center">
                    <i class="fa-regular fa-heart text-2xl text-[#a855f7]"></i>
                </div>

                <p class="muted mt-4">
                    Wishlist
                </p>

                <b id="statWishlist" class="heading text-4xl block">
                    0
                </b>

                <a href="Wishlist.php"
                    class="text-[#a855f7] hover:underline text-sm inline-flex items-center gap-1 mt-2">
                    View wishlist <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

            </div>


            <!-- =================================================
                 REVIEWS
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <div class="w-14 h-14 mx-auto rounded-full bg-[#7c2cff]/15 grid place-items-center">
                    <i class="fa-solid fa-star text-2xl text-[#a855f7]"></i>
                </div>

                <p class="muted mt-4">
                    Reviews
                </p>

                <b id="statReviews" class="heading text-4xl block">
                    0
                </b>

                <a href="Reviews.php"
                    class="text-[#a855f7] hover:underline text-sm inline-flex items-center gap-1 mt-2">
                    View my reviews <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

            </div>

        </div>


        <!-- =====================================================
             RECENT PURCHASES
        ====================================================== -->

        <section class="glass rounded-2xl p-6 mt-8">


            <!-- =================================================
                 SECTION HEADER
            ================================================== -->

            <div class="flex justify-between items-center mb-5">

                <h2 class="heading text-2xl">

                    <i class="fa-solid fa-gamepad text-[#a855f7] mr-2"></i>

                    Recent Purchases

                </h2>


                <a href="Orders.php" class="text-[#a855f7] hover:underline text-sm inline-flex items-center gap-1">
                    View All Orders <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

            </div>


            <!-- =================================================
                 PURCHASES GRID
            ================================================== -->

            <div id="purchasesGrid" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="col-span-full text-center py-8 text-gray-500">
                    Loading purchases...
                </div>

            </div>

        </section>

    </main>


    <!-- =====================================================
         EDIT PROFILE MODAL
    ====================================================== -->

    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">

        <div class="glass neon rounded-2xl w-full max-w-md p-8 relative max-h-[90vh] overflow-y-auto">


            <!-- =================================================
                 CLOSE BUTTON
            ================================================== -->

            <button id="closeEditModal" type="button" class="absolute top-4 right-5 text-2xl muted hover:text-white">
                &times;
            </button>


            <!-- =================================================
                 MODAL TITLE
            ================================================== -->

            <h3 class="heading text-3xl mb-6">

                <i class="fa-solid fa-pen text-[#a855f7] mr-2"></i>

                Edit Profile

            </h3>


            <!-- =================================================
                 EDIT PROFILE FORM
            ================================================== -->

            <form id="editForm" class="space-y-6" enctype="multipart/form-data">


                <!-- =================================================
                     DISPLAY NAME
                ================================================== -->

                <div>

                    <label for="editName" class="block muted mb-2 text-sm">
                        Display Name
                    </label>

                    <input id="editName" name="name" type="text" placeholder="Enter display name" maxlength="50"
                        autocomplete="off" required
                        class="w-full bg-[#0d0f1c] border border-[#24213a] rounded-lg px-4 py-3 outline-none focus:border-[#7c2cff]">

                    <p id="nameError" class="text-red-400 text-sm mt-2 hidden"></p>

                </div>


                <!-- =================================================
                     PROFILE PICTURE
                ================================================== -->

                <div>

                    <label for="editAvatar" class="block muted mb-3 text-sm">
                        Profile Picture
                    </label>


                    <!-- =================================================
                         AVATAR PREVIEW
                    ================================================== -->

                    <div class="flex justify-center mb-5">

                        <div
                            class="w-28 h-28 rounded-full overflow-hidden border-2 border-[#7c2cff] bg-[#0a0614] grid place-items-center">

                            <img id="avatarPreview" src="" alt="Avatar Preview"
                                class="hidden w-full h-full object-cover">

                            <i id="avatarPreviewDefault" class="fa-solid fa-user text-5xl text-[#a855f7]"></i>

                        </div>

                    </div>


                    <!-- =================================================
                         AVATAR FILE INPUT
                    ================================================== -->

                    <input id="editAvatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-gray-300
                        file:mr-4
                        file:py-2
                        file:px-4
                        file:rounded-lg
                        file:border-0
                        file:bg-[#7c2cff]
                        file:text-white
                        file:font-semibold
                        hover:file:bg-[#a855f7]
                        cursor-pointer">


                    <p class="text-xs text-gray-500 mt-2">

                        JPG, PNG or WEBP · Maximum 5MB

                    </p>


                    <p id="avatarError" class="text-red-400 text-sm mt-2 hidden"></p>

                </div>


                <!-- =================================================
                     SAVE PROFILE
                ================================================== -->

                <button id="saveProfileBtn" type="submit"
                    class="purple w-full rounded-xl px-5 py-3 font-semibold shadow-neon">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>

                    Save Changes

                </button>

            </form>

        </div>

    </div>


    <!-- =====================================================
         TOAST
    ====================================================== -->

    <div id="toast"
        class="toast fixed bottom-6 left-1/2 -translate-x-1/2 opacity-0 pointer-events-none glass neon rounded-xl px-6 py-3 z-50">
    </div>


    <!-- =====================================================
         CURRENT USER ID
    ====================================================== -->

    <script>
    window.CURRENT_USER_ID = <?= $userId ?>;
    </script>


    <!-- =====================================================
         PROFILE JAVASCRIPT
    ====================================================== -->

    <script src="./js/player-profile.js"></script>
    <script src="./js/cart.js"></script>
    <script src="./js/favorites.js"></script>


</body>

</html>