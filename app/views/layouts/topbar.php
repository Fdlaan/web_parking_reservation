<?php

    // breadcrumb for the current page
    $breadcrumb = $breadcrumb ?? [];

?>

<!-- topbar container -->
<header class="topbar d-flex flex-row sticky-top">

    <!-- breadcrumb nav -->
    <div class="topbar-item-breadcrumb">

        <?php foreach ($breadcrumb as $item): ?>
        <div class="breadcrumb-item">

            <?php if (($item['url'] ?? null) !== null): ?>
            <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
            <i class= "breadcrumb-icon" aria-hidden="true"></i>

            <?php else: ?>
            <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?> 
            </a>
            <i class= "breadcrumb-icon" aria-hidden="true"></i>

            <?php endif; ?>

        </div>
        <?php endforeach; ?>

    </div>

    <!-- right section -->
    <div class="topbar-item-right">

        <!-- notification button -->
        <a href="#" class="notification" aria-label="Notifications">
            <span class="notification-icon" aria-hidden="true"></span>
        </a>

        <!-- user profile -->
        <a href="?page=profile" class="profile text-uppercase text-decoration-none">
            <img src="<?= htmlspecialchars($user['avatar_url'] ?? 'http://github.com/mdo.png', ENT_QUOTES, 'UTF-8' ) ?>" alt="Profile picture" width="36" height="36">
            <span><?= htmlspecialchars($user['username'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></span>
        </a>

    </div>

</header>