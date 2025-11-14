<?php

use App\Controllers\Home;
use App\Controllers\CampaignController;
use App\Controllers\DonationController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\CampaignManageController;
use App\Controllers\Admin\DonationManageController;
use App\Controllers\Migrate;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->get('/', [Home::class, 'index']);
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
});

// Admin Panel Routes
$routes->group('admin', static function (RouteCollection $routes) {
    $routes->get('', [AdminController::class, 'dashboard']);
    $routes->get('dashboard', [AdminController::class, 'dashboard']);

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
});

service('auth')->routes($routes);
