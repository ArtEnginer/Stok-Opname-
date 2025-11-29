<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCountedDateToStockOpnameItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('stock_opname_items', [
            'counted_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'is_counted',
                'comment' => 'Tanggal saat barang ini dihitung (untuk SO bertahap)'
            ],
            'counted_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'counted_date',
                'comment' => 'Nama penghitung'
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'counted_by',
                'comment' => 'Lokasi/Area (Lantai 1, Gudang, dll)'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('stock_opname_items', ['counted_date', 'counted_by', 'location']);
    }
}
