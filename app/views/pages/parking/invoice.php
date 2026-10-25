<?php

$titlePage = isset($selectedMall['name'])
    ? $selectedMall['name'] . ' | Parkeasy'
    : 'Invoice | Parkeasy';

$fileCss = [
    'pages/parking/invoice.css',
    'pages/parking/index.css',
];

$fileJs = [];

?>

<main class="main-content invoice-page">
    <?php if (($invoicePageData['error'] ?? null) !== null): ?>
        <section>
            <a href="?page=parking" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>
            <span><?= parkEasyEscape($invoicePageData['error']) ?></span>
        </section>
        
    <?php else: ?>

    <section>
        <a href="?page=home" class="back-button">
            <i class="back-icon"></i>
            <span>
                Back
            </span>
        </a>
    </section>


    <h1>Reservation Invoice</h1>
    <p>Review your parking reservation details.</p>

    <?php if ($invoiceError !== null || $invoiceData === null): ?>

        <section class="invoice-card invoice-error">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>

            <h2>Unable to Open Invoice</h2>

            <p>
                <?= parkEasyEscape(
                    $invoiceError ?? 'Invoice details are unavailable.'
                ) ?>
            </p>

            <a href="?page=history" class="invoice-button invoice-button-secondary">
                Back to History
            </a>
        </section>

    <?php else: ?>

        <?php
            $reservation = $invoiceData['reservation'] ?? null;
            $mall = $invoiceData['mall'];
            $floor = $invoiceData['floor'];
            $rate = $invoiceData['rate'];

            $isExistingReservation = $reservation !== null;

            $reservationId = $reservation['reservation_id'] ?? null;
            $slotCode = $reservation['slot_code']
                ?? $invoiceData['slot']['slot_code']
                ?? '-';

            $parkingType = $reservation['parking_type']
                ?? $invoiceData['parking_type']
                ?? 'REGULER';

            $plateNumber = $reservation['plate_number'] ?? '-';

            $reservedAt = $reservation['reserved_at'] ?? null;
            $reservedDate = $reservedAt
                ? new DateTimeImmutable($reservedAt)
                : null;

            $reservationFee = (int) ($rate['price_per_slot'] ?? 0);
            $serviceFee = (int) ($rate['admin_fee'] ?? 0);
            $total = $reservationFee + $serviceFee;

            $status = strtoupper(
                (string) ($reservation['status'] ?? 'NOT_CREATED')
            );

            $isPaid = $isExistingReservation
                && $status === 'CONFIRMED';
        ?>

        <div class="invoice-grid">

            <section class="invoice-card invoice-details">

                <div class="invoice-title-row">
                    <div>
                        <span class="invoice-eyebrow">INVOICE NUMBER</span>

                        <h2>
                            <?= $isExistingReservation
                                ? parkEasyEscape($reservationId)
                                : 'Reservation Preview'
                            ?>
                        </h2>
                    </div>

                    <span class="invoice-status <?= $isPaid
                        ? 'is-paid'
                        : 'is-unpaid'
                    ?>">
                        <?= $isPaid ? 'Paid' : (
                            $isExistingReservation
                                ? parkEasyEscape($status)
                                : 'Not Reserved'
                        ) ?>
                    </span>
                </div>

                <hr>

                <div class="invoice-info-row">
                    <span>Parking Location</span>
                    <strong><?= parkEasyEscape($mall['name']) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Parking Slot</span>
                    <strong><?= parkEasyEscape($slotCode) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Floor</span>
                    <strong><?= parkEasyEscape($floor['floor_code']) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Parking Type</span>
                    <strong><?= parkEasyEscape(
                        $parkingType === 'VALET' ? 'Valet' : 'Regular'
                    ) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Plate Number</span>
                    <strong><?= parkEasyEscape($plateNumber) ?></strong>
                </div>

                <div class="invoice-info-row">
                    <span>Reservation Date</span>
                    <strong>
                        <?= $reservedDate
                            ? parkEasyEscape($reservedDate->format('d M Y'))
                            : 'Not set'
                        ?>
                    </strong>
                </div>

                <div class="invoice-info-row">
                    <span>Reserved Time</span>
                    <strong>
                        <?= $reservedDate
                            ? parkEasyEscape($reservedDate->format('H:i'))
                            : 'Not set'
                        ?>
                    </strong>
                </div>

                <hr>

                <h3>Payment Details</h3>

                <div class="invoice-info-row">
                    <span>Reservation Fee</span>
                    <strong>
                        Rp <?= number_format($reservationFee, 0, ',', '.') ?>
                    </strong>
                </div>

                <div class="invoice-info-row">
                    <span>Service Fee</span>
                    <strong>
                        Rp <?= number_format($serviceFee, 0, ',', '.') ?>
                    </strong>
                </div>

                <div class="invoice-info-row invoice-total">
                    <span>Total</span>
                    <strong>
                        Rp <?= number_format($total, 0, ',', '.') ?>
                    </strong>
                </div>

                <hr>

                <h3>Notes</h3>

                <p class="invoice-note">
                    This invoice covers the reservation fee and service fee.
                    Additional parking charges, if applicable, may be handled
                    separately.
                </p>

            </section>

            <aside class="invoice-sidebar">

                <section class="invoice-card">
                    <h3>Payment Status</h3>

                    <p class="invoice-payment-status">
                        <?= $isPaid ? 'Payment completed' : (
                            $isExistingReservation
                                ? 'Payment not verified'
                                : 'Reservation not created yet'
                        ) ?>
                    </p>

                    <?php if ($isExistingReservation && !$isPaid): ?>
                        <a
                            href="?page=payment&reservation_id=<?= rawurlencode(
                                (string) $reservationId
                            ) ?>"
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
                            <i class="fa-solid fa-file-arrow-down"
                                aria-hidden="true"></i>
                            Download / Print Invoice
                        </button>
                    <?php endif; ?>

                    <?php if (!$isExistingReservation): ?>
                        <p class="invoice-note">
                            This is a slot preview. Your reservation has not
                            been created yet.
                        </p>
                    <?php endif; ?>

                </section>

                <a href="?page=home" class="invoice-button invoice-button-secondary">
                    Back to Home
                </a>

            </aside>

        </div>

    <?php endif; ?>
    <?php endif; ?>

</main>