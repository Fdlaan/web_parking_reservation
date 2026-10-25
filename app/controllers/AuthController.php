<?php

function parkEasyHandleAuthRequest(
    string $page,
    string $step,
    string $requestMethod,
    array $users
): void {
    if ($page === 'logout') {
        parkEasyLogout();
        header('Location: ?page=auth&step=login');
        exit;
    }

    if ($requestMethod !== 'POST' || $page !== 'auth' || $step !== 'login') {
        return;
    }

    $matchedUser = parkEasyFindUserByEmail(trim($_POST['email'] ?? ''), $users);

    if ($matchedUser === null) {
        $_SESSION['login_message'] = 'Email is not registered.';
        header('Location: ?page=auth&step=login');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $matchedUser['id'];

    header('Location: ?page=home');
    exit;
}
