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

function parkEasyFindRate(
    string $mallId,
    string $parkingType,
    array $rates
): ?array {
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

/**
 * Returns active reservations that still block their selected slot.
 * Dummy reservation times are treated as the start time.
 */
function parkEasyGetOccupiedSlotIds(array $reservations, ?DateTimeImmutable $now = null): array
{
    $now ??= new DateTimeImmutable('now');
    $occupied = [];

    foreach ($reservations as $reservation) {
        $status = strtoupper((string) ($reservation['status'] ?? ''));
        $slotId = (string) ($reservation['slot_id'] ?? '');
        $reservedAt = $reservation['reserved_at'] ?? null;

        if (
            $slotId === ''
            || !in_array($status, ['PENDING_PAYMENT', 'CONFIRMED'], true)
            || !$reservedAt
        ) {
            continue;
        }

        try {
            $reservationTime = new DateTimeImmutable($reservedAt);
        } catch (Exception) {
            continue;
        }

        // Reservations in the past no longer block a slot in the demo.
        if ($reservationTime >= $now) {
            $occupied[$slotId] = true;
        }
    }

    return $occupied;
}

/**
 * Returns availability for every active floor belonging to the selected mall.
 */
function parkEasyFloorAvailability(
    string $mallId,
    array $malls,
    array $floors,
    array $slots,
    array $reservations,
    ?DateTimeImmutable $now = null
): array {
    if (parkEasyFindMall($mallId, $malls) === null) {
        return [];
    }

    $occupiedSlots = parkEasyGetOccupiedSlotIds($reservations, $now);
    $availability = [];

    foreach ($floors as $floor) {
        if (
            (string) ($floor['mall_id'] ?? '') !== $mallId
            || empty($floor['is_active'])
        ) {
            continue;
        }

        $availableCount = 0;

        foreach ($slots as $slot) {
            if (
                (string) ($slot['floor_id'] ?? '') !== (string) $floor['floor_id']
                || empty($slot['is_active'])
            ) {
                continue;
            }

            if (!isset($occupiedSlots[(string) $slot['slot_id']])) {
                $availableCount++;
            }
        }

        $availability[] = [
            'floor_id' => (string) $floor['floor_id'],
            'floor_code' => (string) $floor['floor_code'],
            'floor_name' => (string) ($floor['floor_name'] ?? $floor['floor_code']),
            'sort_order' => (int) ($floor['sort_order'] ?? 0),
            'available' => $availableCount,
            'total' => count(array_filter(
                $slots,
                fn($slot) =>
                    (string) ($slot['floor_id'] ?? '') === (string) $floor['floor_id']
                    && !empty($slot['is_active'])
            )),
        ];
    }

    usort(
        $availability,
        fn($a, $b) => $a['sort_order'] <=> $b['sort_order']
    );

    return $availability;
}