<?php

// setting title page
$titlePage = 'Search Parking | Parkeasy';

// file CSS yang terhubung
$fileCss = [
    'pages/parking.css',
    'components/card-parking.css',
];

// file JS yang terhubung
$fileJs = [];

require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/models/Mall.php';

$mallsByCity = [];

if ($selectedLocation !== null) {
    $recommendedMalls = Mall::getRecommended(
        (float) $selectedLocation['latitude'],
        (float) $selectedLocation['longitude'],
        $parkingMalls
    );

    foreach ($recommendedMalls as $parkingMall) {
        $city = $parkingMall['city'];
        $mallsByCity[$city][] = $parkingMall;
    }

    foreach ($mallsByCity as $city => $cityMalls) {
        $mallsByCity[$city] = array_slice($cityMalls, 0, 5);
    }
}

?>

<!-- Main Content -->
<main class="main-content">

    <!-- Section | Location -->
    <section>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/user-location.php'; ?>
    </section>
    
    <section>
        <button type="button" class="d-flex align-items-center search-section" data-bs-toggle="dialog" data-bs-target="#bdSearchDialog" aria-haspopup="dialog" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Search Here</span>
        </button>
    </section>

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
                require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>
    <?php endforeach; ?>

</main>