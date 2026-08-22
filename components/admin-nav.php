<nav class="bg-[#070612] sticky top-0 left-0 right-0 z-50 w-full border-b border-b-[#4d1d955c] p-3">

    <!-- ========================================================= -->
    <!-- LOGO -->
    <!-- ========================================================= -->

    <div class="flex flex-wrap items-center justify-between gap-4">

        <h1 class="text-2xl font-bold flex items-center gap-2">

            <a href="dashboard.php">

                <img width="180px" src="src/Images/logo.png" alt="GAME X Logo" />

            </a>

        </h1>


        <!-- ========================================================= -->
        <!-- ADMIN NAVIGATION -->
        <!-- ========================================================= -->

        <ul class="flex flex-wrap gap-6 text-lg items-center">

            <!-- Dashboard -->

            <li class="hover:text-[#a855f7] duration-300">

                <a href="dashboard.php">

                    <i class="fa-solid fa-chart-line mr-1"></i>

                    Dashboard

                </a>

            </li>


            <!-- Manage Players -->

            <li class="hover:text-[#a855f7] duration-300">

                <a href="manage-players.php">

                    <i class="fa-solid fa-users mr-1"></i>

                    Players

                </a>

            </li>


            <!-- Manage Games -->

            <li class="hover:text-[#a855f7] duration-300">

                <a href="manage-games.php">

                    <i class="fa-solid fa-gamepad mr-1"></i>

                    Games

                </a>

            </li>


            <!-- Manage Orders -->

            <li class="hover:text-[#a855f7] duration-300">

                <a href="manage-orders.php">

                    <i class="fa-solid fa-cart-shopping mr-1"></i>

                    Orders

                </a>

            </li>

        </ul>


        <!-- ========================================================= -->
        <!-- RIGHT SIDE -->
        <!-- ========================================================= -->

        <div class="flex items-center gap-5">


            <!-- ===================================================== -->
            <!-- ADMIN PROFILE -->
            <!-- ===================================================== -->

            <a href="admin-profile.php" class="flex items-center gap-2 hover:text-[#a855f7] duration-300">

                <div
                    class="w-10 h-10 rounded-full bg-[#7c2cff]/20 border border-[#7c2cff] flex items-center justify-center">

                    <i class="fa-solid fa-user-shield text-[#a855f7]"></i>

                </div>

                <span class="hidden lg:block">
                    Admin
                </span>

            </a>


            <!-- ===================================================== -->
            <!-- LOGOUT -->
            <!-- ===================================================== -->

            <a href="logout.php" class="bg-[#5207A1] px-4 py-3 rounded-xl text-lg hover:bg-[#5107a18f] duration-300">

                <i class="fa-solid fa-right-from-bracket mr-1"></i>

                Logout

            </a>

        </div>

    </div>

</nav>