<?php

header('Content-Type: application/json; charset=utf-8');

session_start();


// =========================================================
// DATABASE
// =========================================================

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
// READ JSON INPUT
// =========================================================

$input = json_decode(
    file_get_contents('php://input'),
    true
);

if (!is_array($input)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request data.'
    ]);

    exit;
}


// =========================================================
// READ ORDER ID
// =========================================================

$orderId = isset($input['id'])
    ? (int) $input['id']
    : 0;


// =========================================================
// VALIDATE ORDER ID
// =========================================================

if ($orderId <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid order ID.'
    ]);

    exit;
}


// =========================================================
// DELETE ORDER PROCESS
// =========================================================

try {

    // =====================================================
    // CHECK ORDER EXISTENCE
    // =====================================================

    $checkSql = "
        SELECT id
        FROM orders
        WHERE id = :id
        LIMIT 1
    ";

    $checkStmt = $pdo->prepare($checkSql);

    $checkStmt->execute([
        ':id' => $orderId
    ]);

    $order = $checkStmt->fetch(PDO::FETCH_ASSOC);


    // =====================================================
    // ORDER NOT FOUND
    // =====================================================

    if (!$order) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Order not found.'
        ]);

        exit;
    }


    // =====================================================
    // DELETE ORDER
    // =====================================================

    // Related order_items and payments are deleted
    // automatically through ON DELETE CASCADE.

    $sql = "
        DELETE FROM orders
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $orderId
    ]);


    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'message' => 'Order deleted successfully.',
        'id' => $orderId
    ]);
} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete order.'
    ]);
}