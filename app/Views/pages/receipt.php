<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Receipt Card -->
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-800 p-8 text-white text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10">
                        <div class="absolute top-4 left-4 w-32 h-32 border-4 border-white rounded-full"></div>
                        <div class="absolute bottom-4 right-4 w-24 h-24 border-4 border-white rounded-full"></div>
                    </div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-check-circle text-primary-600 text-4xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold mb-2">Bukti Donasi</h1>
                        <p class="text-primary-100">Terima kasih atas kebaikan Anda</p>
                    </div>
                </div>

                <!-- Receipt Content -->
                <div class="p-8">
                    <!-- Transaction Info -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">ID Transaksi</div>
                                <div class="font-mono font-bold text-gray-800"><?= esc($donation['transaction_id']) ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Tanggal</div>
                                <div class="font-semibold text-gray-800">
                                    <?= date('d F Y H:i', strtotime($donation['created_at'])) ?> WIB
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donor Info -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user text-primary-600 mr-2"></i>
                            Informasi Donatur
                        </h2>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-semibold text-gray-800">
                                    <?= $donation['is_anonymous'] ? 'Hamba Allah (Anonim)' : esc($donation['donor_name']) ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-semibold text-gray-800"><?= esc($donation['donor_email']) ?></span>
                            </div>
                            <?php if ($donation['donor_phone']): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Telepon:</span>
                                    <span class="font-semibold text-gray-800"><?= esc($donation['donor_phone']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-heart text-primary-600 mr-2"></i>
                            Campaign
                        </h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-start gap-4">
                                <img src="<?= $campaign['image'] ? base_url('writable/uploads/campaigns/' . $campaign['image']) : 'https://via.placeholder.com/150' ?>"
                                    alt="<?= esc($campaign['title']) ?>"
                                    class="w-24 h-24 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 mb-2"><?= esc($campaign['title']) ?></h3>
                                    <p class="text-sm text-gray-600"><?= esc($campaign['short_description']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Donation Amount -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-money-bill-wave text-primary-600 mr-2"></i>
                            Rincian Donasi
                        </h2>
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-700">Jumlah Donasi:</span>
                                <span class="text-3xl font-bold text-primary-600">
                                    Rp <?= number_format($donation['amount'], 0, ',', '.') ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Metode Pembayaran:</span>
                                <span class="font-semibold text-gray-800"><?= esc($donation['payment_method']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <?php if ($donation['message']): ?>
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-comment-alt text-primary-600 mr-2"></i>
                                Pesan
                            </h2>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <p class="text-gray-700 italic">"<?= esc($donation['message']) ?>"</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Verification Status -->
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                            <div>
                                <div class="font-semibold text-green-800">Donasi Terverifikasi</div>
                                <div class="text-sm text-green-700">
                                    <?= date('d F Y H:i', strtotime($donation['verified_at'])) ?> WIB
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="text-center text-sm text-gray-500 border-t pt-6">
                        <p class="mb-2">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Dokumen ini adalah bukti sah donasi Anda
                        </p>
                        <p>Terima kasih telah berbagi kebaikan melalui platform kami</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <button onclick="window.print()"
                            class="flex-1 py-3 border-2 border-primary-600 text-primary-600 rounded-lg font-semibold hover:bg-primary-50 transition">
                            <i class="fas fa-print mr-2"></i>Cetak Bukti
                        </button>
                        <a href="/receipt/download/<?= esc($donation['transaction_id']) ?>"
                            class="flex-1 text-center py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                        <a href="/"
                            class="flex-1 text-center py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition">
                            <i class="fas fa-home mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Share Section -->
            <div class="mt-8 text-center">
                <p class="text-gray-600 mb-4">Bagikan kebaikan Anda:</p>
                <div class="flex justify-center gap-3">
                    <button onclick="shareToFacebook()" class="w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button onclick="shareToTwitter()" class="w-12 h-12 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button onclick="shareToWhatsApp()" class="w-12 h-12 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container,
        .container * {
            visibility: visible;
        }

        .container {
            position: absolute;
            left: 0;
            top: 0;
        }

        button,
        .no-print {
            display: none !important;
        }
    }
</style>

<script>
    function shareToFacebook() {
        const url = window.location.href;
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
    }

    function shareToTwitter() {
        const url = window.location.href;
        const text = 'Saya baru saja berdonasi untuk campaign ini!';
        window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`, '_blank');
    }

    function shareToWhatsApp() {
        const url = window.location.href;
        const text = 'Saya baru saja berdonasi! Lihat bukti donasi saya:';
        window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`, '_blank');
    }
</script>

<?= $this->endSection() ?>