<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/models/Mall.php';
// file CSS yang terhubung
$fileCss = [
    'pages/parking.css',
];

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

<main class="main-content">

    <section>
        <a href="?page=home" class="back-button">
            <i class="back-icon"></i>
            <span>
                Back
            </span>
        </a>
    </section>

    <section>
        <span class="card-parking-detail-name"><?= parkEasyEscape($selectedMall['name']) ?></span>

        <div class="card-parking-detail-mall">

            <img class="parking-detail-image" src="<?= parkEasyEscape($selectedMall['image']) ?>"
                alt="<?= parkEasyEscape($selectedMall['name']) ?>">

            <div class="card-parking-detail-information d-flex flex-column justify-content-evenly">

                <div class="card-parking-detail-address d-flex flex-column">

                    <span style="font: var(--text-label); color: var(--secondary-text)">Address:</span>
                    <span style="font: var(--text-body); color: var(--primary-text)"><?= parkEasyEscape($selectedMall['address']) ?></span>

                    <div class="card-parking-detail-location d-flex flex-row">
                        <span class="d-flex flex-row align-items-center" style="gap: 6px;">
                            <i class="location-icon"></i>
                            <?= parkEasyEscape($distanceLabel) ?>
                        </span>

                        <a href="<?= parkEasyEscape($mapsUrl) ?>"  class="button-maps" target="_blank" rel="noopener">
                            <i class="maps-icon"></i>
                            Open with Maps
                        </a>
                    </div>
                </div>

                <hr style="background-color: var(--secondary-text); height: 1px; border: var(--radius-xs);">
                
                <a href="<?= parkEasyEscape($chooseSpotUrl) ?>" class="slot-button">
                    Choose Parking Spot
                </a>
            </div>

        </div>
    </section>

    <section>
        <span class="card-parking-detail-name">Slot Available</span>

        <div class="parking-slot-table">
            <div class="parking-slot-table-heading">
                <span>Floors</span>
                <span>Available</span>
                <span>Status</span>
            </div>
            <div class="parking-slot-item">
                <?php foreach ($floorAvailability as $floor): ?>
                <div class="parking-slot-table-row">
                    <div class="parking-slot-floor">
                        <?= parkEasyEscape($floor['floor_code']) ?>
                    </div>
                    <div class="parking-slot-available">
                        <?= (int) $floor['available'] ?>
                    </div>
                    <div class="parking-slot-status">
                        <?php if ((int) $floor['available'] > 0): ?>
                            <span class="status-available">
                                <i class="dot-icon"></i>
                                Available
                            </span>
                        <?php else: ?>
                            <span class="status-full">
                                <i class="dot-icon"></i>
                                Full
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
</main>