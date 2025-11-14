<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-20 bg-gradient-to-b from-primary-50 to-white">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Icon -->
            <div class="mb-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    Terima Kasih atas Donasi Anda!
                </h1>
                <p class="text-xl text-gray-600">
                    Donasi Anda telah berhasil dikirim dan sedang menunggu verifikasi
                </p>
            </div>

            <!-- Donation Details Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8 text-left">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                    <i class="fas fa-receipt text-primary-600 mr-2"></i>
                    Detail Donasi
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b">
                        <span class="text-gray-600">ID Transaksi:</span>
                        <span class="font-bold text-primary-600"><?= esc($donation['transaction_id']) ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b">
                        <span class="text-gray-600">Campaign:</span>
                        <span class="font-semibold text-gray-800"><?= esc($donation['campaign_title']) ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b">
                        <span class="text-gray-600">Nama Donatur:</span>
                        <span class="font-semibold text-gray-800">
                            <?= $donation['is_anonymous'] ? 'Hamba Allah (Anonim)' : esc($donation['donor_name']) ?>
                        </span>
                    </div>
                    <div class="flex justify-between py-3 border-b">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold text-gray-800"><?= esc($donation['donor_email']) ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b">
                        <span class="text-gray-600">Jumlah Donasi:</span>
                        <span class="font-bold text-2xl text-primary-600">
                            Rp <?= number_format($donation['amount'], 0, ',', '.') ?>
                        </span>
                    </div>
                    <div class="flex justify-between py-3 border-b">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="font-semibold text-gray-800"><?= esc($donation['payment_method']) ?></span>
                    </div>
                    <?php if (!empty($donation['message'])): ?>
                        <div class="py-3 border-b">
                            <span class="text-gray-600 block mb-2">Pesan:</span>
                            <p class="font-semibold text-gray-800 italic">
                                "<?= esc($donation['message']) ?>"
                            </p>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between py-3">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-4 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                            <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                        </span>
                    </div>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-8 text-left">
                <h3 class="font-bold text-blue-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Penting
                </h3>
                <ul class="text-blue-700 space-y-2 text-sm">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-1"></i>
                        <span>Donasi Anda akan diverifikasi oleh admin dalam waktu 1x24 jam</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-1"></i>
                        <span>Kami telah mengirim konfirmasi ke email <strong><?= esc($donation['donor_email']) ?></strong></span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-1"></i>
                        <span>Simpan ID Transaksi Anda untuk referensi di masa mendatang</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-500 mr-2 mt-1"></i>
                        <span>Setelah diverifikasi, donasi Anda akan ditampilkan di halaman campaign</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <?php if ($donation['status'] === 'verified'): ?>
                    <a href="/receipt/<?= esc($donation['transaction_id']) ?>"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-file-invoice mr-2"></i>Lihat Bukti Donasi
                    </a>
                <?php endif; ?>
                <a href="/campaign/<?= esc($donation['campaign_slug']) ?>"
                    class="px-6 py-3 bg-white border-2 border-primary-600 text-primary-600 rounded-lg font-semibold hover:bg-primary-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Campaign
                </a>
                <a href="/"
                    class="px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition">
                    <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                </a>
                <a href="/campaign"
                    class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition">
                    <i class="fas fa-heart mr-2"></i>Donasi Lagi
                </a>
            </div>

            <!-- Share Section -->
            <div class="mt-12 pt-8 border-t">
                <p class="text-gray-700 font-semibold mb-4">
                    Bagikan campaign ini kepada teman dan keluarga Anda!
                </p>
                <div class="flex justify-center gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= base_url('campaign/' . $donation['campaign_slug']) ?>"
                        target="_blank"
                        class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= base_url('campaign/' . $donation['campaign_slug']) ?>&text=<?= esc($donation['campaign_title']) ?>"
                        target="_blank"
                        class="w-12 h-12 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text=<?= esc($donation['campaign_title']) ?> <?= base_url('campaign/' . $donation['campaign_slug']) ?>"
                        target="_blank"
                        class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center hover:bg-green-700 transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>