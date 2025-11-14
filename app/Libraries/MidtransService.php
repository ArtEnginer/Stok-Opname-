<?php

namespace App\Libraries;

use App\Models\AppSettingModel;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->loadConfig();
        $this->initMidtrans();
    }

    /**
     * Load configuration from database settings
     */
    protected function loadConfig()
    {
        $this->serverKey = AppSettingModel::get('midtrans_server_key', '');
        $this->clientKey = AppSettingModel::get('midtrans_client_key', '');
        $this->isProduction = (bool) AppSettingModel::get('midtrans_is_production', false);
        $this->isSanitized = (bool) AppSettingModel::get('midtrans_is_sanitized', true);
        $this->is3ds = (bool) AppSettingModel::get('midtrans_is_3ds', true);
    }

    /**
     * Initialize Midtrans configuration
     */
    protected function initMidtrans()
    {
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;
    }

    /**
     * Create Snap payment token
     *
     * @param array $params Transaction parameters
     * @return string Snap token
     */
    public function createSnapToken(array $params)
    {
        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Snap Token Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get transaction status
     *
     * @param string $orderId Order ID
     * @return object Transaction status
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Transaction Status Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel transaction
     *
     * @param string $orderId Order ID
     * @return object Cancel response
     */
    public function cancelTransaction($orderId)
    {
        try {
            $cancel = Transaction::cancel($orderId);
            return $cancel;
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Cancel Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Approve transaction
     *
     * @param string $orderId Order ID
     * @return object Approve response
     */
    public function approveTransaction($orderId)
    {
        try {
            $approve = Transaction::approve($orderId);
            return $approve;
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Approve Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Expire transaction
     *
     * @param string $orderId Order ID
     * @return object Expire response
     */
    public function expireTransaction($orderId)
    {
        try {
            $expire = Transaction::expire($orderId);
            return $expire;
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Expire Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Refund transaction
     *
     * @param string $orderId Order ID
     * @param array $params Refund parameters
     * @return object Refund response
     */
    public function refundTransaction($orderId, array $params = [])
    {
        try {
            $refund = Transaction::refund($orderId, $params);
            return $refund;
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Refund Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get client key for frontend
     *
     * @return string Client key
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Check if production mode
     *
     * @return bool
     */
    public function isProduction()
    {
        return $this->isProduction;
    }

    /**
     * Get Snap URL based on environment
     *
     * @return string Snap URL
     */
    public function getSnapUrl()
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Verify notification signature
     *
     * @param array $notification Notification data
     * @return bool
     */
    public function verifySignature($notification)
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return $signatureKey === $mySignature;
    }

    /**
     * Build transaction params
     *
     * @param array $data Transaction data
     * @return array
     */
    public function buildTransactionParams($data)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $data['order_id'],
                'gross_amount' => (int) $data['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $data['customer_name'] ?? '',
                'email' => $data['customer_email'] ?? '',
                'phone' => $data['customer_phone'] ?? '',
            ],
            'item_details' => $data['items'] ?? [],
        ];

        // Add custom field
        if (isset($data['custom_field1'])) {
            $params['custom_field1'] = $data['custom_field1'];
        }
        if (isset($data['custom_field2'])) {
            $params['custom_field2'] = $data['custom_field2'];
        }
        if (isset($data['custom_field3'])) {
            $params['custom_field3'] = $data['custom_field3'];
        }

        // Add enabled payments
        if (isset($data['enabled_payments'])) {
            $params['enabled_payments'] = $data['enabled_payments'];
        }

        // Add expiry
        if (isset($data['expiry'])) {
            $params['expiry'] = $data['expiry'];
        }

        return $params;
    }

    /**
     * Reload configuration (useful after settings update)
     */
    public function reloadConfig()
    {
        $this->loadConfig();
        $this->initMidtrans();
    }
}
