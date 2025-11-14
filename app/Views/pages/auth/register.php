<?php

/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('layouts/auth/main') ?>
<?= $this->section('main') ?>

<div class="auth-card">
    <div class="auth-header">
        <div class="logo-container">
            <i class="material-icons jastip-icon">local_shipping</i>
        </div>
        <h1 class="title">
            JASTIP
        </h1>
        <p class="subtitle">Jasa Titip Barang Terpercaya</p>
    </div>

    <div class="welcome-text">
        <h2>Bergabung Bersama Kami!</h2>
        <p>Daftar sekarang dan nikmati layanan jasa titip terpercaya</p>
    </div>

    <div class="row card-body">
        <form class="col s12" action="#!" id="register" method="post">
            <?= csrf_field() ?>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">person</i>
                    <input id="nama" name="nama" type="text" class="validate" value="<?= old('nama') ?>"
                        required>
                    <label for="nama">Nama Lengkap</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">email</i>
                    <input id="email" name="email" type="email" class="validate" value="<?= old('email') ?>"
                        required>
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="username" name="username" type="text" class="validate" value="<?= old('username') ?>"
                        required>
                    <label for="username">Username</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input id="password" name="password" type="password" class="validate" required>
                    <label for="password">Kata Sandi</label>
                </div>
            </div>
            <div class="row mb-0">
                <div class="input-field col s12">
                    <i class="material-icons prefix">lock_outline</i>
                    <input id="password_confirm" name="password_confirm" type="password" class="validate" required>
                    <label for="password_confirm">Konfirmasi Kata Sandi</label>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <p>
                        <label>
                            <input type="checkbox" required />
                            <span>Saya setuju dengan <a href="#!" class="orange-text text-darken-2">Syarat & Ketentuan</a></span>
                        </label>
                    </p>
                </div>
            </div>

            <button type="submit" class="btn waves-effect waves-light btn-auth">
                <i class="material-icons left">person_add</i>
                Daftar Sekarang
            </button>

            <div class="auth-links">
                <p class="center">
                    Sudah punya akun?
                    <a class="orange-text text-darken-2" href="<?= base_url('login') ?>">
                        <strong>Masuk di sini</strong>
                    </a>
                </p>
            </div>
        </form>
    </div>

    <div class="features-preview">
        <div class="feature-item">
            <i class="material-icons">security</i>
            <span>Aman & Terpercaya</span>
        </div>
        <div class="feature-item">
            <i class="material-icons">schedule</i>
            <span>Cepat & Tepat Waktu</span>
        </div>
        <div class="feature-item">
            <i class="material-icons">verified_user</i>
            <span>Garansi Terjamin</span>
        </div>
    </div>
</div>

<?= $this->endSection() ?>