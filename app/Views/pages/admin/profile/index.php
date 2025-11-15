<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Overview Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    <?= strtoupper(substr($user->username ?? 'U', 0, 2)) ?>
                </div>
                <h3 class="mt-4 text-xl font-bold text-gray-800"><?= esc($user->username) ?></h3>
                <p class="text-gray-600"><?= esc($user->getEmailIdentity()->secret ?? 'Tidak ada email') ?></p>
                <div class="mt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <i class="fas fa-circle mr-2 text-xs"></i>
                        <?= $user->active ? 'Aktif' : 'Tidak Aktif' ?>
                    </span>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <p class="mb-2">
                            <i class="fas fa-user-tag text-primary-600 mr-2"></i>
                            <span class="font-medium">Role:</span>
                            <?php
                            $groups = $user->getGroups();
                            echo !empty($groups) ? esc(implode(', ', $groups)) : 'User';
                            ?>
                        </p>
                        <p>
                            <i class="fas fa-calendar text-primary-600 mr-2"></i>
                            <span class="font-medium">Bergabung:</span>
                            <?= date('d M Y', strtotime($user->created_at)) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Forms -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Update Profile Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-gray-800">Informasi Profile</h4>
                    <p class="text-sm text-gray-600">Perbarui informasi profile Anda</p>
                </div>
                <i class="fas fa-user-edit text-3xl text-primary-600"></i>
            </div>

            <form action="<?= base_url('admin/profile/update') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            id="username"
                            name="username"
                            value="<?= old('username', esc($user->username)) ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required>
                        <?php if (session('errors.username')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= session('errors.username') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input type="text"
                            id="name"
                            name="name"
                            value="<?= old('name', esc($user->name ?? '')) ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <?php if (session('errors.name')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= session('errors.name') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Email Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-gray-800">Ubah Email</h4>
                    <p class="text-sm text-gray-600">Perbarui alamat email Anda</p>
                </div>
                <i class="fas fa-envelope text-3xl text-primary-600"></i>
            </div>

            <form action="<?= base_url('admin/profile/update-email') ?>" method="POST">
                <?= csrf_field() ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                        id="email"
                        name="email"
                        value="<?= old('email', esc($user->getEmailIdentity()->secret ?? '')) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required>
                    <?php if (session('errors.email')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= session('errors.email') ?></p>
                    <?php endif; ?>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Ubah Email
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-gray-800">Ubah Password</h4>
                    <p class="text-sm text-gray-600">Perbarui password keamanan Anda</p>
                </div>
                <i class="fas fa-lock text-3xl text-primary-600"></i>
            </div>

            <form action="<?= base_url('admin/profile/update-password') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                            id="current_password"
                            name="current_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required>
                        <?php if (session('errors.current_password')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= session('errors.current_password') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                            id="new_password"
                            name="new_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required>
                        <?php if (session('errors.new_password')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= session('errors.new_password') ?></p>
                        <?php endif; ?>
                        <p class="mt-1 text-xs text-gray-500">Password minimal 4 karakter</p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required>
                        <?php if (session('errors.confirm_password')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= session('errors.confirm_password') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-key"></i>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>