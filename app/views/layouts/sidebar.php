<?php
    // file CSS yang terhubung
    $file_css = [
        'components/layouts/sidebar.css',
    ];

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

<aside class="sidebar d-flex flex-column p-3">

    <a href="?page=home" class="d-flex align-items-center text-decoration-none sidebar-brand">
        <span class="sidebar-brand-icon"></span>
        <span class="sidebar-brand-name"><h3>Parkeasy</h3></span>
    </a>

    <!-- main nav -->
    <nav class="nav nav-pills flex-column mb-auto sidebar-nav">

        <?php foreach ($sidebarItems as $item): ?>
        <?php $isActive = ($currentPage === $item['page']); ?>

        <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>" class="nav-link sidebar-link <?= $isActive ? 'active' : '' ?>" <?= $isActive ? 'aria-current="page"' : '' ?>>
            
            <span class="sidebar-icon <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"></span>
            <span>
                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
            </span>

        </a>

        <?php endforeach; ?>

    </nav>

    <!-- logout nav -->
    <nav class="sidebar-button-nav">

        <a href="?page=logout" class="nav-link sidebar-link sidebar-logout">
            <span class="sidebar-icon logout-icon" aria-hidden="true"></span>
            <span>Log Out</span>
        </a>

    </nav>

</aside>