<?php
    // setting title page
    $title_page = 'Search Parking | Parkeasy';

    // file CSS yang terhubung
    $file_css = [
        'pages/parking.css',
        'components/card-parking.css',
    ];

    // file JS yang terhubung
    $file_js = [];

    require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/models/Mall.php';
    $locations = require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy_data/locations.php';

    $selectedLocation = $locations[5];

    $malls = Mall::getRecommended(
        (float) $selectedLocation['latitude'],
        (float) $selectedLocation['longitude']
    );

    $mallsByCity = [];
    foreach ($malls as $mall) {
        $city = $mall['city'];

        if (!isset($mallsByCity[$city])) {
            $mallsByCity[$city] = [];
        }

        $mallsByCity[$city][] = $mall;
    }
    
    foreach ($mallsByCity as $city => $cityMalls) {
        $mallsByCity[$city] = array_slice($cityMalls, 0, 5);
    }

?>

<!-- Main Content -->
<main class="main-content">

    <!-- Section | Location -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/user-location.php'; ?>

    <button type="button" class="d-flex align-items-center search-section" data-bs-toggle="dialog" data-bs-target="#bdSearchDialog" aria-haspopup="dialog" aria-label="Search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>Search Here</span>
    </button>

    <!-- Section | Area Mall Section -->
    <section>

        <?php foreach ($mallsByCity as $city => $cityMalls): ?>

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

        <?php endforeach; ?>

    </section>

</main>