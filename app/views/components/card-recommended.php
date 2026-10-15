<?php
    $id = $field['id'] ?? '';
    $name = $field['name'] ?? '';
    $image = $field['image'] ?? '';
    $distance = $field['distance'] ?? '';
    $url = $field['url'] ?? '#';
    $alt = $field['alt'] ?? $name;
?>

<div class="w-100"style="min-height: 150px;">
    <a href="<?= htmlspecialchars($url) ?>" class="text-decoration-none" stye="" id="<?= htmlspecialchars($id) ?>">
        <div class="d-flex flex-column justify-content-center h-100" style="background-color: var(--surface-card); padding: 16px; border: 1px solid var(--border-divider); border-radius: var(--radius-xl); gap: 16px;">

            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($alt) ?>" class="w-100 object-fit-cover rounded-4" style="height: 200px; background-color: var(--secondary-text);">

            <div class="d-flex flex-column">
                <span class="d-flex align-items-center justify-content-between gap-2" style="font:var(--text-H4); flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <?= htmlspecialchars($name) ?>
                    <i class="fa-solid fa-arrow-up-right-from-square" style="color: currentColor;"></i>
                </span>
                <div class="d-flex align-items-center justify-content-start gap-1" style="color: var(--secondary-text); font: var(--text-label);">
                    <i class="fa-solid fa-location-dot"></i>
                    <?= htmlspecialchars($distance) ?>
                </div>
            </div>
        </div>
    </a>
</div>