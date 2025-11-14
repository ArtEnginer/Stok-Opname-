<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDonationsTable extends Migration
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
            'campaign_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'donor_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'donor_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'donor_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_anonymous' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'payment_proof' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verified', 'rejected'],
                'default' => 'pending',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verified_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
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
        $this->forge->addForeignKey('campaign_id', 'campaigns', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('donations');
    }

    public function down()
    {
        $this->forge->dropTable('donations');
    }
}
