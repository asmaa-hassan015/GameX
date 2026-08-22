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
// AVATAR COLORS
// =========================================================

// Colors used for player initials badges.

$colors = [
    '#7C2CFF',
    '#22C55E',
    '#F59E0B',
    '#EF4444',
    '#38BDF8',
    '#EC4899',
];


// =========================================================
// GET ORDERS
// =========================================================

try {

    // =====================================================
    // FETCH ORDERS
    // =====================================================

    $sql = "
        SELECT
            o.id,
            o.order_number,
            o.total,
            o.status,
            o.created_at,
            u.username

        FROM orders o

        INNER JOIN users u
            ON u.id = o.user_id

        ORDER BY o.id DESC
    ";


    // =====================================================
    // EXECUTE QUERY
    // =====================================================

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // FORMAT RESPONSE DATA
    // =====================================================

    $result = [];

    foreach ($orders as $order) {

        $result[] = [

            'id' => (int) $order['id'],

            'order_number' =>
            $order['order_number'],

            'player' =>
            $order['username'],

            'total' =>
            (float) $order['total'],

            'status' =>
            ucfirst($order['status']),

            'date' =>
            date(
                'M d, Y',
                strtotime($order['created_at'])
            ),

            'color' =>
            $colors[$order['id'] % count($colors)]
        ];
    }


    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'orders' => $result
    ]);
} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to load orders.'
    ]);
}