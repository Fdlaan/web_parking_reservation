<?php
    $title_page = 'Home | Parkeasy';

    $file_css = [
        'components/icon.css',
        'pages/home.css',
        'layouts/sidebar.css',
        'layouts/topbar.css',
        'layouts/footer.css',
    ];

    $file_js = [];
?>

<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>


        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column">

            <!-- Topbar -->
            <?php include __DIR__ . '/../../layouts/topbar.php'; ?>

            <!-- Main content -->
            <main class="d-flex flex-column gap-4 container-main">

                <div class="top-content">
                    <div class="d-flex justify-items-center align-items-center gap-1">
                        <span style="font: var(--text-body); color: var(--secondary-text); ">Current Location</span>
                        <i class="d-flex fa-solid fa-location-dot" style="color: var(--secondary-text);"></i>
                    </div>
                    <a href="#" class="d-flex justify-items-center align-items-center gap-1">
                        <span style="font: var(--text-H4); color: var(--primary-text);">Universitas Indonesia, Depok, Indonesia</span>
                        <i class="fa-solid fa-caret-down" style="color: var(--primary-text);"></i>
                    </a>
                </div>

                <div class="home-section">
                    <h3>Reservations</h3>
                    <div class="d-flex flex-row p-5 container-fluid rounded-4 bg-opacity-10 border border-dark gap-3" style="background-color: #2C2C2C;">

                    </div>
                </div>

                <!-- Recommended Parking -->
                <div class="home-section">
                    <div class="home-header">
                        <h3>Recommended Parking</h3>
                        <button type="button" class="home-button">View More</button>
                    </div>

                    <div class="recommend-section">
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../../components/card-recommended.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../../components/card-recommended.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../../components/card-recommended.php'
                        ?>
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall',
                                'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                                'distance' => '7.1 KM',
                                'url' => '#',
                                'alt' => 'Grand Indonesia Mall'
                            ];

                            include __DIR__ . '/../../components/card-recommended.php'
                        ?>
                    </div>
                </div>

                

                <!-- Previously Parked -->
                <div class="home-section">
                    <div class="home-header">
                        <h3>Previously Parked</h3>
                        <button type="button" class="home-button">View More</button>
                    </div>

                    <div class="prev-section">
                    <?php
                        $field = [
                            'id' => 'grand-indonesia',
                            'name' => 'Grand Indonesia Mall',
                            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                            'url' => '#',
                            'alt' => 'Grand Indonesia Mall'
                        ];

                        include __DIR__ . '/../../components/card-prev.php'
                    ?>
                    <?php
                        $field = [
                            'id' => 'grand-indonesia',
                            'name' => 'Grand Indonesia Mall',
                            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                            'url' => '#',
                            'alt' => 'Grand Indonesia Mall'
                        ];

                        include __DIR__ . '/../../components/card-prev.php'
                    ?>
                    <?php
                        $field = [
                            'id' => 'grand-indonesia',
                            'name' => 'Grand Indonesia Mall',
                            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
                            'url' => '#',
                            'alt' => 'Grand Indonesia Mall'
                        ];

                        include __DIR__ . '/../../components/card-prev.php'
                    ?>
                </div>
                </div>

                

            </main>

        </div>
    </div>

</body>

</html>