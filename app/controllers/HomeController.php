<?php

/** Prepare all values rendered by the Home page. */
function parkEasyPrepareHomePageData(
    int $currentUserId,
    ?array $selectedLocation,
    array $parkingMalls,
    array $parkingRates,
    array $reservations
): array {
    $recommendedParking = [];

    if ($selectedLocation !== null) {
        $recommendedParking = Mall::getRecommended(
            (float) $selectedLocation['latitude'],
            (float) $selectedLocation['longitude'],
            $parkingMalls,
            $parkingRates
        );
    }

    $recommendedParking = array_slice($recommendedParking, 0, 4);

    $userReservations = array_values(array_filter(
        $reservations,
        fn($reservation) =>
            (int) ($reservation['user_id'] ?? 0) === $currentUserId
            && ($reservation['status'] ?? '') === 'COMPLETED'
    ));

    usort(
        $userReservations,
        fn($a, $b) => strtotime($b['reserved_at']) <=> strtotime($a['reserved_at'])
    );

    $previouslyParking = [];

    foreach (array_slice($userReservations, 0, 3) as $reservation) {
        $mall = parkEasyFindMall((string) $reservation['mall_id'], $parkingMalls);

        if ($mall === null) {
            continue;
        }

        $previouslyParking[] = array_merge($mall, [
            'url' => '?page=detail&mall_id=' . rawurlencode((string) $mall['mall_id']),
            'alt' => $mall['name'],
            'reserved_at' => $reservation['reserved_at'],
            'slot_code' => $reservation['slot_code'] ?? '-',
            'plate_number' => $reservation['plate_number'] ?? '',
        ]);
    }

    $activeReservations = array_values(array_filter(
        $reservations,
        fn($reservation) =>
            (int) ($reservation['user_id'] ?? 0) === $currentUserId
            && in_array($reservation['status'] ?? '', ['PAID', 'UNPAID'], true)
    ));

    usort(
        $activeReservations,
        fn($a, $b) => strtotime($a['reserved_at']) <=> strtotime($b['reserved_at'])
    );

    $reservation = $activeReservations[0] ?? null;
    $reservationStatus = match ($reservation['status'] ?? null) {
        'PAID' => 'paid',
        'UNPAID' => 'unpaid',
        default => 'none',
    };
    $canCancel = false;

    if ($reservation !== null) {
        $mall = parkEasyFindMall((string) $reservation['mall_id'], $parkingMalls);
        $startTime = new DateTimeImmutable($reservation['reserved_at']);

        $reservation = array_merge($reservation, [
            'mall_name' => $mall['name'] ?? 'Unknown Mall',
            'parking_slot' => $reservation['slot_code'] ?? '-',
            'time' => $startTime->format('d M Y, H:i'),
            'parking_type' => $reservation['parking_type'] ?? '-',
        ]);

        $canCancel = parkEasyCanCancel($reservation['reserved_at'], $reservation['status']);
    }

    return [
        'recommendedParking' => $recommendedParking,
        'previouslyParking' => $previouslyParking,
        'reservation' => $reservation,
        'reservationStatus' => $reservationStatus,
        'canCancel' => $canCancel,
    ];
}
