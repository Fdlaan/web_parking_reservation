<?php
    $fileCss[] = 'components/user-location.css';
?>

<div class="user-location">

    <div class="location-header d-flex justify-items-center align-items-center">
        <span>Current Location</span>
        <i class="location-icon"></i>
    </div>

    <form action="?page=set-location" method="POST" class="location-form">
        <?= csrfField() ?>
        <input type="hidden" name="back" value="<?= parkEasyEscape($currentPage ?? 'home') ?>">

        <select name="location_id" class="location-dropdown location-select" aria-label="Current location" onchange="this.form.submit()">
            <?php foreach ($userLocations as $location): ?>
                <option value="<?= (int) $location['id'] ?>" <?= (int) $location['id'] === (int) ($selectedLocation['id'] ?? 0) ? 'selected' : '' ?>>
                    <?= parkEasyEscape($location['address']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

</div>
