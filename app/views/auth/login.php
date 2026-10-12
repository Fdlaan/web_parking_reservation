<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Parkeasy</title>
    <link rel="stylesheet" href="/web_parking_reservation/public/assets/css/auth.css">
    <link rel="stylesheet" href="/web_parking_reservation/public/assets/css/base.css">
    <script src="https://kit.fontawesome.com/5ba89cd74f.js" crossorigin="anonymous"></script>
</head>

<body>
    <main class="container">

        <div class="photo-background" role="img" aria-label="Parking garage entrance">
            <img src="" alt="">
        </div>

        <section class="form-side">
            <div class="form-wrap">

                <h1>Welcome back</h1>
                
                <form class= "input-form" action="../backend/login_process.php" method="POST">
                    <?php
                        $field = [
                        'id' => 'email',
                        'name' => 'email',
                        'label' => 'Email',
                        'type' => 'email',
                        'placeholder' => 'brianfadlan@example.com',
                        'required' => true,
                        'autocomplete' => 'email'
                        ];

                        require __DIR__ . '/../components/input.php';
                    ?>

                    <?php
                        $field = [
                        'id' => 'password',
                        'name' => 'password',
                        'label' => 'Password',
                        'type' => 'password',
                        'placeholder' => 'Brianfadlan123.',
                        'required' => true,
                        'autocomplete' => 'current-email'
                        ];

                        require __DIR__ . '/../components/input.php';
                    ?>

                    <div class="forgot-password">
                        <a class="forgot" href="forgot-password.html">Forgot Password?</a><br>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="checkDefault">
                        <label class="form-check-label" for="checkDefault">Always Login for 30 day's</label>
                    </div>

                    <button class="continue-button" type="submit">Continue</button>

                </form>
            
                <div class="links">
                    <span>
                        Don't have an account?
                    </span>
                    <a href="signup.php">Sign up here.</a>
                </div>

            </div>
        </section>
    </main>
</body>

</html>