<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowMultipleLocationEntries extends Migration
{
    public function up()
    {
        // Drop unique constraint pada session_id + product_id jika ada
        // Karena sekarang 1 product bisa ada di multiple locations dalam 1 session

        // Cek apakah ada unique key pada session_id dan product_id
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'stock_opname_items' 
            AND CONSTRAINT_TYPE = 'UNIQUE'
        ");

        $constraints = $query->getResultArray();
        foreach ($constraints as $constraint) {
            // Drop unique constraint jika ada
            $this->forge->dropKey('stock_opname_items', $constraint['CONSTRAINT_NAME']);
        }

        // Tambah komentar pada tabel untuk dokumentasi
        $db->query("ALTER TABLE stock_opname_items COMMENT = 'Stock opname items - Allows multiple entries per product for different locations'");
    }

    public function down()
    {
        // Tidak perlu rollback karena kita hanya drop constraint yang mungkin membatasi
        // Jika ingin restore, bisa tambahkan unique constraint kembali
        // Tapi ini akan gagal jika sudah ada data dengan product yang sama di lokasi berbeda
    }
}
