<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Semua Campaign</h1>
        <p class="text-xl text-primary-100">Temukan campaign yang ingin Anda dukung</p>
    </div>
</section>

<!-- Filters and Search -->
<section class="py-8 bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <form method="GET" action="/campaign" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text"
                    name="search"
                    value="<?= esc($currentSearch ?? '') ?>"
                    placeholder="Cari campaign..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <!-- Category Filter -->
            <select name="category"
                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc($cat['slug']) ?>" <?= ($currentCategory ?? '') === $cat['slug'] ? 'selected' : '' ?>>
                        <?= esc($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Sort -->
            <select name="sort"
                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="latest" <?= ($currentSort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Terbaru</option>
                <option value="urgent" <?= ($currentSort ?? '') === 'urgent' ? 'selected' : '' ?>>Mendesak</option>
                <option value="popular" <?= ($currentSort ?? '') === 'popular' ? 'selected' : '' ?>>Populer</option>
                <option value="ending" <?= ($currentSort ?? '') === 'ending' ? 'selected' : '' ?>>Segera Berakhir</option>
            </select>

            <button type="submit"
                class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
    </div>
</section>

<!-- Campaigns Grid -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <?php if (empty($campaigns)): ?>
            <div class="text-center py-20">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">Campaign tidak ditemukan</h3>
                <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($campaigns as $campaign):
                    $progress = ($campaign['target_amount'] > 0) ?
                        min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                    $daysLeft = (new DateTime($campaign['end_date']))->diff(new DateTime())->days;
                ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="relative">
                            <img src="<?= base_url('uploads/campaigns/' . ($campaign['image'] ?? 'default.jpg')) ?>"
                                alt="<?= esc($campaign['title']) ?>"
                                class="w-full h-48 object-cover">
                            <?php if ($campaign['is_urgent']): ?>
                                <div class="absolute top-3 right-3 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-fire mr-1"></i>Mendesak
                                </div>
                            <?php endif; ?>
                            <?php if ($campaign['is_featured']): ?>
                                <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-star mr-1"></i>Unggulan
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-5">
                            <div class="text-xs text-primary-600 font-semibold mb-2">
                                <?= esc($campaign['category_name']) ?>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 h-14">
                                <a href="/campaign/<?= esc($campaign['slug']) ?>" class="hover:text-primary-600 transition">
                                    <?= esc($campaign['title']) ?>
                                </a>
                            </h3>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Terkumpul</span>
                                    <span class="font-semibold text-primary-600"><?= number_format($progress, 0) ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: <?= $progress ?>%"></div>
                                </div>
                            </div>

                            <div class="flex justify-between text-xs text-gray-600 mb-4">
                                <div>
                                    <span class="font-semibold text-gray-800 block">Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></span>
                                    <span>terkumpul</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold text-gray-800 block"><?= $daysLeft ?> Hari</span>
                                    <span>tersisa</span>
                                </div>
                            </div>

                            <a href="/donate/<?= esc($campaign['slug']) ?>"
                                class="block w-full text-center bg-primary-600 text-white py-2 rounded-lg font-semibold hover:bg-primary-700 transition text-sm">
                                <i class="fas fa-hand-holding-heart mr-2"></i>Donasi
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="mt-12">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>