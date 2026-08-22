<?php

// =========================================================
// GET DASHBOARD DATA
// =========================================================

header('Content-Type: application/json; charset=utf-8');


// =========================================================
// DATABASE
// =========================================================

require_once __DIR__ . '/../config/db.php';


// =========================================================
// HELPER FUNCTION
// =========================================================

function calculateGrowth($current, $previous)
{
    $current = (float) $current;
    $previous = (float) $previous;

    if ($previous == 0) {
        return $current > 0 ? 100 : 0;
    }

    return round(
        (($current - $previous) / $previous) * 100,
        1
    );
}


// =========================================================
// PERIOD
// =========================================================

$period = $_GET['period'] ?? 'week';

if (!in_array($period, ['week', 'month'], true)) {
    $period = 'week';
}


// =========================================================
// MAIN PROCESS
// =========================================================

try {

    // =====================================================
    // DATE RANGE
    // =====================================================

    if ($period === 'month') {

        $currentStart = date('Y-m-01');
        $currentEnd = date('Y-m-d 23:59:59');

        $previousStart = date(
            'Y-m-01',
            strtotime('-1 month')
        );

        $previousEnd = date(
            'Y-m-t 23:59:59',
            strtotime('-1 month')
        );
    } else {

        $currentStart = date(
            'Y-m-d',
            strtotime('monday this week')
        );

        $currentEnd = date('Y-m-d 23:59:59');

        $previousStart = date(
            'Y-m-d',
            strtotime('monday last week')
        );

        $previousEnd = date(
            'Y-m-d 23:59:59',
            strtotime('sunday last week')
        );
    }


    // =====================================================
    // TOTAL PLAYERS
    // =====================================================

    $stmtPlayers = $pdo->query("
        SELECT COUNT(*)
        FROM users
        WHERE role = 'user'
        AND status = 'active'
    ");

    $totalPlayers = (int) $stmtPlayers->fetchColumn();


    // =====================================================
    // TOTAL GAMES
    // =====================================================

    $stmtGames = $pdo->query("
        SELECT COUNT(*)
        FROM games
        WHERE status = 'active'
    ");

    $totalGames = (int) $stmtGames->fetchColumn();


    // =====================================================
    // TOTAL ORDERS
    // =====================================================

    $stmtOrders = $pdo->query("
        SELECT COUNT(*)
        FROM orders
    ");

    $totalOrders = (int) $stmtOrders->fetchColumn();


    // =====================================================
    // TOTAL REVENUE
    // =====================================================

    $stmtRevenue = $pdo->query("
        SELECT COALESCE(SUM(total), 0)
        FROM orders
        WHERE status = 'completed'
    ");

    $totalRevenue = (float) $stmtRevenue->fetchColumn();


    // =====================================================
    // PLAYERS GROWTH
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM users
        WHERE role = 'user'
        AND status = 'active'
        AND created_at BETWEEN ? AND ?
    ");

    $stmt->execute([
        $currentStart,
        $currentEnd
    ]);

    $currentPlayers = (int) $stmt->fetchColumn();

    $stmt->execute([
        $previousStart,
        $previousEnd
    ]);

    $previousPlayers = (int) $stmt->fetchColumn();

    $playersGrowth = calculateGrowth(
        $currentPlayers,
        $previousPlayers
    );


    // =====================================================
    // GAMES GROWTH
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM games
        WHERE status = 'active'
        AND created_at BETWEEN ? AND ?
    ");

    $stmt->execute([
        $currentStart,
        $currentEnd
    ]);

    $currentGames = (int) $stmt->fetchColumn();

    $stmt->execute([
        $previousStart,
        $previousEnd
    ]);

    $previousGames = (int) $stmt->fetchColumn();

    $gamesGrowth = calculateGrowth(
        $currentGames,
        $previousGames
    );


    // =====================================================
    // ORDERS GROWTH
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM orders
        WHERE created_at BETWEEN ? AND ?
    ");

    $stmt->execute([
        $currentStart,
        $currentEnd
    ]);

    $currentOrders = (int) $stmt->fetchColumn();

    $stmt->execute([
        $previousStart,
        $previousEnd
    ]);

    $previousOrders = (int) $stmt->fetchColumn();

    $ordersGrowth = calculateGrowth(
        $currentOrders,
        $previousOrders
    );


    // =====================================================
    // REVENUE GROWTH
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(total), 0)
        FROM orders
        WHERE status = 'completed'
        AND created_at BETWEEN ? AND ?
    ");

    $stmt->execute([
        $currentStart,
        $currentEnd
    ]);

    $currentRevenue = (float) $stmt->fetchColumn();

    $stmt->execute([
        $previousStart,
        $previousEnd
    ]);

    $previousRevenue = (float) $stmt->fetchColumn();

    $revenueGrowth = calculateGrowth(
        $currentRevenue,
        $previousRevenue
    );


    // =====================================================
    // TOP GAMES
    // =====================================================

    $stmt = $pdo->query("
        SELECT
            oi.game_id,
            oi.game_title AS name,
            SUM(oi.quantity) AS orders
        FROM order_items oi
        INNER JOIN orders o
            ON o.id = oi.order_id
        WHERE o.status = 'completed'
        GROUP BY
            oi.game_id,
            oi.game_title
        ORDER BY orders DESC
        LIMIT 5
    ");

    $topGamesRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // =====================================================
    // TOTAL SOLD GAMES
    // =====================================================

    $stmtTotalSold = $pdo->query("
        SELECT COALESCE(SUM(oi.quantity), 0)
        FROM order_items oi
        INNER JOIN orders o
            ON o.id = oi.order_id
        WHERE o.status = 'completed'
    ");

    $totalSold = (int) $stmtTotalSold->fetchColumn();


    // =====================================================
    // FORMAT TOP GAMES
    // =====================================================

    $topGames = [];

    foreach ($topGamesRaw as $game) {

        $ordersCount = (int) $game['orders'];

        $percentage = $totalSold > 0
            ? round(
                ($ordersCount / $totalSold) * 100,
                1
            )
            : 0;

        $topGames[] = [
            'id' => (int) $game['game_id'],
            'name' => $game['name'],
            'orders' => $ordersCount,
            'percentage' => $percentage
        ];
    }


    // =====================================================
    // RECENT ORDERS
    // =====================================================

    $stmtOrdersRecent = $pdo->query("
        SELECT
            o.id,
            u.username AS player,
            GROUP_CONCAT(
                DISTINCT oi.game_title
                ORDER BY oi.id
                SEPARATOR ', '
            ) AS game,
            o.total AS amount,
            o.status,
            o.created_at AS date
        FROM orders o
        INNER JOIN users u
            ON u.id = o.user_id
        LEFT JOIN order_items oi
            ON oi.order_id = o.id
        GROUP BY
            o.id,
            u.username,
            o.total,
            o.status,
            o.created_at
        ORDER BY o.created_at DESC
        LIMIT 10
    ");

    $recentOrders = [];

    while ($row = $stmtOrdersRecent->fetch(PDO::FETCH_ASSOC)) {

        $recentOrders[] = [
            'id' => (int) $row['id'],
            'player' => $row['player'],
            'game' => $row['game'] ?? '-',
            'amount' => (float) $row['amount'],
            'status' => ucfirst($row['status']),
            'date' => $row['date']
        ];
    }


    // =====================================================
    // ORDERS CHART
    // =====================================================

    $chartLabels = [];
    $chartValues = [];


    // =====================================================
    // MONTH CHART
    // =====================================================

    if ($period === 'month') {

        $daysInMonth = (int) date('t');

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = date(
                'Y-m-',
                strtotime($currentStart)
            ) . str_pad(
                $day,
                2,
                '0',
                STR_PAD_LEFT
            );

            $chartLabels[] = date(
                'M d',
                strtotime($date)
            );


            // =================================================
            // ORDERS FOR DAY
            // =================================================

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM orders
                WHERE DATE(created_at) = ?
            ");

            $stmt->execute([
                $date
            ]);

            $chartValues[] = (int) $stmt->fetchColumn();
        }


        // =====================================================
        // WEEK CHART
        // =====================================================

    } else {

        for ($i = 0; $i < 7; $i++) {

            $date = date(
                'Y-m-d',
                strtotime(
                    $currentStart . ' +' . $i . ' days'
                )
            );

            $chartLabels[] = date(
                'D',
                strtotime($date)
            );


            // =================================================
            // ORDERS FOR DAY
            // =================================================

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM orders
                WHERE DATE(created_at) = ?
            ");

            $stmt->execute([
                $date
            ]);

            $chartValues[] = (int) $stmt->fetchColumn();
        }
    }


    // =====================================================
    // RESPONSE
    // =====================================================

    echo json_encode(
        [
            'success' => true,

            'totalPlayers' => $totalPlayers,

            'totalGames' => $totalGames,

            'totalOrders' => $totalOrders,

            'totalRevenue' => round(
                $totalRevenue,
                2
            ),

            'playersGrowth' => $playersGrowth,

            'gamesGrowth' => $gamesGrowth,

            'ordersGrowth' => $ordersGrowth,

            'revenueGrowth' => $revenueGrowth,

            'topGames' => $topGames,

            'recentOrders' => $recentOrders,

            'ordersChart' => [
                'labels' => $chartLabels,
                'values' => $chartValues
            ]
        ],
        JSON_UNESCAPED_UNICODE
    );


    // =========================================================
    // ERROR HANDLING
    // =========================================================

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode(
        [
            'success' => false,
            'message' => 'Database error.',
            'error' => $e->getMessage()
        ],
        JSON_UNESCAPED_UNICODE
    );
}