<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Import Transactions</h2>
            <p class="mt-1 text-sm text-gray-600">Upload CSV file to import multiple transactions at once</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('/transactions/import/template') ?>"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <i class="fas fa-download mr-2"></i> Download Template
            </a>
            <a href="<?= base_url('/transactions') ?>"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
</div>

<?php if (session()->has('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= session('error') ?>
    </div>
<?php endif; ?>

<!-- Instructions -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-blue-900 mb-3">
        <i class="fas fa-info-circle mr-2"></i> Import Instructions
    </h3>
    <div class="text-blue-800 space-y-2 text-sm">
        <p><strong>Step 1:</strong> Download the Excel template using the button above</p>
        <p><strong>Step 2:</strong> Fill in your transaction data following this format:</p>
        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
            <li><strong>Product Code:</strong> Must match existing product code (e.g., BRG001)</li>
            <li><strong>Type:</strong> purchase / sale / pembelian / penjualan</li>
            <li><strong>Quantity:</strong> Positive number (e.g., 10, 5.5)</li>
            <li><strong>Price:</strong> Price per unit (e.g., 50000, 75000.50)</li>
            <li><strong>Date:</strong> Format: YYYY-MM-DD (e.g., <?= date('Y-m-d') ?>)</li>
            <li><strong>Reference No:</strong> Optional reference number (e.g., PO-001, SO-001)</li>
            <li><strong>Notes:</strong> Optional notes</li>
        </ul>
        <p class="mt-3"><strong>Step 3:</strong> Save and upload the Excel file below</p>
        <p class="mt-2 text-blue-600">
            <i class="fas fa-lightbulb mr-1"></i>
            <strong>Tip:</strong> You can import both purchases and sales in one file!
        </p>
    </div>
</div>

<!-- Upload Form -->
<div class="bg-white shadow-md rounded-lg p-6">
    <form action="<?= base_url('/transactions/import/process') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Select Excel File <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center justify-center w-full">
                <label for="file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-3"></i>
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500">Excel files (.xlsx, .xls) only (MAX. 10MB)</p>
                        <p class="text-xs text-gray-400 mt-2" id="file-name"></p>
                    </div>
                    <input id="file" name="file" type="file" class="hidden" accept=".xlsx,.xls" required onchange="updateFileName(this)">
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('/transactions') ?>"
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-upload mr-2"></i> Upload & Preview
            </button>
        </div>
    </form>
</div>

<!-- Sample Data Preview -->
<div class="bg-white shadow-md rounded-lg p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-table mr-2"></i> Sample Excel Format
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Product Code</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Type</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-500">Quantity</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-500">Price</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Date</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Reference No</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Notes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-4 py-2">BRG001</td>
                    <td class="px-4 py-2">purchase</td>
                    <td class="px-4 py-2 text-right">10</td>
                    <td class="px-4 py-2 text-right">50000</td>
                    <td class="px-4 py-2"><?= date('Y-m-d') ?></td>
                    <td class="px-4 py-2">PO-001</td>
                    <td class="px-4 py-2">Sample purchase</td>
                </tr>
                <tr>
                    <td class="px-4 py-2">BRG002</td>
                    <td class="px-4 py-2">sale</td>
                    <td class="px-4 py-2 text-right">5</td>
                    <td class="px-4 py-2 text-right">75000</td>
                    <td class="px-4 py-2"><?= date('Y-m-d') ?></td>
                    <td class="px-4 py-2">SO-001</td>
                    <td class="px-4 py-2">Sample sale</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        const fileNameDisplay = document.getElementById('file-name');
        if (fileName) {
            fileNameDisplay.textContent = '📄 Selected: ' + fileName;
            fileNameDisplay.classList.add('text-blue-600', 'font-semibold');
        }
    }
</script>
<?= $this->endSection() ?>