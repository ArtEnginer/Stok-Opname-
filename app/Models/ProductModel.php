<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'code',
        'plu',
        'name',
        'unit',
        'buy_price',
        'sell_price',
        'supplier',
        'stock',
        'department',
        'category'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'code' => 'required|is_unique[products.code,id,{id}]',
        'name' => 'required|min_length[3]',
        'unit' => 'required',
        'stock' => 'decimal',
    ];

    protected $validationMessages = [
        'code' => [
            'required' => 'Kode produk harus diisi',
            'is_unique' => 'Kode produk sudah digunakan'
        ],
        'name' => [
            'required' => 'Nama produk harus diisi',
            'min_length' => 'Nama produk minimal 3 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all products with optional filters (optimized for large datasets)
     */
    public function getProducts($filters = [])
    {
        $builder = $this;

        if (!empty($filters['search'])) {
            $builder = $builder->groupStart()
                ->like('code', $filters['search'])
                ->orLike('name', $filters['search'])
                ->orLike('plu', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['category'])) {
            $builder = $builder->where('category', $filters['category']);
        }

        if (!empty($filters['department'])) {
            $builder = $builder->where('department', $filters['department']);
        }

        // Pagination for large datasets
        $perPage = $filters['per_page'] ?? 50;

        return $builder->orderBy('name', 'ASC')->paginate($perPage);
    }

    /**
     * Get products count (for pagination)
     */
    public function getProductsCount($filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('code', $filters['search'])
                ->orLike('name', $filters['search'])
                ->orLike('plu', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }

        if (!empty($filters['department'])) {
            $builder->where('department', $filters['department']);
        }

        return $builder->countAllResults();
    }

    /**
     * Get product by code
     */
    public function getByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    /**
     * Update stock for a product
     */
    public function updateStock($productId, $newStock)
    {
        return $this->update($productId, ['stock' => $newStock]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        return $this->select('category')
            ->distinct()
            ->where('category IS NOT NULL')
            ->orderBy('category', 'ASC')
            ->findAll();
    }

    /**
     * Get all departments
     */
    public function getDepartments()
    {
        return $this->select('department')
            ->distinct()
            ->where('department IS NOT NULL')
            ->orderBy('department', 'ASC')
            ->findAll();
    }
}
