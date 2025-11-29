<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Import Locations</h2>
            <p class="mt-1 text-sm text-gray-600">Upload Excel or CSV file to import multiple locations</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('admin/location/download-template') ?>"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <i class="fas fa-download mr-2"></i> Download Template
            </a>
            <a href="<?= base_url('admin/location') ?>"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <h3 class="text-lg font-semibold text-blue-900 mb-2">
        <i class="fas fa-info-circle mr-2"></i> Import Instructions
    </h3>
    <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800">
        <li>Download the template file using the button above</li>
        <li>Fill in your location data in the Excel/CSV file</li>
        <li>Required columns: <strong>kode_lokasi, nama_lokasi</strong></li>
        <li>Optional columns: <strong>departemen, keterangan, status</strong></li>
        <li>Status values: <strong>aktif</strong> or <strong>tidak_aktif</strong> (default: aktif)</li>
        <li>Upload the completed file below</li>
    </ol>
</div>

<!-- Upload Form -->
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="<?= base_url('admin/location/process-import') ?>" method="post" enctype="multipart/form-data" id="importForm">
        <?= csrf_field() ?>

        <div class="mb-6">
            <label for="import_file" class="block text-sm font-medium text-gray-700 mb-2">
                Select File <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center justify-center w-full">
                <label for="import_file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-4"></i>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500">Excel (.xlsx, .xls) or CSV (.csv)</p>
                        <p class="text-xs text-gray-400 mt-2" id="fileName"></p>
                    </div>
                    <input id="import_file" name="import_file" type="file" class="hidden" accept=".xlsx,.xls,.csv" required onchange="updateFileName(this)">
                </label>
            </div>
            <?php if (session('errors.import_file')): ?>
                <p class="mt-2 text-sm text-red-600"><?= session('errors.import_file') ?></p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="skip_duplicates" value="1" checked
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">Skip duplicate location codes (don't update existing)</span>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <i class="fas fa-upload mr-2"></i> Upload and Import
            </button>
            <a href="<?= base_url('admin/location') ?>"
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- Example Data -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-table mr-2"></i> Example Data Format
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">kode_lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">nama_lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">departemen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">keterangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">RAK-A-01</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Rack A Floor 1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Warehouse</td>
                    <td class="px-6 py-4 text-sm">Left side main warehouse</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">aktif</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">RAK-A-02</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Rack A Floor 2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Warehouse</td>
                    <td class="px-6 py-4 text-sm">Left side level 2</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">aktif</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">SHOW-01</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Showroom Display 1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">Showroom</td>
                    <td class="px-6 py-4 text-sm">Front display area</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">aktif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name;
        const fileNameElement = document.getElementById('fileName');
        if (fileName) {
            fileNameElement.textContent = `Selected: ${fileName}`;
            fileNameElement.classList.add('text-indigo-600', 'font-semibold');
        }
    }

    document.getElementById('importForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('import_file');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to upload');
            return false;
        }

        // Show loading
        const button = e.target.querySelector('button[type="submit"]');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Uploading...';
    });
</script>
<?= $this->endSection() ?>