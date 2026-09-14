<?php
/**
 * Authenticated page layout.
 *
 * @var string $content
 * @var string $title
 * @var \App\Core\Auth $auth
 * @var array<string, mixed> $user
 * @var array<int, array<string, mixed>> $sidebarClassrooms
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f3b38">
    <title><?= e($title ?? '') ?> | <?= e(config('app.name')) ?></title>

    <script src="<?= asset('js/theme.js') ?>"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/app.css') ?>" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <?php $this->insert('partials/sidebar', [
            'auth' => $auth,
            'user' => $user,
            'classrooms' => $sidebarClassrooms,
        ]) ?>

        <div class="sidebar-backdrop" aria-hidden="true"></div>

        <div id="page-wrapper">
            <?php $this->insert('partials/topbar') ?>
            <?php $this->insert('partials/flash') ?>

            <main class="page-body">
                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
