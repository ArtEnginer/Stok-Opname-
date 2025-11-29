<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockOpnameSessionModel;
use App\Models\TransactionModel;

class Home extends BaseController
{
    protected $productModel;
    protected $sessionModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->sessionModel = new StockOpnameSessionModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'totalProducts' => $this->productModel->countAll(),
            'totalStock' => $this->productModel->selectSum('stock')->first()['stock'] ?? 0,
            'openSessions' => $this->sessionModel->where('status', 'open')->countAllResults(),
            'recentSessions' => $this->sessionModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'recentTransactions' => $this->transactionModel
                ->select('transactions.*, products.code, products.name')
                ->join('products', 'products.id = transactions.product_id')
                ->orderBy('transaction_date', 'DESC')
                ->limit(10)
                ->findAll(),
        ];

        return view('dashboard', $data);
    }
}
