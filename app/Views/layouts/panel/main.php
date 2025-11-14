<?php

/** @var \CodeIgniter\View\View $this */
?>

<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <?= $this->include('layouts/head') ?>
    <?= $this->renderSection('head') ?>
    <?= $this->include('layouts/style') ?>
    <link type="text/css" rel="stylesheet" href="<?= base_url('css/materialize.min.css') ?>" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="<?= base_url('css/pages/panel.css?timestamp=' . time()) ?>" />
    <?= $this->renderSection('style') ?>
</head>

<body>
    <div class="frame">
        <div class="nav-wrapper">
            <?= $this->include('layouts/panel/nav') ?>
        </div>
        <div class="topbar-wrapper">
            <?= $this->include('layouts/panel/topbar') ?>
        </div>
        <div class="content-wrapper">
            <?= $this->renderSection('main') ?>
        </div>
    </div>
    <div class="popup-wrapper">
        <div class="popup">
            <h1>Center</h1>
        </div>
        <div class="popup side">
            <h1>Center</h1>
        </div>
        <?= $this->renderSection('popup') ?>
    </div>

    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="<?= base_url('js/materialize.min.js') ?>"></script>
    <?= $this->include('layouts/script') ?>
    <script>
        const dt = new DataTable('.init-datatables', {
            responsive: true
        });
        const menuContainer = $('.menu-container');
        const page = '<?= $page ?? 'dashboard' ?>';
        const baseUrl = '<?= base_url() ?>';
        const userId = '<?= auth()->user()->id ?>';
    </script>
    <script src="<?= base_url("js/pages/panel/main.js?timestamp=" . time()) ?>"></script>
    <?php if (auth()->user()->inGroup('admin')) : ?>
        <script src="<?= base_url("js/pages/panel/admin/$page.js?timestamp=" . time()) ?>"></script>
    <?php endif; ?>
    <?php if (auth()->user()->inGroup('user')) : ?>
        <script src="<?= base_url("js/pages/panel/user/$page.js?timestamp=" . time()) ?>"></script>
    <?php endif; ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>