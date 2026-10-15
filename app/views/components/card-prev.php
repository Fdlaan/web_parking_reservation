<?php
    $id = $field['id'] ?? '';
    $name = $field['name'] ?? '';
    $image = $field['image'] ?? '';
    $url = $field['url'] ?? '#';
    $alt = $field['alt'] ?? $name;
?>

<div class="w-100" style="min-height: 150px;">
    <a href="<?= htmlspecialchars($url) ?>" class="text-decoration-none" id="<?= htmlspecialchars($id) ?>">
        <div class="d-flex flex-column justify-content-center h-100" style="background-color: var(--surface-card); padding: 16px; border: 1px solid var(--border-divider); border-radius: var(--radius-xl); gap: 16px;">

            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($alt) ?>" class="w-100 object-fit-cover rounded-4" style="height: 200px; background-color: var(--secondary-text);">

            <span class="d-flex align-items-center gap-2" style="font: var(--text-H4); min-width: 0;">
                <span style="flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <?= htmlspecialchars($name) ?>
                </span>
                <i class="fa-solid fa-arrow-up-right-from-square" style="color: currentColor; flex: 0 0 auto;"></i>
            </span>

        </div>
    </a>
</div>