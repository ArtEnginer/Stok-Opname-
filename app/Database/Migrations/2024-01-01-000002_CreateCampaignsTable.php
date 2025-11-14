<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCampaignsTable extends Migration
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
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'short_description' => [
                'type' => 'TEXT',
            ],
            'description' => [
                'type' => 'LONGTEXT',
            ],
            'target_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'collected_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'donor_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'images' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of additional images',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'active', 'completed', 'cancelled'],
                'default' => 'draft',
            ],
            'is_featured' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_urgent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'organizer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'organizer_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'organizer_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'views' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('campaigns');
    }

    public function down()
    {
        $this->forge->dropTable('campaigns');
    }
}
