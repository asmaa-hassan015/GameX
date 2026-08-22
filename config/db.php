<?php

// =========================================================
// GAME X - DATABASE CONNECTION
// =========================================================

$host = 'localhost';
$dbname = 'game_x';
$username = 'root';
$password = '';

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (PDOException $e) {

    http_response_code(500);

    die(json_encode([
            'success' => false,
            'message' => 'Database connection failed.',
            'error' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE));
}