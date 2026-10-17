<?php
    $field = $field ?? [];
    $variant = $variant ?? 'recommended';

    $id = (string) ($field['id'] ?? '');
    $name = (string) ($field['name'] ?? '');
    $image = (string) ($field['image'] ?? '');
    $url = (string) ($field['url'] ?? '#');
    $alt = (string) ($field['alt'] ?? $name);
    $distance = (string) ($field['distance'] ?? '');
    $price = (string) ($field['price'] ?? '');

    $show_distance = in_array($variant, ['recommended', 'area'], true) && $distance !== '';
    $show_price = $variant === 'area' && $price !== '';
    $show_external_icon = in_array($variant, ['recommended', 'previous'], true);
?>
<article class="parking-card-item parking-card-item--<?= htmlspecialchars($variant, ENT_QUOTES, 'UTF-8') ?>">
    <a
        href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>"
        class="parking-card-link text-decoration-none"
        <?= $id !== '' ? 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"' : '' ?>
    >
        <div class="parking-card">
            <img
                src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') ?>"
                class="parking-card-image"
                loading="lazy"
            >

            <div class="parking-card-content">
                <div class="parking-card-heading">
                    <h4 class="parking-card-title"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h4>
                    <?php if ($show_external_icon): ?>
                        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                    <?php endif; ?>
                </div>

                <?php if ($show_distance || $show_price): ?>
                    <div class="parking-card-meta">
                        <?php if ($show_price): ?>
                            <span class="parking-card-price"><?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>

                        <?php if ($show_distance): ?>
                            <span class="parking-card-distance">
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                <?= htmlspecialchars($distance, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </a>
</article>
