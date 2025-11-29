<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOpnameSessionModel extends Model
{
    protected $table = 'stock_opname_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'session_code',
        'session_date',
        'status',
        'notes',
        'created_by',
        'closed_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'session_code' => 'required|is_unique[stock_opname_sessions.session_code,id,{id}]',
        'session_date' => 'required|valid_date',
    ];

    protected $validationMessages = [
        'session_code' => [
            'required' => 'Kode sesi harus diisi',
            'is_unique' => 'Kode sesi sudah digunakan'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get the last closed session
     */
    public function getLastClosedSession()
    {
        return $this->where('status', 'closed')
            ->orderBy('session_date', 'DESC')
            ->orderBy('closed_at', 'DESC')
            ->first();
    }

    /**
     * Get all sessions with optional filters
     */
    public function getSessions($filters = [])
    {
        $builder = $this;

        if (!empty($filters['status'])) {
            $builder = $builder->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder = $builder->where('session_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder = $builder->where('session_date <=', $filters['date_to']);
        }

        return $builder->orderBy('session_date', 'DESC')->findAll();
    }

    /**
     * Get session by code
     */
    public function getByCode($code)
    {
        return $this->where('session_code', $code)->first();
    }

    /**
     * Close a session
     */
    public function closeSession($sessionId)
    {
        return $this->update($sessionId, [
            'status' => 'closed',
            'closed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if there is an open session
     */
    public function hasOpenSession()
    {
        return $this->where('status', 'open')->countAllResults() > 0;
    }

    /**
     * Get open sessions
     */
    public function getOpenSessions()
    {
        return $this->where('status', 'open')
            ->orderBy('session_date', 'ASC')
            ->findAll();
    }

    /**
     * Generate session code
     */
    public function generateSessionCode($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $dateStr = date('Ymd', strtotime($date));
        $count = $this->where('DATE(session_date)', date('Y-m-d', strtotime($date)))
            ->countAllResults();

        return 'SO-' . $dateStr . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
