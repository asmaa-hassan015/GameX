<?php

session_start();

require_once __DIR__ . "/config/db.php";

/*
|--------------------------------------------------------------------------
| GET GAME ID
|--------------------------------------------------------------------------
*/

$gameId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($gameId <= 0) {
    die("Invalid game ID.");
}


/*
|--------------------------------------------------------------------------
| GET GAME DETAILS
|--------------------------------------------------------------------------
*/

try {

    /*
    |--------------------------------------------------------------------------
    | GAME
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT
            g.id,
            g.title,
            g.developer,
            g.publisher,
            g.description,
            g.price,
            g.old_price,
            g.discount,
            g.image,
            g.release_date,
            g.rating,
            g.total_reviews,
            g.status

        FROM games g

        WHERE g.id = :game_id
        AND g.status = 'active'

        LIMIT 1
    ");

    $stmt->execute([
        ':game_id' => $gameId
    ]);

    $game = $stmt->fetch(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | GAME NOT FOUND
    |--------------------------------------------------------------------------
    */

    if (!$game) {
        die("Game not found.");
    }


    /*
    |--------------------------------------------------------------------------
    | GAME IMAGES
    |--------------------------------------------------------------------------
    */

    $imageStmt = $pdo->prepare("
        SELECT
            id,
            image,
            sort_order

        FROM game_images

        WHERE game_id = :game_id

        ORDER BY
            sort_order ASC,
            id ASC
    ");

    $imageStmt->execute([
        ':game_id' => $gameId
    ]);

    $gameImages = $imageStmt->fetchAll(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | ADD MAIN IMAGE
    |--------------------------------------------------------------------------
    */

    $images = [];

    if (!empty($game['image'])) {
        $images[] = [
            'id' => 0,
            'image' => $game['image'],
            'sort_order' => 0
        ];
    }

    foreach ($gameImages as $image) {
        $images[] = $image;
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    $categoryStmt = $pdo->prepare("
        SELECT
            c.id,
            c.name

        FROM categories c

        INNER JOIN game_categories gc
            ON gc.category_id = c.id

        WHERE gc.game_id = :game_id

        ORDER BY c.name ASC
    ");

    $categoryStmt->execute([
        ':game_id' => $gameId
    ]);

    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | PLATFORMS
    |--------------------------------------------------------------------------
    */

    $platformStmt = $pdo->prepare("
        SELECT
            p.id,
            p.name

        FROM platforms p

        INNER JOIN game_platforms gp
            ON gp.platform_id = p.id

        WHERE gp.game_id = :game_id

        ORDER BY p.name ASC
    ");

    $platformStmt->execute([
        ':game_id' => $gameId
    ]);

    $platforms = $platformStmt->fetchAll(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | PREPARE GAME DATA
    |--------------------------------------------------------------------------
    */

    $game['id'] = (int) $game['id'];

    $game['price'] = (float) $game['price'];

    $game['old_price'] =
        $game['old_price'] !== null
        ? (float) $game['old_price']
        : null;

    $game['discount'] = (float) $game['discount'];

    $game['rating'] = (float) $game['rating'];

    $game['total_reviews'] = (int) $game['total_reviews'];

    $game['images'] = $images;

    $game['categories'] = $categories;

    $game['platforms'] = $platforms;
} catch (PDOException $e) {

    die("Database error: " . $e->getMessage());
}


/*
|--------------------------------------------------------------------------
| DISPLAY VARIABLES
|--------------------------------------------------------------------------
*/

$gameTitle       = $game['title'] ?? '';
$gamePrice       = $game['price'] ?? 0;
$gameOldPrice    = $game['old_price'] ?? null;
$gameDiscount    = $game['discount'] ?? 0;
$gameRating      = $game['rating'] ?? 0;
$gameReviews     = $game['total_reviews'] ?? 0;
$gameDescription = $game['description'] ?? '';
$gameDeveloper   = $game['developer'] ?? '';
$gamePublisher   = $game['publisher'] ?? '';
$gameReleaseDate = $game['release_date'] ?? '';
$gameImages      = $game['images'] ?? [];
$gameCategories  = $game['categories'] ?? [];
$gamePlatforms   = $game['platforms'] ?? [];

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        GAME X - <?= htmlspecialchars($gameTitle) ?>
    </title>


    <!-- Tailwind -->

    <script src="https://cdn.tailwindcss.com"></script>


    <script>
    tailwind.config = {

        theme: {

            extend: {

                colors: {

                    gx: {
                        bg: "#03040b",
                        panel: "#090b16",
                        line: "#24213a",
                        purple: "#7c2cff",
                        neon: "#a855f7"
                    }

                },

                fontFamily: {

                    rajdhani: [
                        "Rajdhani",
                        "sans-serif"
                    ],

                    poppins: [
                        "Poppins",
                        "sans-serif"
                    ]

                },

                boxShadow: {

                    neon: "0 0 15px rgba(124,44,255,.35)"

                }

            }

        }

    };
    </script>


    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap"
        rel="stylesheet">


    <!-- Font Awesome -->

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- Main CSS -->

    <link rel="stylesheet" href="./css/style2.css">

</head>


<body>


    <?php include "components/nav.php"; ?>


    <main class="min-h-screen max-w-[1450px] mx-auto p-4 md:p-7">


        <!-- =====================================================
             BREADCRUMB
        ====================================================== -->

        <p class="text-gray-400 py-4">

            Home › Games ›

            <span class="text-[#a855f7]" id="breadcrumbTitle">

                <?= htmlspecialchars($gameTitle) ?>

            </span>

        </p>


        <!-- =====================================================
             GAME DETAILS
        ====================================================== -->

        <section class="grid lg:grid-cols-[1.05fr_.95fr] gap-8 mt-4">


            <!-- =================================================
                 LEFT SIDE - IMAGES
            ================================================== -->

            <div>


                <!-- MAIN IMAGE -->

                <div class="aspect-[4/3]
                           rounded-2xl
                           border
                           border-[#24213a]
                           bg-gradient-to-br
                           from-slate-700
                           via-slate-900
                           to-[#08050d]
                           grid
                           place-items-center
                           relative
                           overflow-hidden">

                    <img id="mainDisplayImage" src="<?= htmlspecialchars(
                                                        $gameImages[0]['image']
                                                            ?? ''
                                                    ) ?>" alt="<?= htmlspecialchars($gameTitle) ?>"
                        class="w-full h-full object-cover">


                    <!-- Trailer -->

                    <div class="absolute inset-0
                               bg-black/30
                               flex
                               items-center
                               justify-center">

                        <button type="button" onclick="playTrailer()" class="w-16 h-16
                                   rounded-full
                                   border-2
                                   border-white/80
                                   bg-black/40
                                   flex
                                   items-center
                                   justify-center
                                   hover:scale-110
                                   hover:bg-[#7c2cff]
                                   hover:border-[#7c2cff]
                                   transition
                                   duration-300">

                            <i class="fa-solid fa-play
                                       text-xl
                                       ml-1
                                       text-white"></i>

                        </button>

                    </div>

                </div>


                <!-- THUMBNAILS -->

                <div id="thumbnailContainer" class="grid
                           grid-cols-5
                           gap-3
                           mt-4">

                    <?php foreach ($gameImages as $index => $image): ?>

                    <div class="aspect-video
                                   rounded-xl
                                   border
                                   border-[#24213a]
                                   bg-[#0b0d18]
                                   overflow-hidden
                                   cursor-pointer
                                   <?= $index === 0
                                        ? 'thumb-active border-[#7c2cff]'
                                        : '' ?>" onclick="selectImage(
                                this,
                                <?= htmlspecialchars(
                                    json_encode(
                                        $image['image']
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            )">

                        <img src="<?= htmlspecialchars(
                                            $image['image']
                                        ) ?>" alt="<?= htmlspecialchars($gameTitle) ?>"
                            class="w-full h-full object-cover thumb-img">

                    </div>

                    <?php endforeach; ?>

                </div>

            </div>


            <!-- =================================================
                 RIGHT SIDE - INFO
            ================================================== -->

            <div>


                <!-- TITLE -->

                <h1 id="gameTitle" class="heading
                           text-5xl
                           md:text-6xl
                           font-bold
                           min-h-[60px]">

                    <?= htmlspecialchars($gameTitle) ?>

                </h1>


                <!-- CATEGORIES -->

                <div id="categoryContainer" class="flex
                           flex-wrap
                           gap-3
                           mt-4
                           min-h-[40px]">

                    <?php foreach ($gameCategories as $category): ?>

                    <span class="bg-[#24213a]
                                   px-3
                                   py-1
                                   rounded-lg
                                   text-sm">

                        <?= htmlspecialchars(
                                $category['name']
                            ) ?>

                    </span>

                    <?php endforeach; ?>

                </div>


                <!-- RATING -->

                <div class="mt-6 text-xl">

                    <span class="text-yellow-400">
                        ★★★★★
                    </span>

                    <span id="ratingScore">

                        <?= number_format(
                            $gameRating,
                            1
                        ) ?>

                    </span>

                    <span class="muted">

                        (
                        <?= $gameReviews ?>
                        reviews
                        )

                    </span>

                </div>


                <!-- PRICE -->

                <div class="flex
                           items-center
                           gap-5
                           mt-6">

                    <b id="gamePrice" class="heading text-4xl">

                        $<?= number_format(
                                $gamePrice,
                                2
                            ) ?>

                    </b>


                    <?php if ($gameOldPrice !== null): ?>

                    <del id="oldPrice" class="muted">

                        $<?= number_format(
                                    $gameOldPrice,
                                    2
                                ) ?>

                    </del>

                    <?php endif; ?>


                    <?php if ($gameDiscount > 0): ?>

                    <span id="discountBadge" class="bg-red-600
                                   px-4
                                   py-2
                                   rounded-lg">

                        -<?= number_format(
                                    $gameDiscount,
                                    0
                                ) ?>%

                    </span>

                    <?php endif; ?>

                </div>


                <!-- DESCRIPTION -->

                <p id="gameDescription" class="text-gray-300
                           leading-8
                           mt-6">

                    <?= nl2br(
                        htmlspecialchars(
                            $gameDescription
                        )
                    ) ?>

                </p>


                <!-- GAME INFORMATION -->

                <div class="grid
                           sm:grid-cols-2
                           gap-5
                           mt-8
                           text-sm">


                    <!-- Developer -->

                    <p>

                        Developer

                        <b class="float-right" id="devName">

                            <?= htmlspecialchars(
                                $gameDeveloper
                            ) ?>

                        </b>

                    </p>


                    <!-- Publisher -->

                    <p>

                        Publisher

                        <b class="float-right" id="pubName">

                            <?= htmlspecialchars(
                                $gamePublisher
                            ) ?>

                        </b>

                    </p>


                    <!-- Release Date -->

                    <p>

                        Release Date

                        <b class="float-right" id="relDate">

                            <?= $gameReleaseDate
                                ? htmlspecialchars(
                                    date(
                                        'M d, Y',
                                        strtotime(
                                            $gameReleaseDate
                                        )
                                    )
                                )
                                : 'N/A'
                            ?>

                        </b>

                    </p>


                    <!-- Platforms -->

                    <p>

                        Platform

                        <b id="platformContainer" class="float-right
                                   flex
                                   gap-2">

                            <?php foreach ($gamePlatforms as $platform): ?>

                            <?php

                                $platformName =
                                    strtolower(
                                        $platform['name']
                                    );

                                $icon =
                                    'fa-solid fa-gamepad';

                                if (
                                    str_contains(
                                        $platformName,
                                        'windows'
                                    ) ||
                                    str_contains(
                                        $platformName,
                                        'pc'
                                    )
                                ) {

                                    $icon =
                                        'fa-brands fa-windows';
                                } elseif (
                                    str_contains(
                                        $platformName,
                                        'playstation'
                                    )
                                ) {

                                    $icon =
                                        'fa-brands fa-playstation';
                                } elseif (
                                    str_contains(
                                        $platformName,
                                        'xbox'
                                    )
                                ) {

                                    $icon =
                                        'fa-brands fa-xbox';
                                }

                                ?>

                            <span title="<?= htmlspecialchars(
                                                    $platform['name']
                                                ) ?>">

                                <i class="<?= $icon ?>"></i>

                            </span>

                            <?php endforeach; ?>

                        </b>

                    </p>

                </div>

            </div>

        </section>


        <!-- =====================================================
             ACTION BUTTONS
        ====================================================== -->

        <div class="grid
                   md:grid-cols-2
                   gap-5
                   mt-10">


            <!-- BUY NOW -->

            <button id="buyNowBtn" type="button" onclick="buyNow()" class="purple
                       rounded-xl
                       py-5
                       text-xl
                       shadow-neon
                       hover:opacity-90
                       transition
                       active:scale-[0.99]
                       cursor-pointer">

                <i class="fa-solid fa-cart-shopping mr-3"></i>

                Buy Now

            </button>


            <!-- CART -->

            <button id="cartBtn" type="button" onclick="handleCart()" class="neon
                       rounded-xl
                       py-5
                       text-xl
                       hover:bg-[#7c2cff]/20
                       transition
                       active:scale-[0.99]
                       cursor-pointer">

                <i class="fa-solid fa-cart-shopping mr-3"></i>

                <span id="cartBtnText">

                    Add to Cart

                </span>

            </button>


            <!-- WISHLIST -->

            <button id="wishlistBtn" type="button" onclick="handleWishlist()" class="md:col-span-2
                       neon
                       rounded-xl
                       py-5
                       text-xl
                       hover:bg-[#7c2cff]/20
                       transition
                       active:scale-[0.99]
                       cursor-pointer">

                <i id="wishlistIcon" class="fa-regular fa-heart mr-3"></i>

                <span id="wishlistBtnText">

                    Add to Wishlist

                </span>

            </button>

        </div>

    </main>


    <!-- GAME ID FOR JAVASCRIPT -->

    <script>
    window.GAME_ID =
        <?= (int) $game['id'] ?>;
    </script>

    <script src="./js/navbar-badges.js"></script>

    <script src="./js/game-details.js"></script>

</body>

</html>