<?php

session_start();

header(
    'Content-Type: application/json; charset=utf-8'
);

require_once __DIR__ . '/../config/db.php';


// =========================================================
// Uses existing database structure
// =========================================================

try {

    // =====================================================
    // CHECK LOGIN
    // =====================================================

    if (!isset($_SESSION['user_id'])) {

        http_response_code(401);

        echo json_encode(
            [
                'success' => false,
                'message' => 'Please login first.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    $userId = (int) $_SESSION['user_id'];


    // =====================================================
    // REQUEST METHOD
    // =====================================================

    $method = $_SERVER['REQUEST_METHOD'];


    // =====================================================
    // GET CART
    // =====================================================

    if ($method === 'GET') {

        $stmt = $pdo->prepare("
            SELECT
                c.id AS cart_id,
                ci.id AS cart_item_id,
                ci.game_id,
                ci.quantity,
                ci.price AS cart_price,
                g.id,
                g.title,
                g.price,
                g.image,
                g.publisher
            FROM cart c
            INNER JOIN cart_items ci
                ON ci.cart_id = c.id
            INNER JOIN games g
                ON g.id = ci.game_id
            WHERE c.user_id = ?
            ORDER BY ci.id DESC
        ");

        $stmt->execute([
            $userId
        ]);

        $items = $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );


        // =================================================
        // FORMAT DATA
        // =================================================

        foreach ($items as &$item) {

            $item['id'] = (int) $item['id'];

            $item['cart_id'] = (int) $item['cart_id'];

            $item['cart_item_id'] =
                (int) $item['cart_item_id'];

            $item['game_id'] =
                (int) $item['game_id'];

            $item['quantity'] =
                (int) $item['quantity'];


            // IMPORTANT:
            // Use price stored inside cart_items

            $item['price'] =
                (float) $item['cart_price'];

            $item['game_price'] =
                (float) $item['price'];


            if ($item['image'] === null) {
                $item['image'] = '';
            }

            if ($item['publisher'] === null) {
                $item['publisher'] = '';
            }


            // Frontend compatibility

            $item['img'] = $item['image'];
        }

        unset($item);


        echo json_encode(
            [
                'success' => true,
                'data' => $items,
                'items' => $items,
                'count' => count($items)
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // ONLY POST AFTER THIS
    // =====================================================

    if ($method !== 'POST') {

        http_response_code(405);

        echo json_encode(
            [
                'success' => false,
                'message' => 'Method not allowed.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // READ JSON
    // =====================================================

    $rawInput = file_get_contents(
        'php://input'
    );

    $input = json_decode(
        $rawInput,
        true
    );


    if (!is_array($input)) {

        http_response_code(400);

        echo json_encode(
            [
                'success' => false,
                'message' => 'Invalid JSON data.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // ACTION
    // =====================================================

    $action = $input['action'] ?? '';


    // =====================================================
    // GAME ID
    // =====================================================

    $gameId = isset($input['id'])
        ? (int) $input['id']
        : 0;


    // =====================================================
    // VALIDATE GAME ID
    // =====================================================

    $gameActions = [
        'add',
        'remove',
        'update',
        'increase',
        'decrease'
    ];


    if (
        in_array(
            $action,
            $gameActions,
            true
        )
        &&
        $gameId <= 0
    ) {

        http_response_code(400);

        echo json_encode(
            [
                'success' => false,
                'message' => 'Invalid game ID.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // GET EXISTING CART
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT id
        FROM cart
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->execute([
        $userId
    ]);

    $cart = $stmt->fetch(
        PDO::FETCH_ASSOC
    );


    // =====================================================
    // CREATE CART IF NOT EXISTS
    // =====================================================

    if ($cart) {

        $cartId = (int) $cart['id'];
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO cart (user_id)
            VALUES (?)
        ");

        $stmt->execute([
            $userId
        ]);

        $cartId = (int) $pdo->lastInsertId();
    }


    // =====================================================
    // ADD TO CART
    // =====================================================

    if ($action === 'add') {

        // =================================================
        // GET GAME
        // =================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                price,
                status
            FROM games
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $gameId
        ]);

        $game = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        if (!$game) {

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Game not found.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // CHECK GAME STATUS
        // =================================================

        if ($game['status'] !== 'active') {

            http_response_code(400);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'This game is not available.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // CHECK EXISTING ITEM
        // =================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                quantity,
                price
            FROM cart_items
            WHERE cart_id = ?
            AND game_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $cartId,
            $gameId
        ]);

        $existing = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        // =================================================
        // ALREADY EXISTS
        // =================================================

        if ($existing) {

            $newQuantity =
                (int) $existing['quantity'] + 1;

            $stmt = $pdo->prepare("
                UPDATE cart_items
                SET quantity = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $newQuantity,
                $existing['id']
            ]);


            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Game quantity increased.',
                    'status' => 'updated',
                    'quantity' => $newQuantity,
                    'price' => (float) $existing['price']
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // INSERT NEW ITEM
        // =================================================

        $gamePrice = (float) $game['price'];

        $stmt = $pdo->prepare("
            INSERT INTO cart_items
            (
                cart_id,
                game_id,
                quantity,
                price
            )
            VALUES
            (
                ?,
                ?,
                1,
                ?
            )
        ");

        $stmt->execute([
            $cartId,
            $gameId,
            $gamePrice
        ]);


        echo json_encode(
            [
                'success' => true,
                'message' => 'Game added to cart.',
                'status' => 'added',
                'quantity' => 1,
                'price' => $gamePrice
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // REMOVE
    // =====================================================

    if ($action === 'remove') {

        $stmt = $pdo->prepare("
            DELETE FROM cart_items
            WHERE cart_id = ?
            AND game_id = ?
        ");

        $stmt->execute([
            $cartId,
            $gameId
        ]);


        echo json_encode(
            [
                'success' => true,
                'message' => 'Game removed from cart.',
                'quantity' => 0
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // INCREASE
    // =====================================================

    if ($action === 'increase') {

        // =================================================
        // GET ITEM
        // =================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                quantity,
                price
            FROM cart_items
            WHERE cart_id = ?
            AND game_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $cartId,
            $gameId
        ]);

        $item = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        if (!$item) {

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Game is not in cart.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // NEW QUANTITY
        // =================================================

        $newQuantity =
            (int) $item['quantity'] + 1;

        $stmt = $pdo->prepare("
            UPDATE cart_items
            SET quantity = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $newQuantity,
            $item['id']
        ]);


        echo json_encode(
            [
                'success' => true,
                'message' => 'Quantity increased.',
                'quantity' => $newQuantity,
                'price' => (float) $item['price']
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // DECREASE
    // =====================================================

    if ($action === 'decrease') {

        // =================================================
        // GET ITEM
        // =================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                quantity,
                price
            FROM cart_items
            WHERE cart_id = ?
            AND game_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $cartId,
            $gameId
        ]);

        $item = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        if (!$item) {

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Game is not in cart.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // IF QUANTITY = 1
        // REMOVE ITEM
        // =================================================

        if ((int) $item['quantity'] <= 1) {

            $stmt = $pdo->prepare("
                DELETE FROM cart_items
                WHERE id = ?
            ");

            $stmt->execute([
                $item['id']
            ]);


            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Game removed from cart.',
                    'quantity' => 0,
                    'price' => (float) $item['price']
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // DECREASE QUANTITY
        // =================================================

        $newQuantity =
            (int) $item['quantity'] - 1;

        $stmt = $pdo->prepare("
            UPDATE cart_items
            SET quantity = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $newQuantity,
            $item['id']
        ]);


        echo json_encode(
            [
                'success' => true,
                'message' => 'Quantity decreased.',
                'quantity' => $newQuantity,
                'price' => (float) $item['price']
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // UPDATE QUANTITY
    // =====================================================

    // This is kept for compatibility with other frontend code.
    //
    // It expects:
    //
    // {
    //     action: "update",
    //     id: 1,
    //     quantity: 3
    // }

    if ($action === 'update') {

        $quantity = isset($input['quantity'])
            ? (int) $input['quantity']
            : 1;


        // =================================================
        // REMOVE IF <= 0
        // =================================================

        if ($quantity <= 0) {

            $stmt = $pdo->prepare("
                DELETE FROM cart_items
                WHERE cart_id = ?
                AND game_id = ?
            ");

            $stmt->execute([
                $cartId,
                $gameId
            ]);


            echo json_encode(
                [
                    'success' => true,
                    'message' => 'Game removed from cart.',
                    'quantity' => 0
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // GET CURRENT PRICE
        // =================================================

        $stmt = $pdo->prepare("
            SELECT price
            FROM cart_items
            WHERE cart_id = ?
            AND game_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $cartId,
            $gameId
        ]);

        $item = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        if (!$item) {

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Game is not in cart.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            exit;
        }


        // =================================================
        // UPDATE
        // =================================================

        $stmt = $pdo->prepare("
            UPDATE cart_items
            SET quantity = ?
            WHERE cart_id = ?
            AND game_id = ?
        ");

        $stmt->execute([
            $quantity,
            $cartId,
            $gameId
        ]);


        echo json_encode(
            [
                'success' => true,
                'message' => 'Cart updated.',
                'quantity' => $quantity,
                'price' => (float) $item['price']
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // CLEAR CART
    // =====================================================

    if ($action === 'clear') {

        $stmt = $pdo->prepare("
            DELETE FROM cart_items
            WHERE cart_id = ?
        ");

        $stmt->execute([
            $cartId
        ]);


        echo json_encode(
            [
                'success' => true,
                'message' => 'Cart cleared.'
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }


    // =====================================================
    // INVALID ACTION
    // =====================================================

    http_response_code(400);

    echo json_encode(
        [
            'success' => false,
            'message' => 'Invalid cart action.'
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
            'message' => 'Failed to process cart.',
            'error' => $e->getMessage()
        ],
        JSON_UNESCAPED_UNICODE
    );

    exit;
}