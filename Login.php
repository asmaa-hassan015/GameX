<?php

session_start();

require_once __DIR__ . '/config/db.php';

// ============================================================
// REDIRECT LOGGED-IN USERS
// ============================================================

if (isset($_SESSION["user_id"])) {

    if (($_SESSION["role"] ?? "") === "admin") {
        header("Location: dashboard.php");
    } else {
        header("Location: index.php");
    }

    exit;
}

// ============================================================
// VARIABLES
// ============================================================

$errors = [];
$email = "";

$successMessage = $_SESSION["login_success"] ?? "";

unset($_SESSION["login_success"]);

// ============================================================
// LOGIN PROCESS
// ============================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // ========================================================
    // VALIDATION
    // ========================================================

    if ($email === "") {

        $errors["email"] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors["email"] = "Please enter a valid email.";
    }

    if ($password === "") {

        $errors["password"] = "Password is required.";
    }

    // ========================================================
    // CHECK USER CREDENTIALS
    // ========================================================

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id, username, email, password, role, status, avatar
             FROM users
             WHERE email = :email
             LIMIT 1"
        );

        $stmt->execute([
            "email" => $email
        ]);

        $user = $stmt->fetch();

        // ====================================================
        // CHECK EMAIL AND PASSWORD
        // ====================================================

        if (!$user || !password_verify($password, $user["password"])) {

            $errors["password"] = "Incorrect email or password.";

            // ====================================================
            // CHECK ACCOUNT STATUS
            // ====================================================

        } elseif ($user["status"] === "blocked") {

            $errors["password"] =
                "This account has been blocked. Contact support.";
        } else {

            // ==================================================
            // PREVENT SESSION FIXATION
            // ==================================================

            session_regenerate_id(true);

            // ==================================================
            // SAVE USER DATA IN SESSION
            // ==================================================

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["avatar"] = $user["avatar"];

            // ==================================================
            // REDIRECT BASED ON ROLE
            // ==================================================

            if ($user["role"] === "admin") {

                header("Location: dashboard.php");
            } else {

                header("Location: index.php");
            }

            exit;
        }
    }
}

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>GAME X - Login</title>

    <!-- ===================================================== -->
    <!-- TAILWIND -->
    <!-- ===================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ===================================================== -->
    <!-- PROJECT CSS -->
    <!-- ===================================================== -->

    <link rel="stylesheet" href="./css/style.css" />

    <!-- ===================================================== -->
    <!-- FONT AWESOME -->
    <!-- ===================================================== -->

    <link rel="stylesheet" href="./assets/css/all.min.css" />

    <!-- ===================================================== -->
    <!-- GOOGLE FONTS -->
    <!-- ===================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

</head>

<body class="min-h-screen flex flex-col overflow-hidden bg-bgmain">

    <!-- ===================================================== -->
    <!-- LOGIN MAIN -->
    <!-- ===================================================== -->

    <main class="flex-1 flex items-center justify-center p-3 sm:p-5 lg:p-6">

        <div
            class="w-full max-w-[1100px] h-[calc(100vh-130px)] max-h-[680px] min-h-0 flex flex-col lg:flex-row overflow-hidden rounded-2xl bg-bgmain">

            <!-- ================================================= -->
            <!-- AUTH HERO -->
            <!-- ================================================= -->

            <?php include 'components/auth-hero.php'; ?>

            <!-- ================================================= -->
            <!-- LOGIN FORM SECTION -->
            <!-- ================================================= -->

            <div
                class="w-full lg:w-1/2 min-h-0 flex flex-col items-center justify-center px-5 sm:px-8 lg:px-10 py-4 bg-bgmain">

                <div class="w-full max-w-md">

                    <!-- ================================================= -->
                    <!-- LOGO -->
                    <!-- ================================================= -->

                    <div class="flex items-center justify-center mb-4">

                        <img src="./images/register-login/logo.png" alt="GAME X" class="h-8 sm:h-10 w-auto" />

                    </div>

                    <!-- ================================================= -->
                    <!-- TITLE -->
                    <!-- ================================================= -->

                    <div class="text-center mb-4">

                        <h1
                            class="font-tech text-xl sm:text-2xl font-bold text-textmain flex items-center justify-center gap-2">
                            Welcome Back 👋
                        </h1>

                        <p class="text-xs sm:text-sm text-textsecond mt-1">
                            Sign in to continue your gaming journey.
                        </p>

                    </div>

                    <!-- ================================================= -->
                    <!-- SUCCESS MESSAGE -->
                    <!-- ================================================= -->

                    <?php if ($successMessage !== ""): ?>

                    <div
                        class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 text-green-400 text-xs sm:text-sm text-center py-2 px-3">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>

                    <?php endif; ?>

                    <!-- ================================================= -->
                    <!-- LOGIN FORM -->
                    <!-- ================================================= -->

                    <form id="loginForm" method="POST" action="" class="space-y-3">

                        <!-- ================================================= -->
                        <!-- EMAIL -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block text-xs sm:text-sm text-textmain mb-1">
                                Email
                            </label>

                            <label class="field flex items-center gap-3 rounded-lg px-3.5 py-2.5">

                                <i class="fa-solid fa-envelope w-5 text-center text-textsecond"></i>

                                <input id="loginEmail" name="email" type="email" placeholder="Enter your email"
                                    value="<?php echo htmlspecialchars($email); ?>"
                                    class="bg-transparent outline-none w-full text-xs sm:text-sm text-textmain placeholder:text-textsecond" />

                            </label>

                            <?php if (isset($errors["email"])): ?>

                            <p class="text-red-500 text-xs mt-1">
                                <?php echo htmlspecialchars($errors["email"]); ?>
                            </p>

                            <?php endif; ?>

                        </div>

                        <!-- ================================================= -->
                        <!-- PASSWORD -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block text-xs sm:text-sm text-textmain mb-1">
                                Password
                            </label>

                            <label class="field flex items-center gap-3 rounded-lg px-3.5 py-2.5">

                                <i class="fa-solid fa-lock w-5 text-center text-textsecond"></i>

                                <input id="pw" name="password" type="password" placeholder="Enter your password"
                                    class="bg-transparent outline-none w-full text-xs sm:text-sm text-textmain placeholder:text-textsecond" />

                                <button type="button" onclick="togglePw('pw')"
                                    class="shrink-0 text-textsecond hover:text-accent transition-colors">

                                    <i class="fa-solid fa-eye-slash"></i>

                                </button>

                            </label>

                            <?php if (isset($errors["password"])): ?>

                            <p class="text-red-500 text-xs mt-1">
                                <?php echo htmlspecialchars($errors["password"]); ?>
                            </p>

                            <?php endif; ?>

                        </div>

                        <!-- ================================================= -->
                        <!-- REMEMBER / FORGOT PASSWORD -->
                        <!-- ================================================= -->

                        <div class="flex items-center justify-between text-xs sm:text-sm">

                            <label class="flex items-center gap-2 text-textsecond cursor-pointer select-none">

                                <input type="checkbox"
                                    class="w-3.5 h-3.5 rounded border-borderc bg-transparent accent-accent" />

                                Remember me

                            </label>

                            <a href="#" class="text-accent hover:opacity-80 transition-opacity">
                                Forgot password?
                            </a>

                        </div>

                        <!-- ================================================= -->
                        <!-- SIGN IN BUTTON -->
                        <!-- ================================================= -->

                        <button type="submit"
                            class="btn-primary w-full rounded-lg py-2.5 font-semibold text-white font-tech text-base sm:text-lg tracking-wide uppercase">
                            Sign In
                        </button>

                    </form>

                    <!-- ================================================= -->
                    <!-- DIVIDER -->
                    <!-- ================================================= -->

                    <div class="flex items-center gap-3 my-3.5">

                        <div class="flex-1 h-px bg-borderc"></div>

                        <span class="text-xs text-textsecond">
                            or
                        </span>

                        <div class="flex-1 h-px bg-borderc"></div>

                    </div>

                    <!-- ================================================= -->
                    <!-- REGISTER LINK -->
                    <!-- ================================================= -->

                    <p class="text-center text-xs sm:text-sm text-textsecond">

                        Don't have an account?

                        <a href="register.php" class="text-accent font-medium hover:opacity-80 transition-opacity">
                            Create account
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </main>

    <!-- ===================================================== -->
    <!-- FOOTER -->
    <!-- ===================================================== -->

    <?php include 'components/footer.php'; ?>

    <!-- ===================================================== -->
    <!-- JAVASCRIPT -->
    <!-- ===================================================== -->

    <script src="./js/main.js"></script>

    <script src="./js/auth.js"></script>

</body>

</html>