<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/db.php';

// =========================================================
// ADMIN AUTHORIZATION
// =========================================================

if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    http_response_code(403);

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access.'
    ]);

    exit;
}

// =========================================================
// REQUEST METHOD
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}

// =========================================================
// MAIN PROCESS
// =========================================================

try {

    // =====================================================
    // READ INPUT DATA
    // =====================================================

    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $developer = trim($_POST['developer'] ?? '');
    $publisher = trim($_POST['publisher'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $price = $_POST['price'] ?? 0;
    $oldPrice = $_POST['old_price'] ?? null;
    $discount = $_POST['discount'] ?? 0;

    $image = trim($_POST['image'] ?? '');

    $releaseDate = trim($_POST['release_date'] ?? '');

    $rating = $_POST['rating'] ?? 0;
    $totalReviews = $_POST['total_reviews'] ?? 0;

    $status = $_POST['status'] ?? 'active';

    // =====================================================
    // VALIDATE TITLE
    // =====================================================

    if ($title === '') {
        throw new Exception('Game title is required.');
    }

    if (mb_strlen($title) < 2) {
        throw new Exception(
            'Game title must be at least 2 characters.'
        );
    }

    // =====================================================
    // VALIDATE CATEGORY
    // =====================================================

    if ($category === '') {
        $category = 'Other';
    }

    // =====================================================
    // VALIDATE PRICE
    // =====================================================

    if (!is_numeric($price) || (float)$price < 0) {
        throw new Exception('Invalid price.');
    }

    $price = (float)$price;

    // =====================================================
    // VALIDATE OLD PRICE
    // =====================================================

    if ($oldPrice === '' || $oldPrice === null) {

        $oldPrice = null;
    } else {

        if (!is_numeric($oldPrice) || (float)$oldPrice < 0) {
            throw new Exception('Invalid old price.');
        }

        $oldPrice = (float)$oldPrice;
    }

    // =====================================================
    // VALIDATE DISCOUNT
    // =====================================================

    if (
        !is_numeric($discount) ||
        (float)$discount < 0 ||
        (float)$discount > 100
    ) {
        throw new Exception('Invalid discount.');
    }

    $discount = (float)$discount;

    // =====================================================
    // VALIDATE RATING
    // =====================================================

    if (
        !is_numeric($rating) ||
        (float)$rating < 0 ||
        (float)$rating > 5
    ) {
        throw new Exception(
            'Rating must be between 0 and 5.'
        );
    }

    $rating = (float)$rating;

    // =====================================================
    // VALIDATE TOTAL REVIEWS
    // =====================================================

    if (
        !is_numeric($totalReviews) ||
        (int)$totalReviews < 0
    ) {
        throw new Exception(
            'Invalid total reviews.'
        );
    }

    $totalReviews = (int)$totalReviews;

    // =====================================================
    // VALIDATE STATUS
    // =====================================================

    if (!in_array(
        $status,
        ['active', 'inactive'],
        true
    )) {
        throw new Exception(
            'Invalid game status.'
        );
    }

    // =====================================================
    // VALIDATE RELEASE DATE
    // =====================================================

    if ($releaseDate === '') {

        $releaseDate = null;
    } else {

        $date = DateTime::createFromFormat(
            'Y-m-d',
            $releaseDate
        );

        if (
            !$date ||
            $date->format('Y-m-d') !== $releaseDate
        ) {
            throw new Exception(
                'Invalid release date.'
            );
        }
    }

    // =====================================================
    // START TRANSACTION
    // =====================================================

    $pdo->beginTransaction();

    // =====================================================
    // INSERT GAME
    // =====================================================

    $sql = "
        INSERT INTO games (
            title,
            developer,
            publisher,
            description,
            price,
            old_price,
            discount,
            image,
            release_date,
            rating,
            total_reviews,
            status
        )
        VALUES (
            :title,
            :developer,
            :publisher,
            :description,
            :price,
            :old_price,
            :discount,
            :image,
            :release_date,
            :rating,
            :total_reviews,
            :status
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':title' => $title,

        ':developer' =>
        $developer !== ''
            ? $developer
            : null,

        ':publisher' =>
        $publisher !== ''
            ? $publisher
            : null,

        ':description' =>
        $description !== ''
            ? $description
            : null,

        ':price' => $price,

        ':old_price' => $oldPrice,

        ':discount' => $discount,

        ':image' =>
        $image !== ''
            ? $image
            : null,

        ':release_date' => $releaseDate,

        ':rating' => $rating,

        ':total_reviews' => $totalReviews,

        ':status' => $status
    ]);

    // =====================================================
    // GET NEW GAME ID
    // =====================================================

    $gameId = (int)$pdo->lastInsertId();

    if ($gameId <= 0) {
        throw new Exception(
            'Game was not created.'
        );
    }

    // =====================================================
    // HANDLE CATEGORY
    // =====================================================

    if ($category !== '') {

        // -------------------------------------------------
        // FIND CATEGORY
        // -------------------------------------------------

        $categorySql = "
            SELECT id
            FROM categories
            WHERE name = :name
            LIMIT 1
        ";

        $categoryStmt = $pdo->prepare($categorySql);

        $categoryStmt->execute([
            ':name' => $category
        ]);

        $categoryId = $categoryStmt->fetchColumn();

        // -------------------------------------------------
        // CREATE CATEGORY IF NOT EXISTS
        // -------------------------------------------------

        if (!$categoryId) {

            $insertCategorySql = "
                INSERT INTO categories (name)
                VALUES (:name)
            ";

            $insertCategoryStmt = $pdo->prepare(
                $insertCategorySql
            );

            $insertCategoryStmt->execute([
                ':name' => $category
            ]);

            $categoryId = (int)$pdo->lastInsertId();
        }

        // -------------------------------------------------
        // LINK GAME WITH CATEGORY
        // -------------------------------------------------

        $linkSql = "
            INSERT INTO game_categories (
                game_id,
                category_id
            )
            VALUES (
                :game_id,
                :category_id
            )
        ";

        $linkStmt = $pdo->prepare($linkSql);

        $linkStmt->execute([
            ':game_id' => $gameId,
            ':category_id' => $categoryId
        ]);
    }

    // =====================================================
    // ADD GAME IMAGE
    // =====================================================

    if ($image !== '') {

        $imageSql = "
            INSERT INTO game_images (
                game_id,
                image,
                sort_order
            )
            VALUES (
                :game_id,
                :image,
                :sort_order
            )
        ";

        $imageStmt = $pdo->prepare($imageSql);

        $imageStmt->execute([
            ':game_id' => $gameId,
            ':image' => $image,
            ':sort_order' => 0
        ]);
    }

    // =====================================================
    // COMMIT
    // =====================================================

    $pdo->commit();

    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'message' => 'Game added successfully.',
        'game_id' => $gameId
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {

    // =====================================================
    // ROLLBACK
    // =====================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // =====================================================
    // ERROR RESPONSE
    // =====================================================

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}