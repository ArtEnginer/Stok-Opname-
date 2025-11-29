<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen User</h2>
        <p class="mt-1 text-sm text-gray-600">Kelola pengguna sistem Stock Opname</p>
    </div>
    <a href="<?= base_url('admin/user/create') ?>"
        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah User
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <?php
    $totalUsers = count($users);
    $activeUsers = count(array_filter($users, fn($u) => $u->active));
    $adminUsers = count(array_filter($users, fn($u) => in_array('admin', $u->groups)));
    $regularUsers = $totalUsers - $adminUsers;
    ?>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalUsers ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-check text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">User Aktif</p>
                <p class="text-2xl font-bold text-gray-900"><?= $activeUsers ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-shield text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Admin</p>
                <p class="text-2xl font-bold text-gray-900"><?= $adminUsers ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">User Biasa</p>
                <p class="text-2xl font-bold text-gray-900"><?= $regularUsers ?></p>
            </div>
        </div>
    </div>
</div>

<!-- User Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Daftar User</h3>
            <div class="flex space-x-2">
                <input type="text" id="searchUser" placeholder="Cari user..."
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="userTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login Terakhir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $no = 1;
                foreach ($users as $user): ?>
                    <?php $isOwnAccount = $user->id === auth()->id(); ?>
                    <tr class="hover:bg-gray-50 user-row" data-search="<?= strtolower(esc($user->username) . ' ' . esc($user->email)) ?>">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold"><?= strtoupper(substr($user->username, 0, 1)) ?></span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($user->username) ?></div>
                                    <?php if ($isOwnAccount): ?>
                                        <span class="text-xs text-indigo-600">(Akun Anda)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($user->email) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if (in_array('admin', $user->groups)): ?>
                                <span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">
                                    <i class="fas fa-crown mr-1"></i>Admin
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                    <i class="fas fa-user mr-1"></i>User
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($user->active): ?>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $user->last_active ? date('d M Y H:i', strtotime($user->last_active)) : '<span class="text-gray-400">Belum pernah</span>' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <a href="<?= base_url('admin/user/edit/' . $user->id) ?>"
                                    class="px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (!$isOwnAccount): ?>
                                    <button type="button"
                                        class="px-2 py-1 text-xs font-medium text-white rounded btn-toggle-active
                                           <?= $user->active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' ?>"
                                        data-id="<?= $user->id ?>"
                                        data-active="<?= $user->active ?>"
                                        title="<?= $user->active ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                        <i class="fas fa-<?= $user->active ? 'ban' : 'check' ?>"></i>
                                    </button>
                                    <button type="button"
                                        class="px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 btn-delete"
                                        data-id="<?= $user->id ?>"
                                        data-username="<?= esc($user->username) ?>"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($users)): ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-users text-4xl mb-4"></i>
            <p>Belum ada user terdaftar</p>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Hapus User</h3>
            <p class="text-center text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus user <strong id="deleteUsername"></strong>?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center space-x-4">
                <button type="button" id="cancelDelete"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="button" id="confirmDelete"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchUser');
        const rows = document.querySelectorAll('.user-row');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                row.style.display = searchData.includes(searchTerm) ? '' : 'none';
            });
        });

        // Toggle active status
        document.querySelectorAll('.btn-toggle-active').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.id;
                const isActive = this.dataset.active === '1';

                if (confirm(`${isActive ? 'Nonaktifkan' : 'Aktifkan'} user ini?`)) {
                    fetch(`<?= base_url('admin/user/toggle-active/') ?>${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan');
                            console.error(error);
                        });
                }
            });
        });

        // Delete user
        let deleteUserId = null;
        const deleteModal = document.getElementById('deleteModal');
        const deleteUsername = document.getElementById('deleteUsername');

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteUserId = this.dataset.id;
                deleteUsername.textContent = this.dataset.username;
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
            });
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            deleteUserId = null;
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteUserId) {
                fetch(`<?= base_url('admin/user/delete/') ?>${deleteUserId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan');
                        console.error(error);
                    });
            }
        });

        // Close modal on outside click
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
                deleteUserId = null;
            }
        });
    });
</script>
<?= $this->endSection() ?>