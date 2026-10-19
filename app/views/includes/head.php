<?php
    $title_page = $title_page ?? 'Parkeasy';

    $file_css = $file_css ?? [];
    $file_js = $file_js ?? [];

    $asset_url = '/web_parking_reservation/public/assets';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title_page, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/5ba89cd74f.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS & JS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= $asset_url ?>/css/base.css">
    <link rel="stylesheet" href="<?= $asset_url ?>/css/layouts/sidebar.css">
    <link rel="stylesheet" href="<?= $asset_url ?>/css/layouts/topbar.css">
    <link rel="stylesheet" href="<?= $asset_url ?>/css/layouts/footer.css">
    <link rel="stylesheet" href="<?= $asset_url ?>/css/layouts/footer.css">

    <!-- Layout CSS -->
    <?php foreach ($file_css as $css): ?>
    <link rel="stylesheet" href="<?= $asset_url ?>/css/<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8') ?>">
    <?php endforeach; ?>

    <!-- Custom JS -->
    <?php foreach ($file_js as $js): ?>
    <script src="<?= $asset_url ?>/js/<?= htmlspecialchars($js, ENT_QUOTES,'UTF-8') ?>"defer></script>
    <?php endforeach; ?>

</head>