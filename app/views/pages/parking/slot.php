<?php

$titlePage = isset($selectedMall['name'])
    ? $selectedMall['name'] . ' | Parkeasy'
    : 'Parking Slots | Parkeasy';

$fileCss = [
    'pages/parking/slot.css',
    'pages/parking/index.css',
];

?>

<main class="main-content">

    <?php if (($slotPageData['error'] ?? null) !== null): ?>

        <section>
            <a href="?page=parking" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>

            <span><?= parkEasyEscape($slotPageData['error']) ?></span>
        </section>

    <?php else: ?>

        <section>
            <a href="?page=detail&mall_id=<?= rawurlencode($selectedMall['mall_id']) ?>" class="back-button">
                <i class="back-icon"></i>
                <span>Back</span>
            </a>
        </section>

        <section>
            <span class="parking-header">
                <?= parkEasyEscape($selectedMall['name']) ?>
                <div class="parking-type-switch" role="group" aria-label="Parking type">
                    <a
                        href="?page=slot&mall_id=<?= rawurlencode($selectedMall['mall_id']) ?>&parking_type=REGULER<?= $selectedFloor !== null ? '&floor_id=' . rawurlencode($selectedFloor['floor_id']) : '' ?>"
                        class="parking-type-option <?= $parkingType === 'REGULER' ? 'is-active' : '' ?>"
                        <?= $parkingType === 'REGULER' ? 'aria-current="page"' : '' ?>
                    >Regular</a>

                    <a
                        href="?page=slot&mall_id=<?= rawurlencode($selectedMall['mall_id']) ?>&parking_type=VALET<?= $selectedFloor !== null ? '&floor_id=' . rawurlencode($selectedFloor['floor_id']) : '' ?>"
                        class="parking-type-option <?= $parkingType === 'VALET' ? 'is-active' : '' ?>"
                        <?= $parkingType === 'VALET' ? 'aria-current="page"' : '' ?>
                    >Valet</a>
                </div>
            </span>

            <div class="parking-selected-rate"
                data-parking-type="<?= parkEasyEscape($parkingType) ?>">
                <?php if ($selectedRate !== null): ?>
                    <span class="parking-selected-rate-label">Selected rate</span>
                    <strong class="parking-selected-rate-price">
                        Rp <?= number_format((int) $selectedRate['price_per_slot'], 0, ',', '.') ?>
                    </strong>
                    <span class="parking-selected-rate-fee">
                        Admin fee: Rp <?= number_format((int) ($selectedRate['admin_fee'] ?? 0), 0, ',', '.') ?>
                    </span>
                <?php else: ?>
                    <span class="parking-selected-rate-unavailable">Rate is not configured for this parking type.</span>
                <?php endif; ?>
            </div>

            <div class="parking-selected-floor" style="">

                <span class="parking-selected-floor-header">Floor</span>

                <div class="parking-selected-floor-section">

                    <?php foreach ($parkingPageFloors as $index => $floor): ?>
                        <?php if ($index > 0): ?>
                            <hr class="floor-divider">
                        <?php endif; ?>

                        <a
                            href="?page=slot&mall_id=<?= rawurlencode($selectedMall['mall_id']) ?>&parking_type=<?= rawurlencode($parkingType) ?>&floor_id=<?= rawurlencode($floor['floor_id']) ?>"
                            class="floor-btn <?= $selectedFloor !== null && $selectedFloor['floor_id'] === $floor['floor_id'] ? 'active' : '' ?>"
                            <?= $selectedFloor !== null && $selectedFloor['floor_id'] === $floor['floor_id'] ? 'aria-current="page"' : '' ?>
                        ><?= parkEasyEscape($floor['floor_code']) ?></a>
                    <?php endforeach; ?>

                    <?php if (empty($parkingPageFloors)): ?>
                        <span class="p-3">No active floors available.</span>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section>
            <div class="parking-area-floor">
                
                <?php if ($selectedFloor === null): ?>
                    <p>No parking floor has been configured for this mall yet.</p>
                <?php elseif (empty($parkingPageSlots)): ?>
                    <p>No active parking slots are configured for this floor.</p>
                <?php else: ?>
                
                    <?php $slotGroups = array_chunk($parkingPageSlots, 6); ?>
                    <div class="parking-slots-track">

                        <?php foreach ($slotGroups as $group): ?>
                            <div class="slot-group">

                                <?php foreach ($group as $slot): ?>
                                    <?php
                                        $isOccupied = (bool) $slot['is_occupied'];

                                        $slotUrl = '?page=payment'
                                            . '&mall_id=' . rawurlencode($selectedMall['mall_id'])
                                            . '&floor_id=' . rawurlencode($selectedFloor['floor_id'])
                                            . '&slot_id=' . rawurlencode($slot['slot_id'])
                                            . '&parking_type=' . rawurlencode($parkingType);

                                        $slotClass = 'parking-slot'
                                            . ($isOccupied ? ' is-occupied' : ' is-available');
                                    ?>

                                    <?php if ($isOccupied): ?>
                                        <div
                                            class="<?= $slotClass ?>"
                                            aria-label="<?= parkEasyEscape($slot['slot_code']) ?> unavailable"
                                        >
                                            <span class="slot-dot taken"></span>
                                            <span class="slot-label">
                                                <?= parkEasyEscape($slot['slot_code']) ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <a
                                            href="<?= parkEasyEscape($slotUrl) ?>"
                                            class="<?= $slotClass ?>"
                                            aria-label="Select <?= parkEasyEscape($slot['slot_code']) ?>"
                                        >
                                            <span class="slot-dot available"></span>
                                            <span class="slot-label">
                                                <?= parkEasyEscape($slot['slot_code']) ?>
                                            </span>
                                        </a>
                                    <?php endif; ?>

                                <?php endforeach; ?>

                                <?php for ($i = count($group); $i < 6; $i++): ?>
                                    <div
                                        class="parking-slot is-empty"
                                        aria-hidden="true"
                                    ></div>
                                <?php endfor; ?>

                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>

            </div>
        </section>
    <?php endif; ?>
</main>