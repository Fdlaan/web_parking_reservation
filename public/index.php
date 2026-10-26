<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation';

require_once $projectRoot . '/app/config/app.php';
require_once $projectRoot . '/app/helpers/staff.php'; // $conn, flash(), csrf helpers, redirect()
require_once $projectRoot . '/app/helpers/lookup.php';
require_once $projectRoot . '/app/helpers/auth.php';
require_once $projectRoot . '/app/helpers/parking-functions.php';
require_once $projectRoot . '/app/helpers/reservations.php';
require_once $projectRoot . '/database/DatabaseDataProvider.php';
require_once $projectRoot . '/app/controllers/ParkingController.php';
require_once $projectRoot . '/app/controllers/HomeController.php';
require_once $projectRoot . '/app/controllers/HistoryController.php';
require_once $projectRoot . '/app/controllers/AuthController.php';
require_once $projectRoot . '/app/controllers/ProfileController.php';
require_once $projectRoot . '/app/models/Mall.php';

// Runs on every request, so slots free up without a cron job (also used by the operator dashboard).
parkEasyExpireReservations($conn);

// Staff dashboards have their own login.
if (in_array($_GET['page'] ?? '', ['admin', 'operator'], true)) {
    require $projectRoot . '/app/views/' . $_GET['page'] . '/' . $_GET['page'] . '.php';
    exit;
}

$page = $_GET['page'] ?? 'home';
$step = $_GET['step'] ?? 'login';
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST' && !csrfOk()) {
    flash('!Your session expired. Please try again.');
    redirect($page === 'auth' ? '?page=auth&step=' . urlencode($step) : '?page=home');
}

parkEasyHandleAuthRequest($conn, $page, $step, $requestMethod);

$currentUserId = parkEasyCurrentUserId() ?? 0;
$user = $currentUserId ? parkEasyFindUserById($conn, $currentUserId) : null;
$isLoggedIn = $user !== null;

if (!$isLoggedIn && $page !== 'auth') {
    unset($_SESSION['user_id']);
    redirect('?page=auth&step=login');
}

if ($isLoggedIn && $page === 'auth') {
    redirect('?page=home');
}

if ($isLoggedIn && $requestMethod === 'POST') {
    match ($page) {
        'reserve' => parkEasyActionReserve($conn, $currentUserId),
        'check-payment' => parkEasyActionPay($conn, $currentUserId),
        'cancel-reservation' => parkEasyActionCancel($conn, $currentUserId),
        'set-location' => parkEasyActionSetLocation($conn),
        'profile' => parkEasyHandleProfileUpdate($conn, $currentUserId),
        default => null,
    };
}

$data = $isLoggedIn ? parkEasyLoadDatabaseData($conn, $currentUserId, $assetUrl) : [];
$userLocations = $data['userLocations'] ?? [];
$parkingMalls = $data['parkingMalls'] ?? [];
$parkingRates = $data['parkingRates'] ?? [];
$parkingFloors = $data['parkingFloors'] ?? [];
$parkingSlots = $data['parkingSlots'] ?? [];
$reservations = $data['reservations'] ?? [];
$vehicles = $data['vehicles'] ?? [];

$selectedLocation = parkEasyFindLocation((int) ($_SESSION['location_id'] ?? 0), $userLocations)
    ?? ($userLocations[0] ?? null);

// Prepare parking page data outside the view templates.
if ($page === 'slot') {
    $slotPageData = parkEasyPrepareSlotPageData(
        $_GET,
        $parkingMalls,
        $parkingRates,
        $parkingFloors,
        $parkingSlots,
        $reservations
    );

    if ($slotPageData['error'] !== null) {
        http_response_code(404);
    } else {
        $selectedMall = $slotPageData['mall'];
        $parkingType = $slotPageData['parking_type'];
        $selectedRate = $slotPageData['rate'];
        $selectedPrice = $slotPageData['selected_price'];
        $selectedAdminFee = $slotPageData['selected_admin_fee'];
        $parkingPageFloors = $slotPageData['floors'];
        $selectedFloor = $slotPageData['selected_floor'];
        $parkingPageSlots = $slotPageData['slots'];
        $floorAvailability = $slotPageData['availability'];
    }
}

if ($page === 'detail') {
    $detailPageData = parkEasyPrepareMallDetailData(
        trim((string) ($_GET['mall_id'] ?? '')),
        $selectedLocation,
        $parkingMalls,
        $parkingRates,
        $parkingFloors,
        $parkingSlots,
        $reservations
    );

    if ($detailPageData['error'] !== null) {
        http_response_code(404);
    } else {
        $selectedMall = $detailPageData['mall'];
        $regularRate = $detailPageData['regular_rate'];
        $valetRate = $detailPageData['valet_rate'];
        $distanceLabel = $detailPageData['distance_label'];
        $floorAvailability = $detailPageData['floor_availability'];
        $mapsUrl = $detailPageData['maps_url'];
        $chooseSpotUrl = $detailPageData['choose_spot_url'];
    }
}

// Prepare page data in controllers. View files only render the prepared values.
if ($page === 'home') {
    $homePageData = parkEasyPrepareHomePageData(
        $currentUserId,
        $selectedLocation,
        $parkingMalls,
        $parkingRates,
        $reservations
    );

    $recommendedParking = $homePageData['recommendedParking'];
    $previouslyParking = $homePageData['previouslyParking'];
    $reservation = $homePageData['reservation'];
    $reservationStatus = $homePageData['reservationStatus'];
    $canCancel = $homePageData['canCancel'];
}

if ($page === 'parking') {
    $parkingIndexData = parkEasyPrepareParkingIndexData(
        $selectedLocation,
        $parkingMalls,
        $parkingRates,
        $_GET
    );
    $mallsByCity = $parkingIndexData['malls_by_city'];
}

// Payment and invoice both show one reservation of the logged-in user.
if ($page === 'payment' || $page === 'invoice') {
    $res = parkEasyLoadReservation($conn, (int) ($_GET['reservation_id'] ?? 0), $currentUserId);

    if ($res === null) {
        http_response_code(404);
    } elseif ($page === 'payment' && $res['status'] !== 'UNPAID') {
        redirect('?page=invoice&reservation_id=' . $res['id']); // nothing left to pay
    } else {
        $selectedMall = ['mall_id' => $res['mall_id'], 'name' => $res['mall_name']];
    }
}

if ($page === 'history') {
    $historyPageData = parkEasyPrepareHistoryPageData(
        $currentUserId,
        $reservations,
        $parkingMalls,
        $_GET
    );

    $rows = $historyPageData['rows'];
    $statusLabels = $historyPageData['statusLabels'];
    $totalPages = $historyPageData['totalPages'];
    $historyPage = $historyPageData['historyPage'];
    $paginatedRows = $historyPageData['paginatedRows'];
    $pageNumbers = $historyPageData['pageNumbers'];
    $months = $historyPageData['months'];
}

if ($page === 'profile') {
    $profilePageData = parkEasyPrepareProfilePageData($user, $assetUrl);
    $avatarUrl = $profilePageData['avatarUrl'];
    $profileFields = $profilePageData['profileFields'];
}

$routes = [
    'home' => $projectRoot . '/app/views/pages/home/index.php',
    'parking' => $projectRoot . '/app/views/pages/parking/index.php',
    'history' => $projectRoot . '/app/views/pages/history/index.php',
    'profile' => $projectRoot . '/app/views/pages/profile/index.php',
    'auth' => $projectRoot . '/app/views/pages/auth/index.php',
    'detail' => $projectRoot . '/app/views/pages/parking/detail.php',
    'slot' => $projectRoot . '/app/views/pages/parking/slot.php',
    'invoice' => $projectRoot . '/app/views/pages/parking/invoice.php',
    'payment' => $projectRoot . '/app/views/pages/parking/payment.php',
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

    $breadcrumb = [];

    $parkingMallId = trim((string) ($_GET['mall_id'] ?? ''));
    $parkingMallName = $selectedMall['name'] ?? null;

    // breadcrumb based on current page
    switch ($page) {
        case 'home':
            $breadcrumb = [
                [
                    'label' => 'Home',
                    'url' => null,
                ],
            ];
            break;

        case 'parking':
            $breadcrumb = [
                [
                    'label' => 'Search Park',
                    'url' => null,
                ],
            ];
            break;

        case 'detail':

        case 'slot':
            $parkingMallName = $selectedMall['name'] ?? null;

            $breadcrumb = [
                [
                    'label' => 'Search Park',
                    'url' => '?page=parking',
                ],
            ];

            if ($parkingMallName !== null) {
                $breadcrumb[] = [
                    'label' => $parkingMallName,
                    'url' => '?page=detail&mall_id=' . rawurlencode(
                        (string) $selectedMall['mall_id']
                    ),
                ];
            }

            if ($page === 'slot') {
                $breadcrumb[] = [
                    'label' => 'Select Parking Area',
                    'url' => null,
                ];
            } elseif (!empty($breadcrumb)) {
                $breadcrumb[count($breadcrumb) - 1]['url'] = null;
            }
            break;

        case 'payment':
            $breadcrumb = [
                ['label' => 'Search Park', 'url' => '?page=parking'],
            ];

            if (isset($res)) {
                $breadcrumb[] = [
                    'label' => $res['mall_name'],
                    'url' => '?page=detail&mall_id=' . rawurlencode((string) $res['mall_id']),
                ];
            }

            $breadcrumb[] = ['label' => 'Payment', 'url' => null];
            break;

        case 'invoice':
            $breadcrumb = [
                ['label' => 'History', 'url' => '?page=history'],
                ['label' => 'Invoice', 'url' => null],
            ];
            break;

        case 'history':
            $breadcrumb = [
                [
                    'label' => 'History',
                    'url' => null,
                ],
            ];
            break;

        case 'profile':
            $breadcrumb = [
                [
                    'label' => 'Profile',
                    'url' => null,
                ],
            ];
            break;

        default:
            $breadcrumb = [
                [
                    'label' => 'Home',
                    'url' => '?page=home',
                ],
                [
                    'label' => ucfirst($page),
                    'url' => null,
                ],
            ];
            break;
    }

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

<?php require $projectRoot . '/app/views/includes/head.php'; ?>

<body>

<?php require $projectRoot . '/app/views/components/alerts.php'; ?>

<?php if ($showLayout): ?>

<div class="layout d-flex align-items-stretch">
    <?php require $projectRoot . '/app/views/layouts/sidebar.php'; ?>

    <div class="content d-flex flex-column">

        <?php require $projectRoot . '/app/views/layouts/topbar.php'; ?>

        <?= $pageContent ?>

    </div>
</div>

<?php else: ?>

    <?= $pageContent ?>

<?php endif; ?>

</body>
</html>