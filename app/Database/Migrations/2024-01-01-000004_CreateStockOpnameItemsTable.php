<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockOpnameItemsTable extends Migration
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
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'baseline_stock' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'comment' => 'Stok acuan hasil SO sebelumnya + mutasi atau stok sistem + mutasi',
            ],
            'physical_stock' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'comment' => 'Stok fisik hasil hitung',
            ],
            'difference' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'comment' => 'Selisih (physical_stock - baseline_stock)',
            ],
            'is_counted' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Apakah barang sudah dihitung atau diabaikan',
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
        $this->forge->addKey('session_id');
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('session_id', 'stock_opname_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stock_opname_items');
    }

    public function down()
    {
        $this->forge->dropTable('stock_opname_items');
    }
}
