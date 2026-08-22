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
// READ PLAYER DATA
// =========================================================

$playerId = isset($input['id'])
    ? (int) $input['id']
    : 0;

$status = $input['status'] ?? '';


// =========================================================
// VALIDATE PLAYER ID
// =========================================================

if ($playerId <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid player ID.'
    ]);

    exit;
}


// =========================================================
// VALIDATE PLAYER STATUS
// =========================================================

if (!in_array(
    $status,
    ['active', 'blocked'],
    true
)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid player status.'
    ]);

    exit;
}


// =========================================================
// UPDATE PLAYER PROCESS
// =========================================================

try {

    // =====================================================
    // CHECK PLAYER EXISTENCE
    // =====================================================

    // Only regular users can be updated.

    $checkSql = "
        SELECT id
        FROM users
        WHERE id = :id
        AND role = 'user'
        LIMIT 1
    ";

    $checkStmt = $pdo->prepare($checkSql);

    $checkStmt->execute([
        ':id' => $playerId
    ]);

    $player = $checkStmt->fetch(PDO::FETCH_ASSOC);


    // =====================================================
    // PLAYER NOT FOUND
    // =====================================================

    if (!$player) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Player not found.'
        ]);

        exit;
    }


    // =====================================================
    // UPDATE PLAYER STATUS
    // =====================================================

    $sql = "
        UPDATE users
        SET status = :status
        WHERE id = :id
        AND role = 'user'
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':status' => $status,
        ':id' => $playerId
    ]);


    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'message' => 'Player status updated successfully.',
        'player' => [
            'id' => $playerId,
            'status' => $status
        ]
    ]);
} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to update player.'
    ]);
}