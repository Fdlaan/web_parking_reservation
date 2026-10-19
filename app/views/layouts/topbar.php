<?php
    $file_css = [
        'components/layouts/topbar.css',
    ];
?>

<header class="topbar d-flex flex-row sticky-top">
    <div class="topbar-item-left">
        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb">
                
                <?php foreach ($breadcrumb ?? [] as $index => $item): ?>
                <li class="breadcrumb-item">

                    <?php if ($item['url'] !== null): ?>
                    <a href="<?= htmlspecialchars($item['url']) ?>">
                        <?= htmlspecialchars($item['label']) ?>
                    </a>

                    <?php else: ?>
                    <span aria-current="page">
                        <?= htmlspecialchars($item['label']) ?>
                    </span>

                    <?php endif; ?>

                </li>
                <?php endforeach; ?>

            </ol>
        </nav>
    </div>

    <div class="topbar-item-right">
        <a href="#" class="notification" aria-label="Notifications">
            <span class="notification-icon" aria-hidden="true"></span>
        </a>

        <a href="#" class="d-flex align-items-center text-uppercase text-decoration-none profile">
            <img src="https://github.com/mdo.png" alt="mdo" width="36" height="36">
            Placeholder Name
        </a>
    </div>
</header>