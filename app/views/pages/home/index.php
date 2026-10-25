<?php

$titlePage = 'Home | Parkeasy';

$fileCss = [
    'pages/home.css',
    'components/card-parking.css',
];

$fileJs = [];

?>

<!-- Main Content -->
<main class="main-content">

    <!-- Section | Location -->
    <section>     
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/user-location.php'; ?>
    </section>

    <!-- Section | Reservation -->
    <section>

        <span class="section-header">Reservations</span>

        <!-- Reservation: none  -->
        <?php if ($reservationStatus === 'none'): ?>
        <div class="reservation-card reservation-empty">
            <i class="fa-regular fa-calendar-xmark reservation-empty-icon"></i>
            <span style="font: var(--text-label);">You currently have no reservations.</span>
        </div>

        <!-- Reservation: paid -->
        <?php else: ?>
        <div class="reservation-card">
            <div class="reservation-header">
                <span style="font:var(--text-H4);">
                    <?= htmlspecialchars($reservation['mall_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                </span>
                <span class="reservation-status <?= $reservationStatus?>">
                    <span class="status-dot"></span>
                    <?= $reservationStatus === 'paid' ? 'Paid' : 'Unpaid' ?>
                </span>
            </div>

            <div class="reservation-divider"></div>

            <div class="reservation-details">

                <div class="reservation-detail">
                    <span class="reservation-label">Parking Slot</span>
                    <strong>
                        <?= htmlspecialchars($reservation['parking_slot'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                    </strong>
                </div>

                <div class="reservation-detail">
                    <span class="reservation-label">Time</span>
                    <strong>
                        <?= htmlspecialchars($reservation['time'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                    </strong>
                </div>

                <div class="reservation-detail">
                    <span class="reservation-label">Plate Number</span>
                    <strong>
                        <?= htmlspecialchars($reservation['plate_number'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                    </strong>
                </div>

                <div class="reservation-detail">
                    <span class="reservation-label">Parking Type</span>
                    <strong>
                        <?= htmlspecialchars($reservation['parking_type'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                    </strong>
                </div>

            </div>

            <div class="reservation-actions">

                <!-- Reservation: unpaid -->
                <?php if($reservationStatus === 'unpaid'): ?>
                <a href="?page=payment" class="reservation-button pay-button">
                    Pay Now
                </a>
                
                <!-- Reservation:  -->
                <?php else: ?>
                <?php if ($canCancel): ?>
                <form action="?page=cancel-reservation" method="POST">
                    <input type="hidden" name="reservation_id" value="<?= parkEasyEscape($reservation['reservation_id'] ?? '') ?>">
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
            <button onclick="window.location.href='?page=parking'" type="button" class="view-more-button">View More</button>
        </span>
        
        <div class="rec-mall">
            <?php foreach ($recommendedParking as $field): ?>
                <?php
                $variant = 'recommended';
                
                require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>

    <!-- Section | Previously Mall -->
    <?php if (!empty($previouslyParking)): ?>
    <section>

        <span class="section-header">
            Previously Parked
            <button type="button" onclick="window.location.href='?page=history'" class="view-more-button">View More</button>
        </span>
        
        <div class="prev-mall">

            <?php foreach ($previouslyParking as $field): ?>
                <?php
                $variant = 'previous';
                
                require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/components/card-parking.php';
                ?>
            <?php endforeach; ?>

        </div>

    </section>
    <?php endif; ?>

</main>