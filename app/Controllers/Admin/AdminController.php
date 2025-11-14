<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CampaignModel;
use App\Models\DonationModel;
use App\Models\CategoryModel;

class AdminController extends BaseController
{
    protected $campaignModel;
    protected $donationModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->donationModel = new DonationModel();
        $this->categoryModel = new CategoryModel();
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Admin',
            'totalCampaigns' => $this->campaignModel->countAll(),
            'activeCampaigns' => $this->campaignModel->where('status', 'active')->countAllResults(false),
            'totalDonations' => $this->donationModel->getTotalDonations(),
            'totalDonors' => $this->donationModel->getTotalDonors(),
            'pendingDonations' => $this->donationModel->where('status', 'pending')->countAllResults(),
            'recentCampaigns' => $this->campaignModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'recentDonations' => $this->donationModel->getRecentDonations(null, 10),
            'topCampaigns' => $this->campaignModel->orderBy('collected_amount', 'DESC')->limit(5)->findAll(),
        ];

        return view('admin/dashboard', $data);
    }
}
