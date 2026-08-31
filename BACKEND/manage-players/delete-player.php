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

// =========================================================
// VALIDATE REQUEST DATA
// =========================================================

if (!is_array($input)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request data.'
    ]);

    exit;
}

// =========================================================
// READ PLAYER ID
// =========================================================

$playerId = isset($input['id'])
    ? (int) $input['id']
    : 0;

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
// DELETE PLAYER PROCESS
// =========================================================

try {

    // =====================================================
    // CHECK PLAYER EXISTENCE
    // =====================================================

    // Only regular users can be deleted.

    $checkSql = "
        SELECT id
        FROM users
        WHERE id = :id
        AND role = 'user'
        LIMIT 1
    ";

    $checkStmt =
        $pdo->prepare($checkSql);

    $checkStmt->execute([
        ':id' => $playerId
    ]);

    $player =
        $checkStmt->fetch(
            PDO::FETCH_ASSOC
        );

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
    // DELETE PLAYER
    // =====================================================

    $sql = "
        DELETE FROM users
        WHERE id = :id
        AND role = 'user'
    ";

    $stmt =
        $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $playerId
    ]);

    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'message' => 'Player deleted successfully.',
        'id' => $playerId
    ]);
} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete player.'
    ]);
}
