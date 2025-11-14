<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Campaign Baru</h1>
                <p class="mt-1 text-sm text-gray-600">Lengkapi form di bawah ini untuk membuat campaign baru</p>
            </div>
            <a href="<?= base_url('admin/campaigns') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->has('errors')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6" x-data="{ show: true }" x-show="show">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= base_url('admin/campaigns/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Basic Information Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= old('status') == 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="completed" <?= old('status') == 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= old('status') == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>

                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Campaign <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required minlength="10"
                        value="<?= old('title') ?>"
                        placeholder="Contoh: Bantu Anak Yatim untuk Pendidikan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter</p>
                </div>

                <!-- Short Description -->
                <div class="md:col-span-2">
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Singkat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="short_description" name="short_description" rows="3" required
                        placeholder="Ringkasan singkat tentang campaign ini..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"><?= old('short_description') ?></textarea>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 200 karakter</p>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="8" required
                        placeholder="Jelaskan detail campaign ini..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"><?= old('description') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Target & Date Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Target & Waktu</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Target Amount -->
                <div>
                    <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Donasi (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="target_amount" name="target_amount" required min="100000" step="1000"
                        value="<?= old('target_amount') ?>"
                        placeholder="1000000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="start_date" name="start_date" required
                        value="<?= old('start_date', date('Y-m-d')) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Berakhir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="end_date" name="end_date" required
                        value="<?= old('end_date') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Organizer Information Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penyelenggara</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Organizer Name -->
                <div>
                    <label for="organizer_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Penyelenggara <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="organizer_name" name="organizer_name" required
                        value="<?= old('organizer_name') ?>"
                        placeholder="Yayasan XYZ"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Organizer Phone -->
                <div>
                    <label for="organizer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon
                    </label>
                    <input type="tel" id="organizer_phone" name="organizer_phone"
                        value="<?= old('organizer_phone') ?>"
                        placeholder="081234567890"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Organizer Email -->
                <div>
                    <label for="organizer_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" id="organizer_email" name="organizer_email"
                        value="<?= old('organizer_email') ?>"
                        placeholder="info@yayasan.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Image Upload Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Gambar Campaign</h2>

            <div x-data="{ 
                mainImagePreview: null,
                additionalPreviews: []
            }">
                <!-- Main Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Utama <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="image" name="image" required accept="image/*"
                        @change="mainImagePreview = URL.createObjectURL($event.target.files[0])"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB. Rekomendasi: 1200x600px</p>

                    <!-- Main Image Preview -->
                    <div x-show="mainImagePreview" class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Utama:</p>
                        <img :src="mainImagePreview" alt="Preview" class="max-w-md rounded-lg border border-gray-300">
                    </div>
                </div>

                <!-- Additional Images -->
                <div class="pt-6 border-t border-gray-200">
                    <label for="additional_images" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Tambahan (Opsional)
                    </label>
                    <input type="file" id="additional_images" name="additional_images[]" multiple accept="image/*"
                        @change="
                            additionalPreviews = [];
                            Array.from($event.target.files).forEach(file => {
                                additionalPreviews.push(URL.createObjectURL(file));
                            });
                        "
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Anda bisa upload hingga 5 gambar tambahan. Format: JPG, PNG, JPEG. Maksimal 2MB per file.</p>

                    <!-- Additional Images Preview -->
                    <div x-show="additionalPreviews.length > 0" class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Tambahan:</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <template x-for="(preview, index) in additionalPreviews" :key="index">
                                <div class="relative">
                                    <img :src="preview" :alt="'Preview ' + (index + 1)"
                                        class="w-full h-32 object-cover rounded-lg border border-gray-300">
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        <span x-text="index + 1"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Options Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Opsi Tambahan</h2>

            <div class="space-y-4">
                <!-- Is Featured -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1"
                        <?= old('is_featured') ? 'checked' : '' ?>
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="is_featured" class="ml-2 text-sm text-gray-700">
                        <span class="font-medium">Tampilkan di Halaman Utama</span>
                        <span class="text-gray-500 block">Campaign akan ditampilkan di bagian featured</span>
                    </label>
                </div>

                <!-- Is Urgent -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_urgent" name="is_urgent" value="1"
                        <?= old('is_urgent') ? 'checked' : '' ?>
                        class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_urgent" class="ml-2 text-sm text-gray-700">
                        <span class="font-medium">Tandai sebagai Mendesak</span>
                        <span class="text-gray-500 block">Campaign akan ditampilkan dengan badge "Mendesak"</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4 bg-white rounded-lg shadow-sm p-6">
            <a href="<?= base_url('admin/campaigns') ?>"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-save mr-2"></i> Simpan Campaign
            </button>
        </div>
    </form>
</div>

<script>
    // Validate dates
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = document.getElementById('start_date').value;
        const endDate = this.value;

        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            alert('Tanggal berakhir harus setelah tanggal mulai');
            this.value = '';
        }
    });

    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        const endDate = document.getElementById('end_date').value;

        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            alert('Tanggal berakhir harus setelah tanggal mulai');
            document.getElementById('end_date').value = '';
        }
    });
</script>

<?= $this->endSection() ?>