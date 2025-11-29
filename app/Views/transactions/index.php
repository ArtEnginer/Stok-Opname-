<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Transactions</h2>
    <p class="mt-1 text-sm text-gray-600">Purchase and sales history</p>
</div>

<div class="mb-6 flex gap-3">
    <a href="<?= base_url('/transactions/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i> Add Transaction
    </a>
    <a href="<?= base_url('/transactions/import') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
        <i class="fas fa-file-import mr-2"></i> Import Transactions
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">No transactions found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $trx): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-500"><?= date('d M Y', strtotime($trx['transaction_date'])) ?></td>
                            <td class="px-4 py-3 text-sm">
                                <?php if ($trx['type'] === 'purchase'): ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Purchase</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Sale</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="font-medium"><?= esc($trx['name']) ?></div>
                                <div class="text-gray-500 text-xs"><?= esc($trx['code']) ?></div>
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900"><?= number_format($trx['qty'], 2) ?></td>
                            <td class="px-4 py-3 text-sm text-right text-gray-500"><?= number_format($trx['price'], 0) ?></td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900 font-medium"><?= number_format($trx['qty'] * $trx['price'], 0) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500"><?= esc($trx['reference_no'] ?: '-') ?></td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="<?= base_url('/transactions/delete/' . $trx['id']) ?>"
                                    onclick="return confirm('Are you sure?')"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pager)): ?>
        <div class="px-4 py-3 border-t">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>