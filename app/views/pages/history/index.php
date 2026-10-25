<?php

$titlePage = 'History | Parkeasy';

$fileCss = [
    'pages/history.css',
];

$fileJs = [];

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
                        <tr>
                            <td>
                                <?= parkEasyEscape($r['location']) ?>
                            </td>

                            <td><?= parkEasyEscape($r['slot_code']) ?></td>

                            <td><?= parkEasyEscape($r['time_label']) ?></td>

                            <td><?= parkEasyEscape($r['plate_number']) ?></td>

                            <td>
                                <?= parkEasyEscape($r['date_label']) ?>
                            </td>

                            <td>
                                <span class="status-badge text-bg-<?= parkEasyEscape($r['status_color']) ?>">
                                    <span class="dot-icon"></span>
                                    <span><?= parkEasyEscape($r['status_label']) ?></span>
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
                href="?page=history&history_page=<?= max(1, $historyPage - 1) ?>" 
                class="history-page-arrow <?= $historyPage <= 1 ? 'is-disabled' : '' ?>"
                aria-label="Previous page"
                <?= $historyPage <= 1 ? 'aria-disabled="true" tabIndex="-1"' : ''?>
            >
                <i class="prev-icon"></i>
            </a>
            
            <div class="history-page-numbers">

                <?php foreach ($pageNumbers as $p): ?>
                <?php if ($p === '…'): ?>

                <span class="history-page-ellipsis" aria-hidden="true">...</span>
                
                <?php else: ?>
                
                <a
                    class="history-page-number <?= $p === $historyPage ? 'is-active' : '' ?>"
                    href="?page=history&history_page=<?= $p ?>"
                    <?= $p === $historyPage ? 'aria-current="page"' : '' ?>
                >
                    <?= $p ?>
                </a>
                
                <?php endif; ?>
                <?php endforeach; ?>

            </div>

            <a 
                href="?page=history&history_page=<?= min($totalPages, $historyPage + 1) ?>" 
                class="history-page-arrow <?= $historyPage >= $totalPages ? 'is-disabled' : '' ?>"
                aria-label="Next page"
                <?= $historyPage >= $totalPages ? 'aria-disabled="true" tabIndex="-1"' : ''?>
            >
                <i class="next-icon"></i>
            </a>
            
        </nav>

    </section>
</main>