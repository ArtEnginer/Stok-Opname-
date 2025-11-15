<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAboutContactSettings extends Migration
{
    public function up()
    {
        $data = [
            // About Settings
            [
                'setting_key' => 'about_organization',
                'setting_value' => 'Platform Donasi adalah platform donasi online yang memudahkan Anda untuk membantu sesama yang membutuhkan. Kami menyediakan platform yang aman, transparan, dan terpercaya untuk menghubungkan donatur dengan mereka yang membutuhkan bantuan.',
                'setting_type' => 'text',
                'setting_group' => 'about',
                'description' => 'Deskripsi Lembaga',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'about_vision',
                'setting_value' => 'Menjadi platform donasi online terdepan di Indonesia yang menghubungkan kebaikan dengan mereka yang membutuhkan, menciptakan dampak sosial yang berkelanjutan.',
                'setting_type' => 'text',
                'setting_group' => 'about',
                'description' => 'Visi Lembaga',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'about_mission',
                'setting_value' => json_encode([
                    'Menyediakan platform donasi yang aman, transparan, dan mudah digunakan',
                    'Memudahkan akses bantuan bagi yang membutuhkan',
                    'Menumbuhkan budaya berbagi dan kepedulian dalam masyarakat',
                    'Memastikan setiap donasi sampai ke tujuan dengan benar dan tepat waktu',
                    'Memberikan transparansi penuh dalam pengelolaan dana donasi'
                ]),
                'setting_type' => 'json',
                'setting_group' => 'about',
                'description' => 'Misi Lembaga',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'about_values',
                'setting_value' => json_encode([
                    [
                        'title' => 'Aman & Terpercaya',
                        'description' => 'Sistem keamanan terjamin dan verifikasi ketat untuk setiap campaign',
                        'icon' => 'fa-shield-alt'
                    ],
                    [
                        'title' => 'Transparan',
                        'description' => 'Lacak kemana donasi Anda disalurkan dengan update berkala',
                        'icon' => 'fa-eye'
                    ],
                    [
                        'title' => 'Cepat & Mudah',
                        'description' => 'Proses donasi hanya dalam beberapa klik dengan berbagai metode pembayaran',
                        'icon' => 'fa-bolt'
                    ],
                    [
                        'title' => 'Berdampak',
                        'description' => 'Setiap donasi membuat perbedaan nyata dalam kehidupan sesama',
                        'icon' => 'fa-heart'
                    ]
                ]),
                'setting_type' => 'json',
                'setting_group' => 'about',
                'description' => 'Nilai-Nilai Lembaga',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Contact Settings
            [
                'setting_key' => 'contact_whatsapp',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'contact',
                'description' => 'WhatsApp Number (format: 628xxx)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'contact_working_hours',
                'setting_value' => 'Senin - Jumat: 09:00 - 17:00 WIB',
                'setting_type' => 'string',
                'setting_group' => 'contact',
                'description' => 'Jam Operasional',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'contact_map_embed',
                'setting_value' => null,
                'setting_type' => 'text',
                'setting_group' => 'contact',
                'description' => 'Google Maps Embed URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Check if settings already exist, only insert if not
        $builder = $this->db->table('app_settings');

        foreach ($data as $setting) {
            $exists = $builder->where('setting_key', $setting['setting_key'])->countAllResults(false);

            if ($exists == 0) {
                $builder->insert($setting);
            }
        }
    }

    public function down()
    {
        // Remove the added settings
        $keys = [
            'about_organization',
            'about_vision',
            'about_mission',
            'about_values',
            'contact_whatsapp',
            'contact_working_hours',
            'contact_map_embed',
        ];

        $builder = $this->db->table('app_settings');
        $builder->whereIn('setting_key', $keys)->delete();
    }
}
