<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="min-h-screen py-12 bg-gradient-to-br from-orange-50 via-white to-yellow-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-center">
                <!-- Icon -->
                <div class="bg-gradient-to-r from-orange-500 to-red-600 p-12">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-5xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Pembayaran Belum Selesai
                    </h1>
                    <p class="text-white/90">
                        Anda menutup jendela pembayaran
                    </p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <?php if ($donation): ?>
                        <!-- Donation Details -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <h2 class="font-bold text-gray-800 mb-4">Detail Transaksi</h2>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID Transaksi:</span>
                                    <span class="font-mono font-semibold"><?= esc($donation['transaction_id']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jumlah:</span>
                                    <span class="font-bold text-xl text-primary-600">
                                        Rp <?= number_format($donation['amount'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Warning -->
                        <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-lg mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-orange-600 text-2xl mr-3"></i>
                                <div class="text-sm text-orange-800">
                                    <p class="font-semibold mb-2">Transaksi Masih Tersimpan</p>
                                    <p class="text-orange-700">
                                        Donasi Anda masih tersimpan dalam sistem. Anda dapat melanjutkan pembayaran kapan saja
                                        dalam waktu 24 jam dengan mengklik tombol di bawah ini.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 mb-6">
                            <a href="/payment/<?= esc($donation['transaction_id']) ?>"
                                class="block w-full bg-primary-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-700 transition shadow-lg">
                                <i class="fas fa-redo mr-2"></i>Lanjutkan Pembayaran
                            </a>
                            <a href="/"
                                class="block w-full py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition">
                                <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                            </a>
                        </div>

                        <!-- Help -->
                        <div class="text-center text-sm text-gray-600">
                            <p>Butuh bantuan? <a href="/contact" class="text-primary-600 hover:underline">Hubungi Kami</a></p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6">
                            <p class="text-gray-600 mb-4">Transaksi tidak ditemukan</p>
                            <a href="/" class="text-primary-600 hover:underline">
                                <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>