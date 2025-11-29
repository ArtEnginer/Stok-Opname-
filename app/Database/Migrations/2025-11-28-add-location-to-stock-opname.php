<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToStockOpname extends Migration
{
    public function up()
    {
        // Tambah kolom location_id ke tabel stock_opname_items (BUKAN stock_opname_detail)
        $fields = [
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'product_id',
            ],
        ];

        $this->forge->addColumn('stock_opname_items', $fields);

        // Tambah foreign key ke locations
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'CASCADE', 'fk_stock_opname_items_location');
    }

    public function down()
    {
        // Hapus foreign key terlebih dahulu
        $this->forge->dropForeignKey('stock_opname_items', 'fk_stock_opname_items_location');

        // Hapus kolom location_id
        $this->forge->dropColumn('stock_opname_items', 'location_id');
    }
}
