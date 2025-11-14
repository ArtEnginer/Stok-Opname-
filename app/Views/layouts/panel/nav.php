<a href="#!" class="nav-close"><i class="material-icons">menu</i></a>
<div class="nav-header">
    <h1><b>
            JASTIP
        </b></h1>
</div>
<div class="nav-list">

    <div class="nav-item" data-page="dashboard">
        <a href="<?= base_url('panel') ?>" class="nav-link"><i class="material-icons">dashboard</i>Dashboard</a>
    </div>

    <?php if (auth()->user()->inGroup('superadmin')) : ?>


        <div class="nav-item" data-page="jastip">
            <a href="<?= base_url('panel/jastip') ?>" class="nav-link"><i class="material-icons">shopping_cart</i>Kelola Titipan</a>
        </div>

        <!-- shipment -->
        <div class="nav-item" data-page="shipment">
            <a href="<?= base_url('panel/shipment') ?>" class="nav-link"><i class="material-icons">local_shipping</i>Kelola Pengiriman</a>
        </div>

        <div class="nav-item" data-page="user">
            <a href="<?= base_url('panel/user') ?>" class="nav-link"><i class="material-icons">person</i>Data
                User</a>
        </div>


        <div class="nav-item" data-page="pengaturan">
            <a href="<?= base_url('panel/pengaturan') ?>" class="nav-link"><i class="material-icons">settings</i>Pengaturan</a>
        </div>
    <?php endif ?>



    <?php if (auth()->user()->inGroup('gudang1')) : ?>
        <div class="nav-item" data-page="jastip">
            <a href="<?= base_url('panel/jastip') ?>" class="nav-link"><i class="material-icons">shopping_cart</i>Kelola Titipan</a>
        </div>
        <div class="nav-item" data-page="shipment">
            <a href="<?= base_url('panel/shipment') ?>" class="nav-link"><i class="material-icons">local_shipping</i>Kelola Pengiriman</a>
        </div>
    <?php endif ?>

    <?php if (auth()->user()->inGroup('gudang2')) : ?>
        <div class="nav-item" data-page="jastip">
            <a href="<?= base_url('panel/jastip') ?>" class="nav-link"><i class="material-icons">shopping_cart</i>Kelola Titipan</a>
        </div>
        <div class="nav-item" data-page="shipment">
            <a href="<?= base_url('panel/shipment') ?>" class="nav-link"><i class="material-icons">local_shipping</i>Kelola Pengiriman</a>
        </div>
    <?php endif ?>


    <div class="nav-item">
        <a href="<?= base_url('logout') ?>" class="nav-link btn-logout"><i class="material-icons">logout</i>Logout</a>
    </div>
</div>