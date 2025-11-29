<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Import Preview</h2>
            <p class="mt-1 text-sm text-gray-600">Review and confirm transactions before importing</p>
        </div>
    </div>
</div>

<!-- Validation Summary -->
<?php if (!empty($errors) || !empty($validData)): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Rows -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-csv text-3xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-blue-700">Total Rows</p>
                    <p class="text-2xl font-bold text-blue-900"><?= count($errors) + count($validData) ?></p>
                </div>
            </div>
        </div>

        <!-- Valid -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-green-700">Valid</p>
                    <p class="text-2xl font-bold text-green-900"><?= count($validData) ?></p>
                </div>
            </div>
        </div>

        <!-- Errors -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-red-700">Errors</p>
                    <p class="text-2xl font-bold text-red-900"><?= count($errors) ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Error List -->
<?php if (!empty($errors)): ?>
    <div class="bg-red-50 border border-red-300 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-red-900 mb-4">
            <i class="fas fa-times-circle mr-2"></i> Validation Errors (<?= count($errors) ?>)
        </h3>
        <div class="max-h-64 overflow-y-auto">
            <table class="min-w-full divide-y divide-red-200 text-sm">
                <thead class="bg-red-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-red-700">Row</th>
                        <th class="px-4 py-2 text-left font-medium text-red-700">Product Code</th>
                        <th class="px-4 py-2 text-left font-medium text-red-700">Type</th>
                        <th class="px-4 py-2 text-left font-medium text-red-700">Error</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-red-100">
                    <?php foreach ($errors as $error): ?>
                        <tr>
                            <td class="px-4 py-2 text-red-600 font-mono">#<?= $error['row'] ?></td>
                            <td class="px-4 py-2"><?= esc($error['product_code'] ?? '-') ?></td>
                            <td class="px-4 py-2"><?= esc($error['type'] ?? '-') ?></td>
                            <td class="px-4 py-2 text-red-700">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= esc($error['error']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="mt-4 text-sm text-red-700">
            <i class="fas fa-info-circle mr-1"></i>
            Please fix the errors in your Excel file and upload again.
        </p>
    </div>
<?php endif; ?>

<!-- Valid Data Preview -->
<?php if (!empty($validData)): ?>
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-list-check mr-2"></i> Valid Transactions (<?= count($validData) ?>)
        </h3>
        <p class="text-sm text-gray-600 mb-4">
            <?php
            $previewLimit = 100;
            $totalValid = count($validData);
            $showingCount = min($previewLimit, $totalValid);
            ?>
            Showing first <?= $showingCount ?> of <?= $totalValid ?> transactions
            <?php if ($totalValid > $previewLimit): ?>
                <span class="text-blue-600 font-medium">(Preview limited for performance)</span>
            <?php endif; ?>
        </p>

        <!-- Summary by Type -->
        <?php
        $purchases = array_filter($validData, fn($t) => $t['type'] === 'purchase');
        $sales = array_filter($validData, fn($t) => $t['type'] === 'sale');
        $totalPurchaseQty = array_sum(array_column($purchases, 'qty'));
        $totalSaleQty = array_sum(array_column($sales, 'qty'));
        $totalPurchaseAmount = array_sum(array_map(fn($t) => $t['qty'] * $t['price'], $purchases));
        $totalSaleAmount = array_sum(array_map(fn($t) => $t['qty'] * $t['price'], $sales));
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-blue-50 border border-blue-200 rounded p-4">
                <h4 class="font-semibold text-blue-900 mb-2">📦 Purchases</h4>
                <p class="text-sm text-blue-700">Count: <span class="font-bold"><?= count($purchases) ?></span></p>
                <p class="text-sm text-blue-700">Total Qty: <span class="font-bold"><?= number_format($totalPurchaseQty, 2) ?></span></p>
                <p class="text-sm text-blue-700">Total Value: <span class="font-bold">Rp <?= number_format($totalPurchaseAmount, 0, ',', '.') ?></span></p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded p-4">
                <h4 class="font-semibold text-green-900 mb-2">💰 Sales</h4>
                <p class="text-sm text-green-700">Count: <span class="font-bold"><?= count($sales) ?></span></p>
                <p class="text-sm text-green-700">Total Qty: <span class="font-bold"><?= number_format($totalSaleQty, 2) ?></span></p>
                <p class="text-sm text-green-700">Total Value: <span class="font-bold">Rp <?= number_format($totalSaleAmount, 0, ',', '.') ?></span></p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">#</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Product</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Type</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-500">Qty</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-500">Price</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-500">Total</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Date</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Reference</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Limit preview to first 100 records for performance
                    $previewData = array_slice($validData, 0, $previewLimit);
                    foreach ($previewData as $idx => $row):
                    ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-500"><?= $idx + 1 ?></td>
                            <td class="px-4 py-2">
                                <div>
                                    <div class="font-medium text-gray-900"><?= esc($row['product_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= esc($row['product_code']) ?></div>
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <?php if ($row['type'] === 'purchase'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded">
                                        📦 Purchase
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                        💰 Sale
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 text-right font-mono"><?= number_format($row['qty'], 2) ?></td>
                            <td class="px-4 py-2 text-right font-mono">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2 text-right font-mono font-semibold">
                                Rp <?= number_format($row['qty'] * $row['price'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-2"><?= date('d M Y', strtotime($row['transaction_date'])) ?></td>
                            <td class="px-4 py-2 text-gray-600"><?= esc($row['reference_no'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($totalValid > $previewLimit): ?>
                        <tr class="bg-blue-50">
                            <td colspan="8" class="px-4 py-3 text-center text-sm text-blue-700 font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                + <?= number_format($totalValid - $previewLimit) ?> more transactions (not shown in preview)
                                <br>
                                <span class="text-xs text-blue-600">All <?= number_format($totalValid) ?> transactions will be imported when you confirm</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center bg-gray-50 p-6 rounded-lg">
        <a href="<?= base_url('/transactions/import') ?>"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
            <i class="fas fa-arrow-left mr-2"></i> Back to Upload
        </a>

        <?php if (count($validData) > 0): ?>
            <form action="<?= base_url('/transactions/import/confirm') ?>" method="POST" class="inline">
                <?= csrf_field() ?>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    onclick="return confirm('Are you sure you want to import <?= count($validData) ?> transactions?')">
                    <i class="fas fa-check-circle mr-2"></i> Confirm Import (<?= count($validData) ?> transactions)
                </button>
            </form>
        <?php else: ?>
            <button type="button"
                class="px-6 py-2 bg-gray-400 text-gray-200 rounded-md cursor-not-allowed"
                disabled>
                <i class="fas fa-ban mr-2"></i> No Valid Data
            </button>
        <?php endif; ?>
    </div>
<?php else: ?>
    <!-- No Valid Data -->
    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6 text-center">
        <i class="fas fa-exclamation-triangle text-4xl text-yellow-600 mb-3"></i>
        <h3 class="text-lg font-semibold text-yellow-900 mb-2">No Valid Data Found</h3>
        <p class="text-yellow-700 mb-4">All rows contain errors. Please fix your Excel file and try again.</p>
        <a href="<?= base_url('/transactions/import') ?>"
            class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Upload
        </a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>