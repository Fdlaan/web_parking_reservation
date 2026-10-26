<?php

$titlePage = 'Forgot Password | Parkeasy';

$fields = [
    ['id' => 'email', 'name' => 'email', 'label' => 'Email', 'type' => 'email',
     'placeholder' => 'you@example.com', 'required' => true, 'autocomplete' => 'email'],
];

?>

<main class="auth-content">

    <section class="form-side">
        <div class="form-wrap">

            <span class="auth-header">Forgot your password?</span>

            <form class="input-form" action="?page=auth&step=forgot-email" method="POST">

                <?= csrfField() ?>

                <?php foreach ($fields as $field): ?>
                    <?php require $projectRoot . '/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

                <button class="continue-button" type="submit">Send OTP</button>

            </form>

            <div class="links">
                <a href="?page=auth&step=login">Back to log in.</a>
            </div>

        </div>
    </section>

</main>
