<?php

$titlePage = isset($res['mall_name'])
    ? $res['mall_name'] . ' | Payment'
    : 'Payment | Parkeasy';

$fileCss = [
    'pages/parking/payment.css',
];

$reservedAt = isset($res) ? new DateTimeImmutable($res['reserved_at']) : null;
$payBefore = isset($res) ? new DateTimeImmutable($res['pay_before']) : null;

?>

<main class="main-content payment-page">

    <?php if ($res === null): ?>
        <section>
            <a href="?page=history" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>

            <p class="payment-error-message">Reservation not found.</p>
        </section>

    <?php else: ?>
        <section>
            <a href="?page=slot&mall_id=<?= rawurlencode((string) $res['mall_id']) ?>" class="back-button">
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
                            <strong>Rp <?= number_format((float) $res['parking_fee'], 0, ',', '.') ?></strong>
                        </div>

                        <div class="payment-detail-row">
                            <span>Admin Fee</span>
                            <strong>Rp <?= number_format((float) $res['admin_fee'], 0, ',', '.') ?></strong>
                        </div>
                    </div>
                </div>

                <aside class="payment-summary">
                    <div class="payment-card payment-parking-card">

                        <span class="payment-section-title">Parking Details</span>

                        <hr class="payment-divider">

                        <div class="payment-detail-row-item">
                            <div class="payment-detail-row">
                                <span>Invoice Number</span>
                                <strong><?= parkEasyEscape($res['invoice_number']) ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Parking Location</span>
                                <strong><?= parkEasyEscape($res['mall_name']) ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Parking Slot</span>
                                <strong><?= parkEasyEscape($res['slot_code']) ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Floor</span>
                                <strong><?= parkEasyEscape($res['floor_code']) ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Plate Number</span>
                                <strong><?= parkEasyEscape($res['plate_number']) ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Reserved Date</span>
                                <strong><?= $reservedAt->format('d M Y') ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Reserved Time</span>
                                <strong><?= $reservedAt->format('H:i') ?></strong>
                            </div>

                            <div class="payment-detail-row">
                                <span>Parking Type</span>
                                <strong><?= $res['parking_type'] === 'VALET' ? 'Valet' : 'Regular' ?></strong>
                            </div>
                        </div>

                    </div>

                    <div class="payment-card payment-status-card">

                        <div class="payment-card-header">

                            <span class="payment-section-title">Payment Status</span>

                            <span class="payment-status payment-status-unpaid">Unpaid</span>

                        </div>

                        <p class="payment-status-description">
                            Pay before <strong><?= $payBefore->format('d M Y, H:i') ?></strong>,
                            otherwise the reservation expires.
                        </p>

                        <div class="payment-divider"></div>

                        <div class="payment-summary-total">
                            <span>Total</span>
                            <strong>Rp <?= number_format((float) $res['total'], 0, ',', '.') ?></strong>
                        </div>

                        <form action="?page=check-payment" method="POST">
                            <?= csrfField() ?>
                            <input type="hidden" name="reservation_id" value="<?= (int) $res['id'] ?>">

                            <button type="submit" class="payment-button payment-button-primary">
                                Check Payment Status
                            </button>
                        </form>

                    </div>
                </aside>

            </div>

        </section>
    <?php endif; ?>

</main>
