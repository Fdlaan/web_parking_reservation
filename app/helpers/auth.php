<?php

function parkEasyCurrentUserId(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function parkEasyFindUserById(mysqli $conn, int $id): ?array
{
    return staffRows($conn, 'SELECT id, name, email, phone, avatar_url FROM users WHERE id = ?', [$id])[0] ?? null;
}

function parkEasyLogout(): void
{
    unset($_SESSION['user_id'], $_SESSION['operator_id'], $_SESSION['admin_id']);
    session_regenerate_id(true);
}
