<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section with Dynamic Background -->
<section class="relative text-white py-24 overflow-hidden" x-data="{ 
    currentSlide: 0, 
    heroImages: [
        <?php if (!empty($featuredCampaigns)): ?>
            <?php foreach (array_slice($featuredCampaigns, 0, 5) as $index => $camp): ?>
                '<?= base_url('uploads/campaigns/' . ($camp['image'] ?? 'default.jpg')) ?>'<?= $index < min(4, count($featuredCampaigns) - 1) ? ',' : '' ?>
            <?php endforeach; ?>
        <?php endif; ?>
    ]
}" x-init="
    setInterval(() => {
        currentSlide = (currentSlide + 1) % heroImages.length;
    }, 5000);
">
    <!-- Dynamic Background Images with Transition -->
    <?php if (!empty($featuredCampaigns)): ?>
        <?php foreach (array_slice($featuredCampaigns, 0, 5) as $index => $camp): ?>
            <div class="absolute inset-0 transition-opacity duration-1000"
                x-show="currentSlide === <?= $index ?>"
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <!-- Actual Image with better quality -->
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('<?= base_url('uploads/campaigns/' . ($camp['image'] ?? 'default.jpg')) ?>'); 
                           background-size: cover; 
                           background-position: center center;
                           filter: brightness(1.1) contrast(1.05);">
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Gradient Overlay - Lighter for better image visibility -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/70 via-primary-800/60 to-primary-900/75"></div>

    <!-- Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fadeInUp leading-tight drop-shadow-lg">
                Berbagi Kebaikan,<br>
                <span class="text-primary-200">Mengubah Kehidupan</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 text-white animate-fadeInUp max-w-3xl mx-auto drop-shadow-md" style="animation-delay: 0.2s">
                Bersama kita bisa membantu mereka yang membutuhkan. Mari berdonasi untuk masa depan yang lebih baik.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp" style="animation-delay: 0.4s">
                <a href="/campaign" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-heart mr-3"></i>Mulai Berdonasi
                </a>
                <a href="#campaigns" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white hover:text-primary-600 transition-all shadow-lg">
                    <i class="fas fa-arrow-down mr-3"></i>Lihat Campaign
                </a>
            </div>

            <!-- Slide Indicators -->
            <div class="flex justify-center gap-2 mt-8">
                <?php if (!empty($featuredCampaigns)): ?>
                    <?php foreach (array_slice($featuredCampaigns, 0, 5) as $index => $camp): ?>
                        <button @click="currentSlide = <?= $index ?>"
                            class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="currentSlide === <?= $index ?> ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/75'">
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Wave Separator -->
    <div class="absolute bottom-0 left-0 w-full z-10">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white" />
        </svg>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 -mt-12 relative z-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Total Donations -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all hover:shadow-2xl border-t-4 border-primary-500">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 text-primary-600 rounded-full mb-4">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 animate-pulse-slow">
                    Rp <?= number_format($totalDonations ?? 0, 0, ',', '.') ?>
                </div>
                <div class="text-gray-600 font-medium">Total Donasi Terkumpul</div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Dari semua campaign aktif</p>
                </div>
            </div>

            <!-- Total Donors -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all hover:shadow-2xl border-t-4 border-yellow-500">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full mb-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 animate-pulse-slow" style="animation-delay: 0.2s">
                    <?= number_format($totalDonors ?? 0, 0, ',', '.') ?>+
                </div>
                <div class="text-gray-600 font-medium">Donatur Bergabung</div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Orang peduli yang telah berdonasi</p>
                </div>
            </div>

            <!-- Active Campaigns -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all hover:shadow-2xl border-t-4 border-blue-500">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                    <i class="fas fa-hand-holding-heart text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 animate-pulse-slow" style="animation-delay: 0.4s">
                    <?= count($featuredCampaigns ?? []) + count($urgentCampaigns ?? []) ?>
                </div>
                <div class="text-gray-600 font-medium">Campaign Aktif</div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Menunggu dukungan Anda</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Urgent Campaigns -->
<?php if (!empty($urgentCampaigns)): ?>
    <section id="campaigns" class="py-16 bg-gradient-to-br from-red-50 to-orange-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <span class="inline-block px-4 py-2 bg-red-100 text-red-600 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-bell mr-2"></i>MEMBUTUHKAN BANTUAN SEGERA
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">
                    Campaign Mendesak
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Bantu mereka yang sangat membutuhkan segera. Setiap kontribusi Anda sangat berarti!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($urgentCampaigns as $index => $campaign):
                    $progress = ($campaign['target_amount'] > 0) ?
                        min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                    $daysLeft = (new DateTime($campaign['end_date']))->diff(new DateTime())->days;
                ?>
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2 animate-fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s">
                        <div class="relative overflow-hidden">
                            <img src="<?= base_url('uploads/campaigns/' . ($campaign['image'] ?? 'default.jpg')) ?>"
                                alt="<?= esc($campaign['title']) ?>"
                                class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg animate-pulse">
                                <i class="fas fa-fire mr-2"></i>MENDESAK
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="text-white font-semibold text-sm bg-black/40 backdrop-blur-sm px-3 py-1.5 rounded-full inline-flex items-center">
                                    <i class="<?= $campaign['category_icon'] ?? 'fas fa-heart' ?> mr-2"></i>
                                    <?= esc($campaign['category_name']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 min-h-[56px] group-hover:text-primary-600 transition">
                                <a href="/campaign/<?= esc($campaign['slug']) ?>">
                                    <?= esc($campaign['title']) ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?= esc($campaign['short_description']) ?>
                            </p>

                            <!-- Progress Bar -->
                            <div class="mb-5">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">Terkumpul</span>
                                    <span class="font-bold text-primary-600"><?= number_format($progress, 1) ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-1000 shadow-sm relative overflow-hidden" style="width: <?= $progress ?>%">
                                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-end mb-5 pb-5 border-b border-gray-100">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Terkumpul</div>
                                    <div class="font-bold text-lg text-gray-800">Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></div>
                                    <div class="text-xs text-gray-500">dari Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-2xl text-red-600"><?= $daysLeft ?></div>
                                    <div class="text-xs text-gray-500">Hari tersisa</div>
                                </div>
                            </div>

                            <a href="/donate/<?= esc($campaign['slug']) ?>"
                                class="block w-full text-center bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3.5 rounded-xl font-bold hover:from-primary-700 hover:to-primary-800 transition-all shadow-md hover:shadow-lg transform hover:scale-[1.02]">
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
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-star mr-2"></i>PILIHAN TERBAIK
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">
                    Campaign Unggulan
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Campaign pilihan yang perlu dukungan Anda untuk mencapai tujuan mulia mereka</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($featuredCampaigns as $index => $campaign):
                    $progress = ($campaign['target_amount'] > 0) ?
                        min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                    $daysLeft = (new DateTime($campaign['end_date']))->diff(new DateTime())->days;
                ?>
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2 border border-gray-100 animate-fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s">
                        <div class="relative overflow-hidden">
                            <img src="<?= base_url('uploads/campaigns/' . ($campaign['image'] ?? 'default.jpg')) ?>"
                                alt="<?= esc($campaign['title']) ?>"
                                class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                <i class="fas fa-star mr-2"></i>Unggulan
                            </div>
                            <div class="absolute bottom-4 left-4">
                                <div class="text-white font-semibold text-sm bg-black/50 backdrop-blur-sm px-3 py-1.5 rounded-full">
                                    <i class="fas fa-heart mr-2"></i><?= esc($campaign['category_name']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 min-h-[56px] group-hover:text-primary-600 transition">
                                <a href="/campaign/<?= esc($campaign['slug']) ?>">
                                    <?= esc($campaign['title']) ?>
                                </a>
                            </h3>

                            <div class="mb-5">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">Progress</span>
                                    <span class="font-bold text-primary-600"><?= number_format($progress, 1) ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-1000 relative" style="width: <?= $progress ?>%">
                                        <div class="absolute inset-0 bg-white/20"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-5 pb-5 border-b border-gray-100">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Terkumpul</div>
                                    <div class="font-bold text-lg text-gray-800">Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></div>
                                    <div class="text-xs text-gray-500">dari Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?></div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-users mr-2 text-primary-600"></i>
                                        <span class="font-semibold"><?= $campaign['donor_count'] ?? 0 ?></span>
                                    </div>
                                    <div class="text-xs text-gray-500">Donatur</div>
                                </div>
                            </div>

                            <a href="/donate/<?= esc($campaign['slug']) ?>"
                                class="block w-full text-center bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3.5 rounded-xl font-bold hover:from-primary-700 hover:to-primary-800 transition-all shadow-md hover:shadow-lg transform hover:scale-[1.02]">
                                <i class="fas fa-hand-holding-heart mr-2"></i>Donasi Sekarang
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-12 animate-fadeInUp">
                <a href="/campaign" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-bold hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    Lihat Semua Campaign
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Categories -->
<?php if (!empty($categories)): ?>
    <section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-th-large mr-2"></i>KATEGORI
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">Kategori Campaign</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Pilih kategori sesuai dengan kepedulian Anda dan mulai berdonasi</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <?php foreach ($categories as $index => $category): ?>
                    <a href="/campaign?category=<?= esc($category['slug']) ?>"
                        class="group bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all transform hover:-translate-y-2 border-2 border-transparent hover:border-primary-500 animate-fadeInUp"
                        style="animation-delay: <?= $index * 0.05 ?>s">
                        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform inline-flex items-center justify-center w-20 h-20 bg-primary-50 rounded-full group-hover:bg-primary-100">
                            <i class="<?= esc($category['icon']) ?> text-primary-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 group-hover:text-primary-600 transition"><?= esc($category['name']) ?></h3>
                        <p class="text-xs text-gray-500 mt-2"><?= $category['campaign_count'] ?? 0 ?> Campaign</p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Recent Donors -->
<?php if (!empty($recentDonors)): ?>
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-users mr-2"></i>PARA DONATUR
                </span>
                <h2 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">Donatur Terbaru</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Orang-orang baik yang telah berkontribusi untuk membantu sesama</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach (array_slice($recentDonors, 0, 6) as $index => $donor): ?>
                        <div class="bg-gradient-to-br from-primary-50 to-white rounded-2xl p-6 border border-primary-100 hover:shadow-lg transition-all animate-fadeInUp" style="animation-delay: <?= $index * 0.1 ?>s">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                        <?= strtoupper(substr($donor['donor_name'] ?? 'A', 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-800 truncate"><?= esc($donor['donor_name'] ?? 'Anonim') ?></p>
                                    <p class="text-sm text-gray-500 truncate">
                                        <?php if (isset($donor['campaign_title'])): ?>
                                            untuk <span class="font-medium text-primary-600"><?= esc(substr($donor['campaign_title'], 0, 30)) ?>...</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-primary-600">Rp <?= number_format($donor['amount'] ?? 0, 0, ',', '.') ?></p>
                                    <p class="text-xs text-gray-500"><?= date('d M', strtotime($donor['created_at'] ?? 'now')) ?></p>
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
<section class="py-20 bg-gradient-to-r from-primary-600 to-primary-800 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-5xl font-bold mb-6 animate-fadeInUp">
                Mulai Membuat Perbedaan Hari Ini
            </h2>
            <p class="text-xl md:text-2xl mb-10 text-primary-100 animate-fadeInUp" style="animation-delay: 0.2s">
                Setiap donasi Anda, sekecil apapun, sangat berarti bagi mereka yang membutuhkan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp" style="animation-delay: 0.4s">
                <a href="/campaign" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-heart mr-3"></i>Mulai Berdonasi
                </a>
                <a href="/contact" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-primary-600 transition-all shadow-lg">
                    <i class="fas fa-phone mr-3"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>