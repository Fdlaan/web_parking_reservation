<?php

$titlePage = isset($selectedMall['name'])
    ? $selectedMall['name'] . ' | Parkeasy'
    : 'Parking Slots | Parkeasy';

$fileCss = [
    'pages/parking/slot.css',
    'pages/parking/index.css',
];

$fileJs = [
    'pages/slot.js',
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
                <span class="parking-empty-state">No parking floor has been configured for this mall yet.</span>
            <?php elseif (empty($parkingPageSlots)): ?>
                <span class="parking-empty-state">No <?= $parkingType === 'VALET' ? 'valet' : 'regular' ?> slots on this floor.</span>
            <?php else: ?>
            
                <?php $slotGroups = array_chunk($parkingPageSlots, 6); ?>
                <div class="parking-slots-track">

                    <?php foreach ($slotGroups as $group): ?>
                        <div class="slot-group">

                            <?php foreach ($group as $slot): ?>
                                <?php
                                    $isOccupied = (bool) $slot['is_occupied'];

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
                                    <button
                                        type="button"
                                        class="<?= $slotClass ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#reserveModal"
                                        data-slot-id="<?= parkEasyEscape($slot['slot_id']) ?>"
                                        data-slot-code="<?= parkEasyEscape($slot['slot_code']) ?>"
                                        aria-label="Select <?= parkEasyEscape($slot['slot_code']) ?>"
                                    >
                                        <span class="slot-dot available"></span>
                                        <span class="slot-label">
                                            <?= parkEasyEscape($slot['slot_code']) ?>
                                        </span>
                                    </button>
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

    <?php
        $timeOptions = parkEasyTimeOptions();
        $slotTotal = (int) ($selectedPrice ?? 0) + (int) ($selectedAdminFee ?? 0);
        $hasActiveReservation = (bool) array_filter(
            $reservations,
            fn($r) => $r['user_id'] === $currentUserId && in_array($r['status'], ['UNPAID', 'PAID'], true)
        );
        $canReserve = !$hasActiveReservation && !empty($vehicles) && $selectedRate !== null && !empty($timeOptions);
    ?>

    <div class="modal fade reserve-modal" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="?page=reserve" method="POST">
                    <?= csrfField() ?>
                    <input type="hidden" name="slot_id" value="">

                    <span class="reserve-title" id="reserveModalTitle" data-slot-title>Parking slot</span>

                    <?php if ($hasActiveReservation): ?>
                        <span class="reserve-note">
                            You already have an active reservation. Finish or cancel it on the
                            <a href="?page=home">Home page</a> before reserving another slot.
                        </span>
                    <?php endif; ?>

                    <div class="reserve-field">
                        <label for="reserveVehicle">Plate number</label>

                        <?php if (empty($vehicles)): ?>
                            <span class="reserve-note">
                                You have no plate number yet.
                                <a href="?page=profile">Add one in your profile</a> first.
                            </span>
                        <?php else: ?>
                            <select id="reserveVehicle" name="vehicle_id" required>
                                <option value="" disabled selected>Choose plate number</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                    <option value="<?= (int) $vehicle['id'] ?>"><?= parkEasyEscape($vehicle['plate_number']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="reserve-field">
                        <label for="reserveTime">Reservation time</label>
                        <select id="reserveTime" name="reserved_at" required>
                            <?php foreach ($timeOptions as $value => $label): ?>
                                <option value="<?= parkEasyEscape($value) ?>"><?= parkEasyEscape($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small>You can reserve up to <?= PARKEASY_MAX_AHEAD_HOURS ?> hours from now.</small>
                    </div>

                    <div class="reserve-field">
                        <label for="reserveType">Parking type</label>
                        <input type="text" id="reserveType" value="<?= $parkingType === 'VALET' ? 'Valet' : 'Regular' ?>" disabled>
                    </div>

                    <div class="reserve-actions">
                        <button type="button" class="reserve-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="reserve-submit" <?= $canReserve ? '' : 'disabled' ?>>Reservation</button>
                    </div>

                    <div class="reserve-total">
                        <span>Total (incl. admin fee):</span>
                        <strong><small>Rp</small> <?= number_format($slotTotal, 0, ',', '.') ?></strong>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
</main>