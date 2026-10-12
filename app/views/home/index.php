<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>


        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Topbar -->
            <?php include __DIR__ . '/../layouts/topbar.php'; ?>

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column 1 py-4 ms-4 me-4">
                <div class="container py-4">

                    <header class="dashboard pb-3 mb-4">
                        <p class="d-flex align-items-center text-secondary">Current Location
                            <i class="fa-solid fa-location-dot ms-1"></i>
                        </p>
                        <a href="/" class="d-flex align-items-center text-light text-decoration-none fw-medium w-25 text-nowrap">
                            <span class="fs-4">Universitas Indonesia, Depok, Indonesia
                                <i class="fa-solid fa-caret-down"></i>
                            </span>
                        </a>
                    </header>

                    <h3 class="fw-bold text-light mb-4">Reservations</h3>
                    <div class="p-5 mb-4 rounded-4 bg-opacity-10 border border-dark" style="background-color: #2C2C2C;">
                        <div class="container-fluid py-5">

                        </div>
                    </div>

                    <!-- Recommended Parking -->
                    <div class="d-flex mb-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Recommended Parking</h3>
                        <button type="button" class="ms-auto text-nowrap btn btn-outline-warning"
                            style="width: 95px; height: 30px; padding: 0.1em">View More</button>
                    </div>

                    <div class="row align-items-md-stretch">
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-recommended.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-recommended.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-recommended.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-recommended.php'
                        ?>
                    </div>

                    <!-- Previously Parked -->
                    <div class="d-flex mb-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Previously Parked</h3>
                        <button type="button" class="ms-auto text-nowrap btn btn-outline-warning"
                            style="width: 95px; height: 30px; padding: 0.1em">View More</button>
                    </div>

                    <div class="row g-4">
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-prev.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-prev.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/img/grand-indonesia-mall.jpg',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../components/card-prev.php'
                        ?>
                    </div>

                </div>
            </main>

        </div>
    </div>

</body>

</html>