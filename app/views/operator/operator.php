<?php
// Operator dashboard, reached via ?page=operator (see public/index.php).
// Every query is scoped to the operator's own mall ($op['location_id']).

require_once dirname(__DIR__, 2) . '/helpers/staff.php';

$op = staffAuth($conn, 'operators', 'operator_id', '?page=operator', 'Parkeasy Operator');
$mall = staffRows($conn, 'SELECT name FROM parking_locations WHERE id = ?', [$op['location_id']])[0]['name'] ?? '';

$tabs = ['reservations' => 'Active reservations', 'invoices' => 'Invoices'];
$tab = isset($tabs[$_GET['tab'] ?? '']) ? $_GET['tab'] : 'reservations';
$back = '?page=operator&tab=' . $tab;

// Same joins for both lists: reservation -> slot -> floor -> mall.
$from = 'FROM reservations r
         JOIN users u ON u.id = r.user_id
         JOIN parking_slots s ON s.id = r.slot_id
         JOIN floors f ON f.id = s.floor_id';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'complete') {
    if (!csrfOk()) {
        flash('!Session expired, try again.');
    } else {
        // Only a PAID reservation of this operator's mall can be confirmed (completed).
        $st = $conn->prepare("UPDATE reservations r
                              JOIN parking_slots s ON s.id = r.slot_id
                              JOIN floors f ON f.id = s.floor_id
                              SET r.status = 'COMPLETED'
                              WHERE r.id = ? AND f.location_id = ? AND r.status = 'PAID'");
        $st->execute([(int) ($_POST['id'] ?? 0), $op['location_id']]);
        flash($st->affected_rows ? 'Reservation confirmed and marked as completed.' : '!Reservation not found or not ready to confirm (it may have expired).');
    }
    redirect($back);
}

staffHead('Operator | Parkeasy');
staffBar($mall, $tabs, $tab, '?page=operator&tab=', $op['name']);

echo '<div class="card"><h2>' . e($tabs[$tab]) . '</h2>';

if ($tab === 'reservations') {
    $rows = staffRows($conn, "SELECT r.id, u.name AS user, r.plate_number AS plate, f.floor_code AS floor,
                                     s.slot_code AS slot, r.parking_type AS type, r.reserved_at, r.status
                              $from
                              WHERE f.location_id = ? AND r.status IN ('UNPAID', 'PAID')
                              ORDER BY r.reserved_at", [$op['location_id']]);

    staffTable($rows, function ($r) use ($back) {
        if ($r['status'] !== 'PAID') {
            return '<span style="color:var(--mute)">Awaiting payment</span>';
        }

        return '<form method="post" action="' . e($back) . '" onsubmit="return confirm(\'Mark as completed?\')">' . csrfField()
            . '<input type="hidden" name="action" value="complete"><input type="hidden" name="id" value="' . (int) $r['id']
            . '"><button>Confirm</button></form>';
    });
} else {
    staffTable(staffRows($conn, "SELECT i.invoice_number, u.name AS user, r.plate_number AS plate, s.slot_code AS slot,
                                        i.invoice_date, i.parking_fee, i.admin_fee, i.total,
                                        i.payment_status AS status, i.paid_at
                                 FROM invoices i
                                 JOIN reservations r ON r.id = i.reservation_id
                                 JOIN users u ON u.id = r.user_id
                                 JOIN parking_slots s ON s.id = r.slot_id
                                 JOIN floors f ON f.id = s.floor_id
                                 WHERE f.location_id = ?
                                 ORDER BY i.invoice_date DESC", [$op['location_id']]));
}

echo '</div></main></body></html>';
