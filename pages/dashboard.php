<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body>
    <!-- Wrapper: sidebar + right column side by side -->
    <div class="d-flex">

        <!-- Sidebar -->
        <?php include("../includes/sidebar.php"); ?>


        <!-- Right column: topbar + main content -->
        <div class="flex-grow-1 d-flex flex-column">

            <!-- Topbar -->
            <?php include("../includes/topbar.php"); ?>

            <!-- Main content -->
            <main class="flex-grow-1 p-4">
                <h1 class="h3">Dashboard</h1>
                <p>Your main content goes here.</p>
            </main>

        </div>
    </div>

</body>
</html>