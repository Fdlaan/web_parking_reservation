<?php

const PARKEASY_OTP_MINUTES = 10;

/** Handles logout and every auth form (login, signup, forgot password). Redirects when it acts. */
function parkEasyHandleAuthRequest(mysqli $conn, string $page, string $step, string $method): void
{
    if ($page === 'logout') {
        parkEasyLogout();
        flash('You have been logged out.');
        redirect('?page=auth&step=login');
    }

    if ($page !== 'auth') {
        return;
    }

    // The password reset steps only make sense in order.
    if ($step === 'forgot-otp' && !isset($_SESSION['reset_user_id'])
        || $step === 'reset-password' && !isset($_SESSION['reset_verified_id'])) {
        redirect('?page=auth&step=forgot-email');
    }

    if ($method !== 'POST') {
        return;
    }

    $back = '?page=auth&step=' . $step;
    $fail = function (string $message) use ($back): never {
        flash('!' . $message);
        redirect($back);
    };

    if ($step === 'login') {
        $user = staffRows($conn, 'SELECT id, password_hash FROM users WHERE email = ?',
            [trim((string) ($_POST['email'] ?? ''))])[0] ?? null;

        if ($user === null || !password_verify((string) ($_POST['password'] ?? ''), $user['password_hash'])) {
            $fail('Wrong email or password.');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        flash('Welcome back!');
        redirect('?page=home');
    }

    if ($step === 'signup') {
        $name = trim((string) ($_POST['username'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($name === '') {
            $fail('Username is required.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fail('Please enter a valid email address.');
        }
        if (!preg_match('/^\+?[0-9]{8,15}$/', $phone)) {
            $fail('Phone number must be 8-15 digits.');
        }
        if (strlen($password) < 8) {
            $fail('Password must be at least 8 characters.');
        }
        if ($password !== (string) ($_POST['confirm_password'] ?? '')) {
            $fail('Passwords do not match.');
        }

        try {
            $st = $conn->prepare('INSERT INTO users (name, email, phone, password_hash) VALUES (?, ?, ?, ?)');
            $st->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
        } catch (mysqli_sql_exception $ex) {
            $fail($ex->getCode() === 1062 ? 'That email is already registered.' : 'Could not create your account. Please try again.');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $st->insert_id;
        flash('Account created. Add your plate number below before making a reservation.');
        redirect('?page=profile');
    }

    // Forgot password, demo version: there is no mail server, so the OTP is shown in the alert.
    if ($step === 'forgot-email') {
        $user = staffRows($conn, 'SELECT id FROM users WHERE email = ?', [trim((string) ($_POST['email'] ?? ''))])[0] ?? null;

        if ($user === null) {
            $fail('Email is not registered.');
        }

        $otp = (string) random_int(100000, 999999);
        $conn->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$user['id']]);
        $conn->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)')
            ->execute([$user['id'], password_hash($otp, PASSWORD_DEFAULT),
                date(PARKEASY_DB_TIME, time() + PARKEASY_OTP_MINUTES * 60)]);

        $_SESSION['reset_user_id'] = (int) $user['id'];
        flash('Demo mode, no email is sent. Your OTP is ' . $otp . '.');
        redirect('?page=auth&step=forgot-otp');
    }

    if ($step === 'forgot-otp') {
        $reset = staffRows($conn, 'SELECT id, token_hash FROM password_resets
                                   WHERE user_id = ? AND used_at IS NULL AND expires_at > ?',
            [$_SESSION['reset_user_id'], date(PARKEASY_DB_TIME)])[0] ?? null;

        if ($reset === null) {
            $fail('This OTP has expired. Please request a new one.');
        }
        if (!password_verify(trim((string) ($_POST['otp'] ?? '')), $reset['token_hash'])) {
            $fail('The OTP is incorrect.');
        }

        $conn->prepare('UPDATE password_resets SET used_at = ? WHERE id = ?')->execute([date(PARKEASY_DB_TIME), $reset['id']]);
        $_SESSION['reset_verified_id'] = $_SESSION['reset_user_id'];
        unset($_SESSION['reset_user_id']);
        flash('OTP verified. Choose a new password.');
        redirect('?page=auth&step=reset-password');
    }

    if ($step === 'reset-password') {
        $password = (string) ($_POST['password'] ?? '');

        if (strlen($password) < 8) {
            $fail('Password must be at least 8 characters.');
        }
        if ($password !== (string) ($_POST['confirm_password'] ?? '')) {
            $fail('Passwords do not match.');
        }

        $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $_SESSION['reset_verified_id']]);
        unset($_SESSION['reset_verified_id']);
        flash('Password changed. You can log in now.');
        redirect('?page=auth&step=login');
    }
}
