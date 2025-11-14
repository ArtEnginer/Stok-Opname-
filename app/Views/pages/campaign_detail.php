<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Campaign Header -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Campaign Details -->
            <div class="lg:col-span-2">
                <!-- Campaign Image Gallery -->
                <div class="mb-6">
                    <?php
                    $additionalImages = !empty($campaign['images']) ? json_decode($campaign['images'], true) : [];
                    $allImages = array_merge([$campaign['image'] ?? 'default.jpg'], $additionalImages);
                    ?>

                    <?php if (count($allImages) > 1): ?>
                        <!-- Main Image Display -->
                        <div class="relative campaign-gallery-main">
                            <div class="gallery-main-container rounded-xl overflow-hidden shadow-lg">
                                <?php foreach ($allImages as $index => $image): ?>
                                    <img src="<?= base_url('uploads/campaigns/' . $image) ?>"
                                        alt="<?= esc($campaign['title']) ?>"
                                        class="gallery-main-image w-full h-[500px] object-cover <?= $index === 0 ? '' : 'hidden' ?>"
                                        data-main-index="<?= $index ?>">
                                <?php endforeach; ?>
                            </div>

                            <!-- Navigation Overlays -->
                            <button type="button" onclick="changeMainImage(-1)"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-12 h-12 rounded-full flex items-center justify-center transition group">
                                <i class="fas fa-chevron-left group-hover:scale-110 transition"></i>
                            </button>
                            <button type="button" onclick="changeMainImage(1)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-12 h-12 rounded-full flex items-center justify-center transition group">
                                <i class="fas fa-chevron-right group-hover:scale-110 transition"></i>
                            </button>

                            <!-- Image Counter -->
                            <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-2 rounded-lg">
                                <i class="fas fa-images mr-2"></i>
                                <span class="current-main-image">1</span> / <?= count($allImages) ?>
                            </div>

                            <!-- Fullscreen Button -->
                            <button type="button" onclick="openFullscreen()"
                                class="absolute top-4 left-4 bg-black/70 hover:bg-black/80 text-white px-3 py-2 rounded-lg transition">
                                <i class="fas fa-expand mr-2"></i>Lihat Penuh
                            </button>
                        </div>

                        <!-- Thumbnail Gallery -->
                        <div class="mt-4 grid grid-cols-5 gap-3">
                            <?php foreach ($allImages as $index => $image): ?>
                                <div class="thumbnail-main-wrapper relative cursor-pointer group" onclick="goToMainImage(<?= $index ?>)">
                                    <img src="<?= base_url('uploads/campaigns/' . $image) ?>"
                                        alt="Thumbnail <?= $index + 1 ?>"
                                        class="thumbnail-main w-full h-24 object-cover rounded-lg border-3 <?= $index === 0 ? 'border-primary-600 ring-2 ring-primary-300' : 'border-gray-300' ?> hover:border-primary-400 transition"
                                        data-main-thumb="<?= $index ?>">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition rounded-lg"></div>
                                    <?php if ($index === 0): ?>
                                        <div class="absolute top-1 right-1 bg-primary-600 text-white text-xs px-2 py-0.5 rounded">
                                            Utama
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- Single Image -->
                        <img src="<?= base_url('uploads/campaigns/' . ($campaign['image'] ?? 'default.jpg')) ?>"
                            alt="<?= esc($campaign['title']) ?>"
                            class="w-full h-[500px] object-cover rounded-xl shadow-lg">
                    <?php endif; ?>
                </div>

                <!-- Campaign Title and Info -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php if ($campaign['is_urgent']): ?>
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-fire mr-1"></i>Mendesak
                            </span>
                        <?php endif; ?>
                        <?php if ($campaign['is_featured']): ?>
                            <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-star mr-1"></i>Unggulan
                            </span>
                        <?php endif; ?>
                        <span class="bg-primary-100 text-primary-600 px-3 py-1 rounded-full text-sm font-semibold">
                            <?= esc($campaign['category_name']) ?>
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        <?= esc($campaign['title']) ?>
                    </h1>

                    <p class="text-xl text-gray-600 mb-6">
                        <?= esc($campaign['short_description']) ?>
                    </p>

                    <!-- Organizer Info -->
                    <div class="flex items-center text-gray-600 border-t pt-4">
                        <i class="fas fa-user-circle text-2xl mr-3"></i>
                        <div>
                            <div class="text-sm text-gray-500">Penggalang Dana</div>
                            <div class="font-semibold"><?= esc($campaign['organizer_name']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Campaign Description -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-align-left text-primary-600 mr-2"></i>
                        Deskripsi Campaign
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        <?= nl2br(esc($campaign['description'])) ?>
                    </div>
                </div>

                <!-- Recent Donations -->
                <?php if (!empty($recentDonations)): ?>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-users text-primary-600 mr-2"></i>
                            Donatur Terbaru
                        </h2>
                        <div class="space-y-4">
                            <?php foreach ($recentDonations as $donation): ?>
                                <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-user text-primary-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">
                                                <?= $donation['is_anonymous'] ? 'Hamba Allah' : esc($donation['donor_name']) ?>
                                            </div>
                                            <?php if (!empty($donation['message'])): ?>
                                                <div class="text-sm text-gray-600 italic">
                                                    "<?= esc($donation['message']) ?>"
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-primary-600">
                                            Rp <?= number_format($donation['amount'], 0, ',', '.') ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?= date('d M Y', strtotime($donation['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Donation Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <!-- Amount Collected -->
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-gray-800 mb-2">
                            Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?>
                        </div>
                        <div class="text-gray-600 mb-4">
                            terkumpul dari Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                            <div class="bg-primary-600 h-3 rounded-full transition-all" style="width: <?= $progress ?>%"></div>
                        </div>
                        <div class="text-right text-sm font-semibold text-primary-600">
                            <?= number_format($progress, 0) ?>%
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
                        <div>
                            <div class="text-2xl font-bold text-gray-800">
                                <?= number_format($campaign['donor_count'], 0) ?>
                            </div>
                            <div class="text-sm text-gray-600">Donatur</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">
                                <?= $daysLeft ?>
                            </div>
                            <div class="text-sm text-gray-600">Hari Tersisa</div>
                        </div>
                    </div>

                    <!-- Donate Button -->
                    <a href="/donate/<?= esc($campaign['slug']) ?>"
                        class="block w-full text-center bg-primary-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-700 transition mb-4 shadow-lg">
                        <i class="fas fa-hand-holding-heart mr-2"></i>Donasi Sekarang
                    </a>

                    <!-- Share Buttons -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Bagikan Campaign Ini</p>
                        <div class="flex justify-center gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url() ?>"
                                target="_blank"
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition text-center">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= current_url() ?>&text=<?= esc($campaign['title']) ?>"
                                target="_blank"
                                class="flex-1 bg-sky-500 text-white py-2 px-4 rounded-lg hover:bg-sky-600 transition text-center">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text=<?= esc($campaign['title']) ?> <?= current_url() ?>"
                                target="_blank"
                                class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition text-center">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mulai:</span>
                                <span class="font-semibold"><?= date('d M Y', strtotime($campaign['start_date'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Berakhir:</span>
                                <span class="font-semibold"><?= date('d M Y', strtotime($campaign['end_date'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dilihat:</span>
                                <span class="font-semibold"><?= number_format($campaign['views'], 0) ?>x</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Campaigns -->
        <?php if (!empty($relatedCampaigns)): ?>
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Campaign Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <?php foreach ($relatedCampaigns as $related):
                        $relatedProgress = ($related['target_amount'] > 0) ?
                            min(($related['collected_amount'] / $related['target_amount']) * 100, 100) : 0;
                    ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            <img src="<?= base_url('uploads/campaigns/' . ($related['image'] ?? 'default.jpg')) ?>"
                                alt="<?= esc($related['title']) ?>"
                                class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
                                    <a href="/campaign/<?= esc($related['slug']) ?>" class="hover:text-primary-600 transition">
                                        <?= esc($related['title']) ?>
                                    </a>
                                </h3>
                                <div class="text-sm text-gray-600 mb-2">
                                    <span class="font-semibold">Rp <?= number_format($related['collected_amount'], 0, ',', '.') ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: <?= $relatedProgress ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Fullscreen Modal -->
<div id="fullscreen-modal" class="fixed inset-0 bg-black/95 z-50 hidden flex items-center justify-center">
    <button onclick="closeFullscreen()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl z-10">
        <i class="fas fa-times"></i>
    </button>

    <div class="relative w-full h-full flex items-center justify-center p-8">
        <button onclick="changeFullscreenImage(-1)"
            class="absolute left-8 text-white hover:text-gray-300 text-4xl z-10">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div id="fullscreen-image-container" class="max-w-full max-h-full">
            <!-- Images will be inserted here -->
        </div>

        <button onclick="changeFullscreenImage(1)"
            class="absolute right-8 text-white hover:text-gray-300 text-4xl z-10">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white text-lg">
            <span id="fullscreen-counter"></span>
        </div>
    </div>
</div>

<style>
    .campaign-gallery-main {
        position: relative;
    }

    .gallery-main-image {
        transition: opacity 0.4s ease;
    }

    .thumbnail-main {
        transition: all 0.3s ease;
    }

    .thumbnail-main-wrapper:hover .thumbnail-main {
        transform: scale(1.05);
    }

    #fullscreen-modal {
        backdrop-filter: blur(10px);
    }

    #fullscreen-image-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
</style>

<script src="<?= base_url('js/campaign-gallery.js') ?>?v=<?= time() ?>"></script>

<?= $this->endSection() ?>