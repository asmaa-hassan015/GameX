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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    responseJson(
        false,
        'Invalid request method.',
        [],
        405
    );
}


// =========================================================
// GET FORM DATA
// =========================================================

$username = trim(
    $_POST['username'] ?? ''
);

$email = trim(
    $_POST['email'] ?? ''
);

$password = $_POST['password'] ?? '';

$confirmPassword =
    $_POST['confirm_password'] ?? '';

$terms =
    isset($_POST['terms']);

$errors = [];


// =========================================================
// USERNAME VALIDATION
// =========================================================

if ($username === '') {

    $errors['username'] =
        'Username is required.';
} elseif (mb_strlen($username) < 2) {

    $errors['username'] =
        'Username must be at least 2 characters.';
} elseif (mb_strlen($username) > 50) {

    $errors['username'] =
        'Username is too long.';
}


// =========================================================
// EMAIL VALIDATION
// =========================================================

if ($email === '') {

    $errors['email'] =
        'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $errors['email'] =
        'Please enter a valid email.';
}


// =========================================================
// PASSWORD VALIDATION
// =========================================================

if ($password === '') {

    $errors['password'] =
        'Password is required.';
} elseif (!preg_match('/^[A-Z]/', $password)) {

    $errors['password'] =
        'Password must start with a capital letter.';
} elseif (!preg_match('/[0-9]/', $password)) {

    $errors['password'] =
        'Password must contain at least one number.';
}


// =========================================================
// CONFIRM PASSWORD VALIDATION
// =========================================================

if ($confirmPassword === '') {

    $errors['confirm_password'] =
        'Confirm Password is required.';
} elseif ($password !== $confirmPassword) {

    $errors['confirm_password'] =
        'Passwords do not match.';
}


// =========================================================
// TERMS VALIDATION
// =========================================================

if (!$terms) {

    $errors['terms'] =
        'You must agree to the Terms of Service and Privacy Policy.';
}


// =========================================================
// CHECK DUPLICATE USERNAME / EMAIL
// =========================================================

if (empty($errors)) {

    $stmt = $pdo->prepare("

        SELECT
            username,
            email

        FROM users

        WHERE username = :username
           OR email = :email

        LIMIT 1

    ");

    $stmt->execute([
        'username' => $username,
        'email' => $email
    ]);

    $existing =
        $stmt->fetch(PDO::FETCH_ASSOC);


    // =====================================================
    // DUPLICATE VALIDATION
    // =====================================================

    if ($existing) {

        if (
            isset($existing['username']) &&
            $existing['username'] === $username
        ) {

            $errors['username'] =
                'This username is already taken.';
        }


        if (
            isset($existing['email']) &&
            $existing['email'] === $email
        ) {

            $errors['email'] =
                'This email is already registered.';
        }
    }
}


// =========================================================
// VALIDATION ERRORS
// =========================================================

if (!empty($errors)) {

    responseJson(
        false,
        'Please correct the errors.',
        [
            'errors' => $errors
        ],
        422
    );
}


// =========================================================
// HASH PASSWORD
// =========================================================

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );


// =========================================================
// CREATE USER
// =========================================================

try {

    $stmt = $pdo->prepare("

        INSERT INTO users
        (
            username,
            email,
            password,
            role,
            terms_accepted,
            status
        )

        VALUES
        (
            :username,
            :email,
            :password,
            'user',
            :terms_accepted,
            'active'
        )

    ");

    $stmt->execute([

        'username' =>
        $username,

        'email' =>
        $email,

        'password' =>
        $hashedPassword,

        'terms_accepted' =>
        $terms ? 1 : 0

    ]);


    // =====================================================
    // GET CREATED USER ID
    // =====================================================

    $userId =
        (int) $pdo->lastInsertId();


    // =====================================================
    // CREATE SESSION
    // =====================================================

    $_SESSION['user_id'] =
        $userId;

    $_SESSION['username'] =
        $username;

    $_SESSION['email'] =
        $email;

    $_SESSION['role'] =
        'user';


    // =====================================================
    // SUCCESS
    // =====================================================

    responseJson(

        true,

        'Account created successfully.',

        [

            'user_id' =>
            $userId,

            'redirect' =>
            '../index.php'

        ]

    );
} catch (PDOException $e) {

    error_log(
        'REGISTER DATABASE ERROR: ' .
            $e->getMessage()
    );


    // =====================================================
    // DUPLICATE DATABASE ERROR
    // =====================================================

    if ($e->getCode() === '23000') {

        responseJson(

            false,

            'Username or email is already registered.',

            [],

            409

        );
    }


    // =====================================================
    // GENERAL DATABASE ERROR
    // =====================================================

    responseJson(

        false,

        'Registration failed. Please try again.',

        [],

        500

    );
}