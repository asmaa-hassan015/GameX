<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/db.php';


// =========================================================
// RESPONSE HELPER
// =========================================================

function response($success, $message, $data = [])
{
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


// =========================================================
// CHECK DATABASE
// =========================================================

if (!isset($pdo) || !$pdo) {
    response(false, "Database connection failed.");
}


// =========================================================
// CHECK LOGIN
// =========================================================

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);

    response(
        false,
        "You must be logged in to complete payment."
    );
}

$userId = (int) $_SESSION["user_id"];


// =========================================================
// ONLY POST REQUEST
// =========================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    response(
        false,
        "Invalid request method."
    );
}


// =========================================================
// GET JSON DATA
// =========================================================

$rawInput = file_get_contents("php://input");

$input = json_decode(
    $rawInput,
    true
);

if (!is_array($input)) {
    $input = $_POST;
}


// =========================================================
// PAYMENT METHOD
// =========================================================

$paymentMethod = isset($input["payment_method"])
    ? trim($input["payment_method"])
    : "card";

$allowedMethods = [
    "card",
    "wallet"
];

if (!in_array(
    $paymentMethod,
    $allowedMethods,
    true
)) {
    response(
        false,
        "Invalid payment method."
    );
}


// =========================================================
// CARD DATA
// =========================================================

$cardBrand = null;
$cardLast4 = null;

if ($paymentMethod === "card") {

    $cardNumber = isset($input["card_number"])
        ? preg_replace(
            "/\D/",
            "",
            $input["card_number"]
        )
        : "";

    $cardName = isset($input["card_name"])
        ? trim($input["card_name"])
        : "";

    $expiry = isset($input["expiry"])
        ? trim($input["expiry"])
        : "";

    $cvv = isset($input["cvv"])
        ? preg_replace(
            "/\D/",
            "",
            $input["cvv"]
        )
        : "";


    // =====================================================
    // CARD VALIDATION
    // =====================================================

    if (
        strlen($cardNumber) < 13 ||
        strlen($cardNumber) > 16
    ) {
        response(
            false,
            "Invalid card number."
        );
    }


    if (strlen($cardName) < 2) {
        response(
            false,
            "Invalid card name."
        );
    }


    if (
        !preg_match(
            '/^(\d{2})\s*\/\s*(\d{2})$/',
            $expiry,
            $matches
        )
    ) {
        response(
            false,
            "Invalid expiry date."
        );
    }


    $month = (int) $matches[1];

    $year = (int) $matches[2];


    if (
        $month < 1 ||
        $month > 12
    ) {
        response(
            false,
            "Invalid expiry month."
        );
    }


    $currentYear = (int) date("y");

    $currentMonth = (int) date("m");


    if (
        $year < $currentYear ||
        (
            $year === $currentYear &&
            $month < $currentMonth
        )
    ) {
        response(
            false,
            "Card has expired."
        );
    }


    if (
        strlen($cvv) < 3 ||
        strlen($cvv) > 4
    ) {
        response(
            false,
            "Invalid CVV."
        );
    }


    // =====================================================
    // LUHN CHECK
    // =====================================================

    function luhnCheck($number)
    {
        $sum = 0;

        $alternate = false;

        for (
            $i = strlen($number) - 1;
            $i >= 0;
            $i--
        ) {

            $digit = (int) $number[$i];

            if ($alternate) {

                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;

            $alternate = !$alternate;
        }

        return ($sum % 10 === 0);
    }


    if (!luhnCheck($cardNumber)) {
        response(
            false,
            "Invalid card number."
        );
    }


    // =====================================================
    // DETECT CARD BRAND
    // =====================================================

    if (preg_match(
        '/^4/',
        $cardNumber
    )) {

        $cardBrand = "Visa";
    } elseif (
        preg_match(
            '/^5[1-5]/',
            $cardNumber
        ) ||
        preg_match(
            '/^2(2[2-9]|[3-6][0-9]|7[01]|720)/',
            $cardNumber
        )
    ) {

        $cardBrand = "MasterCard";
    } elseif (preg_match(
        '/^3[47]/',
        $cardNumber
    )) {

        $cardBrand = "American Express";
    } elseif (preg_match(
        '/^6(011|5)/',
        $cardNumber
    )) {

        $cardBrand = "Discover";
    } elseif (preg_match(
        '/^35/',
        $cardNumber
    )) {

        $cardBrand = "JCB";
    } else {

        $cardBrand = "Unknown";
    }


    $cardLast4 = substr(
        $cardNumber,
        -4
    );
}


// =========================================================
// GET USER CART
// =========================================================

$sql = "

    SELECT
        c.id AS cart_id,
        ci.game_id,
        ci.quantity,
        ci.price,
        g.title,
        g.status

    FROM cart c

    INNER JOIN cart_items ci
        ON c.id = ci.cart_id

    INNER JOIN games g
        ON g.id = ci.game_id

    WHERE c.user_id = ?

";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $userId
]);

$cartItems = $stmt->fetchAll(
    PDO::FETCH_ASSOC
);


// =========================================================
// CHECK CART
// =========================================================

if (empty($cartItems)) {

    response(
        false,
        "Your cart is empty."
    );
}


// =========================================================
// CHECK GAME STATUS
// =========================================================

foreach ($cartItems as $item) {

    if ($item["status"] !== "active") {

        response(
            false,
            "The game '" .
                $item["title"] .
                "' is currently unavailable."
        );
    }
}


// =========================================================
// CALCULATE TOTALS
// =========================================================

$subtotal = 0;

foreach ($cartItems as $item) {

    $price = (float) $item["price"];

    $quantity = (int) $item["quantity"];

    $subtotal += $price * $quantity;
}


// =========================================================
// DISCOUNT
// =========================================================

$discount = $subtotal > 0
    ? 10.00
    : 0.00;


// Prevent discount from being greater than subtotal

if ($discount > $subtotal) {
    $discount = $subtotal;
}


// =========================================================
// TAX
// =========================================================

$taxRate = 0.10;

$taxableAmount = max(
    $subtotal - $discount,
    0
);

$tax = $taxableAmount * $taxRate;


// =========================================================
// TOTAL
// =========================================================

$total = $taxableAmount + $tax;


// =========================================================
// TRANSACTION
// =========================================================

try {

    $pdo->beginTransaction();


    // =====================================================
    // CREATE ORDER NUMBER
    // =====================================================

    $orderNumber =
        "ORD-" .
        date("Y") .
        "-" .
        rand(100000, 999999);


    // =====================================================
    // ORDER STATUS
    // =====================================================

    $orderStatus = "processing";


    // =====================================================
    // INSERT ORDER
    // =====================================================

    $orderSql = "

        INSERT INTO orders
        (
            user_id,
            order_number,
            subtotal,
            discount,
            tax,
            total,
            status
        )

        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )

    ";

    $orderStmt = $pdo->prepare(
        $orderSql
    );

    $orderStmt->execute([
        $userId,
        $orderNumber,
        $subtotal,
        $discount,
        $tax,
        $total,
        $orderStatus
    ]);


    $orderId = (int) $pdo->lastInsertId();


    // =====================================================
    // INSERT ORDER ITEMS
    // =====================================================

    $itemSql = "

        INSERT INTO order_items
        (
            order_id,
            game_id,
            game_title,
            price,
            quantity,
            total
        )

        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )

    ";

    $itemStmt = $pdo->prepare(
        $itemSql
    );


    foreach ($cartItems as $item) {

        $gameId = (int) $item["game_id"];

        $gameTitle = $item["title"];

        $price = (float) $item["price"];

        $quantity = (int) $item["quantity"];

        $itemTotal =
            $price * $quantity;


        $itemStmt->execute([
            $orderId,
            $gameId,
            $gameTitle,
            $price,
            $quantity,
            $itemTotal
        ]);
    }


    // =====================================================
    // TRANSACTION ID
    // =====================================================

    $transactionId =
        "TXN" .
        date("YmdHis") .
        rand(100, 999);


    // =====================================================
    // PAYMENT STATUS
    // =====================================================

    $paymentStatus = "processing";


    // =====================================================
    // INSERT PAYMENT
    // =====================================================

    $paymentSql = "

        INSERT INTO payments
        (
            order_id,
            payment_method,
            transaction_id,
            card_brand,
            card_last4,
            amount,
            status,
            paid_at
        )

        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            NULL
        )

    ";

    $paymentStmt = $pdo->prepare(
        $paymentSql
    );


    $paymentStmt->execute([
        $orderId,
        $paymentMethod,
        $transactionId,
        $cardBrand,
        $cardLast4,
        $total,
        $paymentStatus
    ]);


    // =====================================================
    // CLEAR CART ITEMS
    // =====================================================

    $cartIdsSql = "

        SELECT id

        FROM cart

        WHERE user_id = ?

    ";

    $cartStmt = $pdo->prepare(
        $cartIdsSql
    );

    $cartStmt->execute([
        $userId
    ]);

    $cartIds = $cartStmt->fetchAll(
        PDO::FETCH_COLUMN
    );


    foreach ($cartIds as $cartId) {

        $deleteSql = "

            DELETE FROM cart_items

            WHERE cart_id = ?

        ";

        $deleteStmt = $pdo->prepare(
            $deleteSql
        );

        $deleteStmt->execute([
            (int) $cartId
        ]);
    }


    // =====================================================
    // COMMIT
    // =====================================================

    $pdo->commit();


    // =====================================================
    // SUCCESS RESPONSE
    // =====================================================

    response(
        true,
        "Payment processed successfully.",
        [
            "order_id" =>
            $orderId,

            "order_number" =>
            $orderNumber,

            "transaction_id" =>
            $transactionId,

            "subtotal" =>
            round(
                $subtotal,
                2
            ),

            "discount" =>
            round(
                $discount,
                2
            ),

            "tax" =>
            round(
                $tax,
                2
            ),

            "total" =>
            round(
                $total,
                2
            ),

            "payment_method" =>
            $paymentMethod,

            "card_brand" =>
            $cardBrand,

            "card_last4" =>
            $cardLast4,

            "status" =>
            $orderStatus
        ]
    );
} catch (Exception $e) {

    // =====================================================
    // ROLLBACK
    // =====================================================

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log(
        "Payment Error: " .
            $e->getMessage()
    );


    // =====================================================
    // ERROR RESPONSE
    // =====================================================

    http_response_code(500);

    response(
        false,
        "Payment failed. Please try again."
    );
}