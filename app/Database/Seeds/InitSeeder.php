<?php

namespace App\Database\Seeds;

use App\Libraries\Eloquent;
use App\Models\PenggunaModel;
use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {
        // Initialize Eloquent
        new Eloquent();

        PenggunaModel::create([
            'username' => 'superadmin',
            'name' => 'superadmin',
        ])->setEmailIdentity([
            'email' => 'superadmin@gmail.com',
            'password' => "password",
        ])->addGroup('superadmin')->activate();

        PenggunaModel::create([
            'username' => 'adminjkt',
            'name' => 'adminjkt',
        ])->setEmailIdentity([
            'email' => 'adminjkt@gmail.com',
            'password' => "password",
        ])->addGroup('gudang1')->activate();
        PenggunaModel::create([
            'username' => 'adminpp',
            'name' => 'adminpp',
        ])->setEmailIdentity([
            'email' => 'adminpp@gmail.com',
            'password' => "password",
        ])->addGroup('gudang2')->activate();
    }
}
