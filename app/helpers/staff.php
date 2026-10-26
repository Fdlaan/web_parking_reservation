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
 * Handles logout + login for one staff table and returns the logged-in row.
 * Shows the login form and exits when nobody is logged in.
 * $table is a hardcoded string from the caller, never user input.
 */
function staffAuth(mysqli $conn, string $table, string $sessKey, string $self, string $title): array
{
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfOk()) {
        if (($_POST['action'] ?? '') === 'logout') {
            unset($_SESSION[$sessKey]);
            redirect($self);
        }

        if (($_POST['action'] ?? '') === 'login') {
            $st = $conn->prepare("SELECT * FROM `$table` WHERE email = ?");
            $st->execute([trim((string) ($_POST['email'] ?? ''))]);
            $row = $st->get_result()->fetch_assoc();

            $ok = $row
                && password_verify((string) ($_POST['password'] ?? ''), $row['password_hash'])
                && ($row['is_active'] ?? 1); // admins have no is_active column

            if ($ok) {
                session_regenerate_id(true);
                $_SESSION[$sessKey] = (int) $row['id'];
                redirect($self);
            }
            $error = 'Wrong email or password.';
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

    staffHead($title);
    echo '<form method="post" class="card" style="max-width:340px;margin:12vh auto">'
        . '<h2>' . e($title) . '</h2>'
        . ($error ? '<p class="msg err">' . e($error) . '</p>' : '')
        . csrfField() . '<input type="hidden" name="action" value="login">'
        . '<label>Email<input type="email" name="email" required></label>'
        . '<label>Password<input type="password" name="password" required></label>'
        . '<button>Log in</button></form></body></html>';
    exit;
}

function staffHead(string $title): void
{
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<title>' . e($title) . '</title><style>
:root{--bg:#1e1e1e;--card:#2c2c2c;--line:#454545;--text:#f4f2eb;--mute:#b5b5ad;--gold:#d9b061;--ok:#79d5a2;--bad:#f19c9c}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:14px/1.5 Lato,Inter,sans-serif}
header{display:flex;gap:16px;align-items:center;padding:12px 24px;background:#181818;flex-wrap:wrap}
header b{color:var(--gold);font-size:18px}nav{display:flex;gap:4px;flex:1;flex-wrap:wrap}
nav a{color:var(--mute);padding:6px 12px;border-radius:6px;text-decoration:none}
nav a.on,nav a:hover{background:#3d3527;color:var(--gold)}
main{padding:24px;max-width:1200px;margin:auto}h2{margin:0 0 12px}
.card{background:var(--card);border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:20px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px}
label{display:block;font-size:12px;color:var(--mute)}
input,select{width:100%;margin:2px 0 8px;padding:8px;border-radius:6px;border:1px solid var(--line);background:#fff;color:#222}
input[type=checkbox]{width:auto}
button,.btn{background:var(--gold);color:#222;border:0;border-radius:6px;padding:7px 14px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block}
button.red{background:#470909;color:var(--bad)}button.link{background:none;color:var(--text);border:1px solid var(--line)}
.tbl{overflow-x:auto}table{width:100%;border-collapse:collapse}
th,td{text-align:left;padding:8px;border-bottom:1px solid var(--line);white-space:nowrap}th{color:var(--mute);font-weight:500}
td form{display:inline}.msg{padding:8px 12px;border-radius:6px;background:#173d2b;color:var(--ok)}.msg.err{background:#470909;color:var(--bad)}
</style></head><body>';
}

/** Top bar. $nav = ['key' => 'Label'], links go to $self . key. */
function staffBar(string $brand, array $nav, string $active, string $self, string $who): void
{
    echo '<header><b>' . e($brand) . '</b><nav>';
    foreach ($nav as $key => $label) {
        echo '<a href="' . e($self . $key) . '"' . ($key === $active ? ' class="on"' : '') . '>' . e($label) . '</a>';
    }
    echo '</nav><span>' . e($who) . '</span><form method="post">' . csrfField()
        . '<input type="hidden" name="action" value="logout"><button class="link">Log out</button></form></header><main>';

    if ($m = flash()) {
        echo '<p class="msg' . (str_starts_with($m, '!') ? ' err">' . e(substr($m, 1)) : '">' . e($m)) . '</p>';
    }
}

/** Renders rows as a table; $actions(row) returns extra HTML for the last cell. Hidden columns start with "_". */
function staffTable(array $rows, ?callable $actions = null): void
{
    if (!$rows) {
        echo '<p style="color:var(--mute)">Nothing here yet.</p>';
        return;
    }
    $cols = array_filter(array_keys($rows[0]), fn($c) => $c[0] !== '_');
    echo '<div class="tbl"><table><tr>';
    foreach ($cols as $c) {
        echo '<th>' . e(str_replace('_', ' ', $c)) . '</th>';
    }
    echo $actions ? '<th></th>' : '', '</tr>';
    foreach ($rows as $r) {
        echo '<tr>';
        foreach ($cols as $c) {
            echo '<td>' . e($r[$c] ?? '') . '</td>';
        }
        echo $actions ? '<td>' . $actions($r) . '</td>' : '', '</tr>';
    }
    echo '</table></div>';
}

function staffRows(mysqli $conn, string $sql, array $params = []): array
{
    $st = $conn->prepare($sql);
    $st->execute($params);

    return $st->get_result()->fetch_all(MYSQLI_ASSOC);
}
