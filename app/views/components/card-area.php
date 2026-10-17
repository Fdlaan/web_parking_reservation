<?php
    $id = $field['id'] ?? '';
    $name = $field['name'] ?? '';
    $image = $field['image'] ?? '';
    $price = $field['price'] ?? '';
    $distance = $field['distance'] ?? '';
    $url = $field['url'] ?? '#';
    $alt = $field['alt'] ?? $name;
?>

<div class="parking-card-item" style="min-height: 150px;">
    <a href="<?= htmlspecialchars($url) ?>" class="parking-card-link text-decoration-none" id="<?= htmlspecialchars($id) ?>">
        <div class="d-flex flex-column justify-content-center h-100" style="min-width: 0; background-color: var(--surface-card); padding: 16px; border: 1px solid var(--border-divider); border-radius: var(--radius-xl); gap: 16px;">

            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($alt) ?>" class="w-100 object-fit-cover rounded-4" style="height: 200px; background-color: var(--secondary-text);">

            <div class="d-flex flex-column">
                <span class="parking-card-heading">
                    <span class="parking-card-title">
                        <?= htmlspecialchars($name) ?>
                    </span>
                </span>
                <div class="d-flex align-items-center justify-content-start gap-1" style="color: var(--secondary-text); font: var(--text-label);">
                    <span class></span>
                    <span></span>

                    <i class="fa-solid fa-location-dot"></i>
                    <?= htmlspecialchars($distance) ?>
                </div>
            </div>
        </div>
    </a>
</div>