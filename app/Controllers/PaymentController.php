<?php

namespace App\Controllers;

use App\Models\DonationModel;
use App\Models\CampaignModel;
use App\Libraries\MidtransLibrary;

class PaymentController extends BaseController
{
    protected $donationModel;
    protected $campaignModel;
    protected $midtrans;

    public function __construct()
    {
        $this->donationModel = new DonationModel();
        $this->campaignModel = new CampaignModel();
        $this->midtrans = new MidtransLibrary();
    }

    /**
     * Payment page with Snap
     */
    public function index($transactionId)
    {
        $donation = $this->donationModel->where('transaction_id', $transactionId)->first();

        if (!$donation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Debug log
        log_message('debug', 'Payment page - Transaction ID: ' . $transactionId);
        log_message('debug', 'Payment page - Snap Token: ' . ($donation['snap_token'] ?? 'NULL'));

        if (empty($donation['snap_token'])) {
            log_message('error', 'Snap token empty for transaction: ' . $transactionId);
            return redirect()->to('/donate/success/' . $transactionId)
                ->with('error', 'Token pembayaran tidak ditemukan. Silakan hubungi admin.');
        }

        $campaign = $this->campaignModel->find($donation['campaign_id']);

        if (!$campaign) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Campaign tidak ditemukan');
        }

        $midtransConfig = config('Midtrans');

        $data = [
            'title' => 'Pembayaran Donasi',
            'metaDescription' => 'Halaman pembayaran donasi',
            'donation' => $donation,
            'campaign' => $campaign,
            'snapToken' => $donation['snap_token'],
            'clientKey' => $midtransConfig->clientKey,
        ];

        log_message('debug', 'Payment page data prepared successfully');

        return view('pages/payment', $data);
    }

    /**
     * Handle notification from Midtrans
     */
    public function notification()
    {
        try {
            $notification = $this->midtrans->handleNotification();

            $transactionId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? 'accept';
            $paymentType = $notification->payment_type;

            // Verify signature
            $signatureKey = $notification->signature_key;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;

            if (!$this->midtrans->verifySignature($transactionId, $statusCode, $grossAmount, $signatureKey)) {
                log_message('error', 'Invalid signature for transaction: ' . $transactionId);
                return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Invalid signature']);
            }

            $donation = $this->donationModel->where('transaction_id', $transactionId)->first();

            if (!$donation) {
                log_message('error', 'Donation not found: ' . $transactionId);
                return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Donation not found']);
            }

            $updateData = [
                'payment_method' => $this->midtrans->getPaymentMethodName($paymentType),
            ];

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $updateData['status'] = 'verified';
                    $updateData['verified_at'] = date('Y-m-d H:i:s');
                }
            } elseif ($transactionStatus == 'settlement') {
                $updateData['status'] = 'verified';
                $updateData['verified_at'] = date('Y-m-d H:i:s');
            } elseif ($transactionStatus == 'pending') {
                $updateData['status'] = 'pending';
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $updateData['status'] = 'rejected';
                $updateData['notes'] = 'Payment ' . $transactionStatus;
            }

            $this->donationModel->update($donation['id'], $updateData);

            // Update campaign collected amount if verified
            if (isset($updateData['status']) && $updateData['status'] === 'verified') {
                $this->campaignModel->updateCollectedAmount($donation['campaign_id'], $donation['amount']);
            }

            log_message('info', 'Payment notification processed: ' . $transactionId . ' - Status: ' . $transactionStatus);

            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            log_message('error', 'Payment notification error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Finish page - user is redirected here after completing payment
     */
    public function finish()
    {
        $orderId = $this->request->getGet('order_id');
        $statusCode = $this->request->getGet('status_code');
        $transactionStatus = $this->request->getGet('transaction_status');

        if (!$orderId) {
            return redirect()->to('/')->with('error', 'Invalid payment data');
        }

        $donation = $this->donationModel->where('transaction_id', $orderId)->first();

        if (!$donation) {
            return redirect()->to('/')->with('error', 'Donasi tidak ditemukan');
        }

        // Get latest status from Midtrans
        try {
            $status = $this->midtrans->getStatus($orderId);

            $data = [
                'title' => 'Pembayaran Selesai',
                'metaDescription' => 'Status pembayaran donasi',
                'donation' => $donation,
                'transactionStatus' => $status->transaction_status,
                'paymentType' => $status->payment_type ?? '',
                'vaNumbers' => $status->va_numbers ?? null,
                'billKey' => $status->bill_key ?? null,
                'billerCode' => $status->biller_code ?? null,
            ];

            return view('pages/payment_finish', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get status error: ' . $e->getMessage());
            return redirect()->to('/donate/success/' . $orderId);
        }
    }

    /**
     * Unfinish page - user closed payment popup
     */
    public function unfinish()
    {
        $orderId = $this->request->getGet('order_id');

        if (!$orderId) {
            return redirect()->to('/');
        }

        $donation = $this->donationModel->where('transaction_id', $orderId)->first();

        $data = [
            'title' => 'Pembayaran Belum Selesai',
            'metaDescription' => 'Pembayaran belum selesai',
            'donation' => $donation,
        ];

        return view('pages/payment_unfinish', $data);
    }

    /**
     * Error page - payment error
     */
    public function error()
    {
        $orderId = $this->request->getGet('order_id');

        $donation = null;
        if ($orderId) {
            $donation = $this->donationModel->where('transaction_id', $orderId)->first();
        }

        $data = [
            'title' => 'Pembayaran Gagal',
            'metaDescription' => 'Pembayaran gagal',
            'donation' => $donation,
        ];

        return view('pages/payment_error', $data);
    }

    /**
     * Check payment status
     */
    public function checkStatus($transactionId)
    {
        try {
            $donation = $this->donationModel->where('transaction_id', $transactionId)->first();

            if (!$donation) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Donasi tidak ditemukan'
                ]);
            }

            $status = $this->midtrans->getStatus($transactionId);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'transaction_status' => $status->transaction_status,
                    'payment_type' => $status->payment_type,
                    'transaction_time' => $status->transaction_time,
                    'settlement_time' => $status->settlement_time ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
