<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOpnameItemModel extends Model
{
    protected $table = 'stock_opname_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'session_id',
        'product_id',
        'baseline_stock',
        'original_baseline_stock',
        'physical_stock',
        'difference',
        'mutation_at_count',
        'mutation_after_count',
        'is_counted',
        'counted_date',
        'counted_by',
        'location',
        'location_id',
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'session_id' => 'required|numeric',
        'product_id' => 'required|numeric',
        'baseline_stock' => 'decimal',
        'physical_stock' => 'permit_empty|decimal',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get items by session with product details (optimized with pagination)
     */
    public function getItemsBySession($sessionId, $filters = [])
    {
        // Build query with proper table reference
        $this->select('stock_opname_items.*, p.code, p.plu, p.name, p.unit, p.category, p.department, p.buy_price, p.sell_price, l.kode_lokasi, l.nama_lokasi, l.departemen as location_department')
            ->join('products p', 'p.id = stock_opname_items.product_id', 'left')
            ->join('locations l', 'l.id = stock_opname_items.location_id', 'left')
            ->where('stock_opname_items.session_id', $sessionId);

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $this->groupStart()
                ->like('p.code', $search)
                ->orLike('p.plu', $search)
                ->orLike('p.name', $search)
                ->groupEnd();
        }

        if (!empty($filters['category'])) {
            $this->where('p.category', $filters['category']);
        }

        if (!empty($filters['department'])) {
            $this->where('p.department', $filters['department']);
        }

        if (isset($filters['is_counted']) && $filters['is_counted'] !== '') {
            $this->where('stock_opname_items.is_counted', $filters['is_counted']);
        }

        // Pagination
        $perPage = $filters['per_page'] ?? 50;

        return $this->orderBy('p.code', 'ASC')->orderBy('l.nama_lokasi', 'ASC')->paginate($perPage);
    }

    /**
     * Get items grouped by product with aggregated physical stock from multiple locations
     */
    public function getItemsGroupedByProduct($sessionId, $filters = [])
    {
        $builder = $this->db->table('stock_opname_items soi');

        $builder->select('
            p.id as product_id,
            p.code,
            p.plu,
            p.name,
            p.unit,
            p.category,
            p.department,
            p.buy_price,
            p.sell_price,
            MAX(soi.baseline_stock) as baseline_stock,
            MAX(soi.original_baseline_stock) as original_baseline_stock,
            SUM(CASE WHEN soi.is_counted = 1 THEN soi.physical_stock ELSE 0 END) as total_physical_stock,
            SUM(CASE WHEN soi.is_counted = 1 THEN soi.physical_stock ELSE 0 END) - MAX(soi.baseline_stock) as difference,
            MAX(CASE WHEN soi.is_counted = 1 THEN 1 ELSE 0 END) as is_counted,
            COUNT(CASE WHEN soi.is_counted = 1 THEN 1 END) as counted_locations_count,
            GROUP_CONCAT(DISTINCT CASE WHEN soi.is_counted = 1 THEN l.nama_lokasi END SEPARATOR ", ") as counted_locations,
            MAX(soi.counted_date) as counted_date,
            MAX(soi.counted_by) as counted_by
        ')
            ->join('products p', 'p.id = soi.product_id', 'left')
            ->join('locations l', 'l.id = soi.location_id', 'left')
            ->where('soi.session_id', $sessionId)
            ->groupBy('p.id, p.code, p.plu, p.name, p.unit, p.category, p.department, p.buy_price, p.sell_price');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $builder->groupStart()
                ->like('p.code', $search)
                ->orLike('p.plu', $search)
                ->orLike('p.name', $search)
                ->groupEnd();
        }

        if (!empty($filters['category'])) {
            $builder->where('p.category', $filters['category']);
        }

        if (!empty($filters['department'])) {
            $builder->where('p.department', $filters['department']);
        }

        if (isset($filters['is_counted']) && $filters['is_counted'] !== '') {
            $builder->having('is_counted', $filters['is_counted']);
        }

        $builder->orderBy('p.code', 'ASC');

        // Get total for pagination
        $total = $builder->countAllResults(false);

        // Pagination
        $perPage = $filters['per_page'] ?? 50;
        $page = $filters['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        $items = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page
        ];
    }

    /**
     * Update physical stock and calculate difference
     */
    public function updatePhysicalStock($itemId, $physicalStock, $notes = null)
    {
        $item = $this->find($itemId);
        if (!$item) {
            return false;
        }

        $difference = (float)$physicalStock - (float)$item['baseline_stock'];

        $data = [
            'physical_stock' => $physicalStock,
            'difference' => $difference,
            'is_counted' => true
        ];

        if ($notes !== null) {
            $data['notes'] = $notes;
        }

        return $this->update($itemId, $data);
    }

    /**
     * Get counted items for a session
     */
    public function getCountedItems($sessionId)
    {
        return $this->where('session_id', $sessionId)
            ->where('is_counted', true)
            ->findAll();
    }

    /**
     * Get uncounted items for a session
     */
    public function getUncountedItems($sessionId)
    {
        return $this->where('session_id', $sessionId)
            ->where('is_counted', false)
            ->findAll();
    }

    /**
     * Get summary statistics for a session
     */
    public function getSessionSummary($sessionId)
    {
        $builder = $this->db->table($this->table);

        return $builder->select('
            COUNT(*) as total_items,
            SUM(CASE WHEN is_counted = 1 THEN 1 ELSE 0 END) as counted_items,
            SUM(CASE WHEN is_counted = 0 THEN 1 ELSE 0 END) as uncounted_items,
            SUM(CASE WHEN difference > 0 THEN difference ELSE 0 END) as total_surplus,
            SUM(CASE WHEN difference < 0 THEN ABS(difference) ELSE 0 END) as total_shortage,
            SUM(ABS(difference)) as total_variance,
            SUM(difference) as net_variance
        ')
            ->where('session_id', $sessionId)
            ->get()
            ->getRowArray();
    }

    /**
     * Get item by session and product
     */
    public function getItemBySessionProduct($sessionId, $productId)
    {
        return $this->where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Bulk insert items
     */
    public function bulkInsert($items)
    {
        return $this->insertBatch($items);
    }

    /**
     * Get the last counted item for a product (from previous sessions)
     */
    public function getLastCountedItem($productId, $beforeSessionId = null)
    {
        $builder = $this->db->table($this->table . ' soi')
            ->select('soi.*, sos.session_date')
            ->join('stock_opname_sessions sos', 'sos.id = soi.session_id')
            ->where('soi.product_id', $productId)
            ->where('soi.is_counted', true)
            ->where('sos.status', 'closed');

        if ($beforeSessionId) {
            $builder->where('soi.session_id <', $beforeSessionId);
        }

        return $builder->orderBy('sos.session_date', 'DESC')
            ->orderBy('sos.closed_at', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }
}
