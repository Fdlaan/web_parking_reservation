<!DOCTYPE html>
<html lang="en">

<?php include ("../includes/head.php"); ?>

<body>

    <!-- Topbar -->
    <header class="login">
        <a class="brand" href="../home.php">
            <span class="brand-mark">P</span>
            <span class="brand-name">Parking</span>
        </a>
        <nav>
            <a href="../pages/index.php">Home</a>
            <a href="../pages/login.php" class="active">Login</a>
            <a href="../pages/register.php">Register</a>
        </nav>
    </header>
    <!-- End Topbar -->

    <!-- Login -->
    <main>
        <div class="photo" role="img" aria-label="Parking garage entrance"></div>

        <section class="form-side">
            <div class="form-wrap">
                <h1>Welcome Back</h1>
                <div class="input-form">
                    <form action="../backend/login_process.php" method="POST">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="ex. user123@gmail.com"
                            required>
                        <div class="password-container">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
                    <label class="form-check-label" for="checkDefault">Remember me</label>
                </div>

                <button type="submit">Login</button>
                </form>

                <div class="links">
                    <a class="forgot" href="forgot-password.html">Forgot Password?</a><br>
                    Don't have an account? <a href="../pages/register.php">Register here.</a>
                </div>
            </div>
        </section>
    </main>
    <!-- End of Login -->

</body>

</html>