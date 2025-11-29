<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center space-x-4">
        <a href="<?= base_url('admin/user') ?>" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tambah User</h2>
            <p class="mt-1 text-sm text-gray-600">Buat akun pengguna baru</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md">
    <div class="p-6">
        <?php if (session()->has('errors')): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-red-700 text-sm">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/user/store') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="username"
                        name="username"
                        value="<?= old('username') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Masukkan username"
                        required>
                    <p class="mt-1 text-xs text-gray-500">Minimal 3 karakter, hanya huruf dan angka</p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        id="email"
                        name="email"
                        value="<?= old('email') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Masukkan email"
                        required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Masukkan password"
                            required>
                        <button type="button" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                            id="password_confirm"
                            name="password_confirm"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Konfirmasi password"
                            required>
                        <button type="button" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" onclick="togglePassword('password_confirm')">
                            <i class="fas fa-eye" id="password_confirm-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Group/Role -->
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="group"
                        name="group"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        required>
                        <option value="">Pilih Role</option>
                        <?php foreach ($groups as $key => $group): ?>
                            <option value="<?= $key ?>" <?= old('group') === $key ? 'selected' : '' ?>>
                                <?= esc($group['title']) ?> - <?= esc($group['description']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex items-center mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="active" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Aktif</span>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">User aktif dapat login ke sistem</p>
                </div>
            </div>

            <!-- Role Description -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-semibold text-blue-800 mb-2"><i class="fas fa-info-circle mr-2"></i>Informasi Role</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="font-medium text-blue-700"><i class="fas fa-crown mr-1"></i>Administrator</p>
                        <p class="text-blue-600">Full access: kelola user, produk, lokasi, transaksi, dan stock opname</p>
                    </div>
                    <div>
                        <p class="font-medium text-blue-700"><i class="fas fa-user mr-1"></i>User</p>
                        <p class="text-blue-600">Akses terbatas: hanya dapat edit item stock opname dan lihat laporan</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="<?= base_url('admin/user') ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
<?= $this->endSection() ?>