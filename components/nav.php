<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION["user_id"]);

$navUsername = $_SESSION["username"] ?? "Player";

$navAvatar = $_SESSION["avatar"] ?? null;


// =========================================================
// DEFAULT AVATAR
// =========================================================

$avatarSrc = $navAvatar
    ? htmlspecialchars($navAvatar, ENT_QUOTES, 'UTF-8')
    : "src/Images/avatars/blaze.png";


// =========================================================
// NAVBAR
// =========================================================

?>

<nav class="bg-[#070612] sticky top-0 left-0 right-0 z-50 w-full border-b border-b-[#4d1d955c] p-3">

    <div class="flex flex-wrap items-center justify-between gap-4">


        <!-- ===================================================== -->
        <!-- LOGO -->
        <!-- ===================================================== -->

        <h1 class="text-2xl font-bold flex items-center gap-2">

            <a href="index.php">

                <img width="180" src="src/Images/logo.png" alt="GAME X Logo">

            </a>

        </h1>


        <!-- ===================================================== -->
        <!-- NAVIGATION LINKS -->
        <!-- ===================================================== -->

        <ul class="flex gap-6 text-lg flex-wrap">

            <!-- Home -->

            <li class="hover:text-[#ac38de84] duration-300">

                <a href="index.php">
                    Home
                </a>

            </li>


            <!-- Games -->

            <li class="hover:text-[#ac38de84] duration-300">

                <a href="Games.php">
                    Games
                </a>

            </li>


            <!-- Top Players -->

            <li class="hover:text-[#ac38de84] duration-300">

                <a href="top-players.php">
                    Top players
                </a>

            </li>


            <!-- Reviews -->

            <li class="hover:text-[#ac38de84] duration-300">

                <a href="Review.php">
                    Review
                </a>

            </li>


            <!-- Contact -->

            <li class="hover:text-[#ac38de84] duration-300">

                <a href="index.php#contactus">
                    Contact us
                </a>

            </li>

        </ul>


        <!-- ===================================================== -->
        <!-- RIGHT SIDE -->
        <!-- ===================================================== -->

        <div class="flex flex-row gap-6 items-center">


            <!-- ================================================= -->
            <!-- WISHLIST -->
            <!-- ================================================= -->

            <a class="relative text-xl hover:text-[#A726DD] duration-300 cursor-pointer" href="Favorite.php">

                <i class="fa-regular fa-heart"></i>

                <span id="wishlist-badge" class="hidden" style="
                        position: absolute;
                        top: -8px;
                        right: -10px;
                        background-color: #a726dd;
                        color: #fff;
                        font-size: 10px;
                        line-height: 1;
                        font-weight: bold;
                        min-width: 16px;
                        height: 16px;
                        padding: 0 4px;
                        border-radius: 9999px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 2px solid #070612;
                    ">
                    0
                </span>

            </a>


            <!-- ================================================= -->
            <!-- CART -->
            <!-- ================================================= -->

            <a class="relative text-xl hover:text-[#A726DD] duration-300 cursor-pointer" href="Cart.php">

                <i class="fa-solid fa-cart-shopping"></i>

                <span id="cart-badge" class="hidden" style="
                        position: absolute;
                        top: -8px;
                        right: -10px;
                        background-color: #a726dd;
                        color: #fff;
                        font-size: 10px;
                        line-height: 1;
                        font-weight: bold;
                        min-width: 16px;
                        height: 16px;
                        padding: 0 4px;
                        border-radius: 9999px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 2px solid #070612;
                    ">
                    0
                </span>

            </a>


            <?php if ($isLoggedIn): ?>


            <!-- ================================================= -->
            <!-- USER PROFILE -->
            <!-- ================================================= -->

            <a href="player-profile.php" title="<?= htmlspecialchars(
                                                        $navUsername,
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>" class="shrink-0 hover:opacity-80 duration-300">

                <img id="navbarAvatar" src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars(
                                                                            $navUsername,
                                                                            ENT_QUOTES,
                                                                            'UTF-8'
                                                                        ) ?>"
                    class="w-10 h-10 rounded-full object-cover border-2 border-[#A726DD]" style="
                        width: 40px;
                        height: 40px;
                        border-radius: 9999px;
                        object-fit: cover;
                        border: 2px solid #A726DD;
                    ">

            </a>


            <!-- ================================================= -->
            <!-- LOG OUT -->
            <!-- ================================================= -->

            <a href="logout.php"
                class="bg-[#5207A1] p-3 border-0 rounded-xl text-xl hover:bg-[#5107a18f] duration-300 cursor-pointer">
                Log Out
            </a>


            <?php else: ?>


            <!-- ================================================= -->
            <!-- SIGN IN -->
            <!-- ================================================= -->

            <a href="Login.php"
                class="bg-[#5207A1] p-3 border-0 rounded-xl text-xl hover:bg-[#5107a18f] duration-300 cursor-pointer">
                Sign In
            </a>


            <?php endif; ?>

        </div>

    </div>

</nav>