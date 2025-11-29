<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Add New Location</h2>
            <p class="mt-1 text-sm text-gray-600">Create a new warehouse location or storage area</p>
        </div>
        <a href="<?= base_url('admin/location') ?>"
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form action="<?= base_url('admin/location/store') ?>" method="post" id="locationForm">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kode Lokasi -->
            <div>
                <label for="kode_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Location Code <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="kode_lokasi"
                    name="kode_lokasi"
                    value="<?= old('kode_lokasi') ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 uppercase"
                    placeholder="e.g., RAK-A-01"
                    required>
                <p class="mt-1 text-xs text-gray-500">Code will be automatically converted to uppercase</p>
                <?php if (session('errors.kode_lokasi')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= session('errors.kode_lokasi') ?></p>
                <?php endif; ?>
            </div>

            <!-- Nama Lokasi -->
            <div>
                <label for="nama_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Location Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="nama_lokasi"
                    name="nama_lokasi"
                    value="<?= old('nama_lokasi') ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="e.g., Rack A Floor 1"
                    required>
                <?php if (session('errors.nama_lokasi')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= session('errors.nama_lokasi') ?></p>
                <?php endif; ?>
            </div>

            <!-- Departemen -->
            <div>
                <label for="departemen" class="block text-sm font-medium text-gray-700 mb-2">
                    Department
                </label>
                <input type="text"
                    id="departemen"
                    name="departemen"
                    value="<?= old('departemen') ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="e.g., Warehouse"
                    list="departmentList">
                <datalist id="departmentList">
                    <option value="Warehouse">
                    <option value="Showroom">
                    <option value="Store">
                    <option value="Office">
                    <option value="Production">
                </datalist>
                <?php if (session('errors.departemen')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= session('errors.departemen') ?></p>
                <?php endif; ?>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status"
                    name="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                    <option value="aktif" <?= old('status') == 'aktif' ? 'selected' : '' ?>>Active</option>
                    <option value="tidak_aktif" <?= old('status') == 'tidak_aktif' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <?php if (session('errors.status')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= session('errors.status') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mt-6">
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>
            <textarea id="keterangan"
                name="keterangan"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Additional information about this location"><?= old('keterangan') ?></textarea>
            <?php if (session('errors.keterangan')): ?>
                <p class="mt-1 text-sm text-red-600"><?= session('errors.keterangan') ?></p>
            <?php endif; ?>
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex gap-3">
            <button type="submit"
                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <i class="fas fa-save mr-2"></i> Save Location
            </button>
            <a href="<?= base_url('admin/location') ?>"
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('kode_lokasi').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });

    document.getElementById('locationForm').addEventListener('submit', function(e) {
        const kode = document.getElementById('kode_lokasi').value.trim();
        const nama = document.getElementById('nama_lokasi').value.trim();

        if (!kode || !nama) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
    });
</script>
<?= $this->endSection() ?>