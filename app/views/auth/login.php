<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Parkeasy</title>
    <!-- <link rel="stylesheet" href="/web_parking_reservation/css/style.css"> -->
    <link rel="stylesheet" href="/web_parking_reservation/public/assets/css/auth.css">
    <link rel="stylesheet" href="/web_parking_reservation/public/assets/css/base.css">
    <script src="https://kit.fontawesome.com/5ba89cd74f.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Login -->
    <main class="container">
        <div class="photo-background" role="img" aria-label="Parking garage entrance">
            <img src="" alt="">
        </div>

        <section class="form-side">
            <div class="form-wrap">
                <h1>Welcome Back</h1>
                <form class= "input-form" action="../backend/login_process.php" method="POST">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="brianfadlan@example.com" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Brianfadlan123." required>
                </form>
                <div class="forgot-password">
                    <a class="forgot" href="forgot-password.html">Forgot Password?</a><br>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
                    <label class="form-check-label" for="checkDefault">Always Login for 30 day's</label>
                </div>
                <button class="continue-button" type="submit">Continue</button>
                <div class="links">
                    <span>
                        Don't have an account?
                    </span>
                    <a href="signup.php">Sign up here.</a>
                </div>
            </div>
        </section>
    </main>
    <!-- End of Login -->
</body>

</html>