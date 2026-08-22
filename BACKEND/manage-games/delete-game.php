<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/db.php';


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
        SELECT id, title
        FROM games
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        throw new Exception('Game not found.');
    }


    // =====================================================
    // CHECK ORDER ITEMS
    // =====================================================

    // Check whether the game is already used in an order.

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM order_items
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);

    $orderItemsCount = (int) $stmt->fetchColumn();

    if ($orderItemsCount > 0) {
        throw new Exception(
            'This game cannot be deleted because it is already used in an order.'
        );
    }


    // =====================================================
    // START DATABASE TRANSACTION
    // =====================================================

    $pdo->beginTransaction();


    // =====================================================
    // DELETE GAME CATEGORIES
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM game_categories
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);


    // =====================================================
    // DELETE GAME PLATFORMS
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM game_platforms
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);


    // =====================================================
    // DELETE GAME IMAGES
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM game_images
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);


    // =====================================================
    // DELETE WISHLIST ITEMS
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM wishlist
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);


    // =====================================================
    // DELETE CART ITEMS
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM cart_items
        WHERE game_id = :game_id
    ");

    $stmt->execute([
        ':game_id' => $id
    ]);


    // =====================================================
    // DELETE GAME
    // =====================================================

    $stmt = $pdo->prepare("
        DELETE FROM games
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);


    // =====================================================
    // VERIFY DELETE OPERATION
    // =====================================================

    if ($stmt->rowCount() === 0) {
        throw new Exception(
            'Game could not be deleted.'
        );
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
        'message' => 'Game deleted successfully.',
        'deleted_id' => $id
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {

    // =====================================================
    // ROLLBACK DATABASE CHANGES
    // =====================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    // =====================================================
    // SET ERROR STATUS
    // =====================================================

    http_response_code(400);


    // =====================================================
    // HANDLE FOREIGN KEY RESTRICTION
    // =====================================================

    if (
        isset($e->errorInfo[1]) &&
        (int) $e->errorInfo[1] === 1451
    ) {

        echo json_encode([
            'success' => false,
            'message' =>
            'This game cannot be deleted because it is already used in an order.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // =====================================================
    // DATABASE ERROR RESPONSE
    // =====================================================

    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete game.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {

    // =====================================================
    // ROLLBACK DATABASE CHANGES
    // =====================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    // =====================================================
    // SET ERROR STATUS
    // =====================================================

    http_response_code(400);


    // =====================================================
    // GENERAL ERROR RESPONSE
    // =====================================================

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}