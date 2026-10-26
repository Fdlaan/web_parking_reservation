<?php

$fileCss = [
    'pages/auth.css',
    'components/auth-user-input.css'
];

$authSteps = [
    'login' => $projectRoot . '/app/views/pages/auth/login.php',
    'signup' => $projectRoot . '/app/views/pages/auth/signup.php',
    'forgot-email' => $projectRoot . '/app/views/pages/auth/forgot-email.php',
    'forgot-otp' => $projectRoot . '/app/views/pages/auth/forgot-otp.php',
    'reset-password' => $projectRoot . '/app/views/pages/auth/reset-password.php',
];

$step = $_GET['step'] ?? 'login';

if (!isset($authSteps[$step])) {
    $step = 'login';
}

require $authSteps[$step];

?>