<?php

    // cek bagian yang terpilih
    $currentPage = $currentPage ?? '';
    $sidebarBrand = $sidebarBrand ?? 'Parkeasy';
    $sidebarHomeUrl = $sidebarHomeUrl ?? '?page=home';
    $sidebarLogoutUrl = $sidebarLogoutUrl ?? '?page=logout';
    $sidebarLogoutForm = $sidebarLogoutForm ?? false;

    if (!isset($sidebarItems)) {
        $sidebarItems = [
            ['label' => 'Home', 'page' => 'home', 'url' => '?page=home', 'icon' => 'home-icon'],
            ['label' => 'Search Park', 'page' => 'parking', 'url' => '?page=parking', 'icon' => 'search-icon'],
            ['label' => 'History', 'page' => 'history', 'url' => '?page=history', 'icon' => 'history-icon'],
            ['label' => 'Profile', 'page' => 'profile', 'url' => '?page=profile', 'icon' => 'profile-icon'],
        ];
    }
?>

<aside class="sidebar d-flex flex-column p-3" data-sidebar>

    <div class="sidebar-brand-row">
        <a href="<?= htmlspecialchars($sidebarHomeUrl, ENT_QUOTES, 'UTF-8') ?>" class="sidebar-brand d-flex align-items-center text-decoration-none">
            <span class="sidebar-brand-icon logo-icon" aria-hidden="true"></span>
            <span class="sidebar-brand-name"><?= htmlspecialchars($sidebarBrand, ENT_QUOTES, 'UTF-8') ?></span>
        </a>
    </div>

    <!-- main nav -->
    <nav class="main-nav" id="primary-navigation" aria-label="Main navigation">

        <?php foreach ($sidebarItems as $item): ?>
        <?php $isActive = ($currentPage === $item['page']); ?>
        <?php $iconName = $item['icon'] ?? 'home-icon'; ?>

        <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>" class="nav-item <?= $isActive ? 'active' : '' ?>" title="<?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>" <?= $isActive ? 'aria-current="page"' : '' ?>>
            
            <span class="sidebar-icon <?= htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
            <span class="nav-label">
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </span>

        </a>

        <?php endforeach; ?>

    </nav>

    <!-- logout nav -->
    <nav class="buttom-nav">

        <?php if ($sidebarLogoutForm): ?>
            <form class="sidebar-logout-form" action="<?= htmlspecialchars($sidebarLogoutUrl, ENT_QUOTES, 'UTF-8') ?>" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="nav-item sidebar-logout-button" title="Log Out" aria-label="Log Out">
                    <span class="sidebar-icon logout-icon" aria-hidden="true"></span>
                    <span class="nav-label">Log Out</span>
                </button>
            </form>
        <?php else: ?>
            <a href="<?= htmlspecialchars($sidebarLogoutUrl, ENT_QUOTES, 'UTF-8') ?>" class="nav-item" title="Log Out" aria-label="Log Out">
                <span class="sidebar-icon logout-icon" aria-hidden="true"></span>
                <span class="nav-label">Log Out</span>
            </a>
        <?php endif; ?>

    </nav>

</aside>
<button type="button" class="sidebar-backdrop" data-sidebar-backdrop aria-label="Close navigation"></button>