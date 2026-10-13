<?php
    $page = $_GET['page'] ?? 'home';
    $currentPage = $page;

    $routes = [
        'home' => __DIR__ . '/../app/views/pages/home/index.php',
        'parking' => __DIR__ . '/../app/views/pages/parking/search.php',
        'history' => __DIR__ . '/../app/views/pages/history/index.php',
        'profile' => __DIR__ . '/../app/views/pages/profile/index.php',
        'login' => __DIR__ . '/../app/views/pages/auth/login.php',
    ];

    require $routes[$page];
?>