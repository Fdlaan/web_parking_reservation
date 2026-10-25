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

        <?php if ($profileMessage !== null): ?>
            <div class="profile-message" role="status">
                <?= parkEasyEscape($profileMessage) ?>
            </div>
        <?php endif; ?>

        <div class="profile-section">

            <div class="profile-image">
                <img
                    src="<?= parkEasyEscape($avatarUrl) ?>"
                    alt="Profile picture"
                >
            </div>

            <form
                class="profile-information profile-form"
                action="?page=profile"
                method="POST"
            >
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

                        <div class="form-field <?= $name === 'plate_number' ? 'plate-field' : '' ?> <?= $isMissing ? 'is-incomplete' : '' ?>">

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
                                autocomplete="<?= $name === 'username' ? 'username' : ($name === 'email' ? 'email' : 'off') ?>"
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

</main>