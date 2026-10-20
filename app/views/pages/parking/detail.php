<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>


        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Topbar -->
            <?php include __DIR__ . '/../layouts/topbar.php'; ?>

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

                    <div class="row">

                        <div class="col p-3">
                            <p class="fs-1 fw-medium text-white">Grand Indonesia Mall</p>

                            <!-- Picture -->
                            <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                class="w-100 object-fit-cover rounded-4" style="height: 300px;">

                            <!-- Address card -->
                            <div class="card text-light rounded-4 mt-4" style="background-color: #2C2C2C;">
                                <div class="card-body p-4">

                                    <p class="mb-2 text-secondary">Address:</p>

                                    <p class="fs-5 mb-0">
                                        Jalan M.H. Thamrin No.1, RT.1/RW.5, Kelurahan Menteng, Kecamatan Menteng, dan
                                        Kelurahan Kebon Melati, Kecamatan Tanah Abang, Jakarta Pusat 10310
                                    </p>

                                    <hr class="my-3 border-secondary opacity-50">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-secondary">
                                            <i class="fa-solid fa-location-dot me-2"></i>7.1 KM From your location
                                        </span>

                                        <a href="#" class="btn d-flex align-items-center px-4 py-2 fw-medium rounded-3"
                                            style="background-color: #1b3f2b; border: 1px solid #6fd49b; color: #6fd49b;">
                                            <i class="fa-regular fa-map me-3"></i>Open with Maps
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col p-3">
                            <div class="d-flex row container-fluid p-3 justify-content-end">

                                <div class="card text-light rounded-4"
                                    style="background-color: #2C2C2C; max-width: 480px;">
                                    <div class="card-body p-4">

                                        <!-- Title -->
                                        <h2 class="fw-bold fs-3 mb-0">Slot Available</h2>
                                        <hr class="my-4 border-secondary opacity-50">

                                        <!-- Floor rows -->
                                        <div class="d-flex flex-column gap-3">

                                            <div class="row g-0 align-items-center rounded-3 px-4 py-4"
                                                style="background-color: #343434;">
                                                <div class="col fs-5 fw-semibold">LG</div>
                                                <div class="col text-center fs-5 fw-bold text-success-emphasis"
                                                    style="color: #6fd49b !important;">14</div>
                                                <div class="col text-end fs-3"><i class="fa-solid fa-car"></i></div>
                                            </div>

                                            <div class="row g-0 align-items-center rounded-3 px-4 py-4"
                                                style="background-color: #343434;">
                                                <div class="col fs-5 fw-semibold">GF</div>
                                                <div class="col text-center fs-5 fw-bold" style="color: #6fd49b;">12
                                                </div>
                                                <div class="col text-end fs-3"><i class="fa-solid fa-car"></i></div>
                                            </div>

                                            <div class="row g-0 align-items-center rounded-3 px-4 py-4"
                                                style="background-color: #343434;">
                                                <div class="col fs-5 fw-semibold">1</div>
                                                <div class="col text-center fs-5 fw-bold" style="color: #6fd49b;">8
                                                </div>
                                                <div class="col text-end fs-3"><i class="fa-solid fa-car"></i></div>
                                            </div>

                                            <div class="row g-0 align-items-center rounded-3 px-4 py-4"
                                                style="background-color: #343434;">
                                                <div class="col fs-5 fw-semibold">2</div>
                                                <div class="col text-center fs-5 fw-bold" style="color: #6fd49b;">8
                                                </div>
                                                <div class="col text-end fs-3"><i class="fa-solid fa-car"></i></div>
                                            </div>

                                            <div class="row g-0 align-items-center rounded-3 px-4 py-4"
                                                style="background-color: #343434;">
                                                <div class="col fs-5 fw-semibold">3</div>
                                                <div class="col text-center fs-5 fw-bold" style="color: #6fd49b;">8
                                                </div>
                                                <div class="col text-end fs-3"><i class="fa-solid fa-car"></i></div>
                                            </div>

                                        </div>

                                        <!-- Button -->
                                        <a href="#" class="btn w-100 mt-4 py-3 fw-semibold rounded-3"
                                            style="background-color: #1b3f2b; color: #6fd49b;">
                                            Choose Parking Spot
                                        </a>

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

        </div>
        </main>

    </div>
    </div>

</body>

</html>