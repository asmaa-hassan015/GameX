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
    // READ GAME ID
    // =====================================================

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception('Invalid game ID.');
    }

    // =====================================================
    // CHECK GAME EXISTENCE
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT id
        FROM games
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception('Game not found.');
    }

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
    // VALIDATE PRICE
    // =====================================================

    if (
        !is_numeric($price) ||
        (float) $price < 0
    ) {
        throw new Exception('Invalid price.');
    }

    $price = (float) $price;

    // =====================================================
    // VALIDATE OLD PRICE
    // =====================================================

    if (
        $oldPrice !== null &&
        $oldPrice !== ''
    ) {
        if (
            !is_numeric($oldPrice) ||
            (float) $oldPrice < 0
        ) {
            throw new Exception('Invalid old price.');
        }

        $oldPrice = (float) $oldPrice;
    } else {
        $oldPrice = null;
    }

    // =====================================================
    // VALIDATE DISCOUNT
    // =====================================================

    if (
        !is_numeric($discount) ||
        (float) $discount < 0 ||
        (float) $discount > 100
    ) {
        throw new Exception('Invalid discount.');
    }

    $discount = (float) $discount;

    // =====================================================
    // VALIDATE RATING
    // =====================================================

    if (
        !is_numeric($rating) ||
        (float) $rating < 0 ||
        (float) $rating > 5
    ) {
        throw new Exception(
            'Rating must be between 0 and 5.'
        );
    }

    $rating = (float) $rating;

    // =====================================================
    // VALIDATE TOTAL REVIEWS
    // =====================================================

    if (
        !is_numeric($totalReviews) ||
        (int) $totalReviews < 0
    ) {
        throw new Exception(
            'Invalid total reviews.'
        );
    }

    $totalReviews = (int) $totalReviews;

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
    // UPDATE GAME
    // =====================================================

    $sql = "
        UPDATE games
        SET
            title = :title,
            developer = :developer,
            publisher = :publisher,
            description = :description,
            price = :price,
            old_price = :old_price,
            discount = :discount,
            image = :image,
            release_date = :release_date,
            rating = :rating,
            total_reviews = :total_reviews,
            status = :status
        WHERE id = :id
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

        ':status' => $status,

        ':id' => $id
    ]);

    // =====================================================
    // UPDATE CATEGORY
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM game_categories
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);

    // =====================================================
    // ADD CATEGORY
    // =====================================================

    if ($category !== '') {

        $stmt = $pdo->prepare("
            SELECT id
            FROM categories
            WHERE name = :name
            LIMIT 1
        ");

        $stmt->execute([
            ':name' => $category
        ]);

        $categoryId = $stmt->fetchColumn();

        // =================================================
        // CREATE CATEGORY IF NOT EXISTS
        // =================================================

        if (!$categoryId) {

            $stmt = $pdo->prepare("
                INSERT INTO categories (name)
                VALUES (:name)
            ");

            $stmt->execute([
                ':name' => $category
            ]);

            $categoryId = (int) $pdo->lastInsertId();
        }

        // =================================================
        // LINK GAME TO CATEGORY
        // =================================================

        $stmt = $pdo->prepare("
            INSERT INTO game_categories (
                game_id,
                category_id
            )
            VALUES (
                :game_id,
                :category_id
            )
        ");

        $stmt->execute([
            ':game_id' => $id,
            ':category_id' => $categoryId
        ]);
    }

    // =====================================================
    // UPDATE GAME IMAGE
    // =====================================================

    if ($image !== '') {

        // =================================================
        // FIND EXISTING IMAGE
        // =================================================

        $stmt = $pdo->prepare("
            SELECT id
            FROM game_images
            WHERE game_id = :game_id
            ORDER BY sort_order ASC, id ASC
            LIMIT 1
        ");

        $stmt->execute([
            ':game_id' => $id
        ]);

        $imageId = $stmt->fetchColumn();

        // =================================================
        // UPDATE EXISTING IMAGE
        // =================================================

        if ($imageId) {

            $stmt = $pdo->prepare("
                UPDATE game_images
                SET image = :image
                WHERE id = :id
            ");

            $stmt->execute([
                ':image' => $image,
                ':id' => $imageId
            ]);
        } else {

            // =============================================
            // INSERT NEW IMAGE
            // =============================================

            $stmt = $pdo->prepare("
                INSERT INTO game_images (
                    game_id,
                    image,
                    sort_order
                )
                VALUES (
                    :game_id,
                    :image,
                    0
                )
            ");

            $stmt->execute([
                ':game_id' => $id,
                ':image' => $image
            ]);
        }
    }

    // =====================================================
    // COMMIT TRANSACTION
    // =====================================================

    $pdo->commit();

    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'message' => 'Game updated successfully.',
        'game_id' => $id
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {

    // =====================================================
    // ROLLBACK DATABASE CHANGES
    // =====================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Database error while updating game.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {

    // =====================================================
    // ROLLBACK DATABASE CHANGES
    // =====================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}