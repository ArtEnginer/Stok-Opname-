<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    /**
     * Merchant Server Key
     * Get from Midtrans Dashboard: https://dashboard.midtrans.com/settings/config_info
     */
    public string $serverKey = 'Mid-server-toKKlZWXkaLmKbywzLk3z17y';

    /**
     * Merchant Client Key
     * Get from Midtrans Dashboard: https://dashboard.midtrans.com/settings/config_info
     */
    public string $clientKey = 'Mid-client-LDxz5bQpP2v2-w0V';

    /**
     * Set to true for production, false for sandbox
     */
    public bool $isProduction = false;

    /**
     * Set to true to enable sanitization
     */
    public bool $isSanitized = true;

    /**
     * Set to true to enable 3D Secure
     */
    public bool $is3ds = true;

    /**
     * Payment notification URL
     * This URL will receive payment status notifications from Midtrans
     */
    public string $notificationUrl = '';

    /**
     * Payment finish URL
     * User will be redirected here after completing payment
     */
    public string $finishUrl = '';

    /**
     * Payment unfinish URL
     * User will be redirected here if payment is not completed
     */
    public string $unfinishUrl = '';

    /**
     * Payment error URL
     * User will be redirected here if payment encounters error
     */
    public string $errorUrl = '';

    /**
     * Allowed payment methods
     * Available: credit_card, gopay, shopeepay, qris, bca_va, bni_va, bri_va, permata_va, other_va
     */
    public array $paymentMethods = [
        'credit_card',
        'gopay',
        'shopeepay',
        'qris',
        'bca_va',
        'bni_va',
        'bri_va',
        'permata_va',
        'other_va'
    ];

    public function __construct()
    {
        parent::__construct();

        // Set URLs based on base URL
        $baseUrl = base_url();
        $this->notificationUrl = $baseUrl . '/payment/notification';
        $this->finishUrl = $baseUrl . '/payment/finish';
        $this->unfinishUrl = $baseUrl . '/payment/unfinish';
        $this->errorUrl = $baseUrl . '/payment/error';
    }
}
