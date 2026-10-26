<?php
// One success/error message per page load, set with flash('text') or flash('!error text').
$flashMessage = flash();
?>
<?php if ($flashMessage !== null): ?>
    <?php $flashIsError = str_starts_with($flashMessage, '!'); ?>
    <div class="flash-stack">
        <div class="alert alert-<?= $flashIsError ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
            <?= parkEasyEscape($flashIsError ? substr($flashMessage, 1) : $flashMessage) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const alertEl = document.querySelector('.flash-stack .alert');
            if (alertEl) bootstrap.Alert.getOrCreateInstance(alertEl).close();
        }, 6000);
    </script>
<?php endif; ?>
