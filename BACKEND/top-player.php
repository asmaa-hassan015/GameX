<?php

session_start();

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json; charset=utf-8');

try {

    // =========================================================
    // PERIOD
    // =========================================================

    $period = $_GET['period'] ?? 'global';

    if (!in_array($period, ['global', 'weekly', 'monthly'])) {
        $period = 'global';
    }

    // =========================================================
    // DATE FILTER
    // =========================================================

    $dateCondition = "";

    if ($period === 'weekly') {

        $dateCondition = "
            AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ";
    } elseif ($period === 'monthly') {

        $dateCondition = "
            AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ";
    }

    // =========================================================
    // GET ALL ACTIVE PLAYERS
    // =========================================================

    $sql = "
        SELECT
            u.id,
            u.username AS name,
            u.avatar,
            COALESCE(SUM(oi.quantity), 0) AS purchases,
            COALESCE(SUM(oi.total), 0) AS spent

        FROM users u

        LEFT JOIN orders o
            ON o.user_id = u.id
            AND o.status = 'completed'
            $dateCondition

        LEFT JOIN order_items oi
            ON oi.order_id = o.id

        WHERE
            u.role = 'user'
            AND u.status = 'active'

        GROUP BY
            u.id,
            u.username,
            u.avatar

        ORDER BY
            spent DESC,
            u.id ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =========================================================
    // BUILD PLAYERS DATA
    // =========================================================

    $result = [];

    foreach ($players as $player) {

        $userId = (int) $player['id'];

        // =====================================================
        // GET LATEST PURCHASED GAME
        // =====================================================

        $gameSql = "
            SELECT
                g.id,
                g.title,
                g.image,
                g.price,
                c.name AS genre

            FROM order_items oi

            INNER JOIN orders o
                ON o.id = oi.order_id

            INNER JOIN games g
                ON g.id = oi.game_id

            LEFT JOIN game_categories gc
                ON gc.game_id = g.id

            LEFT JOIN categories c
                ON c.id = gc.category_id

            WHERE
                o.user_id = :user_id
                AND o.status = 'completed'
                $dateCondition

            ORDER BY
                o.created_at DESC,
                oi.id DESC

            LIMIT 1
        ";

        $gameStmt = $pdo->prepare($gameSql);

        $gameStmt->execute([
            'user_id' => $userId
        ]);

        $game = $gameStmt->fetch(PDO::FETCH_ASSOC);

        // =====================================================
        // AVATAR
        // =====================================================

        $avatar = $player['avatar'];

        if (empty($avatar)) {
            $avatar = 'src/Images/avatars/default.png';
        }

        // =====================================================
        // GAME
        // =====================================================

        $gameData = [
            'id' => $game
                ? (int) $game['id']
                : null,

            'title' => $game['title'] ?? '',

            'img' => $game['image'] ?? '',

            'genre' => $game['genre'] ?? '',

            'price' => $game
                ? (float) $game['price']
                : 0
        ];

        // =====================================================
        // CURRENT PERIOD STATS
        // =====================================================

        $purchases = (int) $player['purchases'];

        $spent = (float) $player['spent'];

        // =====================================================
        // PLAYER
        // =====================================================

        $result[] = [
            'id' => $userId,

            'name' => $player['name'],

            'avatar' => $avatar,

            'stats' => [
                'global' => [
                    'purchases' => 0,
                    'spent' => 0
                ],

                'weekly' => [
                    'purchases' => 0,
                    'spent' => 0
                ],

                'monthly' => [
                    'purchases' => 0,
                    'spent' => 0
                ]
            ],

            'game' => $gameData
        ];

        // =====================================================
        // SET CURRENT PERIOD
        // =====================================================

        $index = count($result) - 1;

        $result[$index]['stats'][$period] = [
            'purchases' => $purchases,
            'spent' => $spent
        ];
    }

    // =========================================================
    // RESPONSE
    // =========================================================

    echo json_encode(
        [
            'success' => true,

            'period' => $period,

            'count' => count($result),

            'players' => $result
        ],
        JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
    );
} catch (PDOException $e) {

    // =========================================================
    // DATABASE ERROR
    // =========================================================

    http_response_code(500);

    echo json_encode(
        [
            'success' => false,

            'message' => 'Database error.'
        ],
        JSON_UNESCAPED_UNICODE
    );
}