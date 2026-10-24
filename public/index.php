<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/config/app.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/helpers/dummy-data.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/helpers/auth.app';

$dummyDataPath = $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy_data/';

$locations = require $dummyDataPath . 'locations.php';
$malls = require $dummyDataPath . 'malls.php';
$reservations = require $dummyDataPath . 'reservations.php';

if (!isset($_SESSION['demo_users'])) {
    $_SESSION['demo_users'] = require $dummyDataPath . 'users.php';
}

$users = &$_SESSION['demo_users'];

$page = $_GET['page'] ?? 'home';
$step = $_GET['step'] ?? 'login';
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($page === 'logout') {
    parkEasyLogout();

    header('Location: ?page=auth&step=login');
    exit;
}

if ($requestMethod === 'POST' && $page === 'auth' && $step === 'login') {
    $matchedUser = parkEasyFindUserByEmail(trim($_POST['email'] ?? ''), $users);

    if ($matchedUser === null) {
        $_SESSION['login_message'] = 'Email is not registered.';

        header('Location: ?page=auth&step=login');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $matchedUser['id'];

    header('Location: ?page=home');
    exit;
}

$currentUserId = parkEasyCurrentUserId() ?? 0;
$user = $users[$currentUserId] ?? null;
$isLoggedIn = $user !== null;

if (!$isLoggedIn && $page !== 'auth') {
    unset($_SESSION['user_id']);

    header('Location: ?page=auth&step=login');
    exit;
}

if ($isLoggedIn && $page === 'auth' && $step === 'login') {
    header('Location: ?page=home');
    exit;
}

$selectedLocation = parkEasyFindLocation(
    (int) ($user['location_id'] ?? 0),
    $locations
);

if ($selectedLocation === null) {
    $selectedLocation = $locations[0] ?? null;
}

if (
    $requestMethod === 'POST'
    && $page === 'profile'
    && ($_POST['profile_action'] ?? '') === 'save'
) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($user['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $plateNumber = trim($_POST['plate_number'] ?? '');

    $errors = [];

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    }

    if ($plateNumber === '') {
        $errors[] = 'Plate number is required.';
    }

    if (empty($errors)) {
        $users[$currentUserId]['username'] = $username;
        $users[$currentUserId]['email'] = $email;
        $users[$currentUserId]['phone'] = $phone;
        $users[$currentUserId]['plate_number'] = $plateNumber;
        $users[$currentUserId]['updated_at'] = date('Y-m-d H:i:s');

        $_SESSION['profile_message'] = 'Profile updated successfully.';
    } else {
        $_SESSION['profile_message'] = implode(' ', $errors);
    }

    header('Location: ?page=profile');
    exit;
}

$routes = [
    'home' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/home/index.php',
    'parking' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/index.php',
    'history' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/history/index.php',
    'profile' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/profile/index.php',
    'auth' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/index.php',
    'detail' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/detail.php',
    'slot' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/slot.php',
    'invoice' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/invoice.php',
    'payment' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/payment.php',
];

if (!isset($routes[$page])) {
    http_response_code(400);

    $titlePage = 'Page not Found | Parkeasy';
    $fileCss = [];
    $fileJs = [];
    $showLayout = false;

    ob_start();
    echo '<main class="main-content">';
    echo '<h1>400 - Page not found</h1>';
    echo '<p>The page you requested could not be found.</p>';
    echo '<a href="?page=home">Back to home.</a>';
    echo '</main>';
    $pageContent = ob_get_clean();
} else {
    $currentPage = $page;
    $showLayout = $page !== 'auth';

    $breadcrumb = [
        [
            'label' => ucfirst($page),
            'url' => null,
        ],
    ];

    $titlePage = 'Parkeasy';
    $fileCss = [];
    $fileJs = [];

    ob_start();
    require $routes[$page];
    $pageContent = ob_get_clean();

    if ($showLayout) {
        $fileCss = array_merge($fileCss, [
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

<?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/includes/head.php'; ?>

<body>

<?php if ($showLayout): ?>

<div class="layout d-flex align-items-stretch">
    <?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/layouts/sidebar.php'; ?>

    <div class="content d-flex flex-column">

        <?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/layouts/topbar.php'; ?>

        <?= $pageContent ?>

    </div>
</div>

<?php else: ?>

    <?= $pageContent ?>

<?php endif; ?>

</body>
</html>