<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
                Berbagi Kebaikan, Mengubah Kehidupan
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-primary-100">
                Bersama kita bisa membantu mereka yang membutuhkan. Mari berdonasi untuk masa depan yang lebih baik.
            </p>
            <a href="/campaign" class="inline-block bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
                <i class="fas fa-heart mr-2"></i>Mulai Berdonasi
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-12 bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-primary-600 mb-2">
                    Rp <?= number_format($totalDonations ?? 0, 0, ',', '.') ?>
                </div>
                <div class="text-gray-600">Total Donasi Terkumpul</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-primary-600 mb-2">
                    <?= number_format($totalDonors ?? 0, 0, ',', '.') ?>
                </div>
                <div class="text-gray-600">Donatur Bergabung</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-primary-600 mb-2">
                    <?= count($featuredCampaigns ?? []) ?>
                </div>
                <div class="text-gray-600">Campaign Aktif</div>
            </div>
        </div>
    </div>
</section>

<!-- Urgent Campaigns -->
<?php if (!empty($urgentCampaigns)): ?>
    <section class="py-16 bg-red-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Campaign Mendesak
                </h2>
                <p class="text-gray-600 text-lg">Bantu mereka yang sangat membutuhkan segera</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($urgentCampaigns as $campaign):
                    $progress = ($campaign['target_amount'] > 0) ?
                        min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                    $daysLeft = (new DateTime($campaign['end_date']))->diff(new DateTime())->days;
                ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="relative">
                            <img src="<?= $campaign['image'] ? base_url('writable/uploads/campaigns/' . $campaign['image']) : 'https://via.placeholder.com/400x250' ?>"
                                alt="<?= esc($campaign['title']) ?>"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-fire mr-1"></i>Mendesak
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-primary-600 font-semibold mb-2">
                                <i class="<?= $campaign['category_icon'] ?? 'fas fa-heart' ?> mr-1"></i>
                                <?= esc($campaign['category_name']) ?>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">
                                <a href="/campaign/<?= esc($campaign['slug']) ?>" class="hover:text-primary-600 transition">
                                    <?= esc($campaign['title']) ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?= esc($campaign['short_description']) ?>
                            </p>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Terkumpul</span>
                                    <span class="font-semibold text-primary-600"><?= number_format($progress, 0) ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: <?= $progress ?>%"></div>
                                </div>
                            </div>

                            <div class="flex justify-between text-sm text-gray-600 mb-4">
                                <div>
                                    <span class="font-semibold text-gray-800">Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></span>
                                    <br>terkumpul
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold text-gray-800"><?= $daysLeft ?> Hari</span>
                                    <br>tersisa
                                </div>
                            </div>

                            <a href="/donate/<?= esc($campaign['slug']) ?>"
                                class="block w-full text-center bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                                <i class="fas fa-hand-holding-heart mr-2"></i>Donasi Sekarang
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Featured Campaigns -->
<?php if (!empty($featuredCampaigns)): ?>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Campaign Unggulan
                </h2>
                <p class="text-gray-600 text-lg">Campaign pilihan yang perlu dukungan Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($featuredCampaigns as $campaign):
                    $progress = ($campaign['target_amount'] > 0) ?
                        min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                        <div class="relative">
                            <img src="<?= $campaign['image'] ? base_url('writable/uploads/campaigns/' . $campaign['image']) : 'https://via.placeholder.com/400x250' ?>"
                                alt="<?= esc($campaign['title']) ?>"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-star mr-1"></i>Unggulan
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-primary-600 font-semibold mb-2">
                                <?= esc($campaign['category_name']) ?>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">
                                <a href="/campaign/<?= esc($campaign['slug']) ?>" class="hover:text-primary-600 transition">
                                    <?= esc($campaign['title']) ?>
                                </a>
                            </h3>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Terkumpul</span>
                                    <span class="font-semibold text-primary-600"><?= number_format($progress, 0) ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: <?= $progress ?>%"></div>
                                </div>
                            </div>

                            <div class="text-sm text-gray-600 mb-4">
                                <span class="font-semibold text-gray-800">Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></span>
                                dari Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?>
                            </div>

                            <a href="/donate/<?= esc($campaign['slug']) ?>"
                                class="block w-full text-center bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                                <i class="fas fa-hand-holding-heart mr-2"></i>Donasi Sekarang
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-12">
                <a href="/campaign" class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                    Lihat Semua Campaign <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Categories -->
<?php if (!empty($categories)): ?>
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Kategori Campaign</h2>
                <p class="text-gray-600 text-lg">Pilih kategori sesuai dengan kepedulian Anda</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <?php foreach ($categories as $category): ?>
                    <a href="/campaign?category=<?= esc($category['slug']) ?>"
                        class="bg-white rounded-xl p-6 text-center hover:shadow-lg transition transform hover:-translate-y-1">
                        <div class="text-4xl text-primary-600 mb-3">
                            <i class="<?= esc($category['icon']) ?>"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800"><?= esc($category['name']) ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Recent Donors -->
<?php if (!empty($recentDonors)): ?>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-users text-primary-600 mr-2"></i>
                    Donatur Terbaru
                </h2>
                <p class="text-gray-600 text-lg">Terima kasih kepada para donatur yang telah berdonasi</p>
            </div>

            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
                <div class="space-y-4">
                    <?php foreach (array_slice($recentDonors, 0, 5) as $donor): ?>
                        <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-primary-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?= $donor['is_anonymous'] ? 'Hamba Allah' : esc($donor['donor_name']) ?>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?= esc($donor['campaign_title']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-primary-600">
                                    Rp <?= number_format($donor['amount'], 0, ',', '.') ?>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?= date('d M Y', strtotime($donor['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-5xl font-bold mb-6">
            Mulai Campaign Anda Sendiri
        </h2>
        <p class="text-xl mb-8 text-primary-100 max-w-2xl mx-auto">
            Butuh bantuan untuk diri sendiri atau orang lain? Buat campaign Anda dan bagikan kepada dunia.
        </p>
        <a href="/admin/campaigns/create"
            class="inline-block bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
            <i class="fas fa-plus-circle mr-2"></i>Buat Campaign
        </a>
    </div>
</section>

<?= $this->endSection() ?>