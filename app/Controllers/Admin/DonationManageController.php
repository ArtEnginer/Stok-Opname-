<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DonationModel;
use App\Models\CampaignModel;

class DonationManageController extends BaseController
{
    protected $donationModel;
    protected $campaignModel;

    public function __construct()
    {
        $this->donationModel = new DonationModel();
        $this->campaignModel = new CampaignModel();
    }

    public function index()
    {
        $perPage = 20;
        $page = $this->request->getVar('page') ?? 1;
        $status = $this->request->getVar('status');

        $builder = $this->donationModel
            ->select('donations.*, campaigns.title as campaign_title')
            ->join('campaigns', 'campaigns.id = donations.campaign_id')
            ->orderBy('donations.created_at', 'DESC');

        if ($status) {
            $builder->where('donations.status', $status);
        }

        $donations = $builder->paginate($perPage, 'default', $page);
        $pager = $this->donationModel->pager;

        // Get statistics
        $stats = [
            'pending' => $this->donationModel->where('status', 'pending')->countAllResults(false),
            'verified' => $this->donationModel->where('status', 'verified')->countAllResults(false),
            'rejected' => $this->donationModel->where('status', 'rejected')->countAllResults(false),
            'total_amount' => $this->donationModel->selectSum('amount')->where('status', 'verified')->first()['amount'] ?? 0,
        ];

        $data = [
            'title' => 'Kelola Donasi',
            'donations' => $donations,
            'pager' => $pager,
            'currentStatus' => $status,
            'stats' => $stats,
        ];

        return view('admin/donations/index', $data);
    }

    public function detail($id)
    {
        $donation = $this->donationModel
            ->select('donations.*, campaigns.title as campaign_title, campaigns.slug as campaign_slug')
            ->join('campaigns', 'campaigns.id = donations.campaign_id')
            ->find($id);

        if (!$donation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Detail Donasi',
            'donation' => $donation,
        ];

        return view('admin/donations/detail', $data);
    }

    public function verify($id)
    {
        $donation = $this->donationModel->find($id);

        if (!$donation) {
            return redirect()->back()->with('error', 'Donasi tidak ditemukan');
        }

        if ($donation['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Donasi sudah diverifikasi');
        }

        // Get user ID (assuming you have authentication)
        $userId = 1; // Replace with actual user ID from session

        if ($this->donationModel->verifyDonation($id, $userId)) {
            // Update campaign collected amount
            $this->campaignModel->updateCollectedAmount($donation['campaign_id'], $donation['amount']);

            return redirect()->back()->with('success', 'Donasi berhasil diverifikasi');
        }

        return redirect()->back()->with('error', 'Gagal memverifikasi donasi');
    }

    public function reject($id)
    {
        $donation = $this->donationModel->find($id);

        if (!$donation) {
            return redirect()->back()->with('error', 'Donasi tidak ditemukan');
        }

        if ($donation['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Donasi sudah diproses');
        }

        $notes = $this->request->getPost('notes');
        if (empty($notes)) {
            return redirect()->back()->with('error', 'Alasan penolakan harus diisi');
        }

        // Get user ID (assuming you have authentication)
        $userId = 1; // Replace with actual user ID from session

        if ($this->donationModel->rejectDonation($id, $notes, $userId)) {
            return redirect()->back()->with('success', 'Donasi berhasil ditolak');
        }

        return redirect()->back()->with('error', 'Gagal menolak donasi');
    }

    public function export()
    {
        $donations = $this->donationModel
            ->select('donations.*, campaigns.title as campaign_title')
            ->join('campaigns', 'campaigns.id = donations.campaign_id')
            ->orderBy('donations.created_at', 'DESC')
            ->findAll();

        // Create CSV
        $filename = 'donations_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Headers
        fputcsv($output, ['ID', 'Campaign', 'Donor Name', 'Email', 'Phone', 'Amount', 'Status', 'Date']);

        // Data
        foreach ($donations as $donation) {
            fputcsv($output, [
                $donation['id'],
                $donation['campaign_title'],
                $donation['donor_name'],
                $donation['donor_email'],
                $donation['donor_phone'],
                $donation['amount'],
                $donation['status'],
                $donation['created_at'],
            ]);
        }

        fclose($output);
        exit;
    }
}
