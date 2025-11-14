<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-bullhorn mr-2"></i>Manajemen Campaign
        </h2>
        <p class="text-gray-600 mt-1">Kelola semua campaign donasi</p>
    </div>
    <a href="/admin/campaigns/create" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg inline-flex items-center transition">
        <i class="fas fa-plus mr-2"></i>Tambah Campaign
    </a>
</div>

<!-- Campaigns Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Campaign</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Image</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Title</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Category</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Target</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Terkumpul</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($campaigns)): ?>
                        <?php foreach ($campaigns as $campaign):
                            $progress = ($campaign['target_amount'] > 0) ?
                                min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                        ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4"><?= $campaign['id'] ?></td>
                                <td class="py-3 px-4">
                                    <?php if ($campaign['image']): ?>

                                        <img src="<?= base_url('uploads/campaigns/' . $campaign['image']) ?>"
                                            alt="<?= esc($campaign['title']) ?>"
                                            class="w-20 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <div class="w-20 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-800"><?= esc($campaign['title']) ?></div>
                                    <div class="flex gap-2 mt-1">
                                        <?php if ($campaign['is_featured']): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">Featured</span>
                                        <?php endif; ?>
                                        <?php if ($campaign['is_urgent']): ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Urgent</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full"><?= esc($campaign['category_name']) ?></span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?></td>
                                <td class="py-3 px-4">
                                    <div class="whitespace-nowrap text-sm font-semibold text-gray-700">
                                        Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1"><?= number_format($progress, 1) ?>%</div>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($campaign['status'] === 'active'): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold">Active</span>
                                    <?php elseif ($campaign['status'] === 'completed'): ?>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-semibold">Completed</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-semibold">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <a href="/campaign/<?= esc($campaign['slug']) ?>"
                                            target="_blank"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/campaigns/edit/<?= $campaign['id'] ?>"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                            onclick="confirmDelete(<?= $campaign['id'] ?>, '<?= addslashes(esc($campaign['title'])) ?>')"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                <p class="text-gray-500 mb-4">Belum ada campaign</p>
                                <a href="/admin/campaigns/create" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded inline-flex items-center transition">
                                    <i class="fas fa-plus mr-2"></i>Tambah Campaign Pertama
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($campaigns) && isset($pager)): ?>
            <div class="mt-6 flex justify-center">
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-red-600 text-white p-4 rounded-t-lg">
            <h3 class="text-lg font-bold">
                <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus campaign:</p>
            <p class="font-bold text-gray-900 mb-4" id="campaignTitle"></p>
            <p class="text-red-600 text-sm">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Tindakan ini tidak dapat dibatalkan!
            </p>
        </div>
        <div class="p-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
            <button type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, title) {
        document.getElementById('campaignTitle').textContent = title;
        document.getElementById('deleteForm').action = '/admin/campaigns/delete/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

<?= $this->endSection() ?>