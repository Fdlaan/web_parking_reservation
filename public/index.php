<?php

// kite panggil dummy data dulu, nanti tinggal hapus kalau sudah connect ke database
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/config/app.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/helpers/dummy-data.php';

$dummyDataPath = $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy_data/';

$locations = require $dummyDataPath . 'locations.php';
$malls = require $dummyDataPath . 'malls.php';
$reservations = require $dummyDataPath . 'reservations.php';

if (!isset($_SESSION['demo_users'])) {
    $_SESSION['demo_users'] = require $dummyDataPath . 'users.php';
}
    
$users = &$_SESSION['demo_users'];

// kalau mau test user lain
$currentUserId = (int) ($_SESSION['user_id'] ?? 1);

$user = $users[$currentUserId] ?? [];

if (empty($user) && !empty($users)) {
    $user = reset($users);
}

$currentUserId = (int) ($user['id'] ?? 0);

$selectedLocation = parkEasyFindLocation(
    (int) ($user['location_id'] ?? 0),
    $locations
);

// echo '<pre>';
// var_dump([
//     'current_user_id' => $currentUserId,
//     'username' => $user['username'] ?? null,
//     'location_id' => $user['location_id'] ?? null,
//     'selected_location' => $selectedLocation['address'] ?? null,
// ]);
// echo '</pre>';

if ($selectedLocation === null) {
    $selectedLocation = $locations[0] ?? null;
}   

// Router buat ngatur halaman
$page = $_GET['page'] ?? 'home';

$routes = [
    'home' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/home/index.php',
    'parking' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/index.php',
    'history' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/history/index.php',
    'profile' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/profile/index.php',
    'login' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/login.php',
    'detail' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/detail.php',
    'slot' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/slot.php',
    'invoice' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/invoice.php',
    'payment' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/payment.php',
];

$show_layout = ($page !== 'login');

if (!isset($routes[$page])) {
    http_response_code(400);
    $title_page = 'Page not Found | Parkeasy';
    $file_css = [];
    $file_js = [];

    ob_start();
    echo '<main class="main-content">';
    echo '<h1>400 - Page not found</h1>';
    echo '<p>The page you requested could not be found.</p>';
    echo '<a href="?page=home">Back to home.</a>';
    echo '</main>';
    $page_content = ob_get_clean();

    $show_layout = false;

} else {
    $currentPage = $page;
    $show_layout = ($page !== 'login');

    $breadcrumb = [
        [
            'label' => ucfirst($page),
            'url' => null,
        ],
    ];

    $title_page = 'Parkeasy';
    $file_css = [];
    $file_js = [];

    ob_start();
    require $routes[$page];
    $page_content = ob_get_clean();

    if ($show_layout) {
        $file_css = array_merge($file_css, [
            '/components/icon.css',
            '/layouts/sidebar.css',
            '/layouts/topbar.css',
            '/layouts/footer.css',
        ]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<!-- Head Section -->
<?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/includes/head.php'; ?>

<body>

<?php if ($show_layout): ?>

<div class="layout d-flex align-items-stretch">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/layouts/sidebar.php'; ?>
    
    <div class="content d-flex flex-column">

    <?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/layouts/topbar.php'; ?>

        <?= $page_content ?>

    </div>
</div>

<?php else: ?>
    
    <?= $page_content ?>

<?php endif; ?>
    
</body>
</html>