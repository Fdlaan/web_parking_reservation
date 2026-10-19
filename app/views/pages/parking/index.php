<?php
    // setting title page
    $title_page = 'Home | Parkeasy';

    // file CSS yang terhubung
    $file_css = [
        'pages/parking.css',
        'components/card-parking.css',
    ];

    // file JS yang terhubung
    $file_js = [];

    // ambil dummy data
    $parking_data = require __DIR__ . '/../../../../database/dummy_data/parking.php';

    // data yang di ambil (parking area, nanti masih on going dummy data nya)
    $parking_area = $parking_data['parking_area'] ?? [];

    // apa ya, ini tuh lebih ke ngasih tau lokasi user
    $breadcrumb = [
        [
            'label' => 'Search Parking',
            'url' => '?page=parking',
        ],
        [
            'label' => 'South Jakarta',
            'url' => '?page=parking$city=south-jakarta',
        ],
        [
            'label' => 'Grand Indonesia',
            'url' => null,
        ],
    ];
?>

<!-- Main Content -->
<main class="main-content">

    <!-- Section | Location -->
    <section>

        <div class="d-flex justify-items-center align-items-center">
            <span style="font: var(--text-body); color: var(--secondary-text); ">Current Location</span>
            <i class="d-flex fa-solid fa-location-dot" style="color: var(--secondary-text);"></i>
        </div>

        <a href="#" class="d-flex justify-items-center align-items-center">
            <span style="font: var(--text-H4); color: var(--primary-text);">Universitas Indonesia, Depok, Indonesia</span>
            <i class="fa-solid fa-caret-down" style="color: var(--primary-text);"></i>
        </a>

    </section>

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