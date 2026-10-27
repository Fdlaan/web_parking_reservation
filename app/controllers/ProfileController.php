<?php

/** Handles the profile form and the plate number list. Always redirects back to the profile. */
function parkEasyHandleProfileUpdate(mysqli $conn, int $userId, string $assetUrl): never
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
    } elseif ($action === 'avatar_upload') {
        $upload = $_FILES['profile_photo'] ?? null;
        $maxBytes = 5 * 1024 * 1024;
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!is_array($upload) || !isset($upload['error'])) {
            $error = 'Choose a profile photo to upload.';
        } elseif ($upload['error'] !== UPLOAD_ERR_OK) {
            $error = match ($upload['error']) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The photo must be 5 MB or smaller.',
                UPLOAD_ERR_NO_FILE => 'Choose a profile photo to upload.',
                default => 'The photo could not be uploaded. Please try again.',
            };
        } elseif ($upload['size'] > $maxBytes) {
            $error = 'The photo must be 5 MB or smaller.';
        } else {
            $fileInfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $fileInfo->file($upload['tmp_name']);
            $imageInfo = getimagesize($upload['tmp_name']);

            if (!isset($allowedTypes[$mimeType]) || $imageInfo === false || $imageInfo['mime'] !== $mimeType) {
                $error = 'Use a valid JPEG, PNG, or WebP image.';
            } else {
                $directory = dirname(__DIR__, 2) . '/public/assets/images/profile-photos';
                if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                    $error = 'Photo storage is unavailable. Please contact support.';
                } else {
                    $filename = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
                    $destination = $directory . '/' . $filename;

                    if (!move_uploaded_file($upload['tmp_name'], $destination)) {
                        $error = 'The photo could not be saved. Please try again.';
                    } else {
                        $avatarUrl = $assetUrl . '/images/profile-photos/' . $filename;
                        $conn->prepare('UPDATE users SET avatar_url = ? WHERE id = ?')->execute([$avatarUrl, $userId]);
                        flash('Profile photo updated.');
                    }
                }
            }
        }
    } elseif ($action === 'add_plate') {
        $plate = strtoupper(trim((string) ($_POST['plate_number'] ?? '')));

        if (!preg_match('/^([A-Z]{1,2}) ?([0-9]{1,4}) ?([A-Z]{0,3})$/', $plate, $m)) {
            $error = 'Enter a valid plate number, for example B 1234 FSD.';
        } else {
            $plate = trim($m[1] . ' ' . $m[2] . ' ' . $m[3]);
            $existingPlate = staffRows(
                $conn,
                'SELECT user_id FROM vehicles WHERE plate_number = ? LIMIT 1',
                [$plate]
            )[0] ?? null;

            if ($existingPlate !== null && (int) $existingPlate['user_id'] !== $userId) {
                $error = 'This plate number is already registered to another user.';
            } else {
                try {
                    $conn->prepare('INSERT INTO vehicles (user_id, plate_number) VALUES (?, ?)')
                        ->execute([$userId, $plate]);
                    flash('Plate number added.');
                } catch (mysqli_sql_exception $ex) {
                    if ($ex->getCode() !== 1062) {
                        throw $ex;
                    }
                    $existingPlate = staffRows(
                        $conn,
                        'SELECT user_id FROM vehicles WHERE plate_number = ? LIMIT 1',
                        [$plate]
                    )[0] ?? null;
                    $error = $existingPlate !== null && (int) $existingPlate['user_id'] !== $userId
                        ? 'This plate number is already registered to another user.'
                        : 'You already added that plate number.';
                }
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
    $avatarUrl = parkEasyResolveProfileAvatarUrl($user['avatar_url'] ?? null, $assetUrl);

    return [
        'avatarUrl' => $avatarUrl,
        'profileFields' => [
            'name' => ['label' => 'Username:', 'type' => 'text', 'placeholder' => 'Enter your username'],
            'email' => ['label' => 'Email:', 'type' => 'email', 'placeholder' => 'Enter your email'],
            'phone' => ['label' => 'Phone:', 'type' => 'tel', 'placeholder' => 'Enter your phone number'],
        ],
    ];
}

function parkEasyResolveProfileAvatarUrl(?string $avatarUrl, string $assetUrl): string
{
    $defaultUrl = $assetUrl . '/images/icons/profile-icon.svg';
    $avatarUrl = trim((string) $avatarUrl);

    if ($avatarUrl === '') {
        return $defaultUrl;
    }

    $localPrefix = $assetUrl . '/images/profile-photos/';
    if (str_starts_with($avatarUrl, $localPrefix)) {
        $filename = basename(parse_url($avatarUrl, PHP_URL_PATH) ?: '');
        $localPath = dirname(__DIR__, 2) . '/public/assets/images/profile-photos/' . $filename;

        return $filename !== '' && is_file($localPath) ? $avatarUrl : $defaultUrl;
    }

    return $defaultUrl;
}
