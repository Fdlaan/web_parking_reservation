<?php

    // breadcrumb for the current page
    $breadcrumb = $breadcrumb ?? [];

?>

<!-- topbar container -->
<header class="topbar d-flex flex-row sticky-top">

    <!-- breadcrumb nav -->
    <div class="topbar-item-breadcrumb">

        <?php $lastIndex = count($breadcrumb) - 1; ?>
        <?php foreach ($breadcrumb as $index => $item): ?>
            <?php if ($index === $lastIndex || ($item['url'] ?? null) === null): ?>
                <span class="breadcrumb-current" aria-current="page"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php else: ?>
                <a class="breadcrumb-link" href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></a>
                <i class="breadcrumb-icon" aria-hidden="true"></i>
            <?php endif; ?>
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
            <?= htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>
        </a>

    </div>

</header>