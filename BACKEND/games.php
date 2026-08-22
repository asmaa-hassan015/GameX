<?php

// =========================================================
// GAME X - GAMES API
// =========================================================

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';


// =========================================================
// GET GAMES
// =========================================================

try {

    $sql = "
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
            g.status,
            g.created_at,

            GROUP_CONCAT(
                DISTINCT c.name
                ORDER BY c.name
                SEPARATOR ', '
            ) AS categories,

            GROUP_CONCAT(
                DISTINCT p.name
                ORDER BY p.name
                SEPARATOR ', '
            ) AS platforms

        FROM games g

        LEFT JOIN game_categories gc
            ON g.id = gc.game_id

        LEFT JOIN categories c
            ON gc.category_id = c.id

        LEFT JOIN game_platforms gp
            ON g.id = gp.game_id

        LEFT JOIN platforms p
            ON gp.platform_id = p.id

        WHERE g.status = 'active'

        GROUP BY g.id

        ORDER BY g.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // GET CATEGORIES
    // =====================================================

    $categoryStmt = $pdo->query("
        SELECT
            id,
            name
        FROM categories
        ORDER BY name ASC
    ");

    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // GET PLATFORMS
    // =====================================================

    $platformStmt = $pdo->query("
        SELECT
            id,
            name
        FROM platforms
        ORDER BY name ASC
    ");

    $platforms = $platformStmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // RESPONSE
    // =====================================================

    echo json_encode(
        [
            'success' => true,
            'games' => $games,
            'categories' => $categories,
            'platforms' => $platforms
        ],
        JSON_UNESCAPED_UNICODE
    );

    exit;
} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode(
        [
            'success' => false,
            'message' => 'Failed to load games.'
        ],
        JSON_UNESCAPED_UNICODE
    );

    exit;
}