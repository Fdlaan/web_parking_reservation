<?php
// Shared by the admin and operator dashboards: DB, session login, CSRF, layout.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/config/database.php'; // defines $conn (mysqli)

function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

function csrfField(): string
{
    $_SESSION['csrf'] ??= bin2hex(random_bytes(16));

    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

function csrfOk(): bool
{
    return hash_equals($_SESSION['csrf'] ?? '', (string) ($_POST['csrf'] ?? ''));
}

function flash(?string $msg = null): ?string
{
    if ($msg !== null) {
        $_SESSION['flash'] = $msg;
        return null;
    }
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $m;
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Requires an active staff session and returns the logged-in row.
 * Guests are redirected to the shared role-aware login form.
 * $table is a hardcoded string from the caller, never user input.
 */
function staffAuth(mysqli $conn, string $table, string $sessKey, string $self): array
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfOk()) {
        if (($_POST['action'] ?? '') === 'logout') {
            unset($_SESSION['user_id'], $_SESSION['operator_id'], $_SESSION['admin_id']);
            session_regenerate_id(true);
            redirect($self);
        }
    }

    if (isset($_SESSION[$sessKey])) {
        $st = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
        $st->execute([$_SESSION[$sessKey]]);
        $row = $st->get_result()->fetch_assoc();

        if ($row && ($row['is_active'] ?? 1)) {
            return $row;
        }
        unset($_SESSION[$sessKey]);
    }

    $role = $table === 'admins' ? 'admin' : 'operator';
    redirect('?page=auth&step=login&return=' . $role);
}

function staffHead(string $title): void
{
    $assetUrl = $GLOBALS['assetUrl'] ?? '';
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<title>' . e($title) . '</title>'
        . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">'
        . '<link rel="stylesheet" href="' . e($assetUrl . '/css/base.css') . '">'
        . '<link rel="stylesheet" href="' . e($assetUrl . '/css/components/confirmation-dialog.css') . '">'
        . '<link rel="stylesheet" href="' . e($assetUrl . '/css/components/icon.css') . '">'
        . '<link rel="stylesheet" href="' . e($assetUrl . '/css/layouts/sidebar.css') . '">'
        . '<link rel="stylesheet" href="' . e($assetUrl . '/css/layouts/topbar.css') . '">'
        . '<link rel="stylesheet" href="' . e($assetUrl . '/css/pages/staff.css') . '">'
        . '<script src="' . e($assetUrl . '/js/layouts/sidebar.js') . '" defer></script>'
        . '<script src="' . e($assetUrl . '/js/layouts/confirm-actions.js') . '" defer></script></head><body class="staff-dashboard">';
}

/** Shared role layout with a role-specific navigation set. */
function staffBar(string $brand, array $nav, string $active, string $self, string $who): void
{
    $isAdmin = str_starts_with($self, '?page=admin');
    $roleBrand = $isAdmin ? 'Parkeasy Admin' : 'Parkeasy Operator';
    $sidebarBrand = $roleBrand;
    $sidebarHomeUrl = $self . array_key_first($nav);
    $sidebarLogoutUrl = $self . $active;
    $sidebarLogoutForm = true;
    $currentPage = $active;
    $sidebarItems = [];
    $staffIcons = [
        'malls' => 'location-icon',
        'operators' => 'profile-icon',
        'rates' => 'rate-icon',
        'areas' => 'maps-icon',
        'users' => 'users-icon',
        'floors' => 'floors-icon',
        'slots' => 'parking-icon',
        'reservations' => 'reservation-icon',
        'invoices' => 'invoice-icon',
    ];

    foreach ($nav as $key => $label) {
        $sidebarItems[] = [
            'label' => $label,
            'page' => $key,
            'url' => $self . $key,
            'icon' => $staffIcons[$key] ?? 'history-icon',
        ];
    }

    $user = ['name' => $who, 'avatar_url' => null];
    $topbarProfileUrl = $sidebarHomeUrl;
    $topbarShowAvatar = false;
    $topbarRole = $isAdmin ? 'ADMIN' : 'OPERATOR';
    $breadcrumb = [['label' => $brand], ['label' => $nav[$active] ?? $roleBrand]];
    $layoutRoot = dirname(__DIR__) . '/views/layouts/';

    echo '<div class="layout d-flex align-items-stretch staff-layout">';
    require $layoutRoot . 'sidebar.php';
    echo '<div class="content d-flex flex-column">';
    require $layoutRoot . 'topbar.php';
    echo '<main class="main-content">';

    if ($m = flash()) {
        echo '<span class="msg' . (str_starts_with($m, '!') ? ' err">' . e(substr($m, 1)) : '">' . e($m)) . '</span>';
    }
}

function staffPageUrl(int $page): string
{
    $query = $_GET;
    unset($query['edit']);
    $query['table_page'] = max(1, $page);

    return '?' . http_build_query($query);
}

function staffCurrentPage(): int
{
    $page = filter_var($_GET['table_page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    return $page === false ? 1 : $page;
}

/** Renders paginated table rows; $actions(row) returns extra HTML for the last cell. Hidden columns start with "_". */
function staffTable(array $rows, ?callable $actions = null): void
{
    if (!$rows) {
        echo '<div class="staff-table-shell"><div class="staff-table-empty">'
            . '<span>Nothing here yet.</span></div></div>';
        return;
    }

    $pageSize = 10;
    $totalRows = count($rows);
    $totalPages = (int) ceil($totalRows / $pageSize);
    $currentPage = min(staffCurrentPage(), $totalPages);
    $visibleRows = array_slice($rows, ($currentPage - 1) * $pageSize, $pageSize);
    $cols = array_filter(array_keys($rows[0]), fn($c) => $c[0] !== '_');
    echo '<div class="staff-table-shell"><div class="staff-table-wrapper">'
        . '<table class="staff-table"><thead><tr>';
    foreach ($cols as $c) {
        echo '<th>' . e(str_replace('_', ' ', $c)) . '</th>';
    }
    echo $actions ? '<th>Actions</th>' : '', '</tr></thead><tbody>';
    foreach ($visibleRows as $r) {
        echo '<tr>';
        foreach ($cols as $c) {
            $value = $r[$c] ?? '';
            if ($c === 'status' && $value !== '') {
                echo '<td><span class="staff-status staff-status-' . e(strtolower((string) $value)) . '">'
                    . e($value) . '</span></td>';
            } else {
                echo '<td>' . e($value) . '</td>';
            }
        }
        echo $actions ? '<td>' . $actions($r) . '</td>' : '', '</tr>';
    }
    echo '</tbody></table></div>';

    if ($totalPages > 1) {
        $startPage = max(1, min($currentPage - 2, $totalPages - 4));
        $endPage = min($totalPages, $startPage + 4);
        echo '<div class="staff-table-footer"><span class="staff-pagination-summary">Showing '
            . (($currentPage - 1) * $pageSize + 1) . '-'
            . min($currentPage * $pageSize, $totalRows) . ' of ' . $totalRows
            . '</span><nav class="staff-pagination" aria-label="Table pagination">';

        if ($currentPage > 1) {
            echo '<a class="staff-page-arrow" href="' . e(staffPageUrl($currentPage - 1))
                . '" aria-label="Previous page"><i class="prev-icon" aria-hidden="true"></i></a>';
        } else {
            echo '<span class="staff-page-arrow is-disabled" aria-hidden="true"><i class="prev-icon"></i></span>';
        }

        echo '<div class="staff-pagination-links">';
        for ($page = $startPage; $page <= $endPage; $page++) {
            $current = $page === $currentPage;
            echo '<a href="' . e(staffPageUrl($page)) . '"'
                . ($current ? ' class="current" aria-current="page"' : '')
                . ' aria-label="Page ' . $page . '">' . $page . '</a>';
        }
        echo '</div>';

        if ($currentPage < $totalPages) {
            echo '<a class="staff-page-arrow" href="' . e(staffPageUrl($currentPage + 1))
                . '" aria-label="Next page"><i class="next-icon" aria-hidden="true"></i></a>';
        } else {
            echo '<span class="staff-page-arrow is-disabled" aria-hidden="true"><i class="next-icon"></i></span>';
        }

        echo '</nav></div>';
    }

    echo '</div>';
}

function staffRows(mysqli $conn, string $sql, array $params = []): array
{
    $st = $conn->prepare($sql);
    $st->execute($params);

    return $st->get_result()->fetch_all(MYSQLI_ASSOC);
}
