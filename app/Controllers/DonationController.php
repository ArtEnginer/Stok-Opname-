<?php

namespace App\Controllers;

use App\Models\CampaignModel;
use App\Models\DonationModel;
use App\Libraries\MidtransLibrary;

class DonationController extends BaseController
{
    protected $campaignModel;
    protected $donationModel;
    protected $midtrans;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->donationModel = new DonationModel();
        $this->midtrans = new MidtransLibrary();
    }

    public function form($slug)
    {
        $campaign = $this->campaignModel->getCampaignBySlug($slug);

        if (!$campaign) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($campaign['status'] !== 'active') {
            return redirect()->to('/campaign/' . $slug)->with('error', 'Campaign ini tidak aktif');
        }

        $data = [
            'title' => 'Donasi untuk ' . $campaign['title'],
            'metaDescription' => 'Berdonasi untuk ' . $campaign['title'],
            'campaign' => $campaign,
            'progress' => $this->campaignModel->getProgress($campaign),
        ];

        return view('pages/donation_form', $data);
    }

    public function process()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'campaign_id' => 'required|integer',
            'donor_name' => 'required|min_length[3]',
            'donor_email' => 'required|valid_email',
            'donor_phone' => 'permit_empty',
            'amount' => 'required|numeric|greater_than[9999]',
            'message' => 'permit_empty',
            'is_anonymous' => 'permit_empty',
            'payment_method' => 'required|in_list[midtrans,manual]',
        ];

        // Add payment proof validation only for manual payment
        if ($this->request->getPost('payment_method') === 'manual') {
            $rules['payment_proof'] = 'uploaded[payment_proof]|max_size[payment_proof,2048]|is_image[payment_proof]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $campaignId = $this->request->getPost('campaign_id');
        $campaign = $this->campaignModel->find($campaignId);

        if (!$campaign) {
            return redirect()->back()->with('error', 'Campaign tidak ditemukan');
        }

        $paymentMethod = $this->request->getPost('payment_method');
        $paymentProofName = null;

        // Handle manual payment with proof upload
        if ($paymentMethod === 'manual') {
            $paymentProof = $this->request->getFile('payment_proof');

            if ($paymentProof && $paymentProof->isValid() && !$paymentProof->hasMoved()) {
                $paymentProofName = $paymentProof->getRandomName();
                $uploadPath = WRITEPATH . 'uploads/payments';

                // Create directory if not exists
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $paymentProof->move($uploadPath, $paymentProofName);
            }
        }

        $donationData = [
            'campaign_id' => $campaignId,
            'donor_name' => $this->request->getPost('donor_name'),
            'donor_email' => $this->request->getPost('donor_email'),
            'donor_phone' => $this->request->getPost('donor_phone'),
            'amount' => $this->request->getPost('amount'),
            'message' => $this->request->getPost('message'),
            'is_anonymous' => $this->request->getPost('is_anonymous') ? 1 : 0,
            'payment_method' => $paymentMethod,
            'payment_proof' => $paymentProofName,
            'status' => 'pending',
        ];

        $donationId = $this->donationModel->insert($donationData);

        if (!$donationId) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses donasi');
        }

        $donation = $this->donationModel->find($donationId);

        // Handle Midtrans payment
        if ($paymentMethod === 'midtrans') {
            try {
                log_message('info', 'Processing Midtrans payment for donation ID: ' . $donationId);

                $transactionParams = $this->midtrans->buildTransactionParams([
                    'transaction_id' => $donation['transaction_id'],
                    'campaign_id' => $campaign['id'],
                    'campaign_title' => $campaign['title'],
                    'amount' => $donation['amount'],
                    'donor_name' => $donation['donor_name'],
                    'donor_email' => $donation['donor_email'],
                    'donor_phone' => $donation['donor_phone'] ?? '',
                    'donor_message' => $donation['message'] ?? '',
                ]);

                log_message('debug', 'Transaction params: ' . json_encode($transactionParams));

                $snapToken = $this->midtrans->createSnapToken($transactionParams);

                if (empty($snapToken)) {
                    throw new \Exception('Snap token empty from Midtrans');
                }

                log_message('info', 'Snap token created: ' . substr($snapToken, 0, 20) . '...');

                // Update donation with snap token
                $updated = $this->donationModel->update($donationId, ['snap_token' => $snapToken]);

                if (!$updated) {
                    log_message('error', 'Failed to update snap token for donation ID: ' . $donationId);
                    throw new \Exception('Failed to save snap token');
                }

                // Verify snap token was saved
                $updatedDonation = $this->donationModel->find($donationId);
                log_message('debug', 'Snap token in DB: ' . ($updatedDonation['snap_token'] ?? 'NULL'));

                // Redirect to payment page with snap token
                return redirect()->to('/payment/' . $donation['transaction_id']);
            } catch (\Exception $e) {
                log_message('error', 'Midtrans Error: ' . $e->getMessage());
                log_message('error', 'Stack trace: ' . $e->getTraceAsString());

                // Delete failed donation
                $this->donationModel->delete($donationId);

                return redirect()->back()->withInput()
                    ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage() . '. Silakan coba lagi.');
            }
        }

        // Manual payment - redirect to success page
        return redirect()->to('/donate/success/' . $donation['transaction_id'])
            ->with('success', 'Terima kasih atas donasi Anda!');
    }

    public function success($transactionId)
    {
        $donation = $this->donationModel->getDonationsByTransactionId($transactionId);

        if (!$donation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Donasi Berhasil',
            'metaDescription' => 'Terima kasih atas donasi Anda',
            'donation' => $donation,
        ];

        return view('pages/donation_success', $data);
    }
}
