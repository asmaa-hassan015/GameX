<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X · Admin Dashboard</title>

    <!-- Tailwind -->

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind Config -->

    <script>
    tailwind.config = {

        theme: {

            extend: {

                colors: {

                    dash: {

                        bg: '#080813',

                        card: '#0f0f23',

                        border: '#1d1d3b',

                        purple: '#8b5cf6'

                    }

                },

                fontFamily: {

                    poppins: ['Poppins', 'sans-serif']

                }

            }

        }

    };
    </script>

    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="./assets/css/all.min.css">

    <!-- Main CSS -->

    <link rel="stylesheet" href="./css/style2.css">

</head>

<body class="bg-[#080813] text-white font-poppins">

    <!-- =====================================================
         ADMIN NAVBAR
    ====================================================== -->

    <?php include 'components/admin-nav.php'; ?>

    <!-- =====================================================
         DASHBOARD
    ====================================================== -->

    <main class="max-w-[1300px] mx-auto p-6 md:p-10 space-y-6">

        <!-- =================================================
             KPI CARDS
        ================================================== -->

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Total Players -->

            <div class="card rounded-2xl p-5 flex items-center justify-between">

                <div class="space-y-1">

                    <p class="text-xs text-gray-400 font-medium">
                        Total Players
                    </p>

                    <h3 id="totalPlayers" class="text-2xl font-bold">

                        0

                    </h3>

                    <p id="playersGrowth" class="text-[11px] text-purple-400 font-medium">

                        <i class="fa-solid fa-arrow-up text-[9px]"></i>

                        0%

                        <span class="text-gray-500 font-normal">
                            from last week
                        </span>

                    </p>

                </div>

                <div
                    class="w-12 h-12 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-lg">

                    <i class="fa-solid fa-users"></i>

                </div>

            </div>

            <!-- Total Games -->

            <div class="card rounded-2xl p-5 flex items-center justify-between">

                <div class="space-y-1">

                    <p class="text-xs text-gray-400 font-medium">
                        Total Games
                    </p>

                    <h3 id="totalGames" class="text-2xl font-bold">

                        0

                    </h3>

                    <p id="gamesGrowth" class="text-[11px] text-purple-400 font-medium">

                        <i class="fa-solid fa-arrow-up text-[9px]"></i>

                        0%

                        <span class="text-gray-500 font-normal">
                            from last week
                        </span>

                    </p>

                </div>

                <div
                    class="w-12 h-12 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-lg">

                    <i class="fa-solid fa-gamepad"></i>

                </div>

            </div>

            <!-- Total Orders -->

            <div class="card rounded-2xl p-5 flex items-center justify-between">

                <div class="space-y-1">

                    <p class="text-xs text-gray-400 font-medium">
                        Total Orders
                    </p>

                    <h3 id="totalOrders" class="text-2xl font-bold">

                        0

                    </h3>

                    <p id="ordersGrowth" class="text-[11px] text-purple-400 font-medium">

                        <i class="fa-solid fa-arrow-up text-[9px]"></i>

                        0%

                        <span class="text-gray-500 font-normal">
                            from last week
                        </span>

                    </p>

                </div>

                <div
                    class="w-12 h-12 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-lg">

                    <i class="fa-solid fa-cart-shopping"></i>

                </div>

            </div>

            <!-- Total Revenue -->

            <div class="card rounded-2xl p-5 flex items-center justify-between">

                <div class="space-y-1">

                    <p class="text-xs text-gray-400 font-medium">
                        Total Revenue
                    </p>

                    <h3 id="totalRevenue" class="text-2xl font-bold">

                        $0

                    </h3>

                    <p id="revenueGrowth" class="text-[11px] text-purple-400 font-medium">

                        <i class="fa-solid fa-arrow-up text-[9px]"></i>

                        0%

                        <span class="text-gray-500 font-normal">
                            from last week
                        </span>

                    </p>

                </div>

                <div
                    class="w-12 h-12 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-lg">

                    <i class="fa-solid fa-dollar-sign"></i>

                </div>

            </div>

        </div>

        <!-- =================================================
             ORDERS CHART + TOP GAMES
        ================================================== -->

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Orders Overview -->

            <div class="card rounded-2xl p-6 lg:col-span-2 flex flex-col justify-between">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-base font-semibold">
                        Orders Overview
                    </h3>

                    <select id="ordersPeriod"
                        class="bg-[#14162e] border border-[#24274c] text-gray-300 text-xs rounded-lg px-3 py-1.5 focus:outline-none">

                        <option value="week">
                            This Week
                        </option>

                        <option value="month">
                            This Month
                        </option>

                    </select>

                </div>

                <div class="h-64 w-full">

                    <canvas id="ordersChart"></canvas>

                </div>

            </div>

            <!-- Top Games -->

            <div class="card rounded-2xl p-6 flex flex-col justify-between">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-base font-semibold">
                        Top Games
                    </h3>

                    <a href="manage-games.php" class="text-xs text-purple-400 hover:underline">

                        View All

                    </a>

                </div>

                <!-- Backend will insert games here -->

                <div id="topGamesContainer" class="space-y-3 min-h-[220px]">

                    <div id="topGamesEmpty" class="min-h-[220px] flex items-center justify-center">

                        <p class="text-xs text-gray-500 font-medium">
                            No games available.
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- =================================================
             RECENT ORDERS
        ================================================== -->

        <div class="card rounded-2xl p-6">

            <div class="flex justify-between items-center mb-6">

                <h3 class="text-base font-semibold">
                    Recent Orders
                </h3>

                <a href="manage-orders.php" class="text-xs text-purple-400 hover:underline">

                    View All

                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-xs">

                    <thead class="text-gray-400 border-b border-[#1d1d3b]">

                        <tr>

                            <th class="pb-3 font-medium">
                                Order ID
                            </th>

                            <th class="pb-3 font-medium">
                                Player
                            </th>

                            <th class="pb-3 font-medium">
                                Game
                            </th>

                            <th class="pb-3 font-medium">
                                Amount
                            </th>

                            <th class="pb-3 font-medium">
                                Status
                            </th>

                            <th class="pb-3 font-medium">
                                Date
                            </th>

                        </tr>

                    </thead>

                    <!-- Backend will insert orders here -->

                    <tbody id="recentOrdersTable" class="divide-y divide-[#171833]">

                        <tr id="ordersEmptyRow">

                            <td colspan="6" class="py-12 text-center text-gray-500 font-medium">

                                No recent orders found.

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

    <!-- =====================================================
         DASHBOARD JAVASCRIPT
    ====================================================== -->

    <script src="./js/dashboard.js"></script>

</body>

</html>