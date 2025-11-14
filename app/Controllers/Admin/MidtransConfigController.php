<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppSettingModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class MidtransConfigController extends BaseController
{
    use ResponseTrait;

    /**
     * Test Midtrans connection
     * POST /admin/midtrans/test-connection
     */


    public function testConnection()
    {
        try {
            $data = $this->request->getJSON(true);

            // Validasi dasar
            if (empty($data['server_key'])) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Server key is required',
                    'data' => ['connected' => false]
                ], 400);
            }

            // Setup konfigurasi sederhana
            \Midtrans\Config::$serverKey = $data['server_key'];
            \Midtrans\Config::$isProduction = (bool)($data['is_production'] ?? false);

            // Minimal CURL options
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ];

            // Test dengan request yang lebih sederhana
            $payload = [
                'transaction_details' => [
                    'order_id' => 'test-' . time(),
                    'gross_amount' => 1000
                ]
            ];

            $ch = curl_init();
            $url = \Midtrans\Config::$isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Basic ' . base64_encode(\Midtrans\Config::$serverKey . ':')
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($httpCode === 200 || $httpCode === 201) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Midtrans connection successful',
                    'data' => ['connected' => true]
                ]);
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Midtrans connection failed',
                    'error' => $error ?: "HTTP Code: $httpCode",
                    'data' => ['connected' => false]
                ], 400);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Test connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get Midtrans dashboard info
     * GET /admin/midtrans/dashboard-info
     */
    public function getDashboardInfo()
    {
        try {
            $isProduction = (bool) AppSettingModel::get('midtrans_is_production', false);

            $dashboardUrl = $isProduction
                ? 'https://dashboard.midtrans.com'
                : 'https://dashboard.sandbox.midtrans.com';

            return $this->respond([
                'status' => 'success',
                'data' => [
                    'dashboard_url' => $dashboardUrl,
                    'environment' => $isProduction ? 'production' : 'sandbox',
                    'snap_url' => $isProduction
                        ? 'https://app.midtrans.com/snap/snap.js'
                        : 'https://app.sandbox.midtrans.com/snap/snap.js',
                    'docs_url' => 'https://docs.midtrans.com',
                ]
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to get dashboard info',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Validate Midtrans credentials
     * POST /admin/midtrans/validate
     */
    public function validateCredentials()
    {
        try {
            $data = $this->request->getJSON(true);

            $errors = [];

            // Validate server key format
            if (empty($data['server_key'])) {
                $errors['server_key'] = 'Server key is required';
            } elseif (!preg_match('/^Mid-server-/', $data['server_key'])) {
                $errors['server_key'] = 'Invalid server key format. Should start with "Mid-server-"';
            }

            // Validate client key format
            if (empty($data['client_key'])) {
                $errors['client_key'] = 'Client key is required';
            } elseif (!preg_match('/^Mid-client-/', $data['client_key'])) {
                $errors['client_key'] = 'Invalid client key format. Should start with "Mid-client-"';
            }

            if (!empty($errors)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Credentials format is valid',
                'data' => [
                    'valid' => true
                ]
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Validation failed',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get payment methods available
     * GET /admin/midtrans/payment-methods
     */
    public function getPaymentMethods()
    {
        try {
            $isProduction = (bool) AppSettingModel::get('midtrans_is_production', false);

            $methods = [
                'credit_card' => [
                    'name' => 'Credit Card',
                    'enabled' => true,
                    'description' => 'Visa, MasterCard, JCB, Amex'
                ],
                'bca_va' => [
                    'name' => 'BCA Virtual Account',
                    'enabled' => true,
                    'description' => 'Transfer via BCA Virtual Account'
                ],
                'bni_va' => [
                    'name' => 'BNI Virtual Account',
                    'enabled' => true,
                    'description' => 'Transfer via BNI Virtual Account'
                ],
                'bri_va' => [
                    'name' => 'BRI Virtual Account',
                    'enabled' => true,
                    'description' => 'Transfer via BRI Virtual Account'
                ],
                'permata_va' => [
                    'name' => 'Permata Virtual Account',
                    'enabled' => true,
                    'description' => 'Transfer via Permata Virtual Account'
                ],
                'gopay' => [
                    'name' => 'GoPay',
                    'enabled' => true,
                    'description' => 'Pay with GoPay e-wallet'
                ],
                'qris' => [
                    'name' => 'QRIS',
                    'enabled' => true,
                    'description' => 'Scan QR code to pay'
                ],
                'shopeepay' => [
                    'name' => 'ShopeePay',
                    'enabled' => true,
                    'description' => 'Pay with ShopeePay e-wallet'
                ],
            ];

            return $this->respond([
                'status' => 'success',
                'data' => [
                    'environment' => $isProduction ? 'production' : 'sandbox',
                    'payment_methods' => $methods
                ]
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to get payment methods',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
