<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
    <p class="mt-1 text-sm text-gray-600">Stock Opname System Overview</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Products</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= number_format($totalProducts) ?></h3>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-box text-blue-600 text-2xl"></i>
            </div>
        </div>
        <a href="<?= base_url('/products') ?>" class="text-sm text-blue-600 hover:underline mt-2 inline-block">
            View All Products →
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Stock</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= number_format($totalStock, 2) ?></h3>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <i class="fas fa-warehouse text-green-600 text-2xl"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Combined stock quantity</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Open Sessions</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= number_format($openSessions) ?></h3>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
                <i class="fas fa-clipboard-list text-orange-600 text-2xl"></i>
            </div>
        </div>
        <a href="<?= base_url('/stock-opname') ?>" class="text-sm text-orange-600 hover:underline mt-2 inline-block">
            View Sessions →
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <a href="<?= base_url('/stock-opname/create') ?>" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-md p-6 hover:from-blue-600 hover:to-blue-700 transition">
        <div class="flex items-center">
            <i class="fas fa-plus-circle text-3xl mr-4"></i>
            <div>
                <h4 class="font-semibold text-lg">New Stock Opname</h4>
                <p class="text-sm text-blue-100">Start a new counting session</p>
            </div>
        </div>
    </a>

    <a href="<?= base_url('/products/create') ?>" class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-md p-6 hover:from-green-600 hover:to-green-700 transition">
        <div class="flex items-center">
            <i class="fas fa-box text-3xl mr-4"></i>
            <div>
                <h4 class="font-semibold text-lg">Add Product</h4>
                <p class="text-sm text-green-100">Register a new product</p>
            </div>
        </div>
    </a>

    <a href="<?= base_url('/transactions/create') ?>" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg shadow-md p-6 hover:from-purple-600 hover:to-purple-700 transition">
        <div class="flex items-center">
            <i class="fas fa-exchange-alt text-3xl mr-4"></i>
            <div>
                <h4 class="font-semibold text-lg">Add Transaction</h4>
                <p class="text-sm text-purple-100">Record purchase or sale</p>
            </div>
        </div>
    </a>
</div>

<!-- Recent Sessions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent SO Sessions</h3>
            <a href="<?= base_url('/stock-opname') ?>" class="text-sm text-blue-600 hover:underline">View All</a>
        </div>

        <?php if (empty($recentSessions)): ?>
            <p class="text-gray-500 text-center py-4">No sessions yet</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($recentSessions as $session): ?>
                    <a href="<?= base_url('/stock-opname/' . $session['id']) ?>" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900"><?= esc($session['session_code']) ?></p>
                                <p class="text-sm text-gray-500"><?= date('d M Y', strtotime($session['session_date'])) ?></p>
                            </div>
                            <?php if ($session['status'] === 'open'): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Open</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Closed</span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
            <a href="<?= base_url('/transactions') ?>" class="text-sm text-blue-600 hover:underline">View All</a>
        </div>

        <?php if (empty($recentTransactions)): ?>
            <p class="text-gray-500 text-center py-4">No transactions yet</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($recentTransactions as $trx): ?>
                    <div class="p-3 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm"><?= esc($trx['name']) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($trx['code']) ?> | <?= date('d M Y', strtotime($trx['transaction_date'])) ?></p>
                            </div>
                            <div class="text-right ml-2">
                                <?php if ($trx['type'] === 'purchase'): ?>
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">+<?= number_format($trx['qty'], 0) ?></span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-800">-<?= number_format($trx['qty'], 0) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- System Info -->
<div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Stock Opname Bertahap</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>Sistem ini mendukung stock opname bertahap dengan baseline dinamis:</p>
                <ul class="list-disc list-inside mt-1 ml-2">
                    <li>Baseline dihitung otomatis berdasarkan SO sebelumnya + mutasi</li>
                    <li>Barang yang belum dihitung tetap menggunakan stok sistem</li>
                    <li>Stok sistem hanya berubah saat sesi ditutup</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>