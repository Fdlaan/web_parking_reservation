<?php

$fileCss = [
    'pages/auth.css',
    'components/auth-user-input.css'
];

$authSteps = [
    'login' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/login.php',
    'signup' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/signup.php',
    'forgot-email' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/forgot-email.php',
    'forgot-otp' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/forgot-otp.php',
    'reset-password' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/reset-password.php',
];

$step = $_GET['step'] ?? 'login';

if (!isset($authSteps[$step])) {
    $step = 'login';
}

require $authSteps[$step];

?>