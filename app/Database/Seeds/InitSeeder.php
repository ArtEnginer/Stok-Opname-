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

      
    }
}
