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
            <main class="d-flex flex-grow flex-column-1 py-3 ms-4 me-4">
                <div class="container py-4">


                    <div class="d-flex flex-column">
                        <a href="/web_parking_reservation/app/views/parking/slot.php" class="text-decoration-none fw-bold"
                            style="width: fit-content;">
                            <p for="back-icon" style="color: var(--text)">
                                <i class="fa-solid fa-arrow-left-long me-2"></i>
                                Back
                            </p>
                        </a>
                    </div>

                    <div class="row">

                        <div class="col p-3">

                            <div class="card text-light rounded-4 mt-4" style="background-color: #2C2C2C;">
                                <div class="card-body p-4">

                                    <div class="row">
                                        <p class="col-10 fw-medium">Payment Method:
                                            <img class="ms-2" src="/web_parking_reservation/img/qris-logo.png"
                                                alt="QRIS Logo" style="width: 3.5rem; height: 1.5rem;">
                                        </p>
                                        <p class="col-2 d-flex justify-content-center align-items-center rounded-2 border fw-medium"
                                            style="color: #D9B061; background-color: #3D3527; height: 26px; width: min-content; font-size: 14px; --bs-border-color: #D9B061;">
                                            Regular
                                        </p>
                                    </div>

                                    <img src="/web_parking_reservation/img/qr-example.png" alt="QR Code"
                                        class="w-100 object-fit-contain rounded-4 mb-4" style="height: 520px;">

                                    <p class="fs-5 mb-2 fw-medium" style="color: #B5B5AD;">Payment Details:</p>

                                    <hr class="my-3 border-secondary opacity-50">

                                    <div class="row">

                                        <p class="col-9 fs-5 mb-0 fw-medium">Park fee:</p>
                                        <p class="col-3 fs-5 mb-0">Rp 15.000</p>

                                        <p class="col-9 fs-5 mb-0 fw-medium">Service fee:</p>
                                        <p class="col-3 fs-5 mb-0">Rp 2.500</p>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col p-3">
                            <div class="d-flex row container-fluid p-3 justify-content-end">

                                <div class="card text-light rounded-4"
                                    style="background-color: #343434; max-width: 480px;">
                                    <div class="card-body p-4">
                                        <p class="fs-5 mb-2 fw-medium" style="color: #B5B5AD;">Parking Details:</p>

                                        <hr class="my-3 border-secondary opacity-50">

                                        <div class="row">

                                            <p class="col-6 fs-5 mb-0 fw-medium">Parking Lot:</p>
                                            <p class="col-6 fs-5 mb-0">LG-12</p>

                                            <p class="col-6 fs-5 mb-0 fw-medium">Floor:</p>
                                            <p class="col-6 fs-5 mb-0">LG</p>

                                            <p class="col-6 fs-5 mb-0 fw-medium">Reserved Time:</p>
                                            <p class="col-6 fs-5 mb-0">10:00 AM</p>

                                        </div>

                                    </div>
                                </div>

                                <div class="card text-light rounded-4 mt-4"
                                    style="background-color: #343434; max-width: 480px;">
                                    <div class="card-body p-4">
                                        <p class="fs-5 mb-2 fw-medium" style="color: #B5B5AD;">Pay Before:</p>

                                        <p class="fs-5 mb-2 fw-medium text-white">22 Sept 2026, 09.59 WIB</p>

                                        <hr class="my-3 border-secondary opacity-50">

                                        <div class="row justify-content-between">

                                            <p class="col-9 fs-5 mb-0 fw-medium">Payment Status:</p>
                                            <p class="col-3 d-flex justify-content-center align-items-center mb-0 rounded-2 border fw-medium"
                                                style="color: #F19C9C; background-color: #470909; height: 26px; width: min-content; font-size: 14px; --bs-border-color: #F19C9C;">
                                                Unpaid
                                            </p>

                                        </div>

                                    </div>
                                </div>

                                <div class="card text-light rounded-4 mt-4"
                                    style="background-color: #343434; max-width: 480px;">
                                    <div class="card-body p-4">
                                        <p class="fs-5 mb-2 fw-medium" style="color: #B5B5AD;">Total:</p>

                                        <hr class="my-3 border-secondary opacity-50">

                                        <p class="fs-5 mb-0 fw-medium">Rp. 17.500,00</p>

                                    </div>
                                </div>

                                <div class="text-light mt-4" style="max-width: 480px;">
                                        <div class="row">

                                            <a href="/web_parking_reservation/app/views/home/index.php"
                                                class="btn col btn-primary rounded-4 fw-medium pb-2 me-2"
                                                style="background-color: #3D3527; color: #D9B061; border: 1px solid #D9B061; height: 48px; padding-top: 0.65rem;">
                                                Back to Home
                                            </a>

                                            <a href="/web_parking_reservation/app/views/parking/payment_success.php"
                                                class="btn col btn-primary rounded-4 fw-medium pb-2 ms-2"
                                                style="background-color: #D9B061; color: #3D3527; border: none; height: 48px; padding-top: 0.65rem;">
                                                Check Invoice
                                                <i class="fa-solid fa-receipt"></i>
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