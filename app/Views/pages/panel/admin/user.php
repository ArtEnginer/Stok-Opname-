<?php

/** @var \CodeIgniter\View\View $this */
?>

<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<h1 class="page-title">Data Pengguna Sistem</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-12 text-end">
                    <button class="btn waves-effect waves-light green btn-slider" data-action="add" type="button"
                        data-target="form"><i class="material-icons left">add</i>Tambah</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-wrapper">
                        <table class="striped highlight responsive-table" id="table-user" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page slider" data-page="form">
        <div class="row">
            <div class="col-12">
                <button class="btn waves-effect waves-light btn-slider-close orange darken-2"><i
                        class="material-icons left">arrow_back</i>Kembali</button>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <form class="col s12 m6 offset-m3 form" id="form-user" action="" method="POST">
                    <h1 class="form-title">Tambah Data Pengguna</h1>
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="name" id="name" type="text" class="validate" required>
                            <label for="name">Nama Pengguna</label>
                        </div>
                        <div class="input-field col s12">
                            <input name="email" id="email" type="email" class="validate" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field col s12">
                            <input name="username" id="username" type="text" class="validate" required>
                            <label for="username">Username</label>
                        </div>
                        <div class="input-field col s12">
                            <input name="password" id="password" type="password" class="validate" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="input-field col s12">
                            <input name="password_confirm" id="password_confirm" type="password" class="validate"
                                required>
                            <label for="password_confirm">Konfirmasi Password</label>
                        </div>
                        <!-- ROLE -->
                        <div class="input-field col s12">
                            <select name="role" id="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="gudang1">Admin Jakarta</option>
                                <option value="gudang2">Admin Papua</option>
                            </select>
                            <label>Role</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 center">
                            <button class="btn waves-effect waves-light green" type="submit"><i
                                    class="material-icons left">save</i>Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('popup') ?>
<div class="popup side" data-page="edit">
    <h1>Edit Data</h1>
    <br>
    <form action="" id="form-edit" class="row">
        <input type="hidden" name="id" id="edit-id">
        <div class="input-field col s12">
            <input name="name" id="edit-name" type="text" class="validate" required>
            <label for="edit-name">Nama Pengguna</label>
        </div>
        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i
                        class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>
<div class="popup side" data-page="password">
    <h1>Ubah Password</h1>
    <br>
    <form action="" id="form-password" class="row">
        <input type="hidden" name="id" id="password-id">
        <div class="input-field col s12">
            <input name="password" id="edit-password" type="password" class="validate" required>
            <label for="edit-password">Password</label>
        </div>
        <div class="input-field col s12">
            <input name="password_confirm" id="edit-password_confirm" type="password" class="validate" required>
            <label for="edit-password_confirm">Konfirmasi Password</label>
        </div>
        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i
                        class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>