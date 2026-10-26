<?php

$titlePage = 'Log in | Parkeasy';

$fields = [
    [
        'id' => 'email',
        'name' => 'email',
        'label' => 'Email',
        'type' => 'email',
        'placeholder' => 'you@example.com',
        'required' => true,
        'autocomplete' => 'email',
    ],
    [
        'id' => 'password',
        'name' => 'password',
        'label' => 'Password',
        'type' => 'password',
        'placeholder' => 'Your password',
        'required' => true,
        'autocomplete' => 'current-password',
    ],
];

?>

<main class="auth-content">

    <div class="auth-image" role="img" aria-label="Parking garage entrance">
        <img src="" alt="">
    </div>

    <section class="form-side">
        <div class="form-wrap">

            <span class="auth-header">Welcome back</span>

            <form class="input-form" action="?page=auth&step=login" method="POST">

                <?= csrfField() ?>

                <?php foreach ($fields as $field): ?>
                    <?php require $projectRoot . '/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

                <div class="forgot-password">
                    <a class="forgot" href="?page=auth&step=forgot-email">Forgot Password?</a><br>
                </div>

                <button class="continue-button" type="submit">Continue</button>

            </form>

            <div class="links">
                <span>
                    Don't have an account?
                </span>
                <a href="?page=auth&step=signup">Sign up here.</a>
            </div>

        </div>
    </section>

</main>