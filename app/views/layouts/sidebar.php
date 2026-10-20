<?php

    // cek bagian yang terpilih
    $currentPage = $currentPage ?? '';

    // sidebar item for each section
    $sidebarItems = [
        [
            'label' => 'Home',
            'page' => 'home',
            'url' => '?page=home',
            'icon' => 'home-icon',
            'type' => 'page'
        ],
        [
            'label' => 'Search Park',
            'page' => 'parking',
            'url' => '?page=parking',
            'icon' => 'search-icon',
            'type' => 'page'
        ],
        [
            'label' => 'History',
            'page' => 'history',
            'url' => '?page=history',
            'icon' => 'history-icon',
            'type' => 'page'
        ],
        [
            'label' => 'Profile',
            'page' => 'profile',
            'url' => '?page=profile',
            'icon' => 'profile-icon',
            'type' => 'page'
        ]
    ];
?>

<!-- sidebar containr -->
<aside class="sidebar d-flex flex-column p-3">

    <a href="?page=home" class="sidebar-brand d-flex align-items-center text-decoration-none">
        <span class="sidebar-brand-icon logo-icon" aria-hidden="true"></span>
        <span class="sidebar-brand-name">Parkeasy</span>
    </a>

    <!-- main nav -->
    <nav class="main-nav">

        <?php foreach ($sidebarItems as $item): ?>
        <?php $isActive = ($currentPage === $item['page']); ?>

        <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>" class="nav-item <?= $isActive ? 'active' : '' ?>" <?= $isActive ? 'aria-current="page"' : '' ?>>
            
            <span class="sidebar-icon <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
            <span>
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </span>

        </a>

        <?php endforeach; ?>

    </nav>

    <!-- logout nav -->
    <nav class="buttom-nav">

        <a href="?page=logout" class="nav-item">
            <span class="sidebar-icon logout-icon" aria-hidden="true"></span>
            <span>Log Out</span>
        </a>

    </nav>

</aside>