<?php

$titlePage = isset($paymentPageData['mall']['name'])
    ? 'Payment | ' . $paymentPageData['mall']['name']
    : 'Payment | Parkeasy';

$fileCss = [
    'pages/parking/payment.css',
];

$fileJs = [];

?>

<main class="main-content payment-page">

    <?php if (($paymentPageData['error'] ?? null) !== null): ?>

        <section class="payment-error">
            <a href="?page=parking" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>

            <div class="payment-card">
                <i
                    class="fa-solid fa-circle-exclamation payment-error-icon"
                    aria-hidden="true"
                ></i>

                <h2>Unable to Open Payment</h2>

                <p>
                    <?= parkEasyEscape($paymentPageData['error']) ?>
                </p>

                <a href="?page=history" class="payment-button payment-button-secondary">
                    Back to History
                </a>
            </div>
        </section>

    <?php else: ?>

        <?php
            $reservation = $paymentPageData['reservation'] ?? null;
            $mall = $paymentPageData['mall'] ?? [];
            $floor = $paymentPageData['floor'] ?? [];
            $slot = $paymentPageData['slot'] ?? [];
            $rate = $paymentPageData['rate'] ?? [];

            $reservationFee = (int) ($rate['price_per_slot'] ?? 0);
            $serviceFee = (int) ($rate['admin_fee'] ?? 0);
            $total = $reservationFee + $serviceFee;

            $reservedAt = null;

            if (!empty($reservation['reserved_at'])) {
                try {
                    $reservedAt = new DateTimeImmutable(
                        $reservation['reserved_at']
                    );
                } catch (Exception $exception) {
                    $reservedAt = null;
                }
            }

            $parkingType = strtoupper(
                (string) (
                    $reservation['parking_type']
                    ?? $paymentPageData['parking_type']
                    ?? 'REGULER'
                )
            );

            $reservationId = $reservation['reservation_id'] ?? null;
            $slotCode = $reservation['slot_code']
                ?? $slot['slot_code']
                ?? $slot['slot_number']
                ?? '-';

            $floorCode = $floor['floor_code'] ?? '-';
        ?>

        <section class="payment-header">

            <a href="?page=history" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>

            <div>
                <h1>Payment</h1>
                <p>Complete your payment to confirm your reservation.</p>
            </div>

        </section>

        <div class="payment-layout">

            <!-- Payment method and fee breakdown -->
            <section class="payment-card payment-method-card">

                <div class="payment-card-header">

                    <div class="payment-eyebrow">
                        <span>PAYMENT METHOD:</span>
                        <img src="<?= $assetUrl ?>/images/qris-logo.svg" alt="">
                    </div>

                    <span class="payment-type-badge">
                        <?= $parkingType === 'VALET' ? 'Valet' : 'Regular' ?>
                    </span>

                </div>

                <div class="payment-qr-placeholder">

                    <img src="<?= $assetUrl ?>/images/qr_example.png" alt="">

                </div>

                <div class="payment-breakdown">

                    <h3>Payment Details</h3>

                    <div class="payment-detail-row">
                        <span>Reservation Fee</span>
                        <strong>
                            Rp <?= number_format($reservationFee, 0, ',', '.') ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Admin Fee</span>
                        <strong>
                            Rp <?= parkEasyEscape($mall['name'] ?? '-') ?>
                        </strong>
                    </div>

                </div>

            </section>

            <!-- Reservation summary -->
            <aside class="payment-summary">

                <section class="payment-card">

                    <h3>Parking Details</h3>

                    <div class="payment-divider"></div>

                    <div class="payment-detail-row">
                        <span>Reservation ID</span>
                        <strong>
                            <?= $reservationId !== null ? parkEasyEscape($reservationId) : "Not created yet" ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Parking Location</span>
                        <strong>
                            <?= parkEasyEscape($mall['name']) ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Parking Slot</span>
                        <strong>
                            <?= parkEasyEscape($slotCode) ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Floor</span>
                        <strong>
                            <?= parkEasyEscape($floor['floor_code']) ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Reserved Date</span>
                        <strong>
                            <?= $reservedAt !== null
                                ? parkEasyEscape($reservedAt->format('d M Y'))
                                : 'Not scheduled' ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Reserved Time</span>
                        <strong>
                            <?= $reservedAt !== null
                                ? parkEasyEscape($reservedAt->format('H:i'))
                                : 'Not scheduled' ?>
                        </strong>
                    </div>

                    <div class="payment-detail-row">
                        <span>Parking Type</span>
                        <strong>
                            <?= $parkingType === 'VALET' ? 'Valet' : 'Regular' ?>
                        </strong>
                    </div>

                </section>

                <section class="payment-card">

                    <div class="payment-card-header">
                        <h3>Payment Status</h3>

                        <span class="payment-status payment-status-unpaid">
                            Unpaid
                        </span>
                    </div>

                    <p class="payment-status-description">
                        Complete your payment to receive your invoice.
                    </p>

                    <div class="payment-divider"></div>

                    <div class="payment-summary-total">
                        <span>Total</span>
                        <strong>
                            Rp <?= number_format($total, 0, ',', '.') ?>
                        </strong>
                    </div>

                    <!-- Payment action will be connected after payment verification is implemented. -->
                    <button
                        type="button"
                        class="payment-button payment-button-primary"
                        disabled
                    >
                        Payment Gateway Not Available
                    </button>

                    <p class="payment-footnote">
                        Invoice access will be available after payment has
                        been successfully verified.
                    </p>

                </section>

                <a href="?page=home" class="payment-button payment-button-secondary">
                    Back to Home
                </a>

            </aside>

        </div>

    <?php endif; ?>

</main>