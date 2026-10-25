<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation';

require_once $projectRoot . '/app/config/app.php';
require_once $projectRoot . '/app/helpers/dummy-data.php';
require_once $projectRoot . '/app/helpers/auth.php';
require_once $projectRoot . '/app/helpers/parking-functions.php';
require_once $projectRoot . '/app/controllers/ParkingController.php';
require_once $projectRoot . '/app/controllers/HomeController.php';
require_once $projectRoot . '/app/controllers/HistoryController.php';
require_once $projectRoot . '/app/controllers/AuthController.php';
require_once $projectRoot . '/app/controllers/ProfileController.php';
require_once $projectRoot . '/app/models/Mall.php';

/*
 * Change this value when the real database provider is ready.
 * Page templates and controllers consume the same variable names regardless
 * of where the data comes from.
 */
$dataSource = 'dummy';

if ($dataSource === 'dummy') {
    $dummyDataPath = $projectRoot . '/database/dummy-data/';

    $userLocations = require $dummyDataPath . 'user-locations.php';
    $parkingMalls = require $dummyDataPath . 'parking-malls.php';
    $parkingRates = require $dummyDataPath . 'parking-rates.php';
    $parkingFloors = require $dummyDataPath . 'parking-floors.php';
    $parkingSlots = require $dummyDataPath . 'parking-slots.php';
    $reservations = require $dummyDataPath . 'reservations.php';
    $vehicles = require $dummyDataPath . 'vehicles.php';

    if (!isset($_SESSION['demo_users'])) {
        $_SESSION['demo_users'] = require $dummyDataPath . 'users.php';
    }

    $users = &$_SESSION['demo_users'];
} elseif ($dataSource === 'database') {
    /*
     * Database provider must return the same dataset contract as the dummy
     * source above. Implement it here when the real schema is ready, then
     * switch only $dataSource to 'database'.
     */
    require_once $projectRoot . '/app/config/database.php';
    require_once $projectRoot . '/database/DatabaseDataProvider.php';

    $databaseData = parkEasyLoadDatabaseData($conn);
    $userLocations = $databaseData['userLocations'];
    $parkingMalls = $databaseData['parkingMalls'];
    $parkingRates = $databaseData['parkingRates'];
    $parkingFloors = $databaseData['parkingFloors'];
    $parkingSlots = $databaseData['parkingSlots'];
    $reservations = $databaseData['reservations'];
    $vehicles = $databaseData['vehicles'];
    $users = $databaseData['users'];
} else {
    throw new RuntimeException('Unknown Parkeasy data source.');
}

$page = $_GET['page'] ?? 'home';
$step = $_GET['step'] ?? 'login';
$requestMethod = $_SERVER['REQUEST_METHOD'];

parkEasyHandleAuthRequest($page, $step, $requestMethod, $users);

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
    $userLocations
);

if ($selectedLocation === null) {
    $selectedLocation = $userLocations[0] ?? null;
}

parkEasyHandleProfileUpdate(
    $page,
    $requestMethod,
    $currentUserId,
    $user,
    $users
);

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
        $user,
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
        $parkingRates
    );
    $mallsByCity = $parkingIndexData['malls_by_city'];
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
    $profileMessage = $profilePageData['profileMessage'];
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

<?php require $projectRoot . '/app/views/includes/head.php'; ?>

<body>

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