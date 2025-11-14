<?php

namespace App\Models;

use CodeIgniter\Model;

class CampaignUpdateModel extends Model
{
    protected $table = 'campaign_updates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'campaign_id',
        'title',
        'content',
        'image',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'campaign_id' => 'required|integer',
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get updates by campaign ID
     */
    public function getUpdatesByCampaign($campaignId, $limit = null)
    {
        $builder = $this->where('campaign_id', $campaignId)
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get latest update for a campaign
     */
    public function getLatestUpdate($campaignId)
    {
        return $this->where('campaign_id', $campaignId)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * Count updates for a campaign
     */
    public function countUpdatesByCampaign($campaignId)
    {
        return $this->where('campaign_id', $campaignId)->countAllResults();
    }
}
