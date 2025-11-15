<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // General Settings
            [
                'setting_key' => 'app_name',
                'setting_value' => 'Platform Donasi',
                'setting_type' => 'string',
                'setting_group' => 'general',
                'description' => 'Nama Aplikasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_description',
                'setting_value' => 'Platform donasi online untuk membantu sesama',
                'setting_type' => 'text',
                'setting_group' => 'general',
                'description' => 'Deskripsi Aplikasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_logo',
                'setting_value' => null,
                'setting_type' => 'file',
                'setting_group' => 'general',
                'description' => 'Logo Aplikasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_favicon',
                'setting_value' => null,
                'setting_type' => 'file',
                'setting_group' => 'general',
                'description' => 'Favicon Aplikasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_email',
                'setting_value' => 'info@donasi.com',
                'setting_type' => 'string',
                'setting_group' => 'general',
                'description' => 'Email Aplikasi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_phone',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'general',
                'description' => 'Nomor Telepon',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'app_address',
                'setting_value' => null,
                'setting_type' => 'text',
                'setting_group' => 'general',
                'description' => 'Alamat',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Midtrans Payment Gateway Settings
            [
                'setting_key' => 'midtrans_server_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'setting_group' => 'payment',
                'description' => 'Midtrans Server Key',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'midtrans_client_key',
                'setting_value' => '',
                'setting_type' => 'string',
                'setting_group' => 'payment',
                'description' => 'Midtrans Client Key',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'midtrans_merchant_id',
                'setting_value' => '',
                'setting_type' => 'string',
                'setting_group' => 'payment',
                'description' => 'Midtrans Merchant ID',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'midtrans_is_production',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'setting_group' => 'payment',
                'description' => 'Midtrans Mode Production (1=Production, 0=Sandbox)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'midtrans_is_sanitized',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'setting_group' => 'payment',
                'description' => 'Midtrans Sanitization',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'midtrans_is_3ds',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'setting_group' => 'payment',
                'description' => 'Midtrans 3D Secure',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

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

            // Social Media Settings
            [
                'setting_key' => 'social_facebook',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'social',
                'description' => 'Facebook URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'social_twitter',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'social',
                'description' => 'Twitter URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'social_instagram',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'social',
                'description' => 'Instagram URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'social_linkedin',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'social',
                'description' => 'LinkedIn URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'social_youtube',
                'setting_value' => null,
                'setting_type' => 'string',
                'setting_group' => 'social',
                'description' => 'YouTube URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // SEO Settings
            [
                'setting_key' => 'seo_meta_title',
                'setting_value' => 'Platform Donasi Online',
                'setting_type' => 'string',
                'setting_group' => 'seo',
                'description' => 'Meta Title',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'seo_meta_description',
                'setting_value' => 'Platform donasi online terpercaya untuk membantu sesama',
                'setting_type' => 'text',
                'setting_group' => 'seo',
                'description' => 'Meta Description',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'seo_meta_keywords',
                'setting_value' => 'donasi, charity, sedekah, zakat',
                'setting_type' => 'text',
                'setting_group' => 'seo',
                'description' => 'Meta Keywords',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Email Settings
            [
                'setting_key' => 'email_from_name',
                'setting_value' => 'Platform Donasi',
                'setting_type' => 'string',
                'setting_group' => 'email',
                'description' => 'Email From Name',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'email_from_address',
                'setting_value' => 'noreply@donasi.com',
                'setting_type' => 'string',
                'setting_group' => 'email',
                'description' => 'Email From Address',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // System Settings
            [
                'setting_key' => 'maintenance_mode',
                'setting_value' => '0',
                'setting_type' => 'boolean',
                'setting_group' => 'system',
                'description' => 'Mode Maintenance (1=Active, 0=Inactive)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'setting_key' => 'enable_registration',
                'setting_value' => '1',
                'setting_type' => 'boolean',
                'setting_group' => 'system',
                'description' => 'Allow User Registration',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Check if table is empty before inserting
        $builder = $this->db->table('app_settings');

        if ($builder->countAll() == 0) {
            $builder->insertBatch($data);
            echo "App settings seeded successfully.\n";
        } else {
            echo "App settings table already contains data. Skipping seed.\n";
        }
    }
}
