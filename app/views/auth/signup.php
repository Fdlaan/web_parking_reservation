<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up | Parkeasy</title>
    <link rel="stylesheet" href="/web_parking_reservation/public/assets/css/auth.css">
    <link rel="stylesheet" href="/web_parking_reservation/public/assets/css/base.css">
    <script src="https://kit.fontawesome.com/5ba89cd74f.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Sign up -->
    <main class="container">
        <section class="form-side">
            <div class="form-wrap">
                <h1>Let's Create Your Account</h1>
                <form class="input-form" action="../backend/register_process.php" method="POST">
                    <label for="fullname">Name</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Brian Fadlan" required>
                    <label>Email</label>
                    <input type="email" name="email" id="email" placeholder="brianfadlan@example.com" required>
                    <label for="password">Create Password</label>
                    <input type="password" id="password" name="password" placeholder="Brianfadlan123." required>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Brianfadlan123." required>
                </form>
                <button class="continue-button" type="submit">Continue</button>
                <div class="links">
                    <span>Already have an account?</span>
                    <a href="login.php">Login here.</a>
                </div>
            </div>
        </section>
        <div class="photo-background" role="img" aria-label="Parking garage entrance">
            <img src="" alt="">
        </div>
    </main>
    <!-- End of Sign up -->
</body>

</html>