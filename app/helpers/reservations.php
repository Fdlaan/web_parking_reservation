<?php
// Reservation rules and actions. All times are PHP local time (Asia/Jakarta, set in index.php).

const PARKEASY_MAX_AHEAD_HOURS = 2;     // can only reserve up to 2 hours from now
const PARKEASY_CANCEL_CUTOFF_HOURS = 1; // no cancelling within 1 hour of the reserved time
const PARKEASY_GRACE_HOURS = 1;         // a paid reservation expires 1 hour after its reserved time
const PARKEASY_MIN_LEAD_MIN = 5;        // earliest reservation is 5 minutes from now, so there is time to pay (pay before = reserved time - 1 minute)

const PARKEASY_DB_TIME = 'Y-m-d H:i:s';

/** Expires unpaid reservations past their pay-before time and paid ones past the grace period. Runs every request. */
function parkEasyExpireReservations(mysqli $conn): void
{
    $now = new DateTimeImmutable('now');

    $conn->prepare("UPDATE reservations r JOIN invoices i ON i.reservation_id = r.id
                    SET r.status = 'EXPIRED', i.payment_status = 'EXPIRED'
                    WHERE r.status = 'UNPAID' AND i.pay_before < ?")
        ->execute([$now->format(PARKEASY_DB_TIME)]);

    $conn->prepare("UPDATE reservations SET status = 'EXPIRED' WHERE status = 'PAID' AND reserved_at < ?")
        ->execute([$now->modify('-' . PARKEASY_GRACE_HOURS . ' hour')->format(PARKEASY_DB_TIME)]);
}

 function parkEasyCanCancel(string $reservedAt, string $status = 'PAID'): bool
   {
       return $status === 'UNPAID'
           || new DateTimeImmutable('now')
               <= (new DateTimeImmutable($reservedAt))->modify('-' . PARKEASY_CANCEL_CUTOFF_HOURS . ' hour');
   }

/** Selectable reservation times: every 15 minutes from now + 5 minutes until now + 2 hours. ['Y-m-d H:i' => label] */
function parkEasyTimeOptions(): array
{
    $now = new DateTimeImmutable('now');
    $earliest = $now->modify('+' . PARKEASY_MIN_LEAD_MIN . ' minutes');
    $latest = $now->modify('+' . PARKEASY_MAX_AHEAD_HOURS . ' hours');
    $time = $earliest->setTime((int) $earliest->format('H'), (int) ceil((int) $earliest->format('i') / 15) * 15);
    $options = [];

    for (; $time <= $latest; $time = $time->modify('+15 minutes')) {
        $options[$time->format('Y-m-d H:i')] = $time->format('H:i')
            . ($time->format('Y-m-d') !== $now->format('Y-m-d') ? ' (tomorrow)' : '');
    }

    return $options;
}

function parkEasyLoadReservation(mysqli $conn, int $id, int $userId): ?array
{
    return staffRows($conn, 'SELECT r.id, r.plate_number, r.parking_type, r.reserved_at, r.status,
                                    s.slot_code, f.floor_code, l.id AS mall_id, l.name AS mall_name,
                                    i.invoice_number, i.pay_before, i.parking_fee, i.admin_fee, i.total, i.paid_at
                             FROM reservations r
                             JOIN parking_slots s ON s.id = r.slot_id
                             JOIN floors f ON f.id = s.floor_id
                             JOIN parking_locations l ON l.id = f.location_id
                             JOIN invoices i ON i.reservation_id = r.id
                             WHERE r.id = ? AND r.user_id = ?', [$id, $userId])[0] ?? null;
}

function parkEasyActionReserve(mysqli $conn, int $userId): never
{
    $slot = staffRows($conn, 'SELECT s.id, s.parking_type, s.floor_id, f.location_id
                              FROM parking_slots s JOIN floors f ON f.id = s.floor_id
                              WHERE s.id = ? AND s.is_active = 1', [(int) ($_POST['slot_id'] ?? 0)])[0] ?? null;

    if ($slot === null) {
        flash('!That parking slot is not available.');
        redirect('?page=parking');
    }

    $back = '?page=slot&mall_id=' . $slot['location_id'] . '&floor_id=' . $slot['floor_id']
        . '&parking_type=' . $slot['parking_type'];
    $fail = function (string $message) use ($back): never {
        flash('!' . $message);
        redirect($back);
    };

    if (staffRows($conn, "SELECT id FROM reservations WHERE user_id = ? AND status IN ('UNPAID', 'PAID') LIMIT 1", [$userId])) {
        $fail('You already have an active reservation. Finish or cancel it before reserving another slot.');
    }

    $vehicle = staffRows($conn, 'SELECT id, plate_number FROM vehicles WHERE id = ? AND user_id = ?',
        [(int) ($_POST['vehicle_id'] ?? 0), $userId])[0] ?? null;

    if ($vehicle === null) {
        $fail('Please choose one of the plate numbers saved in your profile.');
    }

    $at = DateTimeImmutable::createFromFormat('!Y-m-d H:i', (string) ($_POST['reserved_at'] ?? ''));
    $now = new DateTimeImmutable('now');
    $latest = $now->modify('+' . PARKEASY_MAX_AHEAD_HOURS . ' hours');
    $earliest = $now->modify('+' . PARKEASY_MIN_LEAD_MIN . ' minutes');

    if ($at === false) {
        $fail('Please choose a reservation time.');
    }
    if ($at < $earliest->setTime((int) $earliest->format('H'), (int) $earliest->format('i'))) {
        $fail('Please choose a time at least ' . PARKEASY_MIN_LEAD_MIN . ' minutes from now.');
    }
    if ($at > $latest) {
        $fail('You can only reserve up to ' . PARKEASY_MAX_AHEAD_HOURS . ' hours from now (latest ' . $latest->format('H:i') . ').');
    }

    $rate = staffRows($conn, 'SELECT price_per_slot, admin_fee FROM parking_rates WHERE location_id = ? AND parking_type = ?',
        [$slot['location_id'], $slot['parking_type']])[0] ?? null;

    if ($rate === null) {
        $fail('The parking rate for this slot is not set up yet.');
    }

    try {
        $conn->begin_transaction();

        $st = $conn->prepare("INSERT INTO reservations (user_id, slot_id, vehicle_id, plate_number, parking_type, reserved_at, status)
                              VALUES (?, ?, ?, ?, ?, ?, 'UNPAID')");
        $st->execute([$userId, $slot['id'], $vehicle['id'], $vehicle['plate_number'], $slot['parking_type'], $at->format(PARKEASY_DB_TIME)]);
        $id = $st->insert_id;

        $conn->prepare('INSERT INTO invoices (invoice_number, reservation_id, invoice_date, pay_before, parking_fee, admin_fee)
                        VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([
                'INV-' . $now->format('Ymd') . '-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT),
                $id,
                $now->format(PARKEASY_DB_TIME),
                $at->modify('-1 minute')->format(PARKEASY_DB_TIME), // pay before = 1 minute before the reserved time
                $rate['price_per_slot'],
                $rate['admin_fee'],
            ]);

        $conn->commit();
    } catch (mysqli_sql_exception $ex) {
        $conn->rollback();
        // 1062 = the unique key on active_slot_key: someone else holds this slot
        $fail($ex->getCode() === 1062
            ? 'Sorry, someone just reserved that slot. Please pick another one.'
            : 'We could not create your reservation. Please try again.');
    }

    flash('Reservation created. Please pay before ' . $at->modify('-1 minute')->format('H:i') . ' or it will expire.');
    redirect('?page=payment&reservation_id=' . $id);
}

/** Demo payment: there is no gateway, so "check payment status" simply marks the invoice as paid. */
function parkEasyActionPay(mysqli $conn, int $userId): never
{
    $id = (int) ($_POST['reservation_id'] ?? 0);

    $st = $conn->prepare("UPDATE reservations r JOIN invoices i ON i.reservation_id = r.id
                          SET r.status = 'PAID', i.payment_status = 'PAID', i.paid_at = ?
                          WHERE r.id = ? AND r.user_id = ? AND r.status = 'UNPAID'");
    $st->execute([date(PARKEASY_DB_TIME), $id, $userId]);

    if ($st->affected_rows > 0) {
        flash('Payment received. Your reservation is confirmed.');
    } else {
        flash('!This reservation can no longer be paid. It may have expired or been cancelled.');
    }

    redirect('?page=invoice&reservation_id=' . $id);
}

function parkEasyActionCancel(mysqli $conn, int $userId): never
{
    $id = (int) ($_POST['reservation_id'] ?? 0);
    $res = staffRows($conn, 'SELECT reserved_at, status FROM reservations WHERE id = ? AND user_id = ?', [$id, $userId])[0] ?? null;

    if ($res === null || !in_array($res['status'], ['UNPAID', 'PAID'], true)) {
        flash('!This reservation can no longer be cancelled.');
    } elseif (!parkEasyCanCancel($res['reserved_at'], $res['status'])) {
        flash('!Reservations cannot be cancelled within ' . PARKEASY_CANCEL_CUTOFF_HOURS . ' hour of the reserved time.');
    } else {
        $conn->prepare("UPDATE reservations r JOIN invoices i ON i.reservation_id = r.id
                        SET r.status = 'CANCELLED', r.cancelled_at = ?,
                            i.payment_status = IF(i.payment_status = 'PAID', 'REFUNDED', 'EXPIRED')
                        WHERE r.id = ? AND r.user_id = ?")
            ->execute([date(PARKEASY_DB_TIME), $id, $userId]);
        flash('Reservation cancelled.');
    }

    redirect('?page=home');
}
