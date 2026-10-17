<?php
    $title_page = 'Search | Parkeasy';

    $file_css = [
        'components/icon.css',
        'pages/parking.css',
        'layouts/sidebar.css',
        'layouts/topbar.css',
        'layouts/footer.css',
    ];

    $malls = [
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
        [
            'name' => 'Grand Indonesia Mall',
            'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
            'price' => 'Rp 15.000 per slot',
            'distance' => '7.1 KM',
            'url' => '#',
        ],
    ];

    $malls = array_slice($malls, 0, 8);
?>

<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="layout">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>


        <!-- Right column: topbar + main content -->
        <div class="content">

            <!-- Topbar -->
            <?php include __DIR__ . '/../../layouts/topbar.php'; ?>

            <!-- Main content -->
            <main class="main-content">

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
            
                <button type="button" class="d-flex align-items-center search-section" style="" data-bs-toggle="dialog" data-bs-target="#bdSearchDialog" aria-haspopup="dialog" aria-label="Search">
                    <i class="fa-solid fa-magnifying-glass" style="color: var(--secondary-text);"></i>
                    <span class="" style="color: var(--secondary-text); font: var(--text-body);">Search Here</span>
                </button>

                <div class="section">
                    <div class="header">
                        <h3>Jakarta Pusat</h3>
                        <button type="button" class="view-more-button">View More</button>
                    </div>

                    <div class="mall-section">

                        <div class="mall-list">

                            <?php foreach ($malls as $mall): ?>
                                <div href="#" class="mall-item">
                                    <a href="<?= htmlspecialchars($mall['url']) ?>" class="mall-card text-decoration-none">
                                        <img src="<?= htmlspecialchars($mall['image']) ?>" alt="<?= htmlspecialchars($mall['name']) ?>" class="mall-image">
                                        
                                        <div class="mall-info">
                                            <h4 class="mall-name">
                                                <?= htmlspecialchars($mall['name']) ?>
                                            </h4>
                                            <div class="mall-detail">
                                                <p>
                                                    <?= htmlspecialchars($mall['price']) ?>
                                                </p>
    
                                                <p class="mall-distance">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                    <?= htmlspecialchars($mall['distance']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>

</html>