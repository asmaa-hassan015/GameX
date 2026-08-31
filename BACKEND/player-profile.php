<?php

// =========================================================
// GAME X - PLAYER PROFILE API
// Place this file at: BACKEND/player-profile.php
// =========================================================

session_start();

header('Content-Type: application/json; charset=utf-8');

// =========================================================
// FORCE EVERY ERROR TO COME BACK AS JSON
// (never let PHP print raw HTML "Warning:" / "Fatal error:")
// =========================================================

ini_set('display_errors', '0');
error_reporting(E_ALL);

set_error_handler(function ($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'PHP error: ' . $message,
        'file' => $file,
        'line' => $line,
    ], JSON_UNESCAPED_UNICODE);
    exit;
});

register_shutdown_function(function () {
    $error = error_get_last();

    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
        }

        echo json_encode([
            'success' => false,
            'message' => 'Fatal PHP error: ' . $error['message'],
            'file' => $error['file'],
            'line' => $error['line'],
        ], JSON_UNESCAPED_UNICODE);
    }
});

// =========================================================
// LOCATE db.php AUTOMATICALLY
// =========================================================

$dbCandidates = [
    __DIR__ . '/../db.php',
    __DIR__ . '/db.php',
    __DIR__ . '/../BACKEND/db.php',
    __DIR__ . '/../config/db.php',
    __DIR__ . '/../../db.php',
];

$dbPath = null;

foreach ($dbCandidates as $candidate) {
    if (file_exists($candidate)) {
        $dbPath = $candidate;
        break;
    }
}

if ($dbPath === null) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'db.php not found. Checked: ' . implode(', ', $dbCandidates)
            . '. Edit the $dbCandidates array in this file to add the real path.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once $dbPath;

// =========================================================
// AUTH CHECK
// =========================================================

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

// =========================================================
// PREDEFINED AVATARS WHITELIST
// Mirrors allowedAvatarPaths in player-profile.js
// =========================================================

$allowedAvatars = [
    'src/Images/avatars/blaze.png',
    'src/Images/avatars/sentinel.png',
    'src/Images/avatars/raven.png',
    'src/Images/avatars/phantom.png',
];

// Folder where uploaded custom avatars are stored.
// Adjust to match your real project structure.
$uploadDir = __DIR__ . '/../uploads/avatars/';
$uploadUrlPrefix = 'uploads/avatars/';

// =========================================================
// GET -> LOAD PROFILE
// =========================================================

if ($method === 'GET') {
    try {
        // -----------------------------------------------
        // USER
        // -----------------------------------------------

        $stmt = $pdo->prepare(
            'SELECT id, username, avatar, created_at
             FROM users
             WHERE id = ?
             LIMIT 1'
        );
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'User not found.',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $memberSince = '';
        if (!empty($user['created_at'])) {
            $memberSince = date('F Y', strtotime($user['created_at']));
        }

        // -----------------------------------------------
        // STATS
        // -----------------------------------------------

        // Total orders (all statuses)
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE user_id = ?');
        $stmt->execute([$userId]);
        $totalOrders = (int) $stmt->fetchColumn();

        // Distinct games purchased through completed orders
        $stmt = $pdo->prepare(
            "SELECT COUNT(DISTINCT oi.game_id)
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             WHERE o.user_id = ? AND o.status = 'completed'"
        );
        $stmt->execute([$userId]);
        $gamesPurchased = (int) $stmt->fetchColumn();

        // Wishlist
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = ?');
        $stmt->execute([$userId]);
        $wishlistCount = (int) $stmt->fetchColumn();

        // Reviews written by the user
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM reviews WHERE user_id = ?');
        $stmt->execute([$userId]);
        $reviewsCount = (int) $stmt->fetchColumn();

        // -----------------------------------------------
        // RECENT PURCHASES (last 4 order items, most recent order first)
        // -----------------------------------------------

        $stmt = $pdo->prepare(
            "SELECT
                oi.game_title AS title,
                g.image AS image,
                oi.price AS price,
                o.status AS status,
                o.created_at AS purchasedAt
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             LEFT JOIN games g ON g.id = oi.game_id
             WHERE o.user_id = ?
             ORDER BY o.created_at DESC
             LIMIT 4"
        );
        $stmt->execute([$userId]);
        $recentPurchases = $stmt->fetchAll();

        // -----------------------------------------------
        // RESPONSE
        // -----------------------------------------------

        echo json_encode([
            'success' => true,
            'user' => [
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'avatar' => $user['avatar'],
                'memberSince' => $memberSince,
            ],
            'stats' => [
                'orders' => $totalOrders,
                'games' => $gamesPurchased,
                'wishlist' => $wishlistCount,
                'reviews' => $reviewsCount,
            ],
            'recentPurchases' => $recentPurchases,
        ], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to load profile.',
            'error' => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

// =========================================================
// POST -> UPDATE PROFILE (name + avatar)
// =========================================================

if ($method === 'POST') {
    try {
        // -----------------------------------------------
        // NAME
        // -----------------------------------------------

        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            throw new Exception('Display name is required.');
        }

        if (mb_strlen($name) < 2 || mb_strlen($name) > 50) {
            throw new Exception('Display name must be between 2 and 50 characters.');
        }

        if (!preg_match('/^\p{L}+(?:\s\p{L}+)*$/u', $name)) {
            throw new Exception('Display name may only contain letters.');
        }

        // -----------------------------------------------
        // AVATAR
        // -----------------------------------------------

        $avatarToSave = null; // null = keep existing avatar unchanged

        if (!empty($_FILES['avatar_file']['name'])) {
            $file = $_FILES['avatar_file'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Avatar upload failed.');
            }

            $maxSize = 5 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                throw new Exception('Avatar must be smaller than 5MB.');
            }

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!isset($allowedTypes[$mime])) {
                throw new Exception('Only JPG, PNG or WEBP images are allowed.');
            }

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = $allowedTypes[$mime];
            $filename = 'user_' . $userId . '_' . time() . '.' . $ext;
            $destination = $uploadDir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new Exception('Could not save the uploaded avatar.');
            }

            $avatarToSave = $uploadUrlPrefix . $filename;
        } elseif (isset($_POST['avatar']) && $_POST['avatar'] !== '') {
            $candidate = trim($_POST['avatar']);

            if (!in_array($candidate, $allowedAvatars, true)) {
                throw new Exception('Invalid avatar selection.');
            }

            $avatarToSave = $candidate;
        }

        // -----------------------------------------------
        // UPDATE
        // -----------------------------------------------

        if ($avatarToSave !== null) {
            $stmt = $pdo->prepare(
                'UPDATE users SET username = ?, avatar = ? WHERE id = ?'
            );
            $stmt->execute([$name, $avatarToSave, $userId]);
        } else {
            $stmt = $pdo->prepare(
                'UPDATE users SET username = ? WHERE id = ?'
            );
            $stmt->execute([$name, $userId]);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
    }

    exit;
}

// =========================================================
// METHOD NOT ALLOWED
// =========================================================

http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Method not allowed.',
], JSON_UNESCAPED_UNICODE);