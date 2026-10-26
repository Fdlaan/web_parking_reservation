<?php

/**
 * Prepares all data required by the parking slot template.
 * The template only renders the returned data and does not query raw datasets.
 */

/** Prepare mall cards grouped by city. ?q= searches name/city, ?city= shows one city in full. */
function parkEasyPrepareParkingIndexData(
    ?array $selectedLocation,
    array $parkingMalls,
    array $parkingRates,
    array $query
): array {
    $search = trim((string) ($query['q'] ?? ''));
    $onlyCity = trim((string) ($query['city'] ?? ''));
    $mallsByCity = [];

    if ($selectedLocation !== null) {
        $recommendedMalls = Mall::getRecommended(
            (float) $selectedLocation['latitude'],
            (float) $selectedLocation['longitude'],
            $parkingMalls,
            $parkingRates
        );

        foreach ($recommendedMalls as $mall) {
            $city = (string) ($mall['city'] ?? 'Other');

            if ($onlyCity !== '' && $city !== $onlyCity) {
                continue;
            }
            if ($search !== '' && stripos($mall['name'] . ' ' . $city, $search) === false) {
                continue;
            }

            $mallsByCity[$city][] = $mall;
        }

        if ($search === '' && $onlyCity === '') {
            foreach ($mallsByCity as $city => $cityMalls) {
                $mallsByCity[$city] = array_slice($cityMalls, 0, 5);
            }
        }
    }

    return ['malls_by_city' => $mallsByCity, 'search' => $search, 'only_city' => $onlyCity];
}

function parkEasyPrepareSlotPageData(
    array $query,
    array $parkingMalls,
    array $parkingRates,
    array $parkingFloors,
    array $parkingSlots,
    array $reservations
): array {
    $mallId = trim((string) ($query['mall_id'] ?? ''));

    $selectedMall = $mallId !== ''
        ? parkEasyFindMall($mallId, $parkingMalls)
        : null;

    if ($selectedMall === null) {
        return [
            'error' => 'Parking location not found.',
            'mall' => null,
            'parking_type' => 'REGULER',
            'rate' => null,
            'selected_price' => null,
            'selected_admin_fee' => null,
            'floors' => [],
            'selected_floor' => null,
            'slots' => [],
            'availability' => [],
        ];
    }

    $parkingType = strtoupper(trim((string) ($query['parking_type'] ?? 'REGULER')));
    if (!in_array($parkingType, ['REGULER', 'VALET'], true)) {
        $parkingType = 'REGULER';
    }

    $mallFloors = parkEasyFloorsOfMall($mallId, $parkingFloors);

    $requestedFloorId = trim((string) ($query['floor_id'] ?? ''));
    $selectedFloor = null;

    foreach ($mallFloors as $floor) {
        if ((string) $floor['floor_id'] === $requestedFloorId) {
            $selectedFloor = $floor;
            break;
        }
    }

    // If the requested floor does not belong to this mall, use its first active floor.
    $selectedFloor ??= $mallFloors[0] ?? null;

    $selectedRate = parkEasyFindRate($mallId, $parkingType, $parkingRates);

    $floorSlots = $selectedFloor !== null
        ? parkEasySlotsOfFloor(
            (string) $selectedFloor['floor_id'],
            $parkingType,
            $parkingSlots,
            $reservations
        )
        : [];

    $availability = parkEasyFloorAvailability(
        $mallId,
        $parkingMalls,
        $parkingFloors,
        $parkingSlots,
        $reservations
    );

    return [
        'error' => null,
        'mall' => $selectedMall,
        'parking_type' => $parkingType,
        'rate' => $selectedRate,
        'selected_price' => $selectedRate !== null ? (int) $selectedRate['price_per_slot'] : null,
        'selected_admin_fee' => $selectedRate !== null ? (int) ($selectedRate['admin_fee'] ?? 0) : null,
        'floors' => $mallFloors,
        'selected_floor' => $selectedFloor,
        'slots' => $floorSlots,
        'availability' => $availability,
    ];
}

/**
 * Prepares mall detail data, including rates and per-floor availability.
 */
function parkEasyPrepareMallDetailData(
    string $mallId,
    ?array $selectedLocation,
    array $parkingMalls,
    array $parkingRates,
    array $parkingFloors,
    array $parkingSlots,
    array $reservations
): array {
    $selectedMall = parkEasyFindMall($mallId, $parkingMalls);

    if ($selectedMall === null) {
        return [
            'error' => 'Parking location not found.',
            'mall' => null,
            'regular_rate' => null,
            'valet_rate' => null,
            'distance_label' => '',
            'floor_availability' => [],
            'maps_url' => '#',
            'choose_spot_url' => '?page=parking',
        ];
    }

    $distanceLabel = '';

    if ($selectedLocation !== null) {
        $distanceKm = parkEasyCalculateDistance(
            (float) $selectedLocation['latitude'],
            (float) $selectedLocation['longitude'],
            (float) $selectedMall['latitude'],
            (float) $selectedMall['longitude']
        );

        $distanceLabel = number_format($distanceKm, 1) . ' KM From your location';
    }

    return [
        'error' => null,
        'mall' => $selectedMall,
        'regular_rate' => parkEasyFindRate($mallId, 'REGULER', $parkingRates),
        'valet_rate' => parkEasyFindRate($mallId, 'VALET', $parkingRates),
        'distance_label' => $distanceLabel,
        'floor_availability' => parkEasyFloorAvailability(
            $mallId,
            $parkingMalls,
            $parkingFloors,
            $parkingSlots,
            $reservations
        ),
        'maps_url' => 'https://www.google.com/maps/search/?api=1&query='
            . $selectedMall['latitude'] . ',' . $selectedMall['longitude'],
        'choose_spot_url' => '?page=slot&mall_id=' . rawurlencode($mallId),
    ];
}
