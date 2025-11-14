<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSnapTokenToDonations extends Migration
{
    public function up()
    {
        $fields = [
            'snap_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'payment_proof',
            ],
        ];

        $this->forge->addColumn('donations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('donations', 'snap_token');
    }
}
