<?php

use App\Controllers\Home;
use App\Controllers\AuthController;
use App\Controllers\StockOpnameController;
use App\Controllers\ProductController;
use App\Controllers\TransactionController;
use App\Controllers\Admin\LocationController;
use App\Controllers\Admin\UserController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('login', [AuthController::class, 'login']);
$routes->post('login', [AuthController::class, 'loginProcess']);
$routes->get('register', [AuthController::class, 'register']);
$routes->post('register', [AuthController::class, 'registerProcess']);
$routes->get('logout', [AuthController::class, 'logout']);

// Protected Routes - Require Login
$routes->group('', ['filter' => 'session'], static function (RouteCollection $routes) {
    // Dashboard
    $routes->get('/', [Home::class, 'index']);
    $routes->get('dashboard', [AuthController::class, 'dashboard']);

    // Stock Opname Routes
    $routes->group('stock-opname', static function (RouteCollection $routes) {
        $routes->get('', [StockOpnameController::class, 'index']);
        $routes->get('create', [StockOpnameController::class, 'create'], ['filter' => 'permission:stockopname.create']);
        $routes->post('store', [StockOpnameController::class, 'store'], ['filter' => 'permission:stockopname.create']);
        $routes->get('(:num)', [StockOpnameController::class, 'show/$1']);
        $routes->get('(:num)/batch-input', [StockOpnameController::class, 'batchInput/$1']);
        $routes->get('(:num)/search-item', [StockOpnameController::class, 'searchItem/$1']);
        $routes->post('(:num)/batch-save', [StockOpnameController::class, 'saveBatchInput/$1']);
        $routes->post('update-item/(:num)', [StockOpnameController::class, 'updateItem/$1'], ['filter' => 'permission:stockopname.edit']);
        $routes->get('(:num)/close', [StockOpnameController::class, 'close/$1'], ['filter' => 'permission:stockopname.close']);
        $routes->get('(:num)/reopen', [StockOpnameController::class, 'reopen/$1'], ['filter' => 'permission:stockopname.close']);
        $routes->get('(:num)/export', [StockOpnameController::class, 'export/$1']);

        // Print & Export Report
        $routes->get('(:num)/print-report', [StockOpnameController::class, 'printReport/$1']);
        $routes->get('(:num)/export-report', [StockOpnameController::class, 'exportReport/$1']);

        // New: Freeze/Unfreeze baseline
        $routes->post('(:num)/freeze-baseline', [StockOpnameController::class, 'freezeBaseline/$1'], ['filter' => 'permission:stockopname.edit']);
        $routes->post('(:num)/unfreeze-baseline', [StockOpnameController::class, 'unfreezeBaseline/$1'], ['filter' => 'permission:stockopname.edit']);

        // New: Mutation detail AJAX
        $routes->get('(:num)/mutation-detail/(:num)', [StockOpnameController::class, 'getMutationDetail/$1/$2']);

        // New: Add new product to session
        $routes->post('(:num)/add-new-product', [StockOpnameController::class, 'addNewProduct/$1'], ['filter' => 'permission:stockopname.edit']);
        $routes->get('(:num)/search-new-products', [StockOpnameController::class, 'searchNewProducts/$1']);
    });

    // Product Routes - Admin Only
    $routes->group('products', ['filter' => 'permission:products.manage'], static function (RouteCollection $routes) {
        $routes->get('', [ProductController::class, 'index']);
        $routes->get('create', [ProductController::class, 'create']);
        $routes->post('store', [ProductController::class, 'store']);
        $routes->get('edit/(:num)', [ProductController::class, 'edit/$1']);
        $routes->post('update/(:num)', [ProductController::class, 'update/$1']);
        $routes->get('delete/(:num)', [ProductController::class, 'delete/$1']);

        // Import routes
        $routes->get('import', [ProductController::class, 'import']);
        $routes->post('import/process', [ProductController::class, 'processImport']);
        $routes->get('import-preview', [ProductController::class, 'importPreview']);
        $routes->post('import/confirm', [ProductController::class, 'confirmImport']);
        $routes->get('import/template', [ProductController::class, 'downloadTemplate']);
        $routes->get('download-import-log', [ProductController::class, 'downloadImportLog']);

        // Import Price routes
        $routes->get('import-price', [ProductController::class, 'importPrice']);
        $routes->post('import-price/process', [ProductController::class, 'processImportPrice']);
        $routes->get('import-price/template', [ProductController::class, 'downloadPriceTemplate']);
    });

    // Product API Routes - Accessible to all logged in users (for Stock Opname search)
    $routes->get('products/api/search', [ProductController::class, 'apiSearch']);

    // Transaction Routes - Admin Only
    $routes->group('transactions', ['filter' => 'permission:transactions.manage'], static function (RouteCollection $routes) {
        $routes->get('', [TransactionController::class, 'index']);
        $routes->get('create', [TransactionController::class, 'create']);
        $routes->post('store', [TransactionController::class, 'store']);
        $routes->get('delete/(:num)', [TransactionController::class, 'delete/$1']);

        // Import routes
        $routes->get('import', [TransactionController::class, 'import']);
        $routes->post('import/process', [TransactionController::class, 'processImport']);
        $routes->get('import-preview', [TransactionController::class, 'importPreview']);
        $routes->post('import/confirm', [TransactionController::class, 'confirmImport']);
        $routes->get('import/template', [TransactionController::class, 'downloadTemplate']);
    });

    // Location Routes - Admin Only
    $routes->group('admin/location', ['filter' => 'permission:locations.manage'], static function (RouteCollection $routes) {
        $routes->get('', [LocationController::class, 'index']);
        $routes->get('create', [LocationController::class, 'create']);
        $routes->post('store', [LocationController::class, 'store']);
        $routes->get('edit/(:num)', [LocationController::class, 'edit/$1']);
        $routes->post('update/(:num)', [LocationController::class, 'update/$1']);
        $routes->delete('delete/(:num)', [LocationController::class, 'delete/$1']);

        // Import routes
        $routes->get('import', [LocationController::class, 'import']);
        $routes->post('process-import', [LocationController::class, 'processImport']);
        $routes->get('download-template', [LocationController::class, 'downloadTemplate']);

        // Admin only API routes
        $routes->post('getData', [LocationController::class, 'getData']);
        $routes->get('export', [LocationController::class, 'export']);
    });

    // Location API Routes - Accessible to all logged in users (for Stock Opname form)
    $routes->group('admin/location', static function (RouteCollection $routes) {
        $routes->get('api/list', [LocationController::class, 'apiList']);
        $routes->get('api/search', [LocationController::class, 'apiSearch']);
        $routes->get('getOptions', [LocationController::class, 'getOptions']);
        $routes->get('getSOStatus', [LocationController::class, 'getSOStatus']);
        $routes->get('getSOStatus/(:num)', [LocationController::class, 'getSOStatus/$1']);
    });

    // User Management Routes - Admin Only
    $routes->group('admin/user', ['filter' => 'permission:users.manage'], static function (RouteCollection $routes) {
        $routes->get('', [UserController::class, 'index']);
        $routes->get('create', [UserController::class, 'create']);
        $routes->post('store', [UserController::class, 'store']);
        $routes->get('edit/(:num)', [UserController::class, 'edit/$1']);
        $routes->post('update/(:num)', [UserController::class, 'update/$1']);
        $routes->post('delete/(:num)', [UserController::class, 'delete/$1']);
        $routes->post('toggle-active/(:num)', [UserController::class, 'toggleActive/$1']);
        $routes->post('reset-password/(:num)', [UserController::class, 'resetPassword/$1']);
        $routes->post('getData', [UserController::class, 'getData']);
    });
});
