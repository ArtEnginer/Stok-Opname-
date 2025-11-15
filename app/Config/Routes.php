<?php

use App\Controllers\Home;
use App\Controllers\CampaignController;
use App\Controllers\DonationController;
use App\Controllers\FileController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\CampaignManageController;
use App\Controllers\Admin\DonationManageController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\MidtransConfigController;
use App\Controllers\Admin\ProfileController;
use App\Controllers\Api\SettingsApiController;
use App\Controllers\Migrate;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->get('/', [Home::class, 'index']);

// File Serving Routes (untuk akses file di writable/uploads)
$routes->get('uploads/(:segment)/(:segment)', [FileController::class, 'serve/$1/$2']);
$routes->get('files/campaigns/(:segment)', [FileController::class, 'campaigns/$1']);
$routes->get('files/receipts/(:segment)', [FileController::class, 'receipts/$1']);
$routes->get('files/updates/(:segment)', [FileController::class, 'updates/$1']);
$routes->get('files/download/(:segment)/(:segment)', [FileController::class, 'download/$1/$2']);

$routes->get('campaign', [CampaignController::class, 'index']);
$routes->get('campaign/(:segment)', [CampaignController::class, 'detail/$1']);
$routes->post('campaign/(:segment)/comment', [CampaignController::class, 'postComment/$1']);
$routes->get('donate/(:segment)', [DonationController::class, 'form/$1']);
$routes->post('donate/process', [DonationController::class, 'process']);
$routes->get('donate/success/(:segment)', [DonationController::class, 'success/$1']);

// Payment Routes
$routes->group('payment', ['namespace' => 'App\Controllers'], static function (RouteCollection $routes) {
    $routes->get('(:segment)', 'PaymentController::index/$1');
    $routes->post('notification', 'PaymentController::notification');
    $routes->get('finish', 'PaymentController::finish');
    $routes->get('unfinish', 'PaymentController::unfinish');
    $routes->get('error', 'PaymentController::error');
    $routes->get('check/(:segment)', 'PaymentController::checkStatus/$1');
});

// Receipt Routes
$routes->group('receipt', ['namespace' => 'App\Controllers'], static function (RouteCollection $routes) {
    $routes->get('(:segment)', 'ReceiptController::view/$1');
    $routes->get('download/(:segment)', 'ReceiptController::download/$1');
    $routes->post('email/(:segment)', 'ReceiptController::sendEmail/$1');
});

$routes->get('about', [Home::class, 'about']);
$routes->get('contact', [Home::class, 'contact']);

// Migration routes (development only)
$routes->environment('development', static function ($routes) {
    $routes->get('migrate', [Migrate::class, 'index']);
    $routes->get('migrate/(:any)', [Migrate::class, 'execute']);
    // Test upload access
    $routes->get('test-upload', static function () {
        return view('test_upload');
    });
});

// Admin Panel Routes
$routes->group('admin', static function (RouteCollection $routes) {
    $routes->get('', [AdminController::class, 'dashboard']);
    $routes->get('dashboard', [AdminController::class, 'dashboard']);

    // Settings Page View
    $routes->get('settings-page', static function () {
        return view('admin/settings');
    });

    // Campaign Management
    $routes->get('campaigns', [CampaignManageController::class, 'index']);
    $routes->get('campaigns/create', [CampaignManageController::class, 'create']);
    $routes->post('campaigns/store', [CampaignManageController::class, 'store']);
    $routes->get('campaigns/edit/(:segment)', [CampaignManageController::class, 'edit/$1']);
    $routes->post('campaigns/update/(:segment)', [CampaignManageController::class, 'update/$1']);
    $routes->post('campaigns/delete/(:segment)', [CampaignManageController::class, 'delete/$1']);

    // Donation Management
    $routes->get('donations', [DonationManageController::class, 'index']);
    $routes->get('donations/detail/(:segment)', [DonationManageController::class, 'detail/$1']);
    $routes->post('donations/verify/(:segment)', [DonationManageController::class, 'verify/$1']);
    $routes->post('donations/reject/(:segment)', [DonationManageController::class, 'reject/$1']);
    $routes->get('donations/export', [DonationManageController::class, 'export']);

    // Settings Management
    $routes->get('settings', [SettingsController::class, 'index']);
    $routes->get('settings/grouped', [SettingsController::class, 'getGrouped']);
    $routes->get('settings/payment/midtrans', [SettingsController::class, 'getMidtransSettings']);
    $routes->put('settings/payment/midtrans', [SettingsController::class, 'updateMidtransSettings']);
    $routes->put('settings/batch', [SettingsController::class, 'updateBatch']);
    $routes->post('settings', [SettingsController::class, 'create']);
    $routes->get('settings/(:segment)', [SettingsController::class, 'show/$1']);
    $routes->put('settings/(:segment)', [SettingsController::class, 'update/$1']);
    $routes->delete('settings/(:segment)', [SettingsController::class, 'delete/$1']);
    $routes->post('settings/(:segment)/upload', [SettingsController::class, 'uploadFile/$1']);

    // Midtrans Configuration
    $routes->post('midtrans/test-connection', [MidtransConfigController::class, 'testConnection']);
    $routes->get('midtrans/dashboard-info', [MidtransConfigController::class, 'getDashboardInfo']);
    $routes->post('midtrans/validate', [MidtransConfigController::class, 'validateCredentials']);
    $routes->get('midtrans/payment-methods', [MidtransConfigController::class, 'getPaymentMethods']);

    // Profile Management
    $routes->group('profile', ['namespace' => 'App\Controllers\Admin'], static function (RouteCollection $routes) {
        $routes->get('', 'ProfileController::index');
        $routes->post('update', 'ProfileController::update');
        $routes->post('update-email', 'ProfileController::updateEmail');
        $routes->post('update-password', 'ProfileController::updatePassword');
    });
});

// API Routes
$routes->group('api', static function (RouteCollection $routes) {
    // Public Settings API
    $routes->get('settings/public', [SettingsApiController::class, 'getPublicSettings']);
    $routes->get('settings/app-info', [SettingsApiController::class, 'getAppInfo']);
    $routes->get('settings/midtrans-config', [SettingsApiController::class, 'getMidtransConfig']);
});

service('auth')->routes($routes);
