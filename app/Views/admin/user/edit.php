<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center space-x-4">
        <a href="<?= base_url('admin/user') ?>" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit User</h2>
            <p class="mt-1 text-sm text-gray-600">Perbarui informasi pengguna</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md">
    <div class="p-6">
        <!-- User Info Header -->
        <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-indigo-600"><?= strtoupper(substr($user->username, 0, 1)) ?></span>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-semibold text-gray-900"><?= esc($user->username) ?></h3>
                <p class="text-gray-500"><?= esc($user->email) ?></p>
                <div class="flex items-center mt-1 space-x-2">
                    <?php if (in_array('admin', $userGroups)): ?>
                        <span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Admin</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">User</span>
                    <?php endif; ?>
                    <?php if ($user->active): ?>
                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktif</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Nonaktif</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

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

        <form action="<?= base_url('admin/user/update/' . $user->id) ?>" method="POST">
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
                        value="<?= old('username', $user->username) ?>"
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
                        value="<?= old('email', $user->email) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Masukkan email"
                        required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Kosongkan jika tidak diubah">
                        <button type="button" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password"
                            id="password_confirm"
                            name="password_confirm"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Konfirmasi password baru">
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
                        required
                        <?= $user->id === auth()->id() ? 'disabled' : '' ?>>
                        <?php foreach ($groups as $key => $group): ?>
                            <option value="<?= $key ?>" <?= in_array($key, $userGroups) ? 'selected' : '' ?>>
                                <?= esc($group['title']) ?> - <?= esc($group['description']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($user->id === auth()->id()): ?>
                        <input type="hidden" name="group" value="<?= $userGroups[0] ?? 'user' ?>">
                        <p class="mt-1 text-xs text-yellow-600"><i class="fas fa-exclamation-triangle mr-1"></i>Anda tidak dapat mengubah role akun sendiri</p>
                    <?php endif; ?>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex items-center mt-2">
                        <label class="relative inline-flex items-center cursor-pointer <?= $user->id === auth()->id() ? 'opacity-50' : '' ?>">
                            <input type="checkbox"
                                name="active"
                                value="1"
                                class="sr-only peer"
                                <?= $user->active ? 'checked' : '' ?>
                                <?= $user->id === auth()->id() ? 'disabled' : '' ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Aktif</span>
                        </label>
                    </div>
                    <?php if ($user->id === auth()->id()): ?>
                        <input type="hidden" name="active" value="1">
                        <p class="mt-1 text-xs text-yellow-600"><i class="fas fa-exclamation-triangle mr-1"></i>Anda tidak dapat menonaktifkan akun sendiri</p>
                    <?php else: ?>
                        <p class="mt-1 text-xs text-gray-500">User aktif dapat login ke sistem</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Activity Info -->
            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-2"><i class="fas fa-chart-line mr-2"></i>Informasi Aktivitas</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Dibuat pada</p>
                        <p class="font-medium text-gray-700"><?= date('d M Y H:i', strtotime($user->created_at)) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Terakhir diperbarui</p>
                        <p class="font-medium text-gray-700"><?= $user->updated_at ? date('d M Y H:i', strtotime($user->updated_at)) : '-' ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Login terakhir</p>
                        <p class="font-medium text-gray-700"><?= $user->last_active ? date('d M Y H:i', strtotime($user->last_active)) : 'Belum pernah' ?></p>
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
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
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