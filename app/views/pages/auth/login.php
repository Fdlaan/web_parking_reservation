<?php

$titlePage = 'Log in | Parkeasy';

$loginMessage = $_SESSION['login_message'] ?? null;
unset($_SESSION['login_message']);

$fields = [
    [
        'id' => 'email',
        'name' => 'email',
        'label' => 'Email',
        'type' => 'email',
        'placeholder' => 'brianfadlan@example.com',
        'require' => true,
        'autocomplete' => 'email',
    ],
    [
        'id' => 'password',
        'name' => 'password',
        'label' => 'Password',
        'type' => 'password',
        'placeholder' => 'Brianfadlan123.',
        'require' => true,
        'autocomplete' => 'new-password',
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

            <?php if ($loginMessage !== null): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= parkEasyEscape($loginMessage) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php endif; ?>

            <form class="input-form" action="?page=auth&step=login" method="POST">

                <?php foreach ($fields as $field): ?>
                    <?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

                <div class="forgot-password">
                    <a class="forgot" href="?page=auth&step=forgot-email">Forgot Password?</a><br>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="checkDefault">
                    <label class="form-check-label" for="checkDefault">Remember me</label>
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