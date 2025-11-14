<?php

namespace App\Models;

use CodeIgniter\Model;

class CampaignModel extends Model
{
    protected $table = 'campaigns';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'target_amount',
        'collected_amount',
        'donor_count',
        'image',
        'images',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'is_urgent',
        'organizer_name',
        'organizer_phone',
        'organizer_email',
        'views',
        'created_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'target_amount' => 'float',
        'collected_amount' => 'float',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'views' => 'integer',
        'donor_count' => 'integer',
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
        'category_id' => 'required|integer',
        'title' => 'required|min_length[10]|max_length[255]',
        'slug' => 'required|is_unique[campaigns.slug,id,{id}]',
        'short_description' => 'required',
        'description' => 'required',
        'target_amount' => 'required',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date',
        'organizer_name' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateSlug'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function generateSlug(array $data)
    {
        if (!isset($data['data']['slug']) || empty($data['data']['slug'])) {
            if (isset($data['data']['title'])) {
                $data['data']['slug'] = url_title($data['data']['title'], '-', true) . '-' . time();
            }
        }
        return $data;
    }

    public function getCampaignsWithCategory($limit = null, $offset = 0)
    {
        $builder = $this->select('campaigns.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->where('campaigns.status', 'active')
            ->orderBy('campaigns.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    public function getCampaignBySlug($slug)
    {
        return $this->select('campaigns.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->where('campaigns.slug', $slug)
            ->first();
    }

    public function getFeaturedCampaigns($limit = 6)
    {
        return $this->select('campaigns.*, categories.name as category_name')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->where('campaigns.status', 'active')
            ->where('campaigns.is_featured', 1)
            ->orderBy('campaigns.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getUrgentCampaigns($limit = 3)
    {
        return $this->select('campaigns.*, categories.name as category_name')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->where('campaigns.status', 'active')
            ->where('campaigns.is_urgent', 1)
            ->orderBy('campaigns.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getCampaignsByCategory($categorySlug, $limit = null)
    {
        $builder = $this->select('campaigns.*, categories.name as category_name')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->where('campaigns.status', 'active')
            ->where('categories.slug', $categorySlug)
            ->orderBy('campaigns.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function incrementViews($id)
    {
        return $this->set('views', 'views + 1', false)
            ->where('id', $id)
            ->update();
    }

    public function updateCollectedAmount($campaignId, $amount)
    {
        return $this->set('collected_amount', "collected_amount + $amount", false)
            ->set('donor_count', 'donor_count + 1', false)
            ->where('id', $campaignId)
            ->update();
    }

    public function getProgress($campaign)
    {
        if ($campaign['target_amount'] <= 0) {
            return 0;
        }
        $progress = ($campaign['collected_amount'] / $campaign['target_amount']) * 100;
        return min($progress, 100);
    }

    public function getDaysLeft($endDate)
    {
        $now = new \DateTime();
        $end = new \DateTime($endDate);
        $diff = $now->diff($end);

        if ($diff->invert) {
            return 0;
        }

        return $diff->days;
    }
}
