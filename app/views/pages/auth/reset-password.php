<?php

$titlePage = 'Reset Password | Parkeasy';

$fields = [
    ['id' => 'password', 'name' => 'password', 'label' => 'New Password', 'type' => 'password',
     'placeholder' => 'At least 8 characters', 'required' => true, 'autocomplete' => 'new-password'],
    ['id' => 'confirm_password', 'name' => 'confirm_password', 'label' => 'Confirm Password', 'type' => 'password',
     'placeholder' => 'Repeat the password', 'required' => true, 'autocomplete' => 'new-password'],
];

?>

<main class="auth-content">

    <section class="form-side">
        <div class="form-wrap">

            <span class="auth-header">Reset your password</span>

            <form class="input-form" action="?page=auth&step=reset-password" method="POST">

                <?= csrfField() ?>

                <?php foreach ($fields as $field): ?>
                    <?php require $projectRoot . '/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

                <button class="continue-button" type="submit">Change password</button>

            </form>

            <div class="links">
                <a href="?page=auth&step=login">Back to log in.</a>
            </div>

        </div>
    </section>

</main>
