<?php

namespace App\Models;

use CodeIgniter\Model;

class DonationModel extends Model
{
    protected $table = 'donations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'campaign_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'message',
        'is_anonymous',
        'payment_method',
        'transaction_id',
        'payment_proof',
        'snap_token',
        'status',
        'verified_at',
        'verified_by',
        'notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [

        'is_anonymous' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'campaign_id' => 'required|integer',
        'donor_name' => 'required|min_length[3]',
        'donor_email' => 'required|valid_email',
        'amount' => 'required|decimal',
        'transaction_id' => 'required|is_unique[donations.transaction_id]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateTransactionId'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function generateTransactionId(array $data)
    {
        if (!isset($data['data']['transaction_id']) || empty($data['data']['transaction_id'])) {
            $data['data']['transaction_id'] = 'TRX' . date('YmdHis') . rand(1000, 9999);
        }
        return $data;
    }

    public function getDonationsByTransactionId($transactionId)
    {
        return $this->select('donations.*, campaigns.title as campaign_title, campaigns.slug as campaign_slug')
            ->join('campaigns', 'campaigns.id = donations.campaign_id')
            ->where('donations.transaction_id', $transactionId)
            ->first();
    }

    public function getRecentDonations($campaignId = null, $limit = 10)
    {
        $builder = $this->select('donations.*, campaigns.title as campaign_title')
            ->join('campaigns', 'campaigns.id = donations.campaign_id')
            ->where('donations.status', 'verified')
            ->orderBy('donations.created_at', 'DESC')
            ->limit($limit);

        if ($campaignId) {
            $builder->where('donations.campaign_id', $campaignId);
        }

        return $builder->findAll();
    }

    public function getTopDonors($campaignId = null, $limit = 10)
    {
        $builder = $this->select('donor_name, SUM(amount) as total_amount, COUNT(*) as donation_count')
            ->where('status', 'verified')
            ->where('is_anonymous', 0)
            ->groupBy('donor_name')
            ->orderBy('total_amount', 'DESC')
            ->limit($limit);

        if ($campaignId) {
            $builder->where('campaign_id', $campaignId);
        }

        return $builder->findAll();
    }

    public function getTotalDonations($campaignId = null)
    {
        $builder = $this->selectSum('amount', 'total')
            ->where('status', 'verified');

        if ($campaignId) {
            $builder->where('campaign_id', $campaignId);
        }

        $result = $builder->first();
        return $result['total'] ?? 0;
    }

    public function getTotalDonors($campaignId = null)
    {
        $builder = $this->selectCount('id', 'total')
            ->where('status', 'verified');

        if ($campaignId) {
            $builder->where('campaign_id', $campaignId);
        }

        $result = $builder->first();
        return $result['total'] ?? 0;
    }

    public function verifyDonation($id, $userId)
    {
        return $this->update($id, [
            'status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $userId
        ]);
    }

    public function rejectDonation($id, $notes, $userId)
    {
        return $this->update($id, [
            'status' => 'rejected',
            'notes' => $notes,
            'verified_by' => $userId
        ]);
    }

    public function getPendingDonations()
    {
        return $this->select('donations.*, campaigns.title as campaign_title')
            ->join('campaigns', 'campaigns.id = donations.campaign_id')
            ->where('donations.status', 'pending')
            ->orderBy('donations.created_at', 'DESC')
            ->findAll();
    }
}
