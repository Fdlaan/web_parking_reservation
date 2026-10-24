<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/models/Mall.php';

$mallId = filter_input(
    INPUT_GET,
    'mall_id',
    FILTER_VALIDATE_INT
);

$selectedMall = $mallId !== false
    && $mallId !== null
    && $mallId > 0
    ? parkEasyFindMall($mallId, $parkingMalls)
    : null;

$regularPrice = $selectedMall['prices']['regular'] ?? null;
$valetPrice = $selectedMall['prices']['valet'] ?? null;

if ($selectedMall === null) {
    http_response_code(404);
    exit('Parking location not found');
}

$titlePage = $selectedMall['name'] . ' | Parkeasy';

$distanceKm = null;
$distanceLabel = '';

if ($selectedLocation !== null) {
    $distanceKm = parkEasyCalculateDistance(
        (float) $selectedLocation['latitude'],
        (float) $selectedLocation['longitude'],
        (float) $selectedMall['latitude'],
        (float) $selectedMall['longitude']
    );

    $distanceLabel = number_format($distanceKm, 1)
        . ' KM From your location';
}

$floorAvailability = parkEasyFloorAvailability(
    (int) $selectedMall['id'],
    $parkingMalls,
    $reservations,
    'REGULER'
);

$mapsUrl = 'https://www.google.com/maps/search/?api=1&query='
    . $selectedMall['latitude'] . ',' . $selectedMall['longitude'];

$chooseSpotUrl = '?page=slot&mall_id=' . (int) $selectedMall['id'];

?>

<main class="main-content d-flex flex-column">
    <div class="d-flex">

        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <main class="d-flex flex-grow flex-column-1 py-4 ms-4 me-4">
                <div class="container py-4">

                    <div class="d-flex flex-column">
                        <a href="?page=home" class="text-decoration-none fw-bold" style="width: fit-content;">
                            <p style="color: var(--text)">
                                <i class="fa-solid fa-arrow-left-long me-2"></i>
                                Back
                            </p>
                        </a>
                    </div>

                    <div class="row">

                        <div class="col p-3">
                            <p class="fs-1 fw-medium text-white"><?= parkEasyEscape($selectedMall['name']) ?></p>

                            <img
                                src="<?= parkEasyEscape($selectedMall['image']) ?>"
                                alt="<?= parkEasyEscape($selectedMall['name']) ?>"
                                class="parking-detail-image">

                            <div class="card text-light rounded-4 mt-4" style="background-color: #2C2C2C;">
                                <div class="card-body p-4">

                                    <p class="mb-2 text-secondary">Address:</p>

                                    <p class="fs-5 mb-0">
                                        <?= parkEasyEscape($selectedMall['address']) ?>
                                    </p>

                                    <hr class="my-3 border-secondary opacity-50">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-secondary">
                                            <i class="fa-solid fa-location-dot me-2"></i>
                                            <?= parkEasyEscape($distanceLabel) ?>
                                        </span>

                                        <a href="<?= parkEasyEscape($mapsUrl) ?>" target="_blank" rel="noopener"
                                            class="btn d-flex align-items-center px-4 py-2 fw-medium rounded-3"
                                            style="background-color: #1b3f2b; border: 1px solid #6fd49b; color: #6fd49b;">
                                            <i class="fa-regular fa-map me-3"></i>Open with Maps
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col p-3">
                            <div class="d-flex row container-fluid p-3 justify-content-end">

                                <div class="card text-light rounded-4"
                                    style="background-color: #2C2C2C; max-width: 480px;">
                                    <div class="card-body p-4">

                                        <h2 class="fw-bold fs-3 mb-0">Slot Available</h2>
                                        <hr class="my-4 border-secondary opacity-50">

                                        <div class="d-flex flex-column gap-3">

                                            <?php foreach ($floorAvailability as $floor): ?>
                                                <div class="row g-0 align-items-center rounded-3 px-4 py-4"
                                                    style="background-color: #343434;">
                                                    <div class="col fs-5 fw-semibold">
                                                        <?= parkEasyEscape($floor['floor_code']) ?>
                                                    </div>
                                                    <div class="col text-center fs-5 fw-bold"
                                                        style="color: #6fd49b;">
                                                        <?= (int) $floor['available'] ?>
                                                    </div>
                                                    <div class="col text-end fs-3">
                                                        <i class="fa-solid fa-car"></i>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                        </div>

                                        <a href="<?= parkEasyEscape($chooseSpotUrl) ?>"
                                            class="btn w-100 mt-4 py-3 fw-semibold rounded-3"
                                            style="background-color: #1b3f2b; color: #6fd49b;">
                                            Choose Parking Spot
                                        </a>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </main>
        </div>

    </div>
</main>