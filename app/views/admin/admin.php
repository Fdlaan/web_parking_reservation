<?php
// Admin dashboard, reached via ?page=admin (see public/index.php).
// One config entry per CRUD tab: table, form fields, list query.
// Field = [label, type, required, extra]; extra = select options (array or "SELECT id, ... AS label") or default value.

require_once dirname(__DIR__, 2) . '/helpers/staff.php';

$admin = staffAuth($conn, 'admins', 'admin_id', '?page=admin', 'Parkeasy Admin');

$mallOpts = 'SELECT id, name AS label FROM parking_locations ORDER BY name';
$floorOpts = "SELECT f.id, CONCAT(l.name, ' - ', f.floor_code) AS label
              FROM floors f JOIN parking_locations l ON l.id = f.location_id ORDER BY l.name, f.sort_order";

$entities = [
    'malls' => [
        'title' => 'Malls', 'table' => 'parking_locations',
        'fields' => [
            'mall_code' => ['Code', 'text', true], 'name' => ['Name', 'text', true],
            'region' => ['Region', 'text', true], 'address' => ['Address', 'text', true],
            'latitude' => ['Latitude', 'number', true], 'longitude' => ['Longitude', 'number', true],
            'image_url' => ['Image URL', 'text', false], 'is_active' => ['Active', 'check'],
        ],
        'list' => 'SELECT id, mall_code, name, region, address, is_active FROM parking_locations ORDER BY name',
    ],
    'operators' => [
        'title' => 'Operators', 'table' => 'operators',
        'fields' => [
            'name' => ['Name', 'text', true], 'email' => ['Email', 'email', true],
            'password_hash' => ['Password (min 8)', 'password', true],
            'location_id' => ['Assigned mall', 'select', true, $mallOpts], 'is_active' => ['Active', 'check'],
        ],
        'list' => 'SELECT o.id, o.name, o.email, l.name AS mall, o.is_active
                   FROM operators o JOIN parking_locations l ON l.id = o.location_id ORDER BY o.name',
    ],
    'rates' => [
        'title' => 'Rates', 'table' => 'parking_rates',
        'fields' => [
            'location_id' => ['Mall', 'select', true, $mallOpts],
            'parking_type' => ['Type', 'select', true, ['REGULER' => 'REGULER', 'VALET' => 'VALET']],
            'price_per_slot' => ['Price per slot (Rp)', 'number', true], 'admin_fee' => ['Admin fee (Rp)', 'number', true, 2500],
        ],
        'list' => 'SELECT r.id, l.name AS mall, r.parking_type, r.price_per_slot, r.admin_fee
                   FROM parking_rates r JOIN parking_locations l ON l.id = r.location_id ORDER BY l.name, r.parking_type',
    ],
    'areas' => [
        'title' => 'User areas', 'table' => 'user_locations',
        'fields' => [
            'name' => ['Name', 'text', true], 'address' => ['Address', 'text', true],
            'latitude' => ['Latitude', 'number', true], 'longitude' => ['Longitude', 'number', true],
        ],
        'list' => 'SELECT id, name, address, latitude, longitude FROM user_locations ORDER BY name',
    ],
    'users' => [
        'title' => 'Users', 'table' => 'users',
        'fields' => [
            'name' => ['Name', 'text', true], 'email' => ['Email', 'email', true],
            'phone' => ['Phone', 'text', false], 'password_hash' => ['Password (min 8)', 'password', true],
        ],
        'list' => 'SELECT id, name, email, phone, created_at FROM users ORDER BY id DESC',
    ],
    'floors' => [
        'title' => 'Floors', 'table' => 'floors',
        'fields' => [
            'location_id' => ['Mall', 'select', true, $mallOpts], 'floor_code' => ['Floor code', 'text', true],
            'sort_order' => ['Sort order', 'number', true, 0],
        ],
        'list' => 'SELECT f.id, l.name AS mall, f.floor_code, f.sort_order
                   FROM floors f JOIN parking_locations l ON l.id = f.location_id ORDER BY l.name, f.sort_order',
    ],
    'slots' => [
        'title' => 'Parking slots', 'table' => 'parking_slots',
        'fields' => [
            'floor_id' => ['Mall - floor', 'select', true, $floorOpts], 'slot_code' => ['Slot code', 'text', true],
            'parking_type' => ['Type', 'select', true, ['REGULER' => 'REGULER', 'VALET' => 'VALET']],
            'area_block' => ['Area block', 'text', false], 'pos_row' => ['Row', 'number', false],
            'pos_col' => ['Column', 'number', false], 'is_active' => ['Active', 'check'],
        ],
        'list' => 'SELECT s.id, l.name AS mall, f.floor_code AS floor, s.slot_code, s.parking_type, s.area_block, s.is_active
                   FROM parking_slots s JOIN floors f ON f.id = s.floor_id
                   JOIN parking_locations l ON l.id = f.location_id ORDER BY l.name, f.sort_order, s.slot_code',
    ],
];

$tab = isset($entities[$_GET['tab'] ?? '']) ? $_GET['tab'] : 'malls';
$ent = $entities[$tab];
$back = '?page=admin&tab=' . $tab;

/** Resolve a select field's options to [value => label]. */
$options = function (array $f) use ($conn): array {
    $o = $f[3] ?? [];

    return is_string($o) ? array_column(staffRows($conn, $o), 'label', 'id') : $o;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfOk()) {
        flash('!Session expired, try again.');
        redirect($back);
    }

    $id = (int) ($_POST['id'] ?? 0);

    try {
        if (($_POST['action'] ?? '') === 'delete') {
            $st = $conn->prepare("DELETE FROM `{$ent['table']}` WHERE id = ?");
            $st->execute([$id]);
            flash($st->affected_rows ? 'Deleted.' : '!Nothing deleted.');
        } elseif (($_POST['action'] ?? '') === 'save') {
            $cols = [];
            $vals = [];
            $err = null;

            foreach ($ent['fields'] as $name => $f) {
                [$label, $type] = $f;
                $raw = trim((string) ($_POST[$name] ?? ''));

                if ($type === 'check') {
                    $v = isset($_POST[$name]) ? 1 : 0;
                } elseif ($type === 'password') {
                    if ($raw === '' && $id) {
                        continue; // blank on edit = keep current password
                    }
                    if (strlen($raw) < 8) {
                        $err = "$label must be at least 8 characters.";
                    }
                    $v = password_hash($raw, PASSWORD_DEFAULT);
                } elseif ($raw === '') {
                    if (!empty($f[2])) {
                        $err = "$label is required.";
                    }
                    $v = null;
                } else {
                    $v = $raw;
                    if ($type === 'email' && !filter_var($raw, FILTER_VALIDATE_EMAIL)) {
                        $err = "$label is not a valid email.";
                    } elseif ($type === 'number' && !is_numeric($raw)) {
                        $err = "$label must be a number.";
                    } elseif ($type === 'select' && !array_key_exists($raw, $options($f))) {
                        $err = "$label is invalid.";
                    }
                }
                $cols[] = $name;
                $vals[] = $v;
            }

            if ($err) {
                flash('!' . $err);
            } else {
                if ($id) {
                    $sql = "UPDATE `{$ent['table']}` SET " . implode(', ', array_map(fn($c) => "`$c` = ?", $cols)) . ' WHERE id = ?';
                    $vals[] = $id;
                } else {
                    $sql = "INSERT INTO `{$ent['table']}` (`" . implode('`, `', $cols) . '`) VALUES ('
                        . implode(', ', array_fill(0, count($cols), '?')) . ')';
                }
                $conn->prepare($sql)->execute($vals);
                flash('Saved.');
            }
        }
    } catch (mysqli_sql_exception $ex) {
        // 1451 = still referenced (e.g. reservations), 1062 = duplicate unique value
        flash($ex->getCode() === 1451
            ? '!Cannot delete: it is still used by other records. Set it inactive instead.'
            : ($ex->getCode() === 1062 ? '!That code or email already exists.' : '!Database error, nothing was saved.'));
    }
    redirect($back);
}

$editRow = null;
if (isset($_GET['edit'])) {
    $editRow = staffRows($conn, "SELECT * FROM `{$ent['table']}` WHERE id = ?", [(int) $_GET['edit']])[0] ?? null;
}

staffHead('Admin | Parkeasy');
staffBar('Parkeasy Admin', array_map(fn($x) => $x['title'], $entities), $tab, '?page=admin&tab=', $admin['name']);

echo '<div class="card"><h2>' . ($editRow ? 'Edit' : 'Add') . ' ' . e(strtolower($ent['title'])) . '</h2>';
echo '<form method="post" action="' . e($back) . '">' . csrfField() . '<input type="hidden" name="action" value="save">';
echo '<input type="hidden" name="id" value="' . (int) ($editRow['id'] ?? 0) . '"><div class="grid">';

foreach ($ent['fields'] as $name => $f) {
    $type = $f[1];
    $val = $editRow[$name] ?? ($type === 'check' ? 1 : ($type === 'number' ? ($f[3] ?? '') : ''));
    $req = !empty($f[2]) && !($type === 'password' && $editRow) ? ' required' : '';

    echo '<div>';
    if ($type === 'check') {
        echo '<label><input type="checkbox" name="' . $name . '"' . ($val ? ' checked' : '') . '> ' . e($f[0]) . '</label>';
    } elseif ($type === 'select') {
        echo '<label>' . e($f[0]) . '<select name="' . $name . '"' . $req . '><option value="">-- choose --</option>';
        foreach ($options($f) as $k => $label) {
            echo '<option value="' . e($k) . '"' . ((string) $k === (string) $val ? ' selected' : '') . '>' . e($label) . '</option>';
        }
        echo '</select></label>';
    } elseif ($type === 'password') {
        echo '<label>' . e($f[0]) . ($editRow ? ' - blank keeps current' : '')
            . '<input type="password" name="' . $name . '" autocomplete="new-password"' . $req . '></label>';
    } else {
        echo '<label>' . e($f[0]) . '<input type="' . $type . '"' . ($type === 'number' ? ' step="any"' : '')
            . ' name="' . $name . '" value="' . e($val) . '"' . $req . '></label>';
    }
    echo '</div>';
}

echo '</div><button>' . ($editRow ? 'Update' : 'Create') . '</button>';
echo $editRow ? ' <a class="btn" href="' . e($back) . '" style="background:none;color:inherit;border:1px solid var(--line)">Cancel</a>' : '';
echo '</form></div>';

echo '<div class="card"><h2>' . e($ent['title']) . '</h2>';
staffTable(staffRows($conn, $ent['list']), function ($r) use ($back) {
    return '<a class="btn" href="' . e($back . '&edit=' . $r['id']) . '">Edit</a> '
        . '<form method="post" action="' . e($back) . '" onsubmit="return confirm(\'Delete this row?\')">' . csrfField()
        . '<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="' . (int) $r['id']
        . '"><button class="red">Delete</button></form>';
});
echo '</div></main></body></html>';
