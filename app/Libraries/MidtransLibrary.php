<?php

namespace App\Libraries;

use Config\Midtrans as MidtransConfig;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransLibrary
{
    protected MidtransConfig $config;

    public function __construct()
    {
        $this->config = config('Midtrans');
        $this->initConfig();
    }

    /**
     * Initialize Midtrans configuration
     */
    private function initConfig(): void
    {
        Config::$serverKey = $this->config->serverKey;
        Config::$clientKey = $this->config->clientKey;
        Config::$isProduction = $this->config->isProduction;
        Config::$isSanitized = $this->config->isSanitized;
        Config::$is3ds = $this->config->is3ds;

        // Fix CURL options - gunakan konstanta yang benar
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 60,
        ];
    }
    /**
     * Create Snap payment token using raw CURL
     * 
     * @param array $params Transaction parameters
     * @return string Snap token
     */
    public function createSnapToken(array $params): string
    {
        try {
            // Use raw CURL to avoid Midtrans Config array key issues
            $url = $this->config->isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Basic ' . base64_encode($this->config->serverKey . ':')
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 60
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new \Exception('CURL Error: ' . $curlError);
            }

            if ($httpCode !== 201 && $httpCode !== 200) {
                $errorMsg = 'HTTP ' . $httpCode;
                if ($response) {
                    $responseData = json_decode($response, true);
                    $errorMsg .= ': ' . ($responseData['error_messages'][0] ?? $response);
                }
                throw new \Exception($errorMsg);
            }

            $result = json_decode($response, true);

            if (empty($result['token'])) {
                throw new \Exception('Snap token not found in response');
            }

            return $result['token'];
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Snap Token Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build transaction parameters for donation
     * 
     * @param array $donationData Donation information
     * @return array Transaction parameters
     */
    public function buildTransactionParams(array $donationData): array
    {
        $orderId = $donationData['transaction_id'];
        $amount = (int) $donationData['amount'];

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [
                [
                    'id' => $donationData['campaign_id'],
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Donasi: ' . $donationData['campaign_title'],
                ]
            ],
            'customer_details' => [
                'first_name' => $donationData['donor_name'],
                'email' => $donationData['donor_email'],
                'phone' => $donationData['donor_phone'] ?? '',
            ],
        ];

        // Add optional fields
        if (!empty($donationData['donor_message'])) {
            $params['custom_field1'] = $donationData['donor_message'];
        }

        // Set enabled payments
        if (!empty($this->config->paymentMethods)) {
            $params['enabled_payments'] = $this->config->paymentMethods;
        }

        // Set callbacks
        $params['callbacks'] = [
            'finish' => $this->config->finishUrl,
            'unfinish' => $this->config->unfinishUrl,
            'error' => $this->config->errorUrl,
        ];

        return $params;
    }

    /**
     * Get transaction status from Midtrans
     * 
     * @param string $orderId Order ID
     * @return object Transaction status
     */
    public function getStatus(string $orderId): object
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Get Status Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle notification from Midtrans
     * 
     * @return Notification
     */
    public function handleNotification(): Notification
    {
        try {
            return new Notification();
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Notification Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify notification signature
     * 
     * @param string $orderId Order ID
     * @param string $statusCode Status code
     * @param string $grossAmount Gross amount
     * @param string $signatureKey Signature key from notification
     * @return bool
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = $this->config->serverKey;
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $generatedSignature = hash('sha512', $input);

        return $generatedSignature === $signatureKey;
    }

    /**
     * Cancel transaction
     * 
     * @param string $orderId Order ID
     * @return object
     */
    public function cancel(string $orderId): object
    {
        try {
            return Transaction::cancel($orderId);
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Cancel Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get payment method name
     * 
     * @param string $paymentType Payment type from Midtrans
     * @return string
     */
    public function getPaymentMethodName(string $paymentType): string
    {
        $methods = [
            'credit_card' => 'Kartu Kredit',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer Bank',
            'echannel' => 'Mandiri Bill',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'other_va' => 'Virtual Account',
        ];

        return $methods[$paymentType] ?? ucwords(str_replace('_', ' ', $paymentType));
    }
}
