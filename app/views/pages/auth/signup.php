<?php

$titlePage = 'Sign up | Parkeasy';

$fields = [
    [
        'id' => 'username',
        'name' => 'username',
        'label' => 'Username',
        'type' => 'text',
        'placeholder' => 'Your name',
        'required' => true,
        'autocomplete' => 'username',
    ],
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
        'id' => 'phone',
        'name' => 'phone',
        'label' => 'Phone Number',
        'type' => 'tel',
        'placeholder' => '08123456789',
        'required' => true,
        'autocomplete' => 'tel',
    ],
    [
        'id' => 'password',
        'name' => 'password',
        'label' => 'Create Password',
        'type' => 'password',
        'placeholder' => 'At least 8 characters',
        'required' => true,
        'autocomplete' => 'new-password',
    ],
    [
        'id' => 'confirm_password',
        'name' => 'confirm_password',
        'label' => 'Confirm Password',
        'type' => 'password',
        'placeholder' => 'At least 8 characters',
        'required' => true,
        'autocomplete' => 'new-password',
    ],
];

?>

<main class="auth-content">

    <section class="form-side">
        <div class="form-wrap">

            <span class="auth-header">Let's create your account</span>

            <form class="input-form" action="?page=auth&amp;step=signup" method="POST" novalidate data-auth-form data-auth-step="signup">

                <?= csrfField() ?>

                <?php foreach ($fields as $field): ?>
                    <?php require $projectRoot . '/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

                <button class="continue-button" type="submit">Continue</button>

            </form>

            <div class="links">
                <span>
                    Already have an account?
                </span>
                <a href="?page=auth&step=login">Log in here.</a>
            </div>

        </div>
    </section>

    <div class="auth-image" role="img" aria-label="Parking garage entrance">
        <img src="<?=$assetUrl?>/images/image-auth.svg" alt="">
    </div>

</main>