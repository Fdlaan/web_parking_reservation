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
 * Gets active slots for a floor, with availability derived from reservations.
 */
function parkEasySlotsOfFloor(
    string $floorId,
    array $slots,
    array $reservations,
    ?DateTimeImmutable $now = null
): array {
    $occupiedSlotIds = parkEasyGetOccupiedSlotIds($reservations, $now);
    $floorSlots = [];

    foreach ($slots as $slot) {
        if (
            (string) ($slot['floor_id'] ?? '') !== $floorId
            || empty($slot['is_active'])
        ) {
            continue;
        }

        $slotId = (string) $slot['slot_id'];
        $isOccupied = isset($occupiedSlotIds[$slotId]);

        $floorSlots[] = array_merge($slot, [
            'is_occupied' => $isOccupied,
            'is_available' => !$isOccupied,
        ]);
    }

    usort(
        $floorSlots,
        fn($a, $b) => strnatcasecmp(
            (string) $a['slot_code'],
            (string) $b['slot_code']
        )
    );

    return $floorSlots;
}
