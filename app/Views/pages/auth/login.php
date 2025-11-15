<?php

/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('layouts/auth/main') ?>
<?= $this->section('main') ?>

<div class="auth-card">
    <div class="auth-header">
        <div class="logo-container">
            <i class="material-icons jastip-icon">volunteer_activism</i>
        </div>
        <h1 class="title">
            PLATFORM DONASI
        </h1>
        <p class="subtitle">Berbagi Kebaikan, Wujudkan Harapan</p>
    </div>

    <div class="welcome-text">
        <h2>Selamat Datang Kembali!</h2>
        <p>Masuk untuk melanjutkan perjalanan berbagi kebaikan Anda</p>
    </div>

    <div class="row card-body">
        <form class="col s12" action="#!" id="login" method="post">
            <?= csrf_field() ?>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">email</i>
                    <input id="cred" name="cred" type="email" class="validate" value="<?= old('cred') ?>"
                        required>
                    <label for="cred">Email</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input id="password" name="password" type="password" class="validate" required>
                    <label for="password">Kata Sandi</label>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <p>
                        <label>
                            <input type="checkbox" />
                            <span>Ingat saya</span>
                        </label>
                    </p>
                </div>
            </div>

            <button type="submit" class="btn waves-effect waves-light btn-auth">
                <i class="material-icons left">login</i>
                Masuk Sekarang
            </button>


        </form>
    </div>

    <div class="features-preview">
        <div class="feature-item">
            <i class="material-icons">security</i>
            <span>Aman & Terpercaya</span>
        </div>
        <div class="feature-item">
            <i class="material-icons">favorite</i>
            <span>Transparan & Akuntabel</span>
        </div>
        <div class="feature-item">
            <i class="material-icons">people</i>
            <span>Berdampak Nyata</span>
        </div>
    </div>
</div>

<?= $this->endSection() ?>