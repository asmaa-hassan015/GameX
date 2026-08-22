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
// GET PLAYERS
// =========================================================

try {

    // =====================================================
    // FETCH PLAYERS
    // =====================================================

    $sql = "
        SELECT
            id,
            username,
            email,
            status,
            avatar,
            created_at

        FROM users

        WHERE role = 'user'

        ORDER BY id DESC
    ";


    // =====================================================
    // EXECUTE QUERY
    // =====================================================

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // FORMAT RESPONSE DATA
    // =====================================================

    $result = [];

    foreach ($players as $player) {

        $result[] = [

            'id' =>
                (int) $player['id'],

            'name' =>
                $player['username'],

            'email' =>
                $player['email'],

            'joined' =>
                $player['created_at'],

            'status' =>
                $player['status'],

            'avatar' =>
                $player['avatar']
        ];
    }


    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    echo json_encode([
        'success' => true,
        'players' => $result
    ]);


} catch (PDOException $e) {

    // =====================================================
    // DATABASE ERROR
    // =====================================================

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to load players.'
    ]);
}