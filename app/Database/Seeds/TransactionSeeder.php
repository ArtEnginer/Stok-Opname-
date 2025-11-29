<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Get all product IDs
        $products = $this->db->table('products')->select('id')->get()->getResultArray();

        if (empty($products)) {
            echo "No products found. Please run ProductSeeder first.\n";
            return;
        }

        $data = [];
        $startDate = strtotime('-30 days');
        $endDate = strtotime('now');

        // Generate random transactions for the past 30 days
        for ($i = 0; $i < 50; $i++) {
            $product = $products[array_rand($products)];
            $type = rand(0, 1) ? 'purchase' : 'sale';
            $qty = rand(1, 10);
            $price = rand(50000, 2000000);
            $randomDate = date('Y-m-d H:i:s', rand($startDate, $endDate));

            $data[] = [
                'product_id' => $product['id'],
                'type' => $type,
                'qty' => $qty,
                'price' => $price,
                'reference_no' => $type === 'purchase' ? 'PO-' . rand(1000, 9999) : 'INV-' . rand(1000, 9999),
                'notes' => $type === 'purchase' ? 'Pembelian barang' : 'Penjualan barang',
                'transaction_date' => $randomDate,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        $this->db->table('transactions')->insertBatch($data);
    }
}
