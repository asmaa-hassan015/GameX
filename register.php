<?php

session_start();

// =========================================================
// REGISTER ERRORS + OLD INPUT
// =========================================================

$errors = $_SESSION["register_errors"] ?? [];
$old = $_SESSION["register_old"] ?? [];

$username = $old["username"] ?? "";
$email = $old["email"] ?? "";
$terms = $old["terms"] ?? false;

unset(
    $_SESSION["register_errors"],
    $_SESSION["register_old"]
);

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GAME X - Register</title>


    <!-- ========================================================= -->
    <!-- TAILWIND -->
    <!-- ========================================================= -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- ========================================================= -->
    <!-- CSS -->
    <!-- ========================================================= -->

    <link rel="stylesheet" href="./css/style.css">


    <!-- ========================================================= -->
    <!-- FONT AWESOME -->
    <!-- ========================================================= -->

    <link rel="stylesheet" href="./assets/css/all.min.css">


    <!-- ========================================================= -->
    <!-- GOOGLE FONTS -->
    <!-- ========================================================= -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

</head>


<body class="min-h-screen flex flex-col overflow-hidden bg-bgmain">


    <!-- ========================================================= -->
    <!-- MAIN -->
    <!-- ========================================================= -->

    <main class="flex-1 flex items-center justify-center p-3 sm:p-5 lg:p-6">


        <!-- ===================================================== -->
        <!-- REGISTER CARD -->
        <!-- ===================================================== -->

        <div
            class="w-full max-w-[1100px] h-[calc(100vh-130px)] max-h-[680px] min-h-0 flex flex-col lg:flex-row overflow-hidden rounded-2xl bg-bgmain">


            <!-- ================================================= -->
            <!-- HERO IMAGE -->
            <!-- ================================================= -->

            <?php include 'components/auth-hero.php'; ?>


            <!-- ================================================= -->
            <!-- REGISTER FORM -->
            <!-- ================================================= -->

            <div
                class="w-full lg:w-1/2 min-h-0 flex flex-col items-center justify-center px-5 sm:px-8 lg:px-10 py-4 bg-bgmain">

                <div class="w-full max-w-md">


                    <!-- ================================================= -->
                    <!-- LOGO -->
                    <!-- ================================================= -->

                    <div class="flex items-center justify-center mb-3">

                        <img src="./images/register-login/logo.png" alt="GAME X" class="h-8 sm:h-10 w-auto">

                    </div>


                    <!-- ================================================= -->
                    <!-- TITLE -->
                    <!-- ================================================= -->

                    <div class="text-center mb-3">

                        <h1
                            class="font-tech text-xl sm:text-2xl font-bold text-textmain flex items-center justify-center gap-2">
                            Create Your Account 🎮
                        </h1>

                        <p class="text-xs sm:text-sm text-textsecond mt-1">
                            Join the gaming community and start your journey.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- REGISTER FORM -->
                    <!-- ================================================= -->

                    <form id="registerForm" method="POST" action="BACKEND/register.php" class="space-y-2">


                        <!-- ================================================= -->
                        <!-- USERNAME -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block text-xs sm:text-sm text-textmain mb-1">
                                Username
                            </label>

                            <label class="field flex items-center gap-3 rounded-lg px-3.5 py-2.5">

                                <i class="fa-solid fa-user w-5 text-center text-textsecond"></i>

                                <input id="username" name="username" type="text" placeholder="Choose a username"
                                    value="<?php echo htmlspecialchars($username); ?>"
                                    class="bg-transparent outline-none w-full text-xs sm:text-sm text-textmain placeholder:text-textsecond">

                            </label>

                            <?php if (isset($errors["username"])): ?>

                            <p class="text-red-500 text-xs mt-0.5">
                                <?php echo htmlspecialchars($errors["username"]); ?>
                            </p>

                            <?php endif; ?>

                        </div>


                        <!-- ================================================= -->
                        <!-- EMAIL -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block text-xs sm:text-sm text-textmain mb-1">
                                Email
                            </label>

                            <label class="field flex items-center gap-3 rounded-lg px-3.5 py-2.5">

                                <i class="fa-solid fa-envelope w-5 text-center text-textsecond"></i>

                                <input id="email" name="email" type="email" placeholder="Enter your email"
                                    value="<?php echo htmlspecialchars($email); ?>"
                                    class="bg-transparent outline-none w-full text-xs sm:text-sm text-textmain placeholder:text-textsecond">

                            </label>

                            <?php if (isset($errors["email"])): ?>

                            <p class="text-red-500 text-xs mt-0.5">
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

                                <input id="pw1" name="password" type="password" placeholder="Create a password"
                                    class="bg-transparent outline-none w-full text-xs sm:text-sm text-textmain placeholder:text-textsecond">

                                <button type="button" onclick="togglePw('pw1')"
                                    class="shrink-0 text-textsecond hover:text-accent transition-colors"
                                    aria-label="Show password">

                                    <i class="fa-solid fa-eye-slash"></i>

                                </button>

                            </label>

                            <?php if (isset($errors["password"])): ?>

                            <p class="text-red-500 text-xs mt-0.5">
                                <?php echo htmlspecialchars($errors["password"]); ?>
                            </p>

                            <?php endif; ?>

                        </div>


                        <!-- ================================================= -->
                        <!-- CONFIRM PASSWORD -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block text-xs sm:text-sm text-textmain mb-1">
                                Confirm Password
                            </label>

                            <label class="field flex items-center gap-3 rounded-lg px-3.5 py-2.5">

                                <i class="fa-solid fa-lock w-5 text-center text-textsecond"></i>

                                <input id="pw2" name="confirm_password" type="password"
                                    placeholder="Confirm your password"
                                    class="bg-transparent outline-none w-full text-xs sm:text-sm text-textmain placeholder:text-textsecond">

                                <button type="button" onclick="togglePw('pw2')"
                                    class="shrink-0 text-textsecond hover:text-accent transition-colors"
                                    aria-label="Show confirm password">

                                    <i class="fa-solid fa-eye-slash"></i>

                                </button>

                            </label>

                            <?php if (isset($errors["confirm_password"])): ?>

                            <p class="text-red-500 text-xs mt-0.5">
                                <?php echo htmlspecialchars($errors["confirm_password"]); ?>
                            </p>

                            <?php endif; ?>

                        </div>


                        <!-- ================================================= -->
                        <!-- TERMS -->
                        <!-- ================================================= -->

                        <div>

                            <label
                                class="flex items-start gap-2 text-xs sm:text-sm text-textsecond cursor-pointer select-none pt-0.5">

                                <input type="checkbox" name="terms" <?php echo $terms ? "checked" : ""; ?>
                                    class="w-3.5 h-3.5 mt-0.5 rounded border-borderc bg-transparent accent-accent shrink-0">

                                <span>

                                    I agree to the

                                    <a href="#" class="text-accent hover:opacity-80 transition-opacity">
                                        Terms of Service
                                    </a>

                                    &

                                    <a href="#" class="text-accent hover:opacity-80 transition-opacity">
                                        Privacy Policy
                                    </a>

                                </span>

                            </label>

                            <?php if (isset($errors["terms"])): ?>

                            <p class="text-red-500 text-xs mt-0.5">
                                <?php echo htmlspecialchars($errors["terms"]); ?>
                            </p>

                            <?php endif; ?>

                        </div>


                        <!-- ================================================= -->
                        <!-- CREATE ACCOUNT -->
                        <!-- ================================================= -->

                        <button type="submit"
                            class="btn-primary w-full rounded-lg py-2.5 font-semibold text-white font-tech text-base sm:text-lg tracking-wide uppercase">
                            Create Account
                        </button>

                    </form>


                    <!-- ================================================= -->
                    <!-- DIVIDER -->
                    <!-- ================================================= -->

                    <div class="flex items-center gap-3 my-2.5">

                        <div class="flex-1 h-px bg-borderc"></div>

                        <span class="text-xs text-textsecond">
                            or
                        </span>

                        <div class="flex-1 h-px bg-borderc"></div>

                    </div>


                    <!-- ================================================= -->
                    <!-- SOCIAL BUTTONS -->
                    <!-- ================================================= -->

                    <div class="grid grid-cols-3 gap-3 mb-2.5">


                        <!-- Google -->

                        <button type="button" title="Continue with Google" aria-label="Continue with Google"
                            class="social-btn rounded-lg py-2.5 flex items-center justify-center text-textmain transition-colors">

                            <i class="fa-brands fa-google"></i>

                        </button>


                        <!-- Discord -->

                        <button type="button" title="Continue with Discord" aria-label="Continue with Discord"
                            class="social-btn rounded-lg py-2.5 flex items-center justify-center text-textmain transition-colors">

                            <i class="fa-brands fa-discord"></i>

                        </button>


                        <!-- GitHub -->

                        <button type="button" title="Continue with GitHub" aria-label="Continue with GitHub"
                            class="social-btn rounded-lg py-2.5 flex items-center justify-center text-textmain transition-colors">

                            <i class="fa-brands fa-github"></i>

                        </button>

                    </div>


                    <!-- ================================================= -->
                    <!-- LOGIN LINK -->
                    <!-- ================================================= -->

                    <p class="text-center text-xs sm:text-sm text-textsecond">

                        Already have an account?

                        <a href="Login.php" class="text-accent font-medium hover:opacity-80 transition-opacity">
                            Sign in
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </main>


    <!-- ========================================================= -->
    <!-- FOOTER -->
    <!-- ========================================================= -->

    <?php include 'components/footer.php'; ?>


    <!-- ========================================================= -->
    <!-- JAVASCRIPT -->
    <!-- ========================================================= -->

    <script src="./js/main.js"></script>

    <script src="./js/auth.js"></script>


</body>

</html>