<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

try {

    // =========================================================
    // CHECK LOGIN
    // =========================================================

    if (!isset($_SESSION['user_id'])) {

        http_response_code(401);

        echo json_encode([
            'success' => false,
            'message' => 'Please login first.',
            'count' => 0
        ]);

        exit;
    }

    $userId = (int) $_SESSION['user_id'];

    // =========================================================
    // METHOD
    // =========================================================

    $method = $_SERVER['REQUEST_METHOD'];

    // =========================================================
    // GET WISHLIST
    // =========================================================

    if ($method === 'GET') {

        $stmt = $pdo->prepare("
            SELECT
                w.id AS wishlist_id,
                w.game_id,
                w.created_at,
                g.id,
                g.title,
                g.price,
                g.old_price,
                g.discount,
                g.image,
                g.publisher,
                g.rating,
                g.total_reviews

            FROM wishlist w

            INNER JOIN games g
                ON g.id = w.game_id

            WHERE w.user_id = ?

            ORDER BY w.id DESC
        ");

        $stmt->execute([$userId]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =====================================================
        // FORMAT DATA
        // =====================================================

        foreach ($items as &$item) {

            $item['wishlist_id'] = (int) $item['wishlist_id'];

            $item['game_id'] = (int) $item['game_id'];

            $item['id'] = (int) $item['id'];

            $item['price'] = (float) $item['price'];

            $item['old_price'] =
                $item['old_price'] !== null
                ? (float) $item['old_price']
                : null;

            $item['discount'] = (float) $item['discount'];

            $item['rating'] = (float) $item['rating'];

            $item['total_reviews'] = (int) $item['total_reviews'];

            $item['image'] = $item['image'] ?? '';

            $item['publisher'] = $item['publisher'] ?? '';

            // Frontend compatibility
            $item['img'] = $item['image'];
        }

        unset($item);

        // =====================================================
        // COUNT
        // =====================================================

        $countStmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM wishlist
            WHERE user_id = ?
        ");

        $countStmt->execute([$userId]);

        $count = (int) $countStmt->fetchColumn();

        // =====================================================
        // RESPONSE
        // =====================================================

        echo json_encode([
            'success' => true,
            'data' => $items,
            'items' => $items,
            'count' => $count
        ]);

        exit;
    }

    // =========================================================
    // POST
    // =========================================================

    if ($method === 'POST') {

        // =====================================================
        // GET JSON DATA
        // =====================================================

        $rawInput = file_get_contents('php://input');

        $input = json_decode($rawInput, true);

        if (!is_array($input)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid JSON data.'
            ]);

            exit;
        }

        // =====================================================
        // ACTION
        // =====================================================

        $action = $input['action'] ?? '';

        // Support game_id OR id
        $gameId =
            isset($input['game_id'])
            ? (int) $input['game_id']
            : (
                isset($input['id'])
                ? (int) $input['id']
                : 0
            );

        if ($gameId <= 0) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid game ID.'
            ]);

            exit;
        }

        // =====================================================
        // CHECK GAME
        // =====================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                title,
                status

            FROM games

            WHERE id = ?

            LIMIT 1
        ");

        $stmt->execute([$gameId]);

        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$game) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Game not found.'
            ]);

            exit;
        }

        // =====================================================
        // ADD
        // =====================================================

        if ($action === 'add') {

            // Check existing
            $stmt = $pdo->prepare("
                SELECT id

                FROM wishlist

                WHERE user_id = ?
                AND game_id = ?

                LIMIT 1
            ");

            $stmt->execute([
                $userId,
                $gameId
            ]);

            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            // Already exists
            if ($existing) {

                $countStmt = $pdo->prepare("
                    SELECT COUNT(*)

                    FROM wishlist

                    WHERE user_id = ?
                ");

                $countStmt->execute([$userId]);

                $count = (int) $countStmt->fetchColumn();

                echo json_encode([
                    'success' => true,
                    'status' => 'exists',
                    'message' => 'Game is already in wishlist.',
                    'count' => $count
                ]);

                exit;
            }

            // Insert
            $stmt = $pdo->prepare("
                INSERT INTO wishlist
                (
                    user_id,
                    game_id
                )
                VALUES
                (
                    ?,
                    ?
                )
            ");

            $stmt->execute([
                $userId,
                $gameId
            ]);

            // Get new count
            $countStmt = $pdo->prepare("
                SELECT COUNT(*)

                FROM wishlist

                WHERE user_id = ?
            ");

            $countStmt->execute([$userId]);

            $count = (int) $countStmt->fetchColumn();

            echo json_encode([
                'success' => true,
                'status' => 'added',
                'message' => 'Game added to wishlist.',
                'count' => $count
            ]);

            exit;
        }

        // =====================================================
        // REMOVE
        // =====================================================

        if ($action === 'remove') {

            $stmt = $pdo->prepare("
                DELETE FROM wishlist

                WHERE user_id = ?
                AND game_id = ?
            ");

            $stmt->execute([
                $userId,
                $gameId
            ]);

            // Get new count
            $countStmt = $pdo->prepare("
                SELECT COUNT(*)

                FROM wishlist

                WHERE user_id = ?
            ");

            $countStmt->execute([$userId]);

            $count = (int) $countStmt->fetchColumn();

            echo json_encode([
                'success' => true,
                'status' => 'removed',
                'message' => 'Game removed from wishlist.',
                'count' => $count
            ]);

            exit;
        }

        // =====================================================
        // TOGGLE
        // =====================================================

        if ($action === 'toggle') {

            $stmt = $pdo->prepare("
                SELECT id

                FROM wishlist

                WHERE user_id = ?
                AND game_id = ?

                LIMIT 1
            ");

            $stmt->execute([
                $userId,
                $gameId
            ]);

            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            // ---------------------------------------------
            // REMOVE
            // ---------------------------------------------

            if ($existing) {

                $stmt = $pdo->prepare("
                    DELETE FROM wishlist

                    WHERE id = ?
                ");

                $stmt->execute([
                    $existing['id']
                ]);

                $status = 'removed';

                $message = 'Game removed from wishlist.';
            }

            // ---------------------------------------------
            // ADD
            // ---------------------------------------------

            else {

                $stmt = $pdo->prepare("
                    INSERT INTO wishlist
                    (
                        user_id,
                        game_id
                    )
                    VALUES
                    (
                        ?,
                        ?
                    )
                ");

                $stmt->execute([
                    $userId,
                    $gameId
                ]);

                $status = 'added';

                $message = 'Game added to wishlist.';
            }

            // Get final count
            $countStmt = $pdo->prepare("
                SELECT COUNT(*)

                FROM wishlist

                WHERE user_id = ?
            ");

            $countStmt->execute([$userId]);

            $count = (int) $countStmt->fetchColumn();

            echo json_encode([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'count' => $count
            ]);

            exit;
        }

        // =====================================================
        // INVALID ACTION
        // =====================================================

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Invalid wishlist action.'
        ]);

        exit;
    }

    // =========================================================
    // METHOD NOT ALLOWED
    // =========================================================

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
} catch (PDOException $e) {

    // =========================================================
    // DATABASE ERROR
    // =========================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to process wishlist.',
        'error' => $e->getMessage()
    ]);

    exit;
}