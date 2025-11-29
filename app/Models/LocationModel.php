<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table            = 'locations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_lokasi',
        'nama_lokasi',
        'departemen',
        'keterangan',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'kode_lokasi' => 'required|max_length[50]|is_unique[locations.kode_lokasi,id,{id}]',
        'nama_lokasi' => 'required|max_length[255]',
        'departemen'  => 'permit_empty|max_length[100]',
        'status'      => 'required|in_list[aktif,tidak_aktif]',
    ];

    protected $validationMessages = [
        'kode_lokasi' => [
            'required'   => 'Kode lokasi harus diisi',
            'is_unique'  => 'Kode lokasi sudah digunakan',
            'max_length' => 'Kode lokasi maksimal 50 karakter'
        ],
        'nama_lokasi' => [
            'required'   => 'Nama lokasi harus diisi',
            'max_length' => 'Nama lokasi maksimal 255 karakter'
        ],
        'departemen' => [
            'max_length' => 'Departemen maksimal 100 karakter'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list'  => 'Status tidak valid'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all active locations
     */
    public function getActiveLocations()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    /**
     * Get location by code
     */
    public function getByCode($code)
    {
        return $this->where('kode_lokasi', $code)->first();
    }

    /**
     * Get locations by department
     */
    public function getByDepartment($department)
    {
        return $this->where('departemen', $department)->findAll();
    }

    /**
     * Get location with stock opname status
     */
    public function getLocationsWithSOStatus($sessionId = null)
    {
        $builder = $this->db->table($this->table . ' l');
        $builder->select('l.*, 
            COUNT(DISTINCT soi.id) as total_so,
            COUNT(DISTINCT CASE WHEN soi.is_counted = 1 THEN soi.id END) as completed_so,
            COUNT(DISTINCT CASE WHEN soi.is_counted = 0 THEN soi.id END) as pending_so');

        if ($sessionId) {
            $builder->join('stock_opname_items soi', 'soi.location_id = l.id AND soi.session_id = ' . $sessionId, 'left');
        } else {
            $builder->join('stock_opname_items soi', 'soi.location_id = l.id', 'left');
        }

        $builder->where('l.deleted_at', null);
        $builder->where('l.status', 'aktif');
        $builder->groupBy('l.id');
        $builder->orderBy('l.kode_lokasi', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get unprocessed locations for SO
     */
    public function getUnprocessedLocations($sessionId)
    {
        $builder = $this->db->table($this->table . ' l');
        $builder->select('l.*');
        $builder->where('l.status', 'aktif');
        $builder->where('l.deleted_at', null);
        $builder->where("l.id NOT IN (
            SELECT DISTINCT location_id 
            FROM stock_opname_items 
            WHERE session_id = {$sessionId} 
            AND location_id IS NOT NULL
        )");
        $builder->orderBy('l.kode_lokasi', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Search locations
     */
    public function searchLocations($keyword)
    {
        return $this->groupStart()
            ->like('kode_lokasi', $keyword)
            ->orLike('nama_lokasi', $keyword)
            ->orLike('departemen', $keyword)
            ->groupEnd()
            ->findAll();
    }

    /**
     * Get locations with uncounted items for a specific SO session
     * Returns locations that have items assigned but not all items are counted yet
     */
    public function getUncountedLocations($sessionId)
    {
        $builder = $this->db->table($this->table . ' l');
        $builder->select('l.*, 
            COUNT(DISTINCT soi.id) as total_items,
            SUM(CASE WHEN soi.is_counted = 1 THEN 1 ELSE 0 END) as counted_items,
            SUM(CASE WHEN soi.is_counted = 0 OR soi.is_counted IS NULL THEN 1 ELSE 0 END) as uncounted_items');
        $builder->join('stock_opname_items soi', 'soi.location_id = l.id AND soi.session_id = ' . (int)$sessionId, 'inner');
        $builder->where('l.deleted_at', null);
        $builder->where('l.status', 'aktif');
        $builder->groupBy('l.id');
        $builder->having('uncounted_items >', 0);
        $builder->orderBy('l.departemen', 'ASC');
        $builder->orderBy('l.kode_lokasi', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get locations that have NO items assigned yet in a SO session (completely untouched)
     */
    public function getLocationsWithoutItems($sessionId)
    {
        $builder = $this->db->table($this->table . ' l');
        $builder->select('l.*');
        $builder->where('l.status', 'aktif');
        $builder->where('l.deleted_at', null);
        $builder->where("l.id NOT IN (
            SELECT DISTINCT location_id 
            FROM stock_opname_items 
            WHERE session_id = " . (int)$sessionId . " 
            AND location_id IS NOT NULL
            AND is_counted = 1
        )");
        $builder->orderBy('l.departemen', 'ASC');
        $builder->orderBy('l.kode_lokasi', 'ASC');

        return $builder->get()->getResultArray();
    }
}
