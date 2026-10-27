<?php

function parkEasyPrepareOperatorPageData(mysqli $conn): array
{
    $operator = staffAuth($conn, 'operators', 'operator_id', '?page=operator');
    $mall = staffRows(
        $conn,
        'SELECT name FROM parking_locations WHERE id = ?',
        [$operator['location_id']]
    )[0]['name'] ?? '';

    $tabs = ['reservations' => 'Active reservations', 'invoices' => 'Invoices'];
    $tab = isset($tabs[$_GET['tab'] ?? '']) ? $_GET['tab'] : 'reservations';
    $back = '?page=operator&tab=' . $tab . '&table_page=' . staffCurrentPage();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'complete') {
        if (!csrfOk()) {
            flash('!Session expired, try again.');
        } else {
            $statement = $conn->prepare("UPDATE reservations r
                                         JOIN parking_slots s ON s.id = r.slot_id
                                         JOIN floors f ON f.id = s.floor_id
                                         SET r.status = 'COMPLETED'
                                         WHERE r.id = ? AND f.location_id = ? AND r.status = 'PAID'");
            $statement->execute([(int) ($_POST['id'] ?? 0), $operator['location_id']]);
            flash($statement->affected_rows
                ? 'Reservation confirmed and marked as completed.'
                : '!Reservation not found or not ready to confirm (it may have expired).');
        }
        redirect($back);
    }

    if ($tab === 'reservations') {
        $rows = staffRows(
            $conn,
            "SELECT r.id, u.name AS user, r.plate_number AS plate, f.floor_code AS floor,
                    s.slot_code AS slot, r.parking_type AS type, r.reserved_at, r.status
             FROM reservations r
             JOIN users u ON u.id = r.user_id
             JOIN parking_slots s ON s.id = r.slot_id
             JOIN floors f ON f.id = s.floor_id
             WHERE f.location_id = ? AND r.status IN ('UNPAID', 'PAID')
             ORDER BY r.reserved_at",
            [$operator['location_id']]
        );
    } else {
        $rows = staffRows(
            $conn,
            "SELECT i.invoice_number, u.name AS user, r.plate_number AS plate, s.slot_code AS slot,
                    i.invoice_date, i.parking_fee, i.admin_fee, i.total,
                    i.payment_status AS status, i.paid_at
             FROM invoices i
             JOIN reservations r ON r.id = i.reservation_id
             JOIN users u ON u.id = r.user_id
             JOIN parking_slots s ON s.id = r.slot_id
             JOIN floors f ON f.id = s.floor_id
             WHERE f.location_id = ?
             ORDER BY i.invoice_date DESC",
            [$operator['location_id']]
        );
    }

    return [
        'operator' => $operator,
        'mall' => $mall,
        'tabs' => $tabs,
        'tab' => $tab,
        'back' => $back,
        'rows' => $rows,
    ];
}
