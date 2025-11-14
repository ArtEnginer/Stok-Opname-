<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'campaign_id',
        'user_name',
        'user_email',
        'comment',
        'rating',
        'is_approved',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_approved' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'campaign_id' => 'required|integer',
        'user_name' => 'required|min_length[3]|max_length[100]',
        'user_email' => 'required|valid_email',
        'comment' => 'required|min_length[10]',
        'rating' => 'permit_empty|integer|in_list[1,2,3,4,5]',
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
     * Get approved comments by campaign
     */
    public function getApprovedComments($campaignId, $limit = null)
    {
        $builder = $this->where('campaign_id', $campaignId)
            ->where('is_approved', 1)
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get pending comments for moderation
     */
    public function getPendingComments()
    {
        return $this->select('comments.*, campaigns.title as campaign_title')
            ->join('campaigns', 'campaigns.id = comments.campaign_id')
            ->where('comments.is_approved', 0)
            ->orderBy('comments.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get average rating for a campaign
     */
    public function getAverageRating($campaignId)
    {
        $result = $this->selectAvg('rating', 'avg_rating')
            ->where('campaign_id', $campaignId)
            ->where('is_approved', 1)
            ->where('rating >', 0)
            ->first();

        return $result['avg_rating'] ? round($result['avg_rating'], 1) : 0;
    }

    /**
     * Count comments for a campaign
     */
    public function countCommentsByCampaign($campaignId)
    {
        return $this->where('campaign_id', $campaignId)
            ->where('is_approved', 1)
            ->countAllResults();
    }

    /**
     * Approve comment
     */
    public function approveComment($id)
    {
        return $this->update($id, ['is_approved' => 1]);
    }

    /**
     * Reject/delete comment
     */
    public function rejectComment($id)
    {
        return $this->delete($id);
    }
}
