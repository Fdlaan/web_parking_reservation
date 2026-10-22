<?php

// setting title page
$title_page = 'History | Parkeasy';
    
// file CSS yang terhubung
$file_css = [
    'pages/history.css',
];
    
// file JS yang terhubung
$file_js = [];

const PER_PAGE = 6;

const MONTHS = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
];

const STATUS = [
    'COMPLETED'       => ['Successful', 'success'],
    'CANCELLED'       => ['Cancelled', 'danger'],
    'PENDING_PAYMENT' => ['Pending', 'warning'],
    'CONFIRMED'       => ['Confirmed', 'success'],
    'EXPIRED'         => ['Expired', 'danger'],
];

$rows = [];

foreach ($reservations as $reservation) {
    if ((int) $reservation['user_id'] !== $currentUserId) {
        continue;
    }

    $mall = parkEasyFindMall(
        (int) $reservation['mall_id'],
        $malls
    );

    if ($mall === null) {
        continue;
    }

    $rows[] = [
        'location' => $mall['name'],
        'slot_code' => $reservation['slot_code'],
        'reserved_at' => $reservation['reserved_at'],
        'plate_number' => $reservation['plate_number'],
        'status' => $reservation['status'],
    ];
}

usort(
    $rows,
    fn($a, $b) =>
        strtotime($b['reserved_at']) <=> strtotime($a['reserved_at'])
);

function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function pageList(int $cur, int $total): array
{
    if ($total <= 7) {
        return range(1, $total);
    }

    if ($cur <= 4) {
        return [1, 2, 3, 4, 5, '…', $total];
    }

    if ($cur >= $total - 3) {
        return [
            1, '…',
            $total - 4,
            $total - 3,
            $total - 2,
            $total - 1,
            $total
        ];
    }

    return [1, '…', $cur - 1, $cur, $cur + 1, '…', $total];
}

// Temporary pagination for dummy data.
$totalPages = max(1, (int) ceil(count($rows) / PER_PAGE));

$page = min(
    $totalPages,
    max(1, (int) ($_GET['history_page'] ?? 1))
);

$paginatedRows = array_slice (
    $rows,
    ($page - 1) * PER_PAGE,
    PER_PAGE
);

?>

<main class="main-content">

    <section>

        <span class="section-header">History Reservation</span>

        <div class="table-history-wrapper">
            <table class="table-history">

                <thead class="table-history-head">
                    <tr>
                        <th>Location</th>
                        <th>Parking Slot</th>
                        <th>Time</th>
                        <th>Plate Number</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody class="table-history-body">
                    <?php foreach ($paginatedRows as $r): ?>
                        <?php
                        $dt = new DateTime($r['reserved_at']);
                        [$label, $color] = STATUS[$r['status']]
                            ?? [$r['status'], 'secondary'];
                        ?>

                        <tr>
                            <td>
                                <?= h($r['location']) ?>
                            </td>

                            <td><?= h($r['slot_code']) ?></td>

                            <td><?= $dt->format('H:i') ?></td>

                            <td><?= h($r['plate_number']) ?></td>

                            <td>
                                <?= $dt->format('j') . ' '
                                    . MONTHS[(int) $dt->format('n') - 1]
                                    . ' ' . $dt->format('Y') ?>
                            </td>

                            <td>
                                <span class="status-badge text-bg-<?= h($color) ?>">
                                    <span class="dot-icon"></span>
                                    <span><?= h($label) ?></span>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6"
                                class="text-center text-secondary py-5">
                                No reservations yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav class="history-pagination" aria-label="History pagination">
            
            <a 
                href="?page=history&history_page=<?= max(1, $page - 1) ?>" 
                class="history-page-arrow <?= $page <= 1 ? 'is-disabled' : '' ?>"
                aria-label="Previous page"
                <?= $page <= 1 ? 'aria-disabled="true" tabIndex="-1"' : ''?>
            >
                <i class="prev-icon"></i>
            </a>
            
            <div class="history-page-numbers">

                <?php foreach (pageList($page, $totalPages) as $p): ?>
                <?php if ($p === '…'): ?>

                <span class="history-page-ellipsis" aria-hidden="true">...</span>
                
                <?php else: ?>
                
                <a
                    class="history-page-number <?= $p === $page ? 'is-active' : '' ?>"
                    href="?page=history&history_page=<?= $p ?>"
                    <?= $p === $page ? 'aria-current="page"' : '' ?>
                >
                    <?= $p ?>
                </a>
                
                <?php endif; ?>
                <?php endforeach; ?>

            </div>

            <a 
                href="?page=history&history_page=<?= min($totalPages, $page + 1) ?>" 
                class="history-page-arrow <?= $page >= $totalPages ? 'is-disabled' : '' ?>"
                aria-label="Next page"
                <?= $page >= $totalPages ? 'aria-disabled="true" tabIndex="-1"' : ''?>
            >
                <i class="next-icon"></i>
            </a>
            
        </nav>

    </section>
</main>