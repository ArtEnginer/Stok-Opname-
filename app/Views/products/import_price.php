<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Import Price Update</h1>
            <p class="text-gray-600 mt-1">Upload Excel or CSV file to bulk update product prices</p>
        </div>
        <a href="<?= base_url('products') ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Back to Products
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

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p><?= session()->getFlashdata('success') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Upload Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-upload text-blue-500 mr-2"></i>Upload File
            </h2>

            <form action="<?= base_url('products/import-price/process') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Select File <span class="text-red-500">*</span>
                    </label>
                    <input type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">
                        Accepted formats: .xlsx, .xls, .csv (Max 2MB)
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>File Format:</strong><br>
                        Columns: Product Code*, Buy Price, Sell Price<br>
                        <span class="text-xs">* = Required field</span>
                    </p>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Important:</strong><br>
                        • Only existing products will be updated<br>
                        • Leave price columns empty if you don't want to update them<br>
                        • Product code must match exactly with database
                    </p>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Upload & Update Prices
                </button>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-book text-green-500 mr-2"></i>Instructions
            </h2>

            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3">
                        1
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Download Template</h3>
                        <p class="text-sm text-gray-600">Download the Excel template with sample data</p>
                        <a href="<?= base_url('products/import-price/template') ?>"
                            class="inline-block mt-2 text-blue-500 hover:text-blue-700 text-sm font-semibold">
                            <i class="fas fa-download mr-1"></i>Download Template
                        </a>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3">
                        2
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Fill the Template</h3>
                        <p class="text-sm text-gray-600">
                            Enter product code and new prices:
                        </p>
                        <ul class="text-sm text-gray-600 mt-1 list-disc list-inside">
                            <li><strong>Product Code</strong>: Required, must match existing product</li>
                            <li><strong>Buy Price</strong>: Leave empty to keep current price</li>
                            <li><strong>Sell Price</strong>: Leave empty to keep current price</li>
                        </ul>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3">
                        3
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Upload & Update</h3>
                        <p class="text-sm text-gray-600">Upload the file and prices will be updated immediately</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Tips
                </h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Export current products to get correct product codes</li>
                    <li>• You can update just buy price, just sell price, or both</li>
                    <li>• Products not found in database will be skipped</li>
                    <li>• All updates are done in a single transaction</li>
                </ul>
            </div>

            <div class="mt-6 p-4 bg-red-50 rounded border border-red-200">
                <h3 class="font-semibold text-red-700 mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>Warning
                </h3>
                <p class="text-sm text-red-600">
                    This action will immediately update product prices in the database. Make sure your data is correct before uploading.
                </p>
            </div>
        </div>
    </div>

    <!-- Sample Format -->
    <div class="mt-6 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-table text-purple-500 mr-2"></i>Sample Format
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase border">Product Code*</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase border">Buy Price</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase border">Sell Price</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-2 text-sm border">BRG001</td>
                        <td class="px-4 py-2 text-sm border">10000</td>
                        <td class="px-4 py-2 text-sm border">15000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm border">BRG002</td>
                        <td class="px-4 py-2 text-sm border">50000</td>
                        <td class="px-4 py-2 text-sm border">75000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm border">BRG003</td>
                        <td class="px-4 py-2 text-sm border"></td>
                        <td class="px-4 py-2 text-sm border">35000</td>
                    </tr>
                </tbody>
            </table>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Row 3 example: Only update sell price, keep buy price unchanged
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>