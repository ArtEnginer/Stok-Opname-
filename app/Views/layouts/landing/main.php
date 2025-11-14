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
    <link type="text/css" rel="stylesheet" href="<?= base_url('css/pages/landing.css') ?>" />
    <?= $this->renderSection('style') ?>
</head>

<body>
    <div class="parent-wrapper">
        <?= $this->renderSection('main') ?>
    </div>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="<?= base_url('js/materialize.min.js') ?>"></script>
    <?= $this->include('layouts/script') ?>
    <script>
        const baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('js/pages/landing/main.js') ?>"></script>
    <?= $this->renderSection('script') ?>
</body>

</html>