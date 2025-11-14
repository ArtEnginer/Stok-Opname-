<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-hand-holding-heart mr-2"></i>Manajemen Donasi
        </h2>
        <p class="text-gray-600 mt-1">Kelola dan verifikasi donasi</p>
    </div>
    <a href="/admin/donations/export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition">
        <i class="fas fa-file-excel mr-2"></i>Export Excel
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Pending</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= isset($stats['pending']) ? $stats['pending'] : 0 ?></h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Verified</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= isset($stats['verified']) ? $stats['verified'] : 0 ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Rejected</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= isset($stats['rejected']) ? $stats['rejected'] : 0 ?></h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Amount</p>
                <h3 class="text-xl font-bold text-gray-800">Rp <?= isset($stats['total_amount']) ? number_format($stats['total_amount'], 0, ',', '.') : 0 ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Donations Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Donasi</h3>
    </div>

    <!-- Filter Tabs -->
    <div class="border-b border-gray-200">
        <div class="flex space-x-4 px-6" x-data="{ activeTab: 'all' }">
            <a href="/admin/donations"
                @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-600 hover:text-gray-800'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition">
                Semua (<?= count($donations) ?>)
            </a>
            <a href="/admin/donations?status=pending"
                @click="activeTab = 'pending'"
                :class="activeTab === 'pending' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-600 hover:text-gray-800'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition">
                Pending
            </a>
            <a href="/admin/donations?status=verified"
                @click="activeTab = 'verified'"
                :class="activeTab === 'verified' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-600 hover:text-gray-800'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition">
                Verified
            </a>
            <a href="/admin/donations?status=rejected"
                @click="activeTab = 'rejected'"
                :class="activeTab === 'rejected' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-600 hover:text-gray-800'"
                class="py-4 px-1 border-b-2 font-medium text-sm transition">
                Rejected
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Transaction ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Donatur</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Campaign</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Amount</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Payment</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($donations)): ?>
                        <?php foreach ($donations as $donation): ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-sm"><?= date('d/m/Y', strtotime($donation['created_at'])) ?></td>
                                <td class="py-3 px-4">
                                    <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded"><?= esc($donation['transaction_id']) ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">
                                        <?= $donation['is_anonymous'] ?
                                            '<i class="fas fa-user-secret mr-1"></i>Anonim' :
                                            esc($donation['donor_name'])
                                        ?>
                                    </div>
                                    <div class="text-xs text-gray-500"><?= esc($donation['donor_email']) ?></div>
                                </td>
                                <td class="py-3 px-4 text-sm"><?= esc($donation['campaign_title']) ?></td>
                                <td class="py-3 px-4">
                                    <span class="font-semibold text-primary-600">Rp <?= number_format($donation['amount'], 0, ',', '.') ?></span>
                                </td>
                                <td class="py-3 px-4 text-sm"><?= esc($donation['payment_method']) ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($donation['status'] === 'pending'): ?>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-semibold">Pending</span>
                                    <?php elseif ($donation['status'] === 'verified'): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold">Verified</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded-full font-semibold">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <a href="/admin/donations/detail/<?= $donation['id'] ?>"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($donation['status'] === 'pending'): ?>
                                            <button type="button"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition"
                                                onclick="verifyDonation(<?= $donation['id'] ?>, '<?= addslashes(esc($donation['transaction_id'])) ?>')"
                                                title="Verify">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                                onclick="rejectDonation(<?= $donation['id'] ?>, '<?= addslashes(esc($donation['transaction_id'])) ?>')"
                                                title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada donasi</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($donations) && isset($pager)): ?>
            <div class="mt-6 flex justify-center">
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-green-600 text-white p-4 rounded-t-lg">
            <h3 class="text-lg font-bold">
                <i class="fas fa-check-circle mr-2"></i>Verifikasi Donasi
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-2">Apakah Anda yakin ingin memverifikasi donasi:</p>
            <p class="font-bold text-gray-900" id="verifyTransactionId"></p>
        </div>
        <div class="p-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
            <button type="button"
                onclick="closeVerifyModal()"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition">
                Batal
            </button>
            <form id="verifyForm" method="POST" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition">
                    <i class="fas fa-check mr-2"></i>Verifikasi
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-red-600 text-white p-4 rounded-t-lg">
            <h3 class="text-lg font-bold">
                <i class="fas fa-times-circle mr-2"></i>Tolak Donasi
            </h3>
        </div>
        <form id="rejectForm" method="POST">
            <?= csrf_field() ?>
            <div class="p-6">
                <p class="text-gray-700 mb-2">Alasan penolakan untuk donasi:</p>
                <p class="font-bold text-gray-900 mb-4" id="rejectTransactionId"></p>
                <div>
                    <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">Alasan:</label>
                    <textarea name="notes"
                        id="rejectReason"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        rows="3"
                        required></textarea>
                </div>
            </div>
            <div class="p-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                <button type="button"
                    onclick="closeRejectModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function verifyDonation(id, transactionId) {
        document.getElementById('verifyTransactionId').textContent = transactionId;
        document.getElementById('verifyForm').action = '/admin/donations/verify/' + id;
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }

    function rejectDonation(id, transactionId) {
        document.getElementById('rejectTransactionId').textContent = transactionId;
        document.getElementById('rejectForm').action = '/admin/donations/reject/' + id;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Close modals on outside click
    document.getElementById('verifyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVerifyModal();
        }
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>

<?= $this->endSection() ?>