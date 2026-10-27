<?php

// setting title page
$titlePage = 'Search Parking | Parkeasy';

// file CSS yang terhubung
$fileCss = [
    'pages/parking/index.css',
    'components/card-parking.css',
];

// file JS yang terhubung
$fileJs = [];


?>

<!-- Main Content -->
<main class="main-content">

    <!-- Section | Location -->
    <section>
        <?php include $projectRoot . '/app/views/components/user-location.php'; ?>
    </section>
    
    <section>
        <form method="GET" action="" class="d-flex align-items-center search-section" role="search">
            <input type="hidden" name="page" value="parking">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="q" value="<?= parkEasyEscape($parkingIndexData['search']) ?>" placeholder="Search here" aria-label="Search parking">
        </form>
    </section>

    <?php if (empty($mallsByCity)): ?>
    <section>
        <span style="display:block;font: var(--text-body); color: var(--secondary-text);">
            No parking location found. <a href="?page=parking">Show all</a>
        </span>
    </section>
    <?php endif; ?>

    <!-- Section | Area Mall Section -->
    <?php foreach ($mallsByCity as $city => $cityMalls): ?>
    <section class="horizontal-card">

        <div class="section-header">
            <?= htmlspecialchars($city, ENT_QUOTES, 'UTF-8') ?>
            <a href="?page=parking&city=<?= urlencode($city) ?>" class="view-more-button" aria-label="View more parking in <?= htmlspecialchars($city, ENT_QUOTES, 'UTF-8') ?>">
                View More
            </a>
        </div>

        <div class="parking-list mall-list">

            <?php foreach ($cityMalls as $field): ?>
                <?php
                $variant = 'area';
                require $projectRoot . '/app/views/components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>
    <?php endforeach; ?>

</main>