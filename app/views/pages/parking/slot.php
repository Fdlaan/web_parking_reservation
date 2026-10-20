<main class="main-content d-flex flex-column">

    <style>
        .floor-btn {
            color: #f1f1f1;
            border: 1px solid transparent;
            flex: 1 1 0;
            /* equal widths, regardless of text length */
            text-align: center;
        }

        .floor-btn:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .floor-btn.active {
            color: #e0b45a;
            background-color: #4a3b24;
            border-color: #e0b45a;
        }

        .floor-divider {
            width: 1px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.12);
            flex-shrink: 0;
        }

        .slot-block {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-top: 2px solid #3a3a3a;
        }

        .slot {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            height: 118px;
            color: #b5b5b5;
            font-size: 1.15rem;
            text-decoration: none;
            border-bottom: 2px solid #3a3a3a;
        }

        /* left column of each block gets the vertical divider */
        .slot:nth-child(odd) {
            border-right: 2px solid #3a3a3a;
        }

        a.slot:hover {
            background-color: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .slot-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.06);
            /* outer halo */
        }

        .slot-dot.available {
            background-color: #1f3a2d;
            border: 2px solid #6fd49b;
        }

        .slot-dot.taken {
            background-color: #5a1f22;
            border: 2px solid #e57373;
        }
    </style>
    
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column-1 py-4 ms-4 me-4">
                <div class="container py-4">


                    <div class="d-flex flex-column">
                        <a href="/web_parking_reservation/app/views/home/index.php" class="text-decoration-none fw-bold"
                            style="width: fit-content;">
                            <p for="back-icon" style="color: var(--text)">
                                <i class="fa-solid fa-arrow-left-long me-2"></i>
                                Back
                            </p>
                        </a>
                    </div>

                    <div class="row align-items-center mt-4">

                        <!-- Title + subtitle -->
                        <div class="col-12 col-lg-5">
                            <p class="fs-1 fw-medium text-white mb-0">Grand Indonesia Mall</p>
                            <span style="color: #e0b45a;">Regular Parking</span>
                        </div>

                        <!-- Floor selector -->
                        <div class="col-12 col-lg-7 mt-3 mt-lg-0">
                            <div class="d-flex align-items-center gap-4 p-1 rounded-4 text-light"
                                style="background-color: #2C2C2C;">

                                <h2 class="fw-bold fs-3 mb-1 ms-2">Floor</h2>

                                <!-- Track -->
                                <div class="d-flex flex-grow-1 align-items-center rounded-4"
                                    style="background-color: #343434;">

                                    <button type="button"
                                        class="floor-btn active btn d-flex justify-content-center align-items-center flex-fill rounded-4 py-3 fs-4">LG</button>
                                    <span class="floor-divider"></span>

                                    <button type="button"
                                        class="floor-btn btn d-flex justify-content-center align-items-center flex-fill rounded-4 py-3 fs-4">GF</button>
                                    <span class="floor-divider"></span>

                                    <button type="button"
                                        class="floor-btn btn d-flex justify-content-center align-items-center flex-fill rounded-4 py-3 fs-4">1</button>
                                    <span class="floor-divider"></span>

                                    <button type="button"
                                        class="floor-btn btn d-flex justify-content-center align-items-center flex-fill rounded-4 py-3 fs-4">2</button>
                                    <span class="floor-divider"></span>

                                    <button type="button"
                                        class="floor-btn btn d-flex justify-content-center align-items-center flex-fill rounded-4 py-3 fs-4">3</button>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="d-flex col-2 rounded-3 py-1 px-1 border mt-5 justify-content-between"
                                style="background-color: #3D3527; --bs-border-color: #D9B061;">

                                <a href="#"
                                    class="d-flex justify-content-center align-items-center rounded-2 me-3 text-decoration-none fw-bold w-100"
                                    style="background-color: #D9B061; color: #3D3527; height: 26px; font-size: 14px;">
                                    Regular
                                </a>

                                <a href="#"
                                    class="d-flex justify-content-center align-items-center rounded-2 text-decoration-none fw-bold w-100"
                                    style="background-color: #D9B061; color: #3D3527; height: 26px; font-size: 14px;">
                                    Valet
                                </a>

                            </div>
                        </div>

                        <!-- Parking Slot Demo -->

                        <?php
                        // Demo data: 36 slots. Replace this with rows from your database.
                        $slots = [];
                        for ($i = 1; $i <= 36; $i++) {
                            $slots[] = [
                                'code' => 'LG-' . $i,
                                'taken' => $i % 2 === 0,    // example: even numbers are taken
                            ];
                        }
                        $blocks = array_chunk($slots, 6);   // 6 slots per block
                        ?>

                        <div class="mt-5 p-5 text-light rounded-4" style="background-color: #2C2C2C;">
                            <div class="row row-cols-1 row-cols-lg-3 gx-5 gy-5">

                                <?php foreach ($blocks as $block): ?>
                                    <div class="col">
                                        <div class="slot-block">

                                            <?php foreach ($block as $i => $slot): ?>
                                                <?php
                                                $isLeft = $i % 2 === 0;
                                                $dot = '<span class="slot-dot ' . ($slot['taken'] ? 'taken' : 'available') . '"></span>';
                                                $label = '<span>' . htmlspecialchars($slot['code']) . '</span>';
                                                $content = $isLeft ? $dot . $label : $label . $dot;
                                                ?>

                                                <?php if ($slot['taken']): ?>
                                                    <div class="slot">
                                                        <?= $content ?>
                                                    </div>
                                                <?php else: ?>
                                                    <a href="#" class="slot">
                                                        <?= $content ?>
                                                    </a>
                                                <?php endif; ?>

                                            <?php endforeach; ?>

                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>

                    </div>

                </div>

            </main>
        </div>

    </div>

</main>