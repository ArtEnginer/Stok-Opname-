<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Import Products</h1>
            <p class="text-gray-600 mt-1">Upload Excel or CSV file to bulk import products</p>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Upload Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-upload text-blue-500 mr-2"></i>Upload File
            </h2>

            <form action="<?= base_url('products/import/process') ?>" method="post" enctype="multipart/form-data">
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
                        Columns: Code*, PLU, Name*, Unit, Buy Price, Sell Price, Supplier, Stock, Department, Category<br>
                        <span class="text-xs">* = Required fields</span>
                    </p>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Upload & Preview
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
                        <p class="text-sm text-gray-600">Download the Excel template with sample data and required columns</p>
                        <a href="<?= base_url('products/import/template') ?>"
                            class="inline-block mt-2 text-blue-500 hover:text-blue-700 text-sm font-semibold">
                            <i class="fas fa-download mr-1"></i>Download Template
                        </a>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold mr-3">
                        2
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Fill Product Data</h3>
                        <p class="text-sm text-gray-600">Fill in your product data following the template format</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold mr-3">
                        3
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Upload & Preview</h3>
                        <p class="text-sm text-gray-600">Upload your file and review the data before importing</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold mr-3">
                        4
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Confirm Import</h3>
                        <p class="text-sm text-gray-600">Confirm and complete the import process</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Important:</strong> Duplicate product codes will be skipped. Make sure all codes are unique.
                </p>
            </div>
        </div>
    </div>

    <!-- Column Reference -->
    <div class="mt-6 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            <i class="fas fa-table text-indigo-500 mr-2"></i>Column Reference
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Column</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Required</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Example</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Code</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Unique product code/SKU</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Yes</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">BRG001</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">PLU</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Price Look-Up code (optional)</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">1001</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Name</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Product name</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Yes</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">Laptop Asus ROG</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Unit</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Unit of measurement</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">PCS, BOX, KG</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Buy Price</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Purchase price (numeric)</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">10000</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Sell Price</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Selling price (numeric)</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">15000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Supplier</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Supplier name</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">PT ABC</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Stock</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Initial stock quantity</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">100</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Department</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Department/division</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">IT</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Category</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Product category</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span></td>
                        <td class="px-4 py-3 text-sm text-gray-600">Electronics</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>