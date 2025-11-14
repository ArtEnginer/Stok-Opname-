<?php

namespace App\Controllers;

use App\Models\CampaignModel;
use App\Models\CategoryModel;
use App\Models\DonationModel;

class Home extends BaseController
{
    protected $campaignModel;
    protected $categoryModel;
    protected $donationModel;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->categoryModel = new CategoryModel();
        $this->donationModel = new DonationModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Beranda - Platform Donasi Online',
            'metaDescription' => 'Platform donasi online terpercaya untuk membantu sesama',
            'featuredCampaigns' => $this->campaignModel->getFeaturedCampaigns(6),
            'urgentCampaigns' => $this->campaignModel->getUrgentCampaigns(3),
            'recentCampaigns' => $this->campaignModel->getCampaignsWithCategory(8),
            'categories' => $this->categoryModel->getActiveCategories(),
            'totalDonations' => $this->donationModel->getTotalDonations(),
            'totalDonors' => $this->donationModel->getTotalDonors(),
            'recentDonors' => $this->donationModel->getRecentDonations(null, 10),
        ];

        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang Kami',
            'metaDescription' => 'Tentang platform donasi online kami',
        ];

        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Hubungi Kami',
            'metaDescription' => 'Hubungi kami untuk informasi lebih lanjut',
        ];

        return view('pages/contact', $data);
    }
}
