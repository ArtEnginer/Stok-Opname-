<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'product_id',
        'type',
        'qty',
        'price',
        'reference_no',
        'notes',
        'transaction_date'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'product_id' => 'required|numeric',
        'type' => 'required|in_list[purchase,sale]',
        'qty' => 'required|decimal',
        'transaction_date' => 'required|valid_date',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get transactions between dates
     */
    public function getTransactionsBetween($startDate, $endDate, $productId = null)
    {
        $builder = $this->builder();

        $builder->where('transaction_date >', $startDate)
            ->where('transaction_date <=', $endDate);

        if ($productId) {
            $builder->where('product_id', $productId);
        }

        return $builder->orderBy('transaction_date', 'ASC')->findAll();
    }

    /**
     * Calculate mutation (purchases - sales) between dates for a product
     * 
     * Mutasi = Total Pembelian - Total Penjualan
     * 
     * @param int $productId Product ID
     * @param string $startDate Start date (NOT included - hanya transaksi SETELAH tanggal ini)
     * @param string $endDate End date (included)
     * @return float Mutation value (positive = surplus, negative = deficit)
     * 
     * Contoh:
     * - SO #1 tanggal 2024-01-16
     * - SO #2 tanggal 2024-01-20
     * - getMutation(productId, '2024-01-16', '2024-01-20')
     *   akan menghitung transaksi dari tanggal 17, 18, 19, 20
     */
    public function getMutation($productId, $startDate, $endDate)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $result = $builder->select('
            COALESCE(SUM(CASE WHEN type = "purchase" THEN qty ELSE 0 END), 0) as total_purchase,
            COALESCE(SUM(CASE WHEN type = "sale" THEN qty ELSE 0 END), 0) as total_sale
        ')
            ->where('product_id', $productId)
            ->where('transaction_date >', $startDate)  // Setelah tanggal SO terakhir
            ->where('transaction_date <=', $endDate)   // Sampai dengan tanggal SO baru
            ->get()
            ->getRowArray();

        if (!$result) {
            return 0;
        }

        // Mutation = purchase - sale
        // Positif = lebih banyak pembelian (stok bertambah)
        // Negatif = lebih banyak penjualan (stok berkurang)
        return (float)$result['total_purchase'] - (float)$result['total_sale'];
    }

    /**
     * Get mutations for all products between dates
     */
    public function getAllMutations($startDate, $endDate)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        return $builder->select('
            product_id,
            COALESCE(SUM(CASE WHEN type = "purchase" THEN qty ELSE 0 END), 0) as total_purchase,
            COALESCE(SUM(CASE WHEN type = "sale" THEN qty ELSE 0 END), 0) as total_sale,
            (COALESCE(SUM(CASE WHEN type = "purchase" THEN qty ELSE 0 END), 0) - 
             COALESCE(SUM(CASE WHEN type = "sale" THEN qty ELSE 0 END), 0)) as mutation
        ')
            ->where('transaction_date >', $startDate)
            ->where('transaction_date <=', $endDate)
            ->groupBy('product_id')
            ->get()
            ->getResultArray();
    }

    /**
     * Get transaction summary for a product
     */
    public function getProductSummary($productId, $startDate = null, $endDate = null)
    {
        $builder = $this->builder();

        $builder->select('
            type,
            COUNT(*) as count,
            SUM(qty) as total_qty,
            SUM(qty * price) as total_amount
        ')
            ->where('product_id', $productId);

        if ($startDate && $endDate) {
            $builder->where('transaction_date >=', $startDate)
                ->where('transaction_date <=', $endDate);
        }

        return $builder->groupBy('type')->findAll();
    }
}
