<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Donasi</h1>
                <p class="mt-1 text-sm text-gray-600">Informasi lengkap donasi #<?= $donation['transaction_id'] ?></p>
            </div>
            <a href="<?= base_url('admin/donations') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->has('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6" x-data="{ show: true }" x-show="show">
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                <p class="ml-3 text-sm text-green-700"><?= session('success') ?></p>
                <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6" x-data="{ show: true }" x-show="show">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                <p class="ml-3 text-sm text-red-700"><?= session('error') ?></p>
                <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Status Donasi</h2>
                    <?php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'verified' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'completed' => 'bg-blue-100 text-blue-800',
                    ];
                    $statusIcons = [
                        'pending' => 'fa-clock',
                        'verified' => 'fa-check-circle',
                        'rejected' => 'fa-times-circle',
                        'completed' => 'fa-flag-checkered',
                    ];
                    $statusLabels = [
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                    ];
                    ?>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $statusColors[$donation['status']] ?>">
                        <i class="fas <?= $statusIcons[$donation['status']] ?> mr-1"></i>
                        <?= $statusLabels[$donation['status']] ?>
                    </span>
                </div>

                <!-- Action Buttons for Pending -->
                <?php if ($donation['status'] === 'pending'): ?>
                    <div class="flex gap-3 mt-4 pt-4 border-t border-gray-200">
                        <form method="POST" action="<?= base_url('admin/donations/verify/' . $donation['id']) ?>" class="flex-1">
                            <?= csrf_field() ?>
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memverifikasi donasi ini?')"
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors inline-flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i> Verifikasi Donasi
                            </button>
                        </form>
                        <button onclick="showRejectModal(<?= $donation['id'] ?>)"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Tolak Donasi
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Rejection Notes -->
                <?php if ($donation['status'] === 'rejected' && !empty($donation['notes'])): ?>
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm font-medium text-red-900 mb-2">Alasan Penolakan:</p>
                        <p class="text-sm text-red-700"><?= nl2br(esc($donation['notes'])) ?></p>
                        <?php if (!empty($donation['verified_by'])): ?>
                            <p class="text-xs text-red-600 mt-2">Ditolak oleh: Admin #<?= $donation['verified_by'] ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Verification Info -->
                <?php if ($donation['status'] === 'verified' && !empty($donation['verified_by'])): ?>
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>
                            Diverifikasi oleh Admin #<?= $donation['verified_by'] ?> pada <?= date('d M Y H:i', strtotime($donation['verified_at'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Donation Details Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Donasi</h2>

                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Transaction ID:</span>
                        <span class="text-sm font-medium text-gray-900"><?= esc($donation['transaction_id']) ?></span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Campaign:</span>
                        <a href="<?= base_url('campaign/' . $donation['campaign_slug']) ?>" target="_blank"
                            class="text-sm font-medium text-primary-600 hover:text-primary-700">
                            <?= esc($donation['campaign_title']) ?> <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Jumlah Donasi:</span>
                        <span class="text-lg font-bold text-primary-600">Rp <?= number_format($donation['amount'], 0, ',', '.') ?></span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Metode Pembayaran:</span>
                        <span class="text-sm font-medium text-gray-900 uppercase">
                            <i class="fas fa-credit-card text-blue-600 mr-1"></i> <?= esc($donation['payment_method']) ?>
                        </span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Tanggal Donasi:</span>
                        <span class="text-sm font-medium text-gray-900"><?= date('d M Y H:i', strtotime($donation['created_at'])) ?></span>
                    </div>

                    <?php if (!empty($donation['message'])): ?>
                        <div class="py-2">
                            <p class="text-sm text-gray-600 mb-2">Pesan/Doa:</p>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-700 italic">"<?= nl2br(esc($donation['message'])) ?>"</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Donor Information Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Donatur</h2>

                <div class="space-y-4">
                    <div class="flex items-start py-2 border-b border-gray-100">
                        <i class="fas fa-user text-gray-400 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Nama</p>
                            <p class="text-sm font-medium text-gray-900"><?= esc($donation['donor_name']) ?></p>
                        </div>
                    </div>

                    <div class="flex items-start py-2 border-b border-gray-100">
                        <i class="fas fa-envelope text-gray-400 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-sm font-medium text-gray-900"><?= esc($donation['donor_email']) ?></p>
                        </div>
                    </div>

                    <div class="flex items-start py-2 border-b border-gray-100">
                        <i class="fas fa-phone text-gray-400 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">No. Telepon</p>
                            <p class="text-sm font-medium text-gray-900"><?= esc($donation['donor_phone']) ?></p>
                        </div>
                    </div>

                    <div class="flex items-start py-2">
                        <i class="fas fa-eye-slash text-gray-400 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Tampilkan Nama</p>
                            <p class="text-sm font-medium text-gray-900">
                                <?= $donation['is_anonymous'] ? 'Tidak (Anonim)' : 'Ya' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="<?= base_url('receipt/view/' . $donation['transaction_id']) ?>" target="_blank"
                        class="block w-full px-4 py-2 text-sm text-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-receipt mr-2"></i> Lihat Bukti Donasi
                    </a>
                    <a href="<?= base_url('admin/campaigns/edit/' . $donation['campaign_id']) ?>"
                        class="block w-full px-4 py-2 text-sm text-center bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i> Edit Campaign
                    </a>
                </div>
            </div>

            <!-- Payment Proof Card -->
            <?php if (!empty($donation['payment_proof'])): ?>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Bukti Transfer</h3>
                    <img src="<?= upload_url('receipts', $donation['payment_proof']) ?>"
                        alt="Bukti Transfer"
                        class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 transition-opacity"
                        onclick="showImageModal('<?= upload_url('receipts', $donation['payment_proof']) ?>')">
                    <a href="<?= download_url('receipts', $donation['payment_proof']) ?>"
                        target="_blank" download
                        class="block mt-3 text-center text-sm text-primary-600 hover:text-primary-700">
                        <i class="fas fa-download mr-1"></i> Download Gambar
                    </a>
                </div>
            <?php endif; ?>

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500"><?= date('d M Y H:i', strtotime($donation['created_at'])) ?></p>
                            <p class="text-sm text-gray-900">Donasi dibuat</p>
                        </div>
                    </div>

                    <?php if ($donation['status'] === 'verified'): ?>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500"><?= date('d M Y H:i', strtotime($donation['verified_at'])) ?></p>
                                <p class="text-sm text-gray-900">Donasi diverifikasi</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($donation['status'] === 'rejected' && !empty($donation['updated_at'])): ?>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5"></div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500"><?= date('d M Y H:i', strtotime($donation['updated_at'])) ?></p>
                                <p class="text-sm text-gray-900">Donasi ditolak</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Donasi</h3>
        <form id="rejectForm" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label for="rejection_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_notes" name="notes" rows="4" required
                    placeholder="Jelaskan alasan penolakan..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Tolak Donasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-screen p-4">
        <img id="modalImage" src="" alt="Full Image" class="max-w-full max-h-full rounded-lg">
    </div>
</div>

<script>
    function showRejectModal(id) {
        document.getElementById('rejectForm').action = '<?= base_url('admin/donations/reject/') ?>' + id;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }

    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
    }
</script>

<?= $this->endSection() ?>