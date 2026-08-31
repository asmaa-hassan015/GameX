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
// REQUEST METHOD
// =========================================================

$method = $_SERVER['REQUEST_METHOD'];


// =========================================================
// GET REVIEWS
// =========================================================

if ($method === 'GET') {

    try {

        $stmt = $pdo->prepare("

            SELECT

                r.id,
                r.user_id,
                r.game_id,
                r.rating,
                r.comment,
                r.status,
                r.created_at,

                u.username,
                u.avatar,

                g.title AS game_title

            FROM reviews r

            INNER JOIN users u
                ON u.id = r.user_id

            INNER JOIN games g
                ON g.id = r.game_id

            WHERE r.status = 'approved'

            ORDER BY
                r.created_at DESC,
                r.id DESC

        ");

        $stmt->execute();

        $reviews =
            $stmt->fetchAll(PDO::FETCH_ASSOC);


        // =====================================================
        // FORMAT REVIEWS
        // =====================================================

        $formattedReviews = [];


        foreach ($reviews as $review) {

            $formattedReviews[] = [

                'id' =>
                (int) $review['id'],

                'user_id' =>
                (int) $review['user_id'],

                'game_id' =>
                (int) $review['game_id'],

                'name' =>
                $review['username'],

                'rating' =>
                (int) $review['rating'],

                'text' =>
                $review['comment'],

                'game' =>
                $review['game_title'],

                'avatar' =>
                $review['avatar'],

                'date' =>
                date(
                    'M d, Y',
                    strtotime(
                        $review['created_at']
                    )
                )

            ];
        }


        responseJson(

            true,

            'Reviews loaded successfully.',

            [

                'reviews' =>
                $formattedReviews

            ]

        );
    } catch (PDOException $e) {

        error_log(
            'REVIEWS GET DATABASE ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Database error.',
            [],
            500
        );
    } catch (Throwable $e) {

        error_log(
            'REVIEWS GET GENERAL ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Server error.',
            [],
            500
        );
    }
}


// =========================================================
// POST REVIEW
// =========================================================

if ($method === 'POST') {


    // =====================================================
    // CHECK LOGIN
    // =====================================================

    if (!isset($_SESSION['user_id'])) {

        responseJson(

            false,

            'You must be logged in to write a review.',

            [],

            401

        );
    }


    $userId =
        (int) $_SESSION['user_id'];


    try {


        // =====================================================
        // GET JSON DATA
        // =====================================================

        $input =
            json_decode(
                file_get_contents(
                    'php://input'
                ),
                true
            );


        if (!is_array($input)) {

            responseJson(
                false,
                'Invalid request data.',
                [],
                400
            );
        }


        // =====================================================
        // GET VALUES
        // =====================================================

        $gameId =
            isset($input['game_id'])
            ? (int) $input['game_id']
            : 0;


        $rating =
            isset($input['rating'])
            ? (int) $input['rating']
            : 0;


        $text =
            isset($input['text'])
            ? trim($input['text'])
            : '';


        // =====================================================
        // VALIDATION
        // =====================================================

        if ($gameId <= 0) {

            responseJson(
                false,
                'Please select a game.',
                [],
                422
            );
        }


        if ($rating < 1 || $rating > 5) {

            responseJson(
                false,
                'Rating must be between 1 and 5.',
                [],
                422
            );
        }


        if ($text === '') {

            responseJson(
                false,
                'Review text is required.',
                [],
                422
            );
        }


        if (mb_strlen($text) < 5) {

            responseJson(
                false,
                'Review must contain at least 5 characters.',
                [],
                422
            );
        }


        if (mb_strlen($text) > 1000) {

            responseJson(
                false,
                'Review cannot exceed 1000 characters.',
                [],
                422
            );
        }


        // =====================================================
        // CHECK USER
        // =====================================================

        $userStmt = $pdo->prepare("

            SELECT
                id,
                username,
                avatar,
                status

            FROM users

            WHERE id = ?

            LIMIT 1

        ");


        $userStmt->execute([
            $userId
        ]);


        $user =
            $userStmt->fetch(
                PDO::FETCH_ASSOC
            );


        if (!$user) {

            responseJson(
                false,
                'User account not found.',
                [],
                401
            );
        }


        if ($user['status'] !== 'active') {

            responseJson(
                false,
                'Your account cannot submit reviews.',
                [],
                403
            );
        }


        // =====================================================
        // CHECK GAME
        // =====================================================

        $gameStmt = $pdo->prepare("

            SELECT
                id,
                title,
                status

            FROM games

            WHERE id = ?

            LIMIT 1

        ");


        $gameStmt->execute([
            $gameId
        ]);


        $game =
            $gameStmt->fetch(
                PDO::FETCH_ASSOC
            );


        if (!$game) {

            responseJson(
                false,
                'Game not found.',
                [],
                404
            );
        }


        if ($game['status'] !== 'active') {

            responseJson(
                false,
                'This game is not available for reviews.',
                [],
                400
            );
        }


        // =====================================================
        // INSERT REVIEW
        // =====================================================

        $insertStmt = $pdo->prepare("

            INSERT INTO reviews
            (
                user_id,
                game_id,
                rating,
                comment,
                status
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                'approved'
            )

        ");


        $insertStmt->execute([

            $userId,
            $gameId,
            $rating,
            $text

        ]);


        $reviewId =
            (int) $pdo->lastInsertId();


        // =====================================================
        // SUCCESS
        // =====================================================

        responseJson(

            true,

            'Review submitted successfully.',

            [

                'review' => [

                    'id' =>
                    $reviewId,

                    'user_id' =>
                    $userId,

                    'game_id' =>
                    $gameId,

                    'name' =>
                    $user['username'],

                    'rating' =>
                    $rating,

                    'text' =>
                    $text,

                    'game' =>
                    $game['title'],

                    'avatar' =>
                    $user['avatar'],

                    'status' =>
                    'approved'

                ]

            ]

        );
    } catch (PDOException $e) {

        error_log(
            'REVIEWS POST DATABASE ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Database error.',
            [],
            500
        );
    } catch (Throwable $e) {

        error_log(
            'REVIEWS POST GENERAL ERROR: ' .
                $e->getMessage()
        );

        responseJson(
            false,
            'Server error.',
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
