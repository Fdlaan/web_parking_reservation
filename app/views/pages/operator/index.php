<?php
staffHead('Operator | Parkeasy');
staffBar($mall, $tabs, $tab, '?page=operator&tab=', $operator['name']);
?>

<section class="staff-section">
    <span class="section-header"><?= e($tabs[$tab]) ?></span>
    <?php if ($tab === 'reservations'): ?>
        <?php
            staffTable($rows, function ($row) use ($back) {
                if ($row['status'] !== 'PAID') {
                    return '<span class="staff-pending-status">Awaiting payment</span>';
                }

                return '<form class="staff-row-actions" method="post" action="' . e($back)
                    . '" data-confirm-message="Mark this reservation as completed?">' . csrfField()
                    . '<input type="hidden" name="action" value="complete"><input type="hidden" name="id" value="'
                    . (int) $row['id'] . '"><button>Confirm</button></form>';
            });
        ?>
    <?php else: ?>
        <?php staffTable($rows); ?>
    <?php endif; ?>
</section>
