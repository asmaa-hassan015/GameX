<?php

// =========================================================
// GET GAME DATA
// =========================================================

session_start();


// =========================================================
// DATABASE
// =========================================================

require_once __DIR__ . '/../config/db.php';


// =========================================================
// GET GAME ID
// =========================================================

$gameId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


// =========================================================
// VALIDATE GAME ID
// =========================================================

if ($gameId <= 0) {

    die('Invalid game ID.');
}


// =========================================================
// GET GAME
// =========================================================

$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        price,
        old_price,
        discount,
        description,
        developer,
        publisher,
        release_date,
        image,
        rating,
        total_reviews,
        status
    FROM games
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([
    $gameId
]);

$game = $stmt->fetch(PDO::FETCH_ASSOC);


// =========================================================
// CHECK GAME
// =========================================================

if (!$game) {

    die('Game not found.');
}


// =========================================================
// CHECK GAME STATUS
// =========================================================

if (
    isset($game['status']) &&
    $game['status'] !== 'active'
) {

    die('This game is not available.');
}


// =========================================================
// GAME DATA
// =========================================================

$gameTitle = $game['title'] ?? '';

$gamePrice = $game['price'] ?? '';

$gameOldPrice = $game['old_price'] ?? '';

$gameDiscount = $game['discount'] ?? '';

$gameRating = $game['rating'] ?? '';

$gameReviews = $game['total_reviews'] ?? 0;

$gameDescription = $game['description'] ?? '';

$gameDeveloper = $game['developer'] ?? '';

$gamePublisher = $game['publisher'] ?? '';

$gameReleaseDate = $game['release_date'] ?? '';


// =========================================================
// GAME IMAGES
// =========================================================

$gameImages = [];

if (!empty($game['image'])) {

    $gameImages[] = $game['image'];
}