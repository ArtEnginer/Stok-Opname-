<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'description' => 'Donasi untuk kesehatan dan pengobatan',
                'icon' => 'fa-heart-pulse',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Donasi untuk pendidikan dan beasiswa',
                'icon' => 'fa-graduation-cap',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bencana Alam',
                'slug' => 'bencana-alam',
                'description' => 'Bantuan untuk korban bencana alam',
                'icon' => 'fa-house-tsunami',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kemanusiaan',
                'slug' => 'kemanusiaan',
                'description' => 'Donasi untuk kegiatan kemanusiaan',
                'icon' => 'fa-hands-holding-child',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Lingkungan',
                'slug' => 'lingkungan',
                'description' => 'Donasi untuk pelestarian lingkungan',
                'icon' => 'fa-leaf',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pemberdayaan',
                'slug' => 'pemberdayaan',
                'description' => 'Donasi untuk pemberdayaan masyarakat',
                'icon' => 'fa-handshake',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
