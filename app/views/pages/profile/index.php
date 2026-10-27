<?php

$titlePage = 'Profile | Parkeasy';

$fileCss = [
    'pages/profile.css',
];

$fileJs = [
    'pages/profile.js',
];


?>

<main class="main-content">

    <section>
        <span class="section-header">Manage Profile</span>

        <div class="profile-section">

            <div class="profile-avatar-column">
                <div class="profile-image">
                    <img
                        src="<?= parkEasyEscape($avatarUrl) ?>"
                        alt="Profile picture"
                        data-profile-photo-preview
                    >
                </div>

                <form
                    class="profile-photo-form"
                    action="?page=profile"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    <?= csrfField() ?>
                    <input type="hidden" name="profile_action" value="avatar_upload">
                    <input
                        type="file"
                        id="profile-photo"
                        name="profile_photo"
                        accept="image/jpeg,image/png,image/webp"
                        required
                    >
                    <label for="profile-photo" class="profile-photo-picker">
                        <i class="fa-solid fa-camera" aria-hidden="true"></i>
                        Choose a photo
                    </label>
                    <span class="profile-photo-filename" data-profile-photo-filename aria-live="polite">No photo selected</span>
                    <span class="profile-photo-hint">JPG, PNG, or WebP - Maximum 5 MB</span>
                    <button type="submit" class="btn-upload-photo" disabled>Upload photo</button>
                </form>
            </div>

            <form
                class="profile-information profile-form"
                action="?page=profile"
                method="POST"
            >
                <?= csrfField() ?>
                <input
                    type="hidden"
                    name="profile_action"
                    value="save"
                >

                <div class="profile-fields">

                    <?php foreach ($profileFields as $name => $field): ?>
                        <?php
                            $value = trim((string) ($user[$name] ?? ''));
                            $isMissing = $value === '';
                        ?>

                        <div class="form-field <?= $isMissing ? 'is-incomplete' : '' ?>">

                            <label
                                for="<?= parkEasyEscape($name) ?>"
                                class="form-label"
                            >
                                <?= parkEasyEscape($field['label']) ?>
                            </label>

                            <input
                                type="<?= parkEasyEscape($field['type']) ?>"
                                class="form-control"
                                id="<?= parkEasyEscape($name) ?>"
                                name="<?= parkEasyEscape($name) ?>"
                                placeholder="<?= parkEasyEscape($field['placeholder']) ?>"
                                value="<?= parkEasyEscape($value) ?>"
                                autocomplete="<?= $name === 'name' ? 'username' : ($name === 'email' ? 'email' : 'tel') ?>"
                                <?= $name === 'email' ? 'readonly' : '' ?>
                                disabled
                                required
                                <?= $isMissing ? 'aria-invalid="true"' : '' ?>
                            >

                            <?php if ($isMissing): ?>
                                <small class="field-error">
                                    <?= parkEasyEscape($field['label']) ?> is required.
                                </small>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="profile-actions">

                    <button
                        type="button"
                        class="btn-edit-profile"
                    >
                        Edit Profile
                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                    </button>

                    <div class="profile-edit-actions" hidden>

                        <button
                            type="button"
                            class="btn-cancel-profile"
                        >
                            Cancel
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>

                        <button
                            type="submit"
                            class="btn-save-profile"
                        >
                            Save Profile
                            <i class="fa-solid fa-check" aria-hidden="true"></i>
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </section>

    <section>
        <span class="section-header">My Plate Numbers</span>

        <div class="vehicle-card">

            <?php if (empty($vehicles)): ?>
                <span class="vehicle-empty">
                    You have no plate number yet. Add one to start reserving a parking slot.
                </span>
            <?php else: ?>
                <ul class="vehicle-list">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <li class="vehicle-item">
                            <span><i class="fa-solid fa-car" aria-hidden="true"></i> <?= parkEasyEscape($vehicle['plate_number']) ?></span>

                            <form action="?page=profile" method="POST" data-confirm-message="Remove this plate number from your profile?">
                                <?= csrfField() ?>
                                <input type="hidden" name="profile_action" value="delete_plate">
                                <input type="hidden" name="vehicle_id" value="<?= (int) $vehicle['id'] ?>">
                                <button type="submit" class="vehicle-remove">Remove</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form action="?page=profile" method="POST" class="vehicle-add">
                <?= csrfField() ?>
                <input type="hidden" name="profile_action" value="add_plate">
                <input type="text" name="plate_number" class="form-control" placeholder="B 1234 FSD" maxlength="12" required aria-label="Plate number">
                <button type="submit" class="vehicle-add-button">Add plate</button>
            </form>

        </div>
    </section>

</main>
