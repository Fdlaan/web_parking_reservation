<?php

function parkEasyEscape(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function parkEasyFindMall(string $mallId, array $malls): ?array
{
    foreach ($malls as $mall) {
        if (($mall['mall_id'] ?? '') === $mallId) {
            return $mall;
        }
    }

    return null;
}

function parkEasyFindLocation(int|string $locationId, array $locations): ?array
{
    foreach ($locations as $location) {
        if ((string) ($location['id'] ?? '') === (string) $locationId) {
            return $location;
        }
    }

    return null;
}

function parkEasyFindRate(string $mallId, string $parkingType, array $rates): ?array
{
    $parkingType = strtoupper(trim($parkingType));

    foreach ($rates as $rate) {
        if (
            (string) ($rate['mall_id'] ?? '') === $mallId
            && strtoupper((string) ($rate['parking_type'] ?? '')) === $parkingType
        ) {
            return $rate;
        }
    }

    return null;
}

/** A slot is taken while it has an UNPAID or PAID reservation (the sweep expires stale ones). */
function parkEasyGetOccupiedSlotIds(array $reservations): array
{
    $occupied = [];

    foreach ($reservations as $reservation) {
        if (in_array($reservation['status'] ?? '', ['UNPAID', 'PAID'], true)) {
            $occupied[(string) $reservation['slot_id']] = true;
        }
    }

    return $occupied;
}

/** Availability for every active floor of the selected mall. */
function parkEasyFloorAvailability(
    string $mallId,
    array $malls,
    array $floors,
    array $slots,
    array $reservations
): array {
    if (parkEasyFindMall($mallId, $malls) === null) {
        return [];
    }

    $occupiedSlots = parkEasyGetOccupiedSlotIds($reservations);
    $availability = [];

    foreach ($floors as $floor) {
        if ((string) ($floor['mall_id'] ?? '') !== $mallId || empty($floor['is_active'])) {
            continue;
        }

        $total = 0;
        $available = 0;

        foreach ($slots as $slot) {
            if ((string) $slot['floor_id'] !== (string) $floor['floor_id'] || empty($slot['is_active'])) {
                continue;
            }

            $total++;

            if (!isset($occupiedSlots[(string) $slot['slot_id']])) {
                $available++;
            }
        }

        $availability[] = [
            'floor_id' => (string) $floor['floor_id'],
            'floor_code' => (string) $floor['floor_code'],
            'floor_name' => (string) ($floor['floor_name'] ?? $floor['floor_code']),
            'sort_order' => (int) ($floor['sort_order'] ?? 0),
            'available' => $available,
            'total' => $total,
        ];
    }

    usort($availability, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);

    return $availability;
}
