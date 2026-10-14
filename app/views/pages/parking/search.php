<?php
    $title_page = 'Search | Parkeasy';

    $file_css = [
        'components/icon.css',
        'pages/parking.css',
        'layouts/sidebar.css',
        'layouts/topbar.css',
        'layouts/footer.css',
    ];
?>

<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/../../includes/head.php'; ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>


        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Topbar -->
            <?php include __DIR__ . '/../../layouts/topbar.php'; ?>

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column 1 py-4 ms-4 me-4">
                <div class="container py-4">

                    <header class="dashboard pb-3 mb-4">
                        <p class="d-flex align-items-center text-secondary">Current Location
                            <i class="fa-solid fa-location-dot ms-1"></i>
                        </p>
                        <a href="/" class="d-flex align-items-center mb-4 text-light text-decoration-none fw-medium w-25 text-nowrap">
                            <span class="fs-4">Universitas Indonesia, Depok, Indonesia
                                <i class="fa-solid fa-caret-down"></i>
                            </span>
                        </a>
                        <button type="button" class="d-flex align-items-center" style="border-radius: 8px; background: var(--bs-body-bg);" data-bs-toggle="dialog" data-bs-target="#bdSearchDialog" aria-haspopup="dialog" aria-label="Search">
                            <use href="#search"></use>
                            <i class="fa-solid fa-magnifying-glass m-3" style="color: 454545"></i>
                            <span class="bd-search-trigger-label" style="color: #B5B5AD">Search Here</span>
                        </button>
                    </header>

                    <!-- Locations -->
                    <div class="d-flex mb-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Jakarta Pusat</h3>
                    </div>

                    <div class="row g-4">

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                    </div>

                    <!-- Locations -->
                    <div class="d-flex mb-3 mt-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Jakarta Selatan</h3>
                    </div>

                    <div class="row g-4">

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                    </div>

                    <!-- Locations -->
                    <div class="d-flex mb-3 mt-3 align-items-center">
                        <h3 class="fw-bold flex-shrink 0 text-light">Jakarta Barat</h3>
                    </div>

                    <div class="row g-4">

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="" class="d-block w-100 h-100 text-decoration-none">
                                <div class="d-flex flex-column justify-content-center w-100 h-100 p-3 text-bg-dark rounded-3">

                                    <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Grand Indonesia Mall" class="w-100 object-fit-fill rounded" style="height: 250px;">
                                    
                                    <h4 class="d-flex mt-4 mb-2 fw-bold text-light">Grand Indonesia Mall</h4>

                                    <div class="d-flex justify-content-between mt-4">
                                        <p class="mb-0">Rp. 15.000 per Slot</p>
                                        <p class="pt-1 mb-0" style="font-size: 12px"><i class="fa-solid fa-location-dot"></i>7.1 KM</p>
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