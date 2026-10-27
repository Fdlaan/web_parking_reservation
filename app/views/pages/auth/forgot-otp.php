<?php

$titlePage = 'Verify OTP | Parkeasy';

$fields = [
    ['id' => 'otp', 'name' => 'otp', 'label' => 'OTP code', 'type' => 'text',
     'placeholder' => '6-digit code', 'required' => true, 'autocomplete' => 'one-time-code'],
];

?>

<main class="auth-content">

    <section class="form-side">
        <div class="form-wrap">

            <span class="auth-header">Enter your OTP</span>

            <form class="input-form" action="?page=auth&amp;step=forgot-otp" method="POST" novalidate data-auth-form data-auth-step="forgot-otp">

                <?= csrfField() ?>

                <?php foreach ($fields as $field): ?>
                    <?php require $projectRoot . '/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

                <button class="continue-button" type="submit">Verify</button>

            </form>

            <div class="links">
                <a href="?page=auth&step=forgot-email">Request a new OTP.</a>
            </div>

        </div>
    </section>

</main>
