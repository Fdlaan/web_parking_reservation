<?php
    $id = $field['id'] ?? '';
    $name = $field['name'] ?? '';
    $image = $field['image'] ?? '';
    $url = $field['url'] ?? '#';
    $alt = $field['alt'] ?? $name;
?>

<div class="w-100" style="min-height: 150px;">
    <a href="<?= htmlspecialchars($url) ?>" class="text-decoration-none" id="<?= htmlspecialchars($id) ?>">
        <div class="d-flex flex-column justify-content-center h-100" style="background-color: var(--surface-card); padding: 16px; border: 1px solid var(--border-divider); border-radius: var(--radius-xl); gap: 8px;">

            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($alt) ?>" class="w-100 object-fit-cover rounded-4" style="height: 200px; background-color: var(--secondary-text);">

            <div class="d-flex align-items-center justify-content-between">
                <span style="font:var(--text-H4);">
                    <?= htmlspecialchars($name) ?>
                </span>
            </div>

        </div>
    </a>
</div>