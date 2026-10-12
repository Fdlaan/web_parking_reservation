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
    <main class="container">

        <section class="form-side">
            <div class="form-wrap">

                <h1>Let's create your account</h1>
                
                <form class= "input-form" action="../backend/signup_process.php" method="POST">
                    <?php
                        $field = [
                        'id' => 'username',
                        'name' => 'username',
                        'label' => 'Username',
                        'type' => 'text',
                        'placeholder' => 'brianfadlan',
                        'required' => true,
                        'autocomplete' => 'username'
                        ];

                        require __DIR__ . '/../components/input.php';
                    ?>

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
                        'id' => 'phone',
                        'name' => 'phone',
                        'label' => 'Phone Number',
                        'type' => 'tel',
                        'placeholder' => '08123456789',
                        'required' => true,
                        'autocomplete' => 'tel'
                        ];

                        require __DIR__ . '/../components/input.php';
                    ?>
                    <?php
                        $field = [
                        'id' => 'password',
                        'name' => 'password',
                        'label' => 'Create Password',
                        'type' => 'password',
                        'placeholder' => 'Brianfadlan123.',
                        'required' => true,
                        'autocomplete' => 'new-password'
                        ];

                        require __DIR__ . '/../components/input.php';
                    ?>
                    <?php
                        $field = [
                        'id' => 'confirm_password',
                        'name' => 'confirm_password',
                        'label' => 'Confirm Password',
                        'type' => 'password',
                        'placeholder' => 'Brianfadlan123.',
                        'required' => true,
                        'autocomplete' => 'new-password'
                        ];

                        require __DIR__ . '/../components/input.php';
                    ?>

                    <button class="continue-button" type="submit">Continue</button>

                </form>
            
                <div class="links">
                    <span>
                        Already have an account?
                    </span>
                    <a href="login.php">Log in here.</a>
                </div>

            </div>
        </section>

        <div class="photo-background" role="img" aria-label="Parking garage entrance">
            <img src="" alt="">
        </div>
    </main>
</body>

</html>