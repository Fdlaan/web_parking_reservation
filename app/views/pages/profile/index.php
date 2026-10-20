<main class="main-content d-flex flex-column">
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column" style="background: var(--panel);">

            <!-- Main content -->
            <main class="d-flex flex-grow flex-column-1 py-4 ms-4 me-4">
                <div class="container py-4">

                    <div class="p-3 rounded-4 bg-opacity-10 border border-dark" style="background-color: #2C2C2C;">
                        <div class="d-flex row container-fluid p-3">
                            
                            <div class="col-3">
                                <img src="/web_parking_reservation/img/grand-indonesia-mall.jpg" alt="Profile Picture" class="w-100 object-fit-fill rounded" style="height: 250px;">
                            </div>

                            <div class="col d-flex flex-column">
                                <div class="row">

                                    <div class="col-md-4 form-field">
                                        <label for="username" class="form-label text-light">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Brian Ilda" disabled>
                                    </div>

                                    <div class="col-md-4 form-field">
                                        <label for="email" class="form-label text-light">Email</label>
                                        <input type="email" class="form-control" id="email"
                                            placeholder="email@example.com" disabled>
                                    </div>

                                    <div class="col-md-4 form-field">
                                        <label for="phone" class="form-label text-light">Phone</label>
                                        <input type="tel" class="form-control" id="phone" placeholder="08134567890" disabled>
                                    </div>

                                    <div class="col-12 form-field mt-3">
                                        <label for="plate" class="form-label text-light">Plate Number</label>
                                        <input type="text" class="form-control" id="plate" placeholder="B 1234 FSD" disabled>
                                    </div>

                                </div>

                                <button type="button" class="ms-auto mt-auto text-nowrap btn btn-outline-warning"
                                        style="width: 105px; height: 30px; padding: 0.1em; font-size: 12px;">
                                        Edit Profile
                                        <i class="fa-solid fa-pen-to-square ms-1"></i>
                                    </button>

                            </div>

                        </div>

                    </div>

                </div>
            </main>

        </div>
    </div>
</main>