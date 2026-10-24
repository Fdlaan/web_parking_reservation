<?php

$titlePage = 'Sign up | Parkeasy';

$fields = [
    [
        'id' => 'username',
        'name' => 'username',
        'label' => 'Username',
        'type' => 'text',
        'placeholder' => 'brianfadlan',
        'required' => true,
        'autocomplete' => 'username',
    ],
    [
        'id' => 'email',
        'name' => 'email',
        'label' => 'Email',
        'type' => 'email',
        'placeholder' => 'brianfadlan@example.com',
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
        'placeholder' => 'Brianfadlan123.',
        'required' => true,
        'autocomplete' => 'new-password',
    ],
    [
        'id' => 'confirm_password',
        'name' => 'confirm_password',
        'label' => 'Confirm Password',
        'type' => 'password',
        'placeholder' => 'Brianfadlan123.',
        'required' => true,
        'autocomplete' => 'new-password',
    ],
];

?>

<main class="auth-content">

    <section class="form-side">
        <div class="form-wrap">

            <span class="auth-header">Let's create your account</span>

            <form class="input-form" action="?page=auth&step=signup" method="POST">

                <?php foreach ($fields as $field): ?>
                    <?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/auth-user-input.php'; ?>
                <?php endforeach; ?>

            </form>
            
            <button class="continue-button" type="submit">Continue</button>

            <div class="links">
                <span>
                    Already have an account?
                </span>
                <a href="?page=auth&step=login">Log in here.</a>
            </div>

        </div>
    </section>

    <div class="auth-image" role="img" aria-label="Parking garage entrance">
        <img src="" alt="">
    </div>

</main>