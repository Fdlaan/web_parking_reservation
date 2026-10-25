<?php

    // breadcrumb for the current page
    $breadcrumb = $breadcrumb ?? [];

?>

<!-- topbar container -->
<header class="topbar d-flex flex-row sticky-top">

    <!-- breadcrumb nav -->
    <nav class="topbar-item-breadcrumb" aria-label="Breadcrumb">
        <?php foreach($breadcrumb as $index => $item):?>
            <?php if ($index > 0): ?>
                <span class="breadcrumb-separator" aria-current="true">
                    <i class="breadcrumb-icon" aria-hidden="true"></i>
                </span>
            <?php endif; ?>

            <?php if ($index === array_key_last($breadcrumb)): ?>
                <span class="breadcrumb-current" aria-current="page">
                    <?= parkEasyEscape($item['label'] ?? '') ?>
                </span>
            <?php elseif (!empty($item['url'])): ?>
                <a href="<?= parkEasyEscape($item['url'] ?? '') ?>" class="breadcrumb-link">
                    <?= parkEasyEscape($item['label']) ?>
                </a>
            <?php else: ?>
                <span class="breadcrumb-label">
                    <?= parkEasyEscape($item['label'] ?? '') ?>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

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