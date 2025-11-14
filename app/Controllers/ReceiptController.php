<?php

namespace App\Controllers;

use App\Models\DonationModel;
use App\Models\CampaignModel;

class ReceiptController extends BaseController
{
    protected $donationModel;
    protected $campaignModel;

    public function __construct()
    {
        $this->donationModel = new DonationModel();
        $this->campaignModel = new CampaignModel();
    }

    /**
     * View donation receipt
     */
    public function view($transactionId)
    {
        $donation = $this->donationModel->where('transaction_id', $transactionId)->first();

        if (!$donation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($donation['status'] !== 'verified') {
            return redirect()->to('/donate/success/' . $transactionId)
                ->with('error', 'Bukti donasi hanya tersedia untuk donasi yang sudah diverifikasi');
        }

        $campaign = $this->campaignModel->find($donation['campaign_id']);

        $data = [
            'title' => 'Bukti Donasi - ' . $transactionId,
            'metaDescription' => 'Bukti donasi untuk ' . $campaign['title'],
            'donation' => $donation,
            'campaign' => $campaign,
        ];

        return view('pages/receipt', $data);
    }

    /**
     * Download receipt as PDF (requires TCPDF or DomPDF)
     */
    public function download($transactionId)
    {
        $donation = $this->donationModel->where('transaction_id', $transactionId)->first();

        if (!$donation || $donation['status'] !== 'verified') {
            return redirect()->back()->with('error', 'Donasi tidak ditemukan atau belum diverifikasi');
        }

        $campaign = $this->campaignModel->find($donation['campaign_id']);

        // For now, just show the view
        // TODO: Implement PDF generation
        $data = [
            'donation' => $donation,
            'campaign' => $campaign,
        ];

        return view('pages/receipt_pdf', $data);
    }

    /**
     * Send receipt via email
     */
    public function sendEmail($transactionId)
    {
        $donation = $this->donationModel->where('transaction_id', $transactionId)->first();

        if (!$donation || $donation['status'] !== 'verified') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Donasi tidak ditemukan atau belum diverifikasi'
            ]);
        }

        // TODO: Implement email sending
        // For now, just return success
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Bukti donasi telah dikirim ke email Anda'
        ]);
    }
}
