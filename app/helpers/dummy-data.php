<?php

function parkEasyEscape($value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function parkEasyFindMall(int $mallId, array $malls): ?array {
    foreach ($malls as $mall) {
        if ((int) $mall['id'] === $mallId) {
            return $mall;
        }
    }
    return null;
}

function parkEasyFindLocation(int $locationId, array $locations): ?array {
    foreach ($locations as $location) {
        if ((int) $location['id'] === $locationId) {
            return $location;
        }
    }
    return null;
}

function parkEasyFindRate(int $locationId, string $parkingType, array $rates): ?array {
    foreach ($rates as $rate) {
        if ((int) $rate['location_id'] === $locationId && $rate['parking_type'] === $parkingType) {
            return $rate;
        }
    }
    return null;
}

function parkEasyFloorAvailability(
    int $mallId,
    array $parkingMalls,
    array $reservations,
    string $parkingType = 'REGULER'
): array {
    $selectedMall = parkEasyFindMall($mallId, $parkingMalls);

    if ($selectedMall === null) {
        return [];
    }

    $occupiedSlots = [];

    foreach ($reservations as $reservation) {
        if (
            (int) ($reservation['mall_id'] ?? 0) !== $mallId
            || !in_array(
                $reservation['status'] ?? '',
                ['PENDING_PAYMENT', 'CONFIRMED'],
                true
            )
        ) {
            continue;
        }

        $occupiedSlots[$reservation['slot_code']] = true;
    }

    $availability = [];

    foreach ($selectedMall['floors'] ?? [] as $floor) {
        $availableCount = 0;

        foreach ($floor['slots'] ?? [] as $slot) {
            if (
                strtoupper($slot['parking_type'] ?? 'REGULER')
                    !== strtoupper($parkingType)
                || empty($slot['is_active'])
            ) {
                continue;
            }

            if (!isset($occupiedSlots[$slot['code']])) {
                $availableCount++;
            }
        }

        $availability[] = [
            'floor_id' => (int) $floor['id'],
            'floor_code' => $floor['code'],
            'sort_order' => (int) $floor['sort_order'],
            'available' => $availableCount,
        ];
    }

    usort(
        $availability,
        fn($a, $b) => $a['sort_order'] <=> $b['sort_order']
    );

    return $availability;
}

function parkEasyJoinReservations(array $reservations, array $slots, array $floors): array {
    $slotsById = [];
    foreach ($slots as $slot) {
        $slotsById[(int) $slot['id']] = $slot;
    }

    $floorsById = [];
    foreach ($floors as $floor) {
        $floorsById[(int) $floor['id']] = $floor;
    }

    return array_map(function (array $reservation) use ($slotsById, $floorsById): array {
        $slot = $slotsById[(int) $reservation['slot_id']] ?? null;
        $floor = $slot !== null ? ($floorsById[(int) $slot['floor_id']] ?? null) : null;

        return array_merge($reservation, [
            'slot_code' => $slot['slot_code'] ?? '-',
            'floor_code' => $floor['floor_code'] ?? '-',
            'mall_id' => (int) ($floor['location_id'] ?? 0),
        ]);
    }, $reservations);
}

?>