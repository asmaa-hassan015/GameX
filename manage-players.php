<?php

session_start();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Manage Players</title>


    <!-- ========================================================= -->
    <!-- TAILWIND -->
    <!-- ========================================================= -->

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

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- ========================================================= -->
    <!-- PROJECT CSS -->
    <!-- ========================================================= -->

    <link rel="stylesheet" href="./css/style2.css">

</head>


<body class="min-h-screen bg-[#03040b] text-white">


    <!-- ========================================================= -->
    <!-- ADMIN NAVBAR -->
    <!-- ========================================================= -->

    <?php include 'components/admin-nav.php'; ?>


    <!-- ========================================================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================================================= -->

    <main class="max-w-[1250px] mx-auto p-5 md:p-8">

        <section class="glass rounded-2xl p-6">


            <!-- ================================================= -->
            <!-- PAGE HEADER -->
            <!-- ================================================= -->

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <!-- ================================================= -->
                <!-- TITLE -->
                <!-- ================================================= -->

                <div>

                    <h2 class="heading text-3xl font-bold">
                        Manage Players
                    </h2>

                    <p class="muted text-sm mt-1">
                        View and manage registered players.
                    </p>

                </div>


                <!-- ================================================= -->
                <!-- PLAYER COUNT -->
                <!-- ================================================= -->

                <div class="
                        flex
                        items-center
                        gap-3
                        bg-[#090b16]
                        border
                        border-[#24213a]
                        rounded-xl
                        px-4
                        py-3
                    ">

                    <!-- Player Icon -->

                    <div class="
                            w-10
                            h-10
                            rounded-lg
                            bg-[#7c2cff]/15
                            text-[#a855f7]
                            flex
                            items-center
                            justify-center
                        ">

                        <i class="fa-solid fa-users"></i>

                    </div>


                    <!-- Player Count -->

                    <div>

                        <p class="text-xs text-gray-400">
                            Total Players
                        </p>

                        <p id="playersCount" class="text-xl font-bold">
                            0
                        </p>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- PLAYERS TABLE -->
            <!-- ================================================= -->

            <div class="overflow-x-auto">

                <table class="w-full text-sm">


                    <!-- ================================================= -->
                    <!-- TABLE HEADER -->
                    <!-- ================================================= -->

                    <thead class="
                            text-left
                            text-gray-400
                            border-b
                            border-[#24213a]
                        ">

                        <tr>

                            <!-- Player -->

                            <th class="p-4">
                                Player
                            </th>


                            <!-- Email -->

                            <th class="p-4">
                                Email
                            </th>


                            <!-- Joined Date -->

                            <th class="p-4">
                                Joined
                            </th>


                            <!-- Status -->

                            <th class="p-4">
                                Status
                            </th>


                            <!-- Actions -->

                            <th class="p-4 text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <!-- ================================================= -->
                    <!-- TABLE BODY -->
                    <!-- ================================================= -->

                    <tbody id="playersTableBody" class="divide-y divide-[#24213a]">

                    </tbody>

                </table>

            </div>


            <!-- ================================================= -->
            <!-- PLAYERS MESSAGE -->
            <!-- ================================================= -->

            <div id="playersMessage" class="
                    py-12
                    text-center
                    text-gray-500
                    font-medium
                ">
                Loading players...
            </div>


            <!-- ================================================= -->
            <!-- PAGINATION -->
            <!-- ================================================= -->

            <div id="pagination" class="
                    flex
                    justify-center
                    gap-2
                    mt-6
                ">

            </div>

        </section>

    </main>


    <!-- ========================================================= -->
    <!-- MANAGE PLAYERS JAVASCRIPT -->
    <!-- ========================================================= -->

    <script src="./js/manage-players.js"></script>


</body>

</html>