<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Import Preview</h1>
            <p class="text-gray-600 mt-1">Review data before importing</p>
        </div>
        <a href="<?= base_url('products/import') ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p><?= session()->getFlashdata('error') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                <div>
                    <p class="text-green-700 font-semibold text-lg"><?= count($validData) ?></p>
                    <p class="text-green-600 text-sm">Valid Records</p>
                </div>
            </div>
        </div>

        <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <div>
                    <p class="text-red-700 font-semibold text-lg"><?= count($errors) ?></p>
                    <p class="text-red-600 text-sm">Errors Found</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-database text-blue-500 text-2xl mr-3"></i>
                <div>
                    <p class="text-blue-700 font-semibold text-lg"><?= count($validData) + count($errors) ?></p>
                    <p class="text-blue-600 text-sm">Total Records</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Errors -->
    <?php if (!empty($errors)): ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-red-600 mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>Errors (<?= count($errors) ?>)
            </h2>
            <div class="bg-red-50 border border-red-200 rounded p-4 max-h-64 overflow-y-auto">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li class="text-sm text-red-700"><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <p class="text-sm text-gray-600 mt-3">
                <i class="fas fa-info-circle mr-1"></i>
                Please fix these errors in your file and upload again.
            </p>
        </div>
    <?php endif; ?>

    <!-- Valid Data -->
    <?php if (!empty($validData)): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>Valid Data (<?= count($validData) ?>)
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    <?php
                    $previewLimit = 100;
                    $totalValid = count($validData);
                    $showingCount = min($previewLimit, $totalValid);
                    ?>
                    Showing first <?= $showingCount ?> of <?= $totalValid ?> products
                    <?php if ($totalValid > $previewLimit): ?>
                        <span class="text-blue-600 font-medium">(Preview limited for performance)</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">PLU</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Buy Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sell Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dept</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        // Limit preview to first 100 records for performance
                        $previewData = array_slice($validData, 0, $previewLimit);
                        foreach ($previewData as $index => $row):
                        ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-500"><?= $index + 1 ?></td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= esc($row['code']) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= esc($row['plu']) ?: '-' ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?= esc($row['name']) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= esc($row['unit']) ?></td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900"><?= number_format($row['buy_price'], 0, ',', '.') ?></td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900"><?= number_format($row['sell_price'], 0, ',', '.') ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= esc($row['supplier']) ?: '-' ?></td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900"><?= number_format($row['stock'], 2) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= esc($row['department']) ?: '-' ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600"><?= esc($row['category']) ?: '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($totalValid > $previewLimit): ?>
                            <tr class="bg-blue-50">
                                <td colspan="11" class="px-4 py-3 text-center text-sm text-blue-700 font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    + <?= number_format($totalValid - $previewLimit) ?> more products (not shown in preview)
                                    <br>
                                    <span class="text-xs text-blue-600">All <?= number_format($totalValid) ?> products will be imported when you confirm</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Confirm Button -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ready to Import?</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        <?= count($validData) ?> product(s) will be added to the database
                    </p>
                </div>
                <form action="<?= base_url('products/import/confirm') ?>" method="post" onsubmit="return confirm('Are you sure you want to import these products?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                        <i class="fas fa-check mr-2"></i>Confirm & Import
                    </button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                <p class="text-yellow-700">No valid data to import. Please fix the errors and try again.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>