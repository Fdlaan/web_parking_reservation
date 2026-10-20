<main class="main-content d-flex flex-column">
<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column-1 py-3 ms-4 me-4">
                <div class="container py-4">


                    <div class="d-flex flex-column">
                        <a href="/web_parking_reservation/app/views/parking/slot.php"
                            class="text-decoration-none fw-bold" style="width: fit-content;">
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
                                        <p class="col-12 fs-3 fw-medium">Invoice : BRIAN#123</p>
                                    </div>

                                    <p class="mb-2 fw-medium" style="color: #B5B5AD;">Invoice Time & Date: 22 Sept 2026, 14.00 WIB</p>

                                    <hr class="my-3 border-secondary opacity-50">

                                    <div class="row justify-content-between align-items-center">

                                        <p class="col-9 fs-5 mb-2 fw-medium">Parking Lot:</p>
                                        <p class="col-3 fs-5 mb-2 text-end">LG-12</p>

                                        <p class="col-9 fs-5 mb-2 fw-medium">Parking Type:</p>
                                        <p class="col-3 mb-2 text-end d-flex justify-content-center align-items-center rounded-2 border fw-medium"
                                            style="color: #D9B061; background-color: #3D3527; height: 26px; width: min-content; font-size: 14px; --bs-border-color: #D9B061;">
                                            Regular
                                        </p>

                                        <p class="col-9 fs-5 mb-2 fw-medium">Plate Number:</p>
                                        <p class="col-3 fs-5 mb-2 text-end">B 1234 FSD</p>

                                        <p class="col-8 fs-5 mb-2 fw-medium">Reservation Date:</p>
                                        <p class="col-4 fs-5 mb-2 text-end">22 Sept 2026</p>

                                        <p class="col-9 fs-5 mb-2 fw-medium">Reserved Time:</p>
                                        <p class="col-3 fs-5 text-end mb-2">16:00</p>

                                        <p class="col-9 fs-5 mb-2 fw-medium">Payment Method:</p>
                                        <p class="col-3 fs-5 text-end mb-2">
                                            <img src="/web_parking_reservation/img/qris-logo.png"
                                                alt="QRIS Logo" style="width: 3.5rem; height: 1.5rem;">
                                        </p>

                                        <p class="col-9 fs-5 mb-2 fw-medium">Reservation Fee:</p>
                                        <p class="col-3 fs-5 mb-2 text-end">Rp 15.000</p>

                                        <p class="col-9 fs-5 mb-0 fw-medium">Service Fee:</p>
                                        <p class="col-3 fs-5 text-end mb-0">Rp 2.500</p>

                                    </div>

                                    <hr class="my-3 border-secondary opacity-50">

                                    <p class="mb-2 fw-medium" style="color: #B5B5AD;">Note:</p>
                                    <p class="mb-0" style="color: #B5B5AD;">This invoice is only for the reservation excluding parking fees.</p>
                                    <p class="mb-0" style="color: #B5B5AD;">Your reservation will expire if the parking spot is not occupied within 1 hour.</p>
                                    <p class="mb-0" style="color: #B5B5AD;">Please complete the payment before the due date to avoid any penalties.</p>

                                </div>
                            </div>
                        </div>

                        <div class="col p-3">
                            <div class="d-flex row container-fluid p-3 justify-content-end">

                                <div class="card text-light rounded-4 mt-2"
                                    style="background-color: #343434; max-width: 480px;">
                                    <div class="card-body p-4">
                                        <p class="fs-5 mb-2 fw-medium" style="color: #B5B5AD;">Pay Before:</p>

                                        <p class="fs-5 mb-2 fw-medium text-white">22 Sept 2026, 15.59 WIB</p>

                                        <hr class="my-3 border-secondary opacity-50">

                                        <div class="row justify-content-between align-items-center">

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

                                        <a href="/web_parking_reservation/app/views/parking/payment.php"
                                            class="btn col btn-primary rounded-4 fw-medium pb-2 me-2"
                                            style="background-color: #173D2B; color: #79D5A2; border: 1px solid #79D5A2; height: 48px; padding-top: 0.65rem;">
                                            Pay Now
                                        </a>

                                        <a href="/web_parking_reservation/app/views/parking/invoice.php"
                                            class="btn col btn-primary rounded-4 fw-medium pb-2 ms-2"
                                            style="background-color: #D9B061; color: #3D3527; border: none; height: 48px; padding-top: 0.65rem;">
                                            Download Invoice
                                            <i class="fa-solid fa-file-arrow-down"></i>
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            </main>
        </div>

    </div>
</main>