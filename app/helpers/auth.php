<?php

function parkEasyCurrentUserId(): ?int
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return (int) $_SESSION['user_id'];
}

function parkEasyFindUserByEmail(string $email, array $users): ?array
{
    if ($email === '') {
        return null;
    }

    foreach ($users as $user) {
        if (strcasecmp((string) $user['email'], $email) === 0) {
            return $user;
        }
    }

    return null;
}

function parkEasyLogout(): void
{
    $_SESSION = [];
    session_destroy();
}

?>