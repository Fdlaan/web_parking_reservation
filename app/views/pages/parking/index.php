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

    $parking_area = [];

    require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/models/Mall.php';
    $locations = require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy_data/locations.php';

    $selectedLocation = $locations[2];

    // apa ya, ini tuh lebih ke ngasih tau lokasi user
    // $breadcrumb = [
    //     [
    //         'label' => 'Search Parking',
    //         'url' => '?page=parking',
    //     ],
    //     [
    //         'label' => 'South Jakarta',
    //         'url' => '?page=parking$city=south-jakarta',
    //     ],
    //     [
    //         'label' => 'Grand Indonesia',
    //         'url' => null,
    //     ],
    // ];
?>

<!-- Main Content -->
<main class="main-content">

    <!-- Section | Location -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/user-location.php'; ?>

    <button type="button" class="d-flex align-items-center search-section" style="" data-bs-toggle="dialog" data-bs-target="#bdSearchDialog" aria-haspopup="dialog" aria-label="Search">
        <i class="fa-solid fa-magnifying-glass" style="color: var(--secondary-text);"></i>
        <span class="" style="color: var(--secondary-text); font: var(--text-body);">Search Here</span>
    </button>

    <!-- Section | Area Mall Section -->
    <section>
        

        <span class="section-header">
            Recommended Parking
            <button type="button" class="view-more-button">View More</button>
        </span>


        <div class="parking-list area-mall">

            <?php foreach ($parking_area as $field): ?>
                <?php 
                $variant = 'area';

                include __DIR__ . '/../../components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>

</main>