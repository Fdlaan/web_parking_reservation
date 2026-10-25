<?php

function parkEasyHandleProfileUpdate(
    string $page,
    string $requestMethod,
    int $currentUserId,
    ?array $user,
    array &$users
): void {
    if (
        $requestMethod !== 'POST'
        || $page !== 'profile'
        || ($_POST['profile_action'] ?? '') !== 'save'
    ) {
        return;
    }

    if ($user === null) {
        header('Location: ?page=auth&step=login');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim((string) ($user['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $plateNumber = trim($_POST['plate_number'] ?? '');

    $errors = [];

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    }

    if ($plateNumber === '') {
        $errors[] = 'Plate number is required.';
    }

    if ($errors === []) {
        $users[$currentUserId]['username'] = $username;
        $users[$currentUserId]['email'] = $email;
        $users[$currentUserId]['phone'] = $phone;
        $users[$currentUserId]['plate_number'] = $plateNumber;
        $users[$currentUserId]['updated_at'] = date('Y-m-d H:i:s');

        $_SESSION['profile_message'] = 'Profile updated successfully.';
    } else {
        $_SESSION['profile_message'] = implode(' ', $errors);
    }

    header('Location: ?page=profile');
    exit;
}


/** Prepare values rendered by the Profile template. */
function parkEasyPrepareProfilePageData(?array $user, string $assetUrl): array
{
    $profileMessage = $_SESSION['profile_message'] ?? null;
    unset($_SESSION['profile_message']);

    $avatarUrl = trim((string) ($user['avatar_url'] ?? ''));
    if ($avatarUrl === '') {
        $avatarUrl = $assetUrl . '/images/icons/profile-icon.svg';
    }

    return [
        'profileMessage' => $profileMessage,
        'avatarUrl' => $avatarUrl,
        'profileFields' => [
            'username' => [
                'label' => 'Username:',
                'type' => 'text',
                'placeholder' => 'Enter your username',
            ],
            'email' => [
                'label' => 'Email:',
                'type' => 'email',
                'placeholder' => 'Enter your email',
            ],
            'phone' => [
                'label' => 'Phone:',
                'type' => 'tel',
                'placeholder' => 'Enter your phone number',
            ],
            'plate_number' => [
                'label' => 'Plate Number:',
                'type' => 'text',
                'placeholder' => 'B 1234 FSD',
            ],
        ],
    ];
}
