<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';


// =========================================================
// HELPER
// =========================================================

function responseJson(
    bool $success,
    string $message = '',
    array $extra = [],
    int $statusCode = 200
): void {

    http_response_code($statusCode);

    echo json_encode(
        array_merge(
            [
                'success' => $success,
                'message' => $message
            ],
            $extra
        ),
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


// =========================================================
// CHECK LOGIN
// =========================================================

if (!isset($_SESSION['user_id'])) {

    responseJson(
        false,
        'User is not logged in.',
        [],
        401
    );
}

$userId = (int) $_SESSION['user_id'];


// =========================================================
// REQUEST METHOD
// =========================================================

$method = $_SERVER['REQUEST_METHOD'];


// =========================================================
// ALLOWED PREDEFINED AVATARS
// =========================================================

$allowedAvatars = [

    'src/Images/avatars/blaze.png',

    'src/Images/avatars/sentinel.png',

    'src/Images/avatars/raven.png',

    'src/Images/avatars/phantom.png'

];


// =========================================================
// GET PROFILE
// =========================================================

if ($method === 'GET') {

    try {

        // =====================================================
        // GET USER
        // =====================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                email,
                avatar
            FROM users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // =====================================================
        // CHECK USER
        // =====================================================

        if (!$user) {

            responseJson(
                false,
                'User not found.',
                [],
                404
            );
        }


        // =====================================================
        // USER DATA
        // =====================================================

        $userData = [

            'id' => (int) $user['id'],

            'username' =>
            $user['username'] ?? 'Player',

            'email' =>
            $user['email'] ?? '',

            'avatar' =>
            $user['avatar'] ?? null,

            'level' => 1

        ];


        // =====================================================
        // STATS
        // =====================================================

        $stats = [

            'games' => 0,

            'achievements' => 0,

            'hours' => 0,

            'wishlist' => 0,

            'currentXP' => 0,

            'requiredXP' => 100

        ];


        // =====================================================
        // WISHLIST COUNT
        // =====================================================

        try {

            $wishlistStmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM wishlist
                WHERE user_id = ?
            ");

            $wishlistStmt->execute([$userId]);

            $stats['wishlist'] =
                (int) $wishlistStmt->fetchColumn();
        } catch (PDOException $e) {

            $stats['wishlist'] = 0;
        }


        // =====================================================
        // RESPONSE
        // =====================================================

        responseJson(
            true,
            'Profile loaded successfully.',
            [

                'user' => $userData,

                'stats' => $stats,

                'recentlyPlayed' => []

            ]
        );
    } catch (PDOException $e) {

        error_log(
            'PLAYER PROFILE GET ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Failed to load profile.',
            [],
            500
        );
    }
}


// =========================================================
// UPDATE PROFILE
// =========================================================

if ($method === 'POST') {

    try {

        // =====================================================
        // NAME
        // =====================================================

        $name = trim(
            $_POST['name'] ?? ''
        );


        // =====================================================
        // NAME VALIDATION
        // =====================================================

        if ($name === '') {

            responseJson(
                false,
                'Display name is required.',
                [],
                400
            );
        }

        if (mb_strlen($name) < 2) {

            responseJson(
                false,
                'Display name must contain at least 2 characters.',
                [],
                400
            );
        }

        if (mb_strlen($name) > 50) {

            responseJson(
                false,
                'Display name is too long.',
                [],
                400
            );
        }


        // =====================================================
        // GET CURRENT USER
        // =====================================================

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                email,
                avatar
            FROM users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$userId]);

        $currentUser =
            $stmt->fetch(PDO::FETCH_ASSOC);


        // =====================================================
        // CHECK USER
        // =====================================================

        if (!$currentUser) {

            responseJson(
                false,
                'User not found.',
                [],
                404
            );
        }


        // =====================================================
        // CURRENT AVATAR
        // =====================================================

        $currentAvatar =
            $currentUser['avatar'] ?? null;

        $newAvatar =
            $currentAvatar;


        // =====================================================
        // CUSTOM UPLOADED AVATAR
        // =====================================================

        if (
            isset($_FILES['avatar_file']) &&
            $_FILES['avatar_file']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            $file = $_FILES['avatar_file'];


            // =================================================
            // UPLOAD ERROR
            // =================================================

            if ($file['error'] !== UPLOAD_ERR_OK) {

                responseJson(
                    false,
                    'Failed to upload avatar.',
                    [],
                    400
                );
            }


            // =================================================
            // MAX SIZE 5MB
            // =================================================

            $maxSize = 5 * 1024 * 1024;

            if ($file['size'] > $maxSize) {

                responseJson(
                    false,
                    'Avatar must be smaller than 5MB.',
                    [],
                    400
                );
            }


            // =================================================
            // CHECK MIME TYPE
            // =================================================

            $finfo = new finfo(FILEINFO_MIME_TYPE);

            $mimeType =
                $finfo->file($file['tmp_name']);

            $allowedMimeTypes = [

                'image/jpeg' => 'jpg',

                'image/png' => 'png',

                'image/webp' => 'webp'

            ];


            if (
                !isset(
                    $allowedMimeTypes[$mimeType]
                )
            ) {

                responseJson(
                    false,
                    'Only JPG, PNG or WEBP images are allowed.',
                    [],
                    400
                );
            }


            // =================================================
            // CREATE UPLOAD DIRECTORY
            // =================================================

            $uploadDirectory =
                __DIR__ . '/../uploads/avatars/';


            if (!is_dir($uploadDirectory)) {

                if (
                    !mkdir(
                        $uploadDirectory,
                        0755,
                        true
                    )
                ) {

                    responseJson(
                        false,
                        'Could not create avatar upload directory.',
                        [],
                        500
                    );
                }
            }


            // =================================================
            // GENERATE UNIQUE FILE NAME
            // =================================================

            $extension =
                $allowedMimeTypes[$mimeType];

            $randomName =
                bin2hex(random_bytes(8));

            $fileName =
                'user_' .
                $userId .
                '_' .
                time() .
                '_' .
                $randomName .
                '.' .
                $extension;

            $destination =
                $uploadDirectory .
                $fileName;


            // =================================================
            // MOVE FILE
            // =================================================

            if (
                !move_uploaded_file(
                    $file['tmp_name'],
                    $destination
                )
            ) {

                responseJson(
                    false,
                    'Could not save uploaded avatar.',
                    [],
                    500
                );
            }


            // =================================================
            // DATABASE PATH
            // =================================================

            $newAvatar =
                'uploads/avatars/' .
                $fileName;
        }


        // =====================================================
        // PREDEFINED AVATAR
        // =====================================================

        elseif (isset($_POST['avatar'])) {

            $selectedAvatar =
                trim($_POST['avatar']);


            if ($selectedAvatar !== '') {

                if (
                    !in_array(
                        $selectedAvatar,
                        $allowedAvatars,
                        true
                    )
                ) {

                    responseJson(
                        false,
                        'Invalid avatar selected.',
                        [],
                        400
                    );
                }


                $newAvatar =
                    $selectedAvatar;
            }
        }


        // =====================================================
        // UPDATE DATABASE
        // =====================================================

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                username = ?,
                avatar = ?
            WHERE id = ?
        ");

        $stmt->execute([

            $name,

            $newAvatar,

            $userId

        ]);


        // =====================================================
        // UPDATE SESSION
        // =====================================================

        $_SESSION['username'] =
            $name;

        $_SESSION['avatar'] =
            $newAvatar;


        // =====================================================
        // RESPONSE
        // =====================================================

        responseJson(
            true,
            'Profile updated successfully.',
            [

                'user' => [

                    'id' =>
                    $userId,

                    'username' =>
                    $name,

                    'email' =>
                    $currentUser['email'] ?? '',

                    'avatar' =>
                    $newAvatar,

                    'level' => 1

                ]

            ]
        );
    } catch (PDOException $e) {

        error_log(
            'PLAYER PROFILE POST DATABASE ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Database update failed.',
            [],
            500
        );
    } catch (Throwable $e) {

        error_log(
            'PLAYER PROFILE POST GENERAL ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Something went wrong.',
            [],
            500
        );
    }
}


// =========================================================
// INVALID METHOD
// =========================================================

responseJson(
    false,
    'Method not allowed.',
    [],
    405
);