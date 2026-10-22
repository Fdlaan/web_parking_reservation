<?php
    $file_css[] = 'components/user-location.css';
?>

<div class="user-location">

    <div class="location-header d-flex justify-items-center align-items-center">
        <span>Current Location</span>
        <i class="location-icon"></i>
    </div>

    <a href="#" class="location-dropdown d-flex justify-items-center align-items-center">
        <?= htmlspecialchars($selectedLocation['address'] ?? 'Unknown location', ENT_QUOTES, 'UTF-8') ?>
        <i class="fa-solid fa-caret-down" style="color: var(--primary-text);"></i>
    </a>

</div>