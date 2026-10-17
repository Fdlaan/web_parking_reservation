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

    $reservation_status = 'unpaid';
    $reservation = [
        'mall_name' => 'Grand Indonesia Mall',
        'parking_slot' => 'GF67',
        'time' => '16:00',
        'plate_number' => 'B 3012 JAV',
        'parking_type' => 'Regular',
        'start_time' => '2026-10-16 16:00:00',
    ];

    $reservation_start = new DateTimeImmutable($reservation['start_time']);
    $now = new DateTimeImmutable('now');
    $can_cancel = $now->modify('+2 hours') <= $reservation_start;
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

                <div class="section">
                    <h3>Reservations</h3>

                    <?php if ($reservation_status === 'none'): ?>
                        
                        <!-- No reservation -->
                        <div class="reservation-card reservation-empty">
                            <i class="fa-regular fa-calendar-xmark reservation-empty-icon"></i>
                            <p style="font: var(--text-subtitle);">You currently have no reservations.</p>
                        </div>
                    <?php else: ?>

                        <!-- Active reservation -->
                         <div class="reservation-card">
                            <div class="reservation-header">
                                <span style="font:var(--text-H4);">
                                    <?= htmlspecialchars($reservation['mall_name']) ?>
                                </span>
                                <span class="reservation-status <?= $reservation_status?>">
                                    <span class="status-dot"></span>
                                    <?= $reservation_status === 'paid' ? 'Paid' : 'Unpaid' ?>
                                </span>
                            </div>
                            
                            <div class="reservation-divider"></div>
                            
                            <div class="reservation-details">
                                <div class="reservation-detail">
                                    <span class="reservation-label">Parking Slot</span>
                                    <strong>
                                        <?= htmlspecialchars($reservation['parking_slot']) ?>
                                    </strong>
                                </div>
                                <div class="reservation-detail">
                                    <span class="reservation-label">Time</span>
                                    <strong>
                                        <?= htmlspecialchars($reservation['time']) ?>
                                    </strong>
                                </div>
                                <div class="reservation-detail">
                                    <span class="reservation-label">Plate Number</span>
                                    <strong>
                                        <?= htmlspecialchars($reservation['plate_number']) ?>
                                    </strong>
                                </div>
                                <div class="reservation-detail">
                                    <span class="reservation-label">Parking Type</span>
                                    <strong>
                                        <?= htmlspecialchars($reservation['parking_type']) ?>
                                    </strong>
                                </div>

                            </div>
                            <div class="reservation-actions">
                                <?php if($reservation_status === 'unpaid'): ?>
                                    <a href="?page=payment" class="reservation-button pay-button">
                                        Pay Now
                                    </a>
                                <?php else: ?>
                                    <?php if ($can_cancel): ?>
                                        <form action="?page=cancel-reservation" method="POST">
                                            <input type="hidden" name="reservation_id" value="1">
                                            <button type="submit" class="reservation-button cancel-button">
                                                Cancel Reservation
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button type="button" class="reservation-button cancel-button" disabled>
                                            Cancel Reservation
                                        </button>
                                        <p class="reservation-cancel-note" style = "font: var(--text-body-S)">
                                            Cancellation is only available at least 2 hours before your reservation.
                                        </p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                         </div>
                    <?php endif; ?>
                </div>

                <!-- Recommended Parking -->
                <div class="section">
                    <div class="header">
                        <h3>Recommended Parking</h3>
                        <button type="button" class="view-more-button">View More</button>
                    </div>

                    <div class="recommend-section">
                        <?php
                            $field = [
                                'id' => 'grand-indonesia',
                                'name' => 'Grand Indonesia Mall  anjay kanjay kanjyay anjay kanjay kanjyayv anjay kanjay kanjyay',
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
                <div class="section">
                    <div class="header">
                        <h3>Previously Parked</h3>
                        <button type="button" class="view-more-button">View More</button>
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