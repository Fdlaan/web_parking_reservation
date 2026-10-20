<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
} // history is per-user, never show it logged out

// shortcut: credentials inline, move to a shared config/db.php once a second page needs the database
$pdo = new PDO('mysql:host=localhost;dbname=parking_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

const PER_PAGE = 5;
const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
// Reservation status => [label shown, Bootstrap badge colour]
const STATUS = [
    'COMPLETED' => ['Successful', 'success'],
    'CANCELLED' => ['Cancelled', 'danger'],
    'PENDING_PAYMENT' => ['Pending', 'warning'],
    'CONFIRMED' => ['Confirmed', 'success'],
    'EXPIRED' => ['Expired', 'danger'],
];

function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

// Pages to show: 1 2 3 4 5 … 10, sliding when the current page is far from either end
function pageList(int $cur, int $total): array
{
    if ($total <= 7)
        return range(1, $total);
    if ($cur <= 4)
        return [1, 2, 3, 4, 5, '…', $total];
    if ($cur >= $total - 3)
        return [1, '…', $total - 4, $total - 3, $total - 2, $total - 1, $total];
    return [1, '…', $cur - 1, $cur, $cur + 1, '…', $total];
}
assert(pageList(1, 10) === [1, 2, 3, 4, 5, '…', 10]);
assert(pageList(5, 10) === [1, '…', 4, 5, 6, '…', 10]);

$userId = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT COUNT(*) FROM reservations WHERE user_id = ?');
$stmt->execute([$userId]);
$totalPages = max(1, (int) ceil($stmt->fetchColumn() / PER_PAGE));

// ?page=abc or ?page=999 are clamped to a real page
$page = min($totalPages, max(1, (int) ($_GET['page'] ?? 1)));

$stmt = $pdo->prepare(
    'SELECT l.name AS location, s.slot_code, r.reserved_at, r.plate_number, r.status
       FROM reservations r
       JOIN parking_slots s     ON s.id = r.slot_id
       JOIN floors f            ON f.id = s.floor_id
       JOIN parking_locations l ON l.id = f.location_id
      WHERE r.user_id = :uid
      ORDER BY r.reserved_at DESC
      LIMIT :lim OFFSET :off'
);
$stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
$stmt->bindValue(':lim', PER_PAGE, PDO::PARAM_INT);
$stmt->bindValue(':off', ($page - 1) * PER_PAGE, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main-content d-flex flex-column">
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column-1 py-4 ms-4 me-4">
                <div class="container py-4">

                    <div class="card overflow-hidden rounded-4">
                        <div class="table-responsive">
                            <table class="table table-dark align-middle mb-0">
                                <thead>
                                    <tr class="text-secondary">
                                        <th class="ps-4 py-3 fw-medium">Location</th>
                                        <th class="py-3 fw-medium">Parking Slot</th>
                                        <th class="py-3 fw-medium">Time</th>
                                        <th class="py-3 fw-medium">Plate Number</th>
                                        <th class="py-3 fw-medium">Date</th>
                                        <th class="py-3 fw-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $r):
                                        $dt = new DateTime($r['reserved_at']);
                                        [$label, $color] = STATUS[$r['status']] ?? [$r['status'], 'secondary'];
                                        ?>
                                        <tr style="height:88px">
                                            <td class="ps-4"><?= h($r['location']) ?></td>
                                            <td><?= h($r['slot_code']) ?></td>
                                            <td><?= $dt->format('H:i') ?></td>
                                            <td><?= h($r['plate_number']) ?></td>
                                            <td><?= $dt->format('j') . ' ' . MONTHS[(int) $dt->format('n') - 1] . ' ' . $dt->format('Y') ?>
                                            </td>
                                            <td><span
                                                    class="badge rounded-pill text-bg-<?= $color ?> px-3 py-2 fw-normal">&bull;
                                                    <?= h($label) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (!$rows): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-secondary py-5">No reservations yet.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <nav class="mt-4" aria-label="History pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $page === 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">&lsaquo;</a>
                            </li>
                            <?php foreach (pageList($page, $totalPages) as $p): ?>
                                <?php if ($p === '…'): ?>
                                    <li class="page-item disabled"><span class="page-link">…</span></li>
                                <?php else: ?>
                                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <li class="page-item <?= $page === $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">&rsaquo;</a>
                            </li>
                        </ul>
                    </nav>

                </div>

            </main>
        </div>

    </div>
</main>