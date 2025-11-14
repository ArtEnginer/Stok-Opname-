<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_type' => [
                'type' => 'ENUM',
                'constraint' => ['string', 'text', 'number', 'boolean', 'json', 'file'],
                'default' => 'string',
            ],
            'setting_group' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'general',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('setting_key');
        $this->forge->addKey('setting_group');
        $this->forge->createTable('app_settings');

        // Insert default settings
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

        $this->db->table('app_settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('app_settings');
    }
}
