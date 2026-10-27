<?php

function parkEasyPrepareAdminPageData(mysqli $conn): array
{
    $admin = staffAuth($conn, 'admins', 'admin_id', '?page=admin');

    $mallOptions = 'SELECT id, name AS label FROM parking_locations ORDER BY name';
    $floorOptions = "SELECT f.id, CONCAT(l.name, ' - ', f.floor_code) AS label
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
                'location_id' => ['Assigned mall', 'select', true, $mallOptions], 'is_active' => ['Active', 'check'],
            ],
            'list' => 'SELECT o.id, o.name, o.email, l.name AS mall, o.is_active
                       FROM operators o JOIN parking_locations l ON l.id = o.location_id ORDER BY o.name',
        ],
        'rates' => [
            'title' => 'Rates', 'table' => 'parking_rates',
            'fields' => [
                'location_id' => ['Mall', 'select', true, $mallOptions],
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
                'location_id' => ['Mall', 'select', true, $mallOptions], 'floor_code' => ['Floor code', 'text', true],
                'sort_order' => ['Sort order', 'number', true, 0],
            ],
            'list' => 'SELECT f.id, l.name AS mall, f.floor_code, f.sort_order
                       FROM floors f JOIN parking_locations l ON l.id = f.location_id ORDER BY l.name, f.sort_order',
        ],
        'slots' => [
            'title' => 'Parking slots', 'table' => 'parking_slots',
            'fields' => [
                'floor_id' => ['Mall - floor', 'select', true, $floorOptions], 'slot_code' => ['Slot code', 'text', true],
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
    $entity = $entities[$tab];
    $back = '?page=admin&tab=' . $tab . '&table_page=' . staffCurrentPage();

    $options = function (array $field) use ($conn): array {
        $source = $field[3] ?? [];

        return is_string($source)
            ? array_column(staffRows($conn, $source), 'label', 'id')
            : $source;
    };

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrfOk()) {
            flash('!Session expired, try again.');
            redirect($back);
        }

        $id = (int) ($_POST['id'] ?? 0);

        try {
            if (($_POST['action'] ?? '') === 'delete') {
                $statement = $conn->prepare("DELETE FROM `{$entity['table']}` WHERE id = ?");
                $statement->execute([$id]);
                flash($statement->affected_rows ? 'Deleted.' : '!Nothing deleted.');
            } elseif (($_POST['action'] ?? '') === 'save') {
                $columns = [];
                $values = [];
                $error = null;

                foreach ($entity['fields'] as $name => $field) {
                    [$label, $type] = $field;
                    $raw = trim((string) ($_POST[$name] ?? ''));

                    if ($type === 'check') {
                        $value = isset($_POST[$name]) ? 1 : 0;
                    } elseif ($type === 'password') {
                        if ($raw === '' && $id) {
                            continue;
                        }
                        if (strlen($raw) < 8) {
                            $error = "$label must be at least 8 characters.";
                        }
                        $value = password_hash($raw, PASSWORD_DEFAULT);
                    } elseif ($raw === '') {
                        if (!empty($field[2])) {
                            $error = "$label is required.";
                        }
                        $value = null;
                    } else {
                        $value = $raw;
                        if ($type === 'email' && !parkEasyIsValidEmail($raw)) {
                            $error = "$label is not a valid email address.";
                        } elseif ($type === 'email') {
                            $role = $entity['table'] === 'operators' ? 'operator' : 'user';
                            foreach (parkEasyFindAccountsByEmail($conn, $raw) as $identity) {
                                if ($identity['role'] !== $role || (int) $identity['account']['id'] !== $id) {
                                    $error = "$label is already assigned to another account.";
                                    break;
                                }
                            }
                        } elseif ($type === 'number' && !is_numeric($raw)) {
                            $error = "$label must be a number.";
                        } elseif ($type === 'select' && !array_key_exists($raw, $options($field))) {
                            $error = "$label is invalid.";
                        }
                    }

                    $columns[] = $name;
                    $values[] = $value;
                }

                if ($error !== null) {
                    flash('!' . $error);
                } else {
                    if ($id) {
                        $sql = "UPDATE `{$entity['table']}` SET "
                            . implode(', ', array_map(fn($column) => "`$column` = ?", $columns))
                            . ' WHERE id = ?';
                        $values[] = $id;
                    } else {
                        $sql = "INSERT INTO `{$entity['table']}` (`" . implode('`, `', $columns) . '`) VALUES ('
                            . implode(', ', array_fill(0, count($columns), '?')) . ')';
                    }
                    $conn->prepare($sql)->execute($values);
                    flash('Saved.');
                }
            }
        } catch (mysqli_sql_exception $exception) {
            flash($exception->getCode() === 1451
                ? '!Cannot delete: it is still used by other records. Set it inactive instead.'
                : ($exception->getCode() === 1062
                    ? '!That code or email already exists.'
                    : '!Database error, nothing was saved.'));
        }

        redirect($back);
    }

    $editRow = null;
    if (isset($_GET['edit'])) {
        $editRow = staffRows(
            $conn,
            "SELECT * FROM `{$entity['table']}` WHERE id = ?",
            [(int) $_GET['edit']]
        )[0] ?? null;
    }

    return [
        'admin' => $admin,
        'entities' => $entities,
        'tab' => $tab,
        'entity' => $entity,
        'back' => $back,
        'options' => $options,
        'editRow' => $editRow,
        'rows' => staffRows($conn, $entity['list']),
    ];
}
