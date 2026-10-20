<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/config/app.php';
    
    $page = $_GET['page'] ?? 'home';

    $routes = [
        'home' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/home/index.php',
        'parking' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/parking/index.php',
        'history' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/history/index.php',
        'profile' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/profile/index.php',
        'login' => $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/pages/auth/login.php',
    ];

    $show_layout = ($page !== 'login');

    if (!isset($routes[$page])) {
        http_response_code(400);
        $title_page = 'Page not Found | Parkeasy';
        $file_css = [];
        $file_js = [];

        ob_start();
        echo '<main class="main-content">';
        echo '<h1>400 - Page not found</h1>';
        echo '<p>The page you requested could not be found.</p>';
        echo '<a href="?page=home">Back to home.</a>';
        echo '</main>';
        $page_content = ob_get_clean();

        $show_layout = false;

    } else {
        $currentPage = $page;
        $show_layout = ($page !== 'login');

        $breadcrumb = [
            [
                'label' => ucfirst($page),
                'url' => null,
            ],
        ];

        $title_page = 'Parkeasy';
        $file_css = [];
        $file_js = [];

        ob_start();
        require $routes[$page];
        $page_content = ob_get_clean();

        if ($show_layout) {
            $file_css = array_merge($file_css, [
                '/components/icon.css',
                '/layouts/sidebar.css',
                '/layouts/topbar.css',
                '/layouts/footer.css',
            ]);
        }
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<!-- Head Section -->
<?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/includes/head.php'; ?>

<body>

<?php if ($show_layout): ?>

<div class="layout d-flex align-items-stretch">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/layouts/sidebar.php'; ?>
    
    <div class="content d-flex flex-column">

    <?php require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/app/views/layouts/topbar.php'; ?>

        <?= $page_content ?>

    </div>
</div>

<?php else: ?>
    
    <?= $page_content ?>

<?php endif; ?>
    
</body>
</html>