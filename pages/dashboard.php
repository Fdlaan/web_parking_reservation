<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Sidebar -->
        <?php include("../includes/sidebar.php"); ?>


        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Topbar -->
            <?php include("../includes/topbar.php"); ?>

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column 1 py-4 ms-4 me-4">
                <div class="container py-4">

                    <header class="dashboard pb-3 mb-4">
                        <p class="d-flex align-items-center text-secondary">Current Location
                            <i class="fa-solid fa-location-dot ms-1"></i>
                        </p>
                        <a href="/" class="d-flex align-items-center text-light text-decoration-none fw-medium w-25 text-nowrap">
                            <span class="fs-4">Universitas Indonesia, Depok, Indonesia
                                <i class="fa-solid fa-caret-down"></i>
                            </span>
                        </a>
                    </header>

                    <h3 class="fw-bold text-light mb-4">Reservations</h3>
                    <div class="p-5 mb-4 rounded-4 bg-opacity-10 border border-dark" style="background-color: #2C2C2C;">
                        <div class="container-fluid py-5">

                        </div>
                    </div>

                    <!-- Recommended Parking -->
                    <div class="d-flex mb-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Recommended Parking</h3>
                        <button type="button" class="ms-auto text-nowrap btn btn-outline-warning"
                            style="width: 95px; height: 30px; padding: 0.1em">View More</button>
                    </div>

                    <div class="row align-items-md-stretch">
                        <div class="col-md-6 mb-4">
                            <a href="" class="text-decoration-none">
                                <div class="d-flex flex-column justify-content-center h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                    <p class="mt-2 mb-0">
                                        <i class="fa-solid fa-location-dot me-2"></i>20 KM
                                    </p>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-4">
                            <a href="" class="text-decoration-none">
                                <div class="d-flex flex-column justify-content-center h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                    <p class="mt-2 mb-0">
                                        <i class="fa-solid fa-location-dot me-2"></i>20 KM
                                    </p>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-4">
                            <a href="" class="text-decoration-none">
                                <div class="d-flex flex-column justify-content-center h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                    <p class="mt-2 mb-0">
                                        <i class="fa-solid fa-location-dot me-2"></i>20 KM
                                    </p>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-4">
                            <a href="" class="text-decoration-none">
                                <div class="d-flex flex-column justify-content-center h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                    <p class="mt-2 mb-0">
                                        <i class="fa-solid fa-location-dot me-2"></i>20 KM
                                    </p>

                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Previously Parked -->
                    <div class="d-flex mb-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Previously Parked</h3>
                        <button type="button" class="ms-auto text-nowrap btn btn-outline-warning"
                            style="width: 95px; height: 30px; padding: 0.1em">View More</button>
                    </div>

                    <div class="row g-4">

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div
                                    class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div
                                    class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div
                                    class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="../img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall"
                                        class="w-100 object-fit-fill rounded" style="height: 250px;">

                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                                        <h4 class="fw-bold text-light mb-0">Grand Indonesia Mall</h4>
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </div>

                                </div>
                            </a>
                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>

</body>

</html>