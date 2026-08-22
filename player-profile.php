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

        <section class="profile-scene neon rounded-2xl p-7 md:p-10 mt-7">


            <!-- =================================================
                 PROFILE BACKGROUND
            ================================================== -->

            <svg class="scene-layer" viewBox="0 0 1400 400" preserveAspectRatio="xMaxYMid slice"
                xmlns="http://www.w3.org/2000/svg">

                <!-- Moon -->

                <circle cx="1180" cy="90" r="55" fill="#e9defb" opacity=".18" />

                <circle cx="1180" cy="90" r="80" fill="#a855f7" opacity=".08" />


                <!-- Buildings -->

                <g fill="#150a2b" opacity=".65">

                    <rect x="980" y="150" width="14" height="90" />

                    <rect x="1000" y="120" width="14" height="120" />

                    <polygon points="1000,120 1007,100 1014,120" />

                    <rect x="1020" y="160" width="14" height="80" />

                    <rect x="1045" y="100" width="18" height="140" />

                    <polygon points="1045,100 1054,78 1063,100" />

                    <rect x="1075" y="140" width="14" height="100" />

                    <rect x="1100" y="115" width="16" height="125" />

                    <polygon points="1100,115 1108,95 1116,115" />

                    <rect x="1125" y="155" width="14" height="85" />

                </g>


                <!-- Background Mountains -->

                <polygon points="0,240 200,150 380,220 560,140 760,230 950,170 1150,240 1400,190 1400,400 0,400"
                    fill="#0d0620" opacity=".8" />

                <polygon points="0,280 250,220 500,270 780,210 1050,260 1400,230 1400,400 0,400" fill="#160b30"
                    opacity=".7" />

            </svg>


            <!-- =================================================
                 PROFILE CONTENT
            ================================================== -->

            <div class="relative flex flex-col md:flex-row items-center gap-8">


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


                    <!-- =================================================
                         PLAYER NAME + EDIT
                    ================================================== -->

                    <div class="flex justify-between items-center gap-4 flex-wrap">

                        <div>

                            <h1 id="playerName" class="heading text-4xl font-bold">
                                Loading...
                            </h1>

                            <p class="text-[#a855f7] mt-3">

                                Level

                                <span id="playerLevel">
                                    1
                                </span>

                            </p>

                            <p class="text-gray-500 text-xs mt-1">

                                Level is calculated automatically from your XP.

                            </p>

                        </div>


                        <!-- =================================================
                             EDIT PROFILE
                        ================================================== -->

                        <button id="openEditProfile" type="button"
                            class="neon rounded-lg px-5 py-3 hover:bg-[#7c2cff]/10 transition">

                            <i class="fa-solid fa-pen mr-2"></i>

                            Edit Profile

                        </button>

                    </div>


                    <!-- =================================================
                         XP
                    ================================================== -->

                    <div class="flex justify-between text-sm mt-5">

                        <span id="xpNote">
                            Loading XP...
                        </span>

                        <span id="xpText">
                            0 / 100 XP
                        </span>

                    </div>


                    <!-- XP BAR -->

                    <div class="h-3 rounded-full bg-[#211737] mt-2">

                        <div id="xpBar" class="h-3 rounded-full bg-gradient-to-r from-[#7c2cff] to-[#a855f7]"
                            style="width:0%"></div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             PROFILE STATS
        ====================================================== -->

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mt-8">


            <!-- =================================================
                 GAMES
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <i class="fa-solid fa-gamepad text-4xl text-[#a855f7]"></i>

                <p class="muted mt-4">
                    Games Owned
                </p>

                <b id="statGames" class="heading text-4xl">
                    0
                </b>

            </div>


            <!-- =================================================
                 ACHIEVEMENTS
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <i class="fa-solid fa-trophy text-4xl text-[#a855f7]"></i>

                <p class="muted mt-4">
                    Achievements
                </p>

                <b id="statAchievements" class="heading text-4xl">
                    0
                </b>

            </div>


            <!-- =================================================
                 HOURS
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <i class="fa-regular fa-clock text-4xl text-[#a855f7]"></i>

                <p class="muted mt-4">
                    Hours Played
                </p>

                <b id="statHours" class="heading text-4xl">
                    0h
                </b>

            </div>


            <!-- =================================================
                 WISHLIST
            ================================================== -->

            <div class="glass rounded-2xl p-7 text-center">

                <i class="fa-regular fa-heart text-4xl text-[#a855f7]"></i>

                <p class="muted mt-4">
                    Wishlist
                </p>

                <b id="statWishlist" class="heading text-4xl">
                    0
                </b>

            </div>

        </div>


        <!-- =====================================================
             RECENTLY PLAYED
        ====================================================== -->

        <section class="glass rounded-2xl p-6 mt-8">


            <!-- =================================================
                 SECTION HEADER
            ================================================== -->

            <div class="flex justify-between mb-5">

                <h2 class="heading text-2xl">

                    <i class="fa-solid fa-gamepad text-[#a855f7] mr-2"></i>

                    Recently Played

                </h2>


                <button id="viewAllGames" type="button" class="text-[#a855f7] hover:underline">

                    View All &rsaquo;

                </button>

            </div>


            <!-- =================================================
                 GAMES GRID
            ================================================== -->

            <div id="gamesGrid" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="col-span-full text-center py-8 text-gray-500">
                    Loading games...
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
                     LEVEL INFORMATION
                ================================================== -->

                <div class="rounded-xl border border-[#24213a] bg-[#0d0f1c] p-4">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-lg bg-[#7c2cff]/10 grid place-items-center">

                            <i class="fa-solid fa-star text-[#a855f7]"></i>

                        </div>


                        <div>

                            <p class="text-sm font-semibold text-white">
                                Current Level
                            </p>

                            <p id="editLevelInfo" class="text-[#a855f7] font-bold">
                                Level 1
                            </p>

                        </div>

                    </div>


                    <p class="text-xs text-gray-500 mt-3">

                        Your level is calculated automatically from your XP.
                        You cannot change it manually.

                    </p>

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


</body>

</html>