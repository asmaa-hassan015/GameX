<?php

// =========================================================
// USER AUTHENTICATION
// =========================================================

session_start();


// =========================================================
// DATABASE
// =========================================================

require_once __DIR__ . '/config/db.php';


// =========================================================
// CHECK EXISTING SESSION
// =========================================================

// Redirect logged-in users directly to their page.

if (isset($_SESSION['user_id'])) {

    if (($_SESSION['role'] ?? '') === 'admin') {

        header('Location: dashboard.php');
    } else {

        header('Location: index.php');
    }

    exit;
}


// =========================================================
// VARIABLES
// =========================================================

$errors = [];

$email = '';

$successMessage = $_SESSION['login_success'] ?? '';

unset($_SESSION['login_success']);


// =========================================================
// HANDLE POST REQUEST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =====================================================
    // GET FORM DATA
    // =====================================================

    $email = trim(
        $_POST['email'] ?? ''
    );

    $password = $_POST['password'] ?? '';


    // =====================================================
    // VALIDATE EMAIL
    // =====================================================

    if ($email === '') {

        $errors['email'] = 'Email is required.';
    } elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $errors['email'] =
            'Please enter a valid email.';
    }


    // =====================================================
    // VALIDATE PASSWORD
    // =====================================================

    if ($password === '') {

        $errors['password'] =
            'Password is required.';
    }


    // =====================================================
    // CHECK CREDENTIALS
    // =====================================================

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            SELECT
                id,
                username,
                email,
                password,
                role,
                status,
                avatar
            FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();


        // =================================================
        // VERIFY LOGIN
        // =================================================

        // Use a generic error message to avoid revealing
        // whether the email exists.

        if (
            !$user ||
            !password_verify(
                $password,
                $user['password']
            )
        ) {

            $errors['password'] =
                'Incorrect email or password.';


            // =================================================
            // CHECK ACCOUNT STATUS
            // =================================================

        } elseif (
            $user['status'] === 'blocked'
        ) {

            $errors['password'] =
                'This account has been blocked. Contact support.';


            // =================================================
            // LOGIN SUCCESS
            // =================================================

        } else {

            // Regenerate the session ID to prevent
            // session fixation attacks.

            session_regenerate_id(true);


            // =================================================
            // STORE USER DATA IN SESSION
            // =================================================

            $_SESSION['user_id'] =
                $user['id'];

            $_SESSION['username'] =
                $user['username'];

            $_SESSION['role'] =
                $user['role'];

            $_SESSION['avatar'] =
                $user['avatar'];


            // =================================================
            // REDIRECT USER
            // =================================================

            if ($user['role'] === 'admin') {

                header(
                    'Location: dashboard.php'
                );
            } else {

                header(
                    'Location: index.php'
                );
            }

            exit;
        }
    }
}