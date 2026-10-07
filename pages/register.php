<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/5ba89cd74f.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Topbar -->
    <header>
        <a class="brand" href="../home.php">
            <span class="brand-mark">P</span>
            <span class="brand-name">Parking</span>
        </a>
        <nav>
            <a href="../pages/index.php">Home</a>
            <a href="../pages/login.php">Login</a>
            <a href="../pages/register.php" class="active">Register</a>
        </nav>
    </header>
    <!-- End Topbar -->

    <!-- Register -->
    <main>
        <div class="photo" role="img" aria-label="Parking garage entrance"></div>

        <section class="form-side">
            <div class="form-wrap">
                <h1>Let's Create Your Account</h1>
                <div class="input-form">
                    <form action="../backend/register_process.php" method="POST">
                        <label for="fullname">Name</label>
                        <input type="text" name="fullname" id="fullname" placeholder="ex. Brian Husein" required>
                        <label>Email</label>
                        <input type="email" name="email" id="email" placeholder="ex. user123@gmail.com" required>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit">Register</button>
                </form>

                <div class="links">
                    Already have an account? <a href="../pages/login.php">Login here.</a>
                </div>
            </div>
        </section>
    </main>
    <!-- End of Register -->
</body>

</html>