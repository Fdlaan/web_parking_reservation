<?php

$titlePage = isset($selectedMall['name'])
    ? $selectedMall['name'] . ' | Payment'
    : 'Payment | Parkeasy';

$fileCss = [
    'pages/parking/payment.css',
];

$fileJs = [];

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

<main class="main-content payment-page">

    <?php if (($paymentPageData['error'] ?? null) !== null): ?>
        <section>
            <a href="?page=parking" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>

            <p class="payment-error-message">
                <?= parkEasyEscape($paymentPageData['error']) ?>
            </p>
        </section>

    <?php else: ?>
        <section>
            <a href="?page=slot&mall_id=<?= rawurlencode((string) ($mall['mall_id'] ?? '')) ?>" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>
        </section>

        <section>
            <div class="payment-header">
                <span class="payment-title">Payment</span>

                <span class="payment-description">
                    Complete your payment to confirm your reservation.
                </span>
            </div>

            <div class="payment-layout">
                
                <div class="payment-method-card payment-card">
                    <div class="payment-qr-container">
                        <img src="<?= $assetUrl ?>/images/qr_example.png" alt="QRIS payment code" class="payment-qr-image">
                    </div>

                    <div class="payment-breakdown">
                        <div class="payment-detail-row">
                            <span>Payment Method</span>
                            <i class="qris-icon" aria-label="QRIS"></i>
                        </div>

                        <div class="payment-detail-row">
                            <span>Reservation Fee</span>

                            <strong>
                                Rp <?= number_format($reservationFee, 0, ',', '.') ?>
                            </strong>
                        </div>


                        <div class="payment-detail-row">
                            <span>Admin Fee</span>

                            <strong>
                                Rp <?= number_format($serviceFee, 0, ',', '.') ?>
                            </strong>
                        </div>
                    </div>
                </div>

                <aside class="payment-summary">
                    <div class="payment-card payment-parking-card">

                        <span class="payment-section-title">Parking Details</span>

                        <hr class="payment-divider"></hr>

                        <div class="payment-detail-row-item">
                            <div class="payment-detail-row">
                                <span>Reservation ID</span>

                                <strong>
                                    <?= $reservationId !== null
                                        ? parkEasyEscape($reservationId)
                                        : 'Not created yet' ?>
                                </strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Parking Location</span>

                                <strong>
                                    <?= parkEasyEscape($mall['name'] ?? '-') ?>
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
                                    <?= parkEasyEscape($floorCode) ?>
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
                        </div>


                    </div>

                    <div class="payment-card payment-status-card">

                        <div class="payment-card-header">

                            <span class="payment-section-title">
                                Payment Status
                            </span>

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

                        <button
                            type="button"
                            class="payment-button payment-button-primary"
                            disabled
                        >
                            Payment Gateway Not Available
                        </button>

                        <span class="payment-footnote">
                            Invoice access will be available after payment has
                            been successfully verified.
                        </span>

                    </div>
                </aside>

            </div>


        </section>
    <?php endif; ?>

</main>