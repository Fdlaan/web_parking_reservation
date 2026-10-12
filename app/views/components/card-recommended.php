<?php
    $id = $field['id'] ?? '';
    $name = $field['name'] ?? '';
    $image = $field['image'] ?? '';
    $distance = $field['distance'] ?? '';
    $url = $field['url'] ?? '#';
    $alt = $field['alt'] ?? $name;
?>

<div class="col-md-6 mb-4">
    <a href="<?= htmlspecialchars($url) ?>" class="text-decoration-none" id="<?= htmlspecialchars($id) ?>">
        <div class="d-flex flex-column justify-content-center h-100 p-4 text-bg-dark rounded-4">

            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($alt) ?>" class="w-100 object-fit-cover rounded-4" style="height: 300px;">

            <div class="d-flex align-items-center justify-content-between mt-4">
                <h3 class="fw-bold text-light mb-0">
                    <?= htmlspecialchars($name) ?>
                </h3>
                <i class="fa-solid fa-arrow-up-right-from-square text-warning"></i>
            </div>

            <p class="mt-2 mb-0 text-secondary">
                <i class="fa-solid fa-location-dot me-2"></i>
                <?= htmlspecialchars($distance) ?>
            </p>

        </div>
    </a>
</div>