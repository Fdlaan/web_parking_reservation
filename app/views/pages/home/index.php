<?php
    // setting title page
    $title_page = 'Home | Parkeasy';

    // file CSS yang terhubung
    $file_css = [
        'pages/home.css',
        'components/card-parking.css'
    ];

    // file JS yang terhubung
    $file_js = [];

    require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/models/Mall.php';
    $locations = require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy_data/locations.php';

    $selectedLocation = $locations[2];

    $recommended_parking = Mall::getRecommended(
        (float) $selectedLocation['latitude'],
        (float) $selectedLocation['longitude']
    );

    $recommended_parking = array_slice($recommended_parking, 0, 4);

    $previously_parking = [];
    $reservation_status = 'none'; // none, unpaid, paid
    $reservation = [];
    $can_cancel = false;
?>

<!-- Main Content -->
<main class="main-content d-flex flex-column">

    <!-- Section | Location -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/user-location.php'; ?>

    <!-- Section | Reservation -->
    <section>

        <span class="section-header">Reservations</span>

        <!-- Reservation: none  -->
        <?php if ($reservation_status === 'none'): ?>
        <div class="reservation-card reservation-empty">
            <i class="fa-regular fa-calendar-xmark reservation-empty-icon"></i>
            <span style="font: var(--text-label);">You currently have no reservations.</span>
        </div>

        <!-- Reservation: paid -->
        <?php else: ?>
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

                <!-- Reservation: unpaid -->
                <?php if($reservation_status === 'unpaid'): ?>
                <a href="?page=payment" class="reservation-button pay-button">
                    Pay Now
                </a>
                
                <!-- Reservation:  -->
                <?php else: ?>
                <?php if ($can_cancel): ?>
                <form action="?page=cancel-reservation" method="POST">
                    <input type="hidden" name="reservation_id" value="1">
                    <button type="submit" class="reservation-button cancel-button">
                        Cancel Reservation
                    </button>
                </form>
                
                <!-- Reservation:  -->
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
    </section>

    <!-- Section | Recommended Mall -->
    <section>

        <span class="section-header">
            Recommended Parking
            <button type="button" class="view-more-button">View More</button>
        </span>
        
        <div class="rec-mall">
            <?php foreach ($recommended_parking as $field): ?>
                <?php
                $variant = 'recommended';
                
                require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>

    <!-- Section | Previously Mall -->
    <section>

        <span class="section-header">
            Previously Parked
            <button type="button" class="view-more-button">View More</button>
        </span>
        
        <div class="parking-list previously-mall">

            <?php foreach ($previously_parking as $field): ?>
                <?php
                $variant = 'previous';
                
                require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>

</main>