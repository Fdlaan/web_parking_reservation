<?php

/** Pagination size used by the History page. */
const PARKEASY_HISTORY_PER_PAGE = 6;

function parkEasyHistoryPageList(int $currentPage, int $totalPages): array
{
    if ($totalPages <= 7) {
        return range(1, $totalPages);
    }

    if ($currentPage <= 4) {
        return [1, 2, 3, 4, 5, '…', $totalPages];
    }

    if ($currentPage >= $totalPages - 3) {
        return [
            1, '…', $totalPages - 4, $totalPages - 3,
            $totalPages - 2, $totalPages - 1, $totalPages,
        ];
    }

    return [1, '…', $currentPage - 1, $currentPage, $currentPage + 1, '…', $totalPages];
}

/** Prepare history rows and pagination for the History template. */
function parkEasyPrepareHistoryPageData(
    int $currentUserId,
    array $reservations,
    array $parkingMalls,
    array $query
): array {
    $rows = [];

    foreach ($reservations as $reservation) {
        if ((int) ($reservation['user_id'] ?? 0) !== $currentUserId) {
            continue;
        }

        $mall = parkEasyFindMall((string) ($reservation['mall_id'] ?? ''), $parkingMalls);

        if ($mall === null) {
            continue;
        }

        $reservedAt = (string) ($reservation['reserved_at'] ?? '');
        $date = new DateTimeImmutable($reservedAt);
        $status = (string) ($reservation['status'] ?? 'UNKNOWN');

        $rows[] = [
            'reservation_id' => (string) $reservation['reservation_id'],
            'location' => $mall['name'],
            'slot_code' => $reservation['slot_code'] ?? '-',
            'reserved_at' => $reservedAt,
            'time_label' => $date->format('H:i'),
            'date_label' => $date->format('j') . ' '
                . ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][(int) $date->format('n') - 1]
                . ' ' . $date->format('Y'),
            'plate_number' => $reservation['plate_number'] ?? '-',
            'status' => $status,
        ];
    }

    usort(
        $rows,
        fn($a, $b) => strtotime($b['reserved_at']) <=> strtotime($a['reserved_at'])
    );

    $statusLabels = [
        'COMPLETED' => ['Successful', 'success'],
        'CANCELLED' => ['Cancelled', 'danger'],
        'PENDING_PAYMENT' => ['Pending', 'warning'],
        'CONFIRMED' => ['Confirmed', 'success'],
        'EXPIRED' => ['Expired', 'danger'],
    ];

    $totalPages = max(1, (int) ceil(count($rows) / PARKEASY_HISTORY_PER_PAGE));
    $currentPage = min(
        $totalPages,
        max(1, (int) ($query['history_page'] ?? 1))
    );

    foreach ($rows as &$row) {
        [$row['status_label'], $row['status_color']] = $statusLabels[$row['status']]
            ?? [$row['status'], 'secondary'];
    }
    unset($row);

    return [
        'rows' => $rows,
        'statusLabels' => $statusLabels,
        'totalPages' => $totalPages,
        'historyPage' => $currentPage,
        'paginatedRows' => array_slice(
            $rows,
            ($currentPage - 1) * PARKEASY_HISTORY_PER_PAGE,
            PARKEASY_HISTORY_PER_PAGE
        ),
        'pageNumbers' => parkEasyHistoryPageList($currentPage, $totalPages),
        'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ];
}
