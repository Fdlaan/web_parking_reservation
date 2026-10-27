<?php

$titlePage = isset($res['mall_name'])
    ? $res['mall_name'] . ' | Invoice'
    : 'Invoice | Parkeasy';

$fileCss = [
    'pages/parking/invoice.css',
    'pages/parking/index.css',
];

?>

<main class="main-content invoice-page">

    <section>
        <a href="?page=history" class="back-button">
            <i class="back-icon"></i>
            <span>Back</span>
        </a>
    </section>

    <span class="invoice-page-title">Reservation Invoice</span>
    <span class="invoice-page-description">Review your parking reservation details.</span>

    <?php if ($res === null): ?>

        <section class="invoice-card invoice-error">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>

            <span class="invoice-error-title">Unable to Open Invoice</span>

            <span class="invoice-error-message">Reservation not found.</span>

            <a href="?page=history" class="invoice-button invoice-button-secondary">
                Back to History
            </a>
        </section>

    <?php else: ?>

        <?php
            $reservedDate = new DateTimeImmutable($res['reserved_at']);
            $payBefore = new DateTimeImmutable($res['pay_before']);
            $status = $res['status'];
            $isPaid = in_array($status, ['PAID', 'COMPLETED'], true);

            $statusLabels = [
                'UNPAID' => 'Unpaid',
                'PAID' => 'Paid',
                'COMPLETED' => 'Completed',
                'CANCELLED' => 'Cancelled',
                'EXPIRED' => 'Expired',
            ];

            $paymentText = match ($status) {
                'UNPAID' => 'Waiting for payment, pay before ' . $payBefore->format('d M Y, H:i'),
                'PAID', 'COMPLETED' => 'Payment completed',
                'CANCELLED' => 'Reservation cancelled',
                default => 'Reservation expired',
            };
        ?>

        <div class="invoice-grid">

            <section class="invoice-card invoice-details">

                <div class="invoice-title-row">
                    <div>
                        <span class="invoice-eyebrow">INVOICE NUMBER</span>

                        <span class="invoice-number-title"><?= parkEasyEscape($res['invoice_number']) ?></span>
                    </div>

                    <span class="invoice-status <?= $isPaid ? 'is-paid' : 'is-unpaid' ?>">
                        <?= parkEasyEscape($statusLabels[$status] ?? $status) ?>
                    </span>
                </div>

                <hr>

                <div class="invoice-info-row">
                    <span>Parking Location</span>
                    <strong><?= parkEasyEscape($res['mall_name']) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Parking Slot</span>
                    <strong><?= parkEasyEscape($res['slot_code']) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Floor</span>
                    <strong><?= parkEasyEscape($res['floor_code']) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Parking Type</span>
                    <strong><?= $res['parking_type'] === 'VALET' ? 'Valet' : 'Regular' ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Plate Number</span>
                    <strong><?= parkEasyEscape($res['plate_number']) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Reservation Date</span>
                    <strong><?= $reservedDate->format('d M Y') ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Reserved Time</span>
                    <strong><?= $reservedDate->format('H:i') ?></strong>
                </div>

                <hr>

                <span class="invoice-section-title">Payment Details</span>

                <div class="invoice-info-row">
                    <span>Reservation Fee</span>
                    <strong>Rp <?= number_format((float) $res['parking_fee'], 0, ',', '.') ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Service Fee</span>
                    <strong>Rp <?= number_format((float) $res['admin_fee'], 0, ',', '.') ?></strong>
                </div>

                <div class="invoice-info-row invoice-total">
                    <span>Total</span>
                    <strong>Rp <?= number_format((float) $res['total'], 0, ',', '.') ?></strong>
                </div>

                <hr>

                <span class="invoice-section-title">Notes</span>

                <span class="invoice-note">
                    This invoice covers the reservation fee and service fee.
                    Show your plate number to the parking operator to confirm your reservation.
                </span>

            </section>

            <aside class="invoice-sidebar">

                <section class="invoice-card">
                    <span class="invoice-section-title">Payment Status</span>

                    <span class="invoice-payment-status"><?= parkEasyEscape($paymentText) ?></span>

                    <?php if ($status === 'UNPAID'): ?>
                        <a
                            href="?page=payment&reservation_id=<?= (int) $res['id'] ?>"
                            class="invoice-button invoice-button-primary"
                        >
                            Continue to Payment
                        </a>
                    <?php endif; ?>

                    <?php if ($isPaid): ?>
                        <button
                            type="button"
                            class="invoice-button invoice-button-primary"
                            onclick="window.print()"
                        >
                            <i class="fa-solid fa-file-arrow-down" aria-hidden="true"></i>
                            Download / Print Invoice
                        </button>
                    <?php endif; ?>

                </section>

                <a href="?page=home" class="invoice-button invoice-button-secondary">
                    Back to Home
                </a>

            </aside>

        </div>

    <?php endif; ?>

</main>
