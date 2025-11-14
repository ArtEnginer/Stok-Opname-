<?php

namespace App\Controllers;

use App\Models\CampaignModel;
use App\Models\CategoryModel;
use App\Models\DonationModel;
use App\Models\CampaignUpdateModel;
use App\Models\CommentModel;

class CampaignController extends BaseController
{
    protected $campaignModel;
    protected $categoryModel;
    protected $donationModel;
    protected $campaignUpdateModel;
    protected $commentModel;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->categoryModel = new CategoryModel();
        $this->donationModel = new DonationModel();
        $this->campaignUpdateModel = new CampaignUpdateModel();
        $this->commentModel = new CommentModel();
    }

    public function index()
    {
        $perPage = 12;
        $page = $this->request->getVar('page') ?? 1;
        $category = $this->request->getVar('category');
        $search = $this->request->getVar('search');
        $sort = $this->request->getVar('sort') ?? 'latest';

        $builder = $this->campaignModel
            ->select('campaigns.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->where('campaigns.status', 'active');

        if ($category) {
            $builder->where('categories.slug', $category);
        }

        if ($search) {
            $builder->groupStart()
                ->like('campaigns.title', $search)
                ->orLike('campaigns.short_description', $search)
                ->groupEnd();
        }

        switch ($sort) {
            case 'urgent':
                $builder->orderBy('campaigns.is_urgent', 'DESC');
                break;
            case 'popular':
                $builder->orderBy('campaigns.views', 'DESC');
                break;
            case 'ending':
                $builder->orderBy('campaigns.end_date', 'ASC');
                break;
            default:
                $builder->orderBy('campaigns.created_at', 'DESC');
        }

        $campaigns = $builder->paginate($perPage, 'default', $page);
        $pager = $this->campaignModel->pager;

        $data = [
            'title' => 'Semua Campaign',
            'metaDescription' => 'Lihat semua campaign donasi yang tersedia',
            'campaigns' => $campaigns,
            'pager' => $pager,
            'categories' => $this->categoryModel->getActiveCategories(),
            'currentCategory' => $category,
            'currentSearch' => $search,
            'currentSort' => $sort,
        ];

        return view('pages/campaigns', $data);
    }

    public function detail($slug)
    {
        $campaign = $this->campaignModel->getCampaignBySlug($slug);

        if (!$campaign) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Increment views
        $this->campaignModel->incrementViews($campaign['id']);

        $data = [
            'title' => $campaign['title'],
            'metaDescription' => $campaign['short_description'],
            'campaign' => $campaign,
            'progress' => $this->campaignModel->getProgress($campaign),
            'daysLeft' => $this->campaignModel->getDaysLeft($campaign['end_date']),
            'recentDonations' => $this->donationModel->getRecentDonations($campaign['id'], 10),
            'topDonors' => $this->donationModel->getTopDonors($campaign['id'], 5),
            'relatedCampaigns' => $this->campaignModel->getCampaignsByCategory($campaign['category_slug'], 4),
            'campaignUpdates' => $this->campaignUpdateModel->getUpdatesByCampaign($campaign['id'], 5),
            'comments' => $this->commentModel->getApprovedComments($campaign['id'], 10),
            'averageRating' => $this->commentModel->getAverageRating($campaign['id']),
            'commentCount' => $this->commentModel->countCommentsByCampaign($campaign['id']),
        ];

        return view('pages/campaign_detail', $data);
    }

    public function postComment($slug)
    {
        $campaign = $this->campaignModel->getCampaignBySlug($slug);

        if (!$campaign) {
            return redirect()->back()->with('error', 'Campaign tidak ditemukan');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'user_name' => 'required|min_length[3]',
            'user_email' => 'required|valid_email',
            'comment' => 'required|min_length[10]',
            'rating' => 'permit_empty|integer|in_list[1,2,3,4,5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $commentData = [
            'campaign_id' => $campaign['id'],
            'user_name' => $this->request->getPost('user_name'),
            'user_email' => $this->request->getPost('user_email'),
            'comment' => $this->request->getPost('comment'),
            'rating' => $this->request->getPost('rating') ?: null,
            'is_approved' => 0, // Requires moderation
        ];

        if ($this->commentModel->insert($commentData)) {
            return redirect()->to('/campaign/' . $slug . '#comments')
                ->with('success', 'Komentar Anda telah dikirim dan menunggu moderasi');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengirim komentar');
    }
}
