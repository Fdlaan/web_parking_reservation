<?php

/** Handles the profile form and the plate number list. Always redirects back to the profile. */
function parkEasyHandleProfileUpdate(mysqli $conn, int $userId): never
{
    $action = $_POST['profile_action'] ?? '';
    $error = null;

    if ($action === 'save') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));

        if ($name === '') {
            $error = 'Username is required.';
        } elseif (!preg_match('/^\+?[0-9]{8,15}$/', $phone)) {
            $error = 'Phone number must be 8-15 digits.';
        } else {
            $conn->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?')->execute([$name, $phone, $userId]);
            flash('Profile updated successfully.');
        }
    } elseif ($action === 'add_plate') {
        $plate = strtoupper(trim((string) ($_POST['plate_number'] ?? '')));

        if (!preg_match('/^([A-Z]{1,2}) ?([0-9]{1,4}) ?([A-Z]{0,3})$/', $plate, $m)) {
            $error = 'Enter a valid plate number, for example B 1234 FSD.';
        } else {
            try {
                $conn->prepare('INSERT INTO vehicles (user_id, plate_number) VALUES (?, ?)')
                    ->execute([$userId, trim($m[1] . ' ' . $m[2] . ' ' . $m[3])]);
                flash('Plate number added.');
            } catch (mysqli_sql_exception $ex) {
                $error = $ex->getCode() === 1062 ? 'You already added that plate number.' : 'Could not save the plate number.';
            }
        }
    } elseif ($action === 'delete_plate') {
        $st = $conn->prepare('DELETE FROM vehicles WHERE id = ? AND user_id = ?');
        $st->execute([(int) ($_POST['vehicle_id'] ?? 0), $userId]);

        if ($st->affected_rows > 0) {
            flash('Plate number removed.');
        } else {
            $error = 'Plate number not found.';
        }
    }

    if ($error !== null) {
        flash('!' . $error);
    }

    redirect('?page=profile');
}

/** Saves the location picked on Home / Search Park (kept in the session, no table of its own). */
function parkEasyActionSetLocation(mysqli $conn): never
{
    $id = (int) ($_POST['location_id'] ?? 0);

    if (staffRows($conn, 'SELECT id FROM user_locations WHERE id = ?', [$id])) {
        $_SESSION['location_id'] = $id;
        flash('Location updated.');
    } else {
        flash('!That location does not exist.');
    }

    redirect(($_POST['back'] ?? '') === 'parking' ? '?page=parking' : '?page=home');
}

/** Prepare values rendered by the Profile template. */
function parkEasyPrepareProfilePageData(?array $user, string $assetUrl): array
{
    $avatarUrl = trim((string) ($user['avatar_url'] ?? ''));

    return [
        'avatarUrl' => $avatarUrl !== '' ? $avatarUrl : $assetUrl . '/images/icons/profile-icon.svg',
        'profileFields' => [
            'name' => ['label' => 'Username:', 'type' => 'text', 'placeholder' => 'Enter your username'],
            'email' => ['label' => 'Email:', 'type' => 'email', 'placeholder' => 'Enter your email'],
            'phone' => ['label' => 'Phone:', 'type' => 'tel', 'placeholder' => 'Enter your phone number'],
        ],
    ];
}
