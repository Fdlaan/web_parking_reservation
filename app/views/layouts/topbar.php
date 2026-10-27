<?php

    // breadcrumb for the current page
    $breadcrumb = $breadcrumb ?? [];
    $topbarProfileUrl = $topbarProfileUrl ?? '?page=profile';
    $topbarShowAvatar = $topbarShowAvatar ?? true;
    $topbarRole = $topbarRole ?? null;

?>

<!-- topbar container -->
<header class="topbar d-flex flex-row sticky-top">

    <button
        type="button"
        class="sidebar-toggle"
        data-sidebar-toggle
        aria-label="Open navigation"
        aria-expanded="false"
        aria-controls="primary-navigation"
    >
        <span class="sidebar-toggle-icon" aria-hidden="true"></span>
    </button>

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

        <!-- user profile -->
        <a href="<?= parkEasyEscape($topbarProfileUrl) ?>" class="profile text-decoration-none <?= $topbarRole === null ? 'text-uppercase' : '' ?>">
            <?php if ($topbarShowAvatar): ?>
                <img src="<?= htmlspecialchars($user['avatar_url'] ?: $assetUrl . '/images/icons/profile-icon.svg', ENT_QUOTES, 'UTF-8') ?>" alt="" width="36" height="36">
            <?php endif; ?>
            <?php if ($topbarRole !== null): ?>
                <span class="profile-role"><?= htmlspecialchars($topbarRole, ENT_QUOTES, 'UTF-8') ?>:</span>
            <?php endif; ?>
            <span class="profile-name"><?= htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></span>
        </a>

    </div>

</header>