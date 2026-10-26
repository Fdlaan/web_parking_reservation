<?php

function parkEasyCalculateDistance(
    float $userLat,
    float $userLng,
    float $mallLat,
    float $mallLng
): float {
    $earthRadius = 6371;

    $latDifference = deg2rad($mallLat - $userLat);
    $lngDifference = deg2rad($mallLng - $userLng);

    $a = sin($latDifference / 2) ** 2
        + cos(deg2rad($userLat))
        * cos(deg2rad($mallLat))
        * sin($lngDifference / 2) ** 2;

    $a = min(1, max(0, $a));

    return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

function parkEasyFloorsOfMall(string $mallId, array $floors): array
{
    $mallFloors = array_values(array_filter(
        $floors,
        fn($floor) =>
            (string) ($floor['mall_id'] ?? '') === $mallId
            && !empty($floor['is_active'])
    ));

    usort(
        $mallFloors,
        fn($a, $b) => (int) $a['sort_order'] <=> (int) $b['sort_order']
    );

    return $mallFloors;
}

/**
 * Gets active slots of one parking type on a floor, with availability from reservations.
 */
function parkEasySlotsOfFloor(
    string $floorId,
    string $parkingType,
    array $slots,
    array $reservations
): array {
    $occupiedSlotIds = parkEasyGetOccupiedSlotIds($reservations);
    $floorSlots = [];

    foreach ($slots as $slot) {
        if (
            (string) $slot['floor_id'] !== $floorId
            || empty($slot['is_active'])
            || $slot['parking_type'] !== $parkingType
        ) {
            continue;
        }

        $isOccupied = isset($occupiedSlotIds[(string) $slot['slot_id']]);

        $floorSlots[] = array_merge($slot, [
            'is_occupied' => $isOccupied,
            'is_available' => !$isOccupied,
        ]);
    }

    usort($floorSlots, fn($a, $b) => strnatcasecmp($a['slot_code'], $b['slot_code']));

    return $floorSlots;
}
