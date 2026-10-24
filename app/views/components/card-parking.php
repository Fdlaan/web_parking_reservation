<?php
$field = $field ?? [];
$variant = $variant ?? 'recommended';

$fileCss[] = 'components/card-parking.css';

$id = (string) ($field['id'] ?? '');
$name = (string) ($field['name'] ?? '');
$image = (string) ($field['image'] ?? '');
$url = (string) ($field['url'] ?? '#');
$alt = (string) ($field['alt'] ?? $name);
$distance = (string) ($field['distance'] ?? '');
$regularPrice = $field['prices']['regular'] ?? null;
$valetPrice = $field['prices']['valet'] ?? null;

$formattedRegularPrice = $regularPrice !== null
    ? 'Rp ' . number_format((float) $regularPrice, 0, ',', '.')
    : 'Unavailable';

$formattedValetPrice = $valetPrice !== null
    ? 'Rp ' . number_format((float) $valetPrice, 0, ',', '.')
    : 'Unavailable';

$showDistance = in_array($variant, ['recommended', 'area'], true) && $distance !== '';
$showPrice = $variant === 'area' && $regularPrice !== null;
$showExternalIcon = in_array($variant, ['recommended', 'previous'], true);
?>

<a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" class="parking-card-item parking-card-item--<?= htmlspecialchars($variant, ENT_QUOTES, 'UTF-8') ?>" <?= $id !== '' ? 'id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>

    <div class="parking-card d-flex flex-column justify-content-center">

        <img
            src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') ?>"
            class="parking-card-image"
            loading="lazy"
        >

        <div class="parking-card-content">

            <div class="parking-card-heading">
                <span class="parking-card-title"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></span>
                <?php if ($showExternalIcon): ?>
                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                <?php endif; ?>
            </div>

            <?php if ($showDistance || $showPrice): ?>

            <div class="parking-card-meta">

                <?php if ($showPrice): ?>
                    <span class="parking-card-price"><?= htmlspecialchars($formattedRegularPrice, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>

                <?php if ($showDistance): ?>
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
