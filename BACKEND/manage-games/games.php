<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/db.php';


// =========================================================
// MAIN PROCESS
// =========================================================

try {

    // =====================================================
    // FETCH GAMES
    // =====================================================

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
            g.updated_at,

            GROUP_CONCAT(
                DISTINCT c.name
                ORDER BY c.name
                SEPARATOR ', '
            ) AS categories

        FROM games g

        LEFT JOIN game_categories gc
            ON gc.game_id = g.id

        LEFT JOIN categories c
            ON c.id = gc.category_id

        GROUP BY
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
            g.updated_at

        ORDER BY g.id DESC
    ";


    // =====================================================
    // EXECUTE QUERY
    // =====================================================

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // FORMAT GAME DATA
    // =====================================================

    foreach ($games as &$game) {

        // Format ID

        $game['id'] = (int) $game['id'];


        // Format Price

        $game['price'] = (float) $game['price'];


        // Format Old Price

        $game['old_price'] =
            $game['old_price'] !== null
            ? (float) $game['old_price']
            : null;


        // Format Discount

        $game['discount'] =
            (float) $game['discount'];


        // Format Rating

        $game['rating'] =
            (float) $game['rating'];


        // Format Total Reviews

        $game['total_reviews'] =
            (int) $game['total_reviews'];


        // Frontend Compatibility

        $game['category'] =
            $game['categories'] ?? '';
    }

    unset($game);


    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'games' => $games
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to load games.'
    ], JSON_UNESCAPED_UNICODE);
}