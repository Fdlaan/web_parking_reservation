<?php
    // setting title page
    $title_page = 'Profile | Parkeasy';

    // file CSS yang terhubung
    $file_css = [
        'pages/profile.css',
    ];

    // file JS yang terhubung
    $file_js = [];
    
?>

<main class="main-content">

    <section>

        <span class="section-header">Manage Profile</span>

        <div class="profile-section d-flex">

            <div class="profile-image">
                <img
                    src="/web_parking_reservation/img/grand-indonesia-mall.jpg"
                    alt="Profile Picture"
                >
            </div>

            <div class="profile-information d-flex flex-column">

                <div class="profile-fields d-grid">

                    <?php $usernameMissing = trim($user['username'] ?? '') === ''; ?>

                    <div class="form-field <?= $usernameMissing ? 'is-incomplete' : ''?>">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Brian Adli" disabled required value="<?= parkEasyEscape($user['username'] ?? '') ?>" <?= $usernameMissing ? 'aria-invalid="true"' : '' ?>>
                        <?php if ($usernameMissing): ?>
                            <small class="field-error">Username is required.</small>
                        <?php endif; ?>
                    </div>

                    <?php $emailMissing = trim($user['email'] ?? '') === ''; ?>

                    <div class="form-field <?= $emailMissing ? 'is-incomplete' : ''?>">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="brianadli@example.com" disabled required value="<?= parkEasyEscape($user['email' ?? '']) ?>" <?= $emailMissing ? 'aria-invalid="true"' : '' ?>>
                        <?php if ($emailMissing): ?>
                            <small class="field-error">Email is required.</small>
                        <?php endif; ?>
                    </div>

                    <?php $phoneMissing = trim($user['phone'] ?? '') === ''; ?>

                    <div class="form-field <?= $phoneMissing ? 'is-incomplete' : ''?>">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="8582535461" disabled required value="<?= parkEasyEscape($user['phone'] ?? '') ?>" <?= $phoneMissing ? 'aria-invalid="true"' : '' ?>>
                        <?php if ($phoneMissing): ?>
                            <small class="field-error">Phone number is required.</small>
                        <?php endif; ?>
                    </div>

                    <?php $plateMissing = trim($user['plate_number'] ?? '') === ''; ?>

                    <div class="form-field plate-field <?= $plateMissing ? 'is-incomplete' : ''?>">
                        <label for="plate" class="form-label">Plate Number:</label>
                        <input type="text" class="form-control" id="plate" name="plate_number" placeholder="B 1234 FSD" disabled required value="<?= parkEasyEscape($user['plate_number'] ?? '') ?> <?= $plateMissing ? 'aria-invalid="true"' : '' ?>">
                        <?php if ($plateMissing): ?>
                            <small class="field-error">Plate Number is required.</small>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="profile-actions">
                    <button type="button" class="btn-edit-profile">
                        Edit Profile
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

            </div>

        </div>


    </section>

</main>