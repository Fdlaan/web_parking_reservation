<?php
    $file_css = [
        'components/icon.css',
    ];
    
    $currentPage = $currentPage ?? '';

    $sidebarItems = [
        [
            'label' => 'Home',
            'page' => 'home',
            'url' => '/web_parking_reservation/public/index.php?page=home',
            'icon' => 'home-icon',
            'type' => 'page'
        ],
        [
            'label' => 'Search Park',
            'page' => 'parking',
            'url' => '/web_parking_reservation/public/index.php?page=parking',
            'icon' => 'search-icon',
            'type' => 'page'
        ],
        [
            'label' => 'History',
            'page' => 'history',
            'url' => '/web_parking_reservation/public/index.php?page=history',
            'icon' => 'history-icon',
            'type' => 'page'
        ],
        [
            'label' => 'Profile',
            'page' => 'profile',
            'url' => '/web_parking_reservation/public/index.php?page=profile',
            'icon' => 'profile-icon',
            'type' => 'page'
        ]
    ];
?>

<aside class="d-flex flex-column flex-shrink-0 p-3 vh-100 position-sticky top-0 sidebar">
    <!-- Brnad -->
    <a 
        href="/web_parking_reservation/public/index.php?page=home"
        class="d-flex align-items-center mb-5 text-decoration-none sidebar-brand"
    >

        <span class="sidebar-brand-icon">P</span>
        <span class="sidebar-brand-name">Parkeasy</span>

    </a>

    <!-- Main Nav -->
    <nav class="nav nav-pills flex-column mb-auto sidebar-nav">
        <?php foreach ($sidebarItems as $item): ?>
            <?php $isActive = ($currentPage === $item['page']); ?>

            <a
                href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>"
                class="nav-link sidebar-link <?= $isActive ? 'active' : '' ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>
            >
                <span
                    class="sidebar-icon <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>"
                    aria-hidden="true"
                ></span>

                <span>
                    <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>