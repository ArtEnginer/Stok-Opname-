<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="min-h-screen py-12 bg-gradient-to-br from-red-50 via-white to-pink-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-center">
                <!-- Icon -->
                <div class="bg-gradient-to-r from-red-500 to-red-700 p-12">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-times-circle text-red-600 text-5xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Pembayaran Gagal
                    </h1>
                    <p class="text-white/90">
                        Terjadi kesalahan saat memproses pembayaran
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
                    <?php endif; ?>

                    <!-- Error Info -->
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 text-2xl mr-3"></i>
                            <div class="text-sm text-red-800">
                                <p class="font-semibold mb-2">Kemungkinan Penyebab:</p>
                                <ul class="space-y-1 text-red-700 list-disc list-inside">
                                    <li>Saldo atau limit kartu tidak mencukupi</li>
                                    <li>Koneksi internet terputus</li>
                                    <li>Transaksi ditolak oleh bank</li>
                                    <li>Informasi pembayaran tidak valid</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Suggestions -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-blue-600 text-2xl mr-3"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-2">Saran untuk Anda:</p>
                                <ul class="space-y-1 text-blue-700 list-disc list-inside">
                                    <li>Periksa saldo atau limit kartu Anda</li>
                                    <li>Pastikan koneksi internet stabil</li>
                                    <li>Coba metode pembayaran lain</li>
                                    <li>Hubungi bank Anda jika masalah berlanjut</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 mb-6">
                        <?php if ($donation): ?>
                            <a href="/payment/<?= esc($donation['transaction_id']) ?>"
                                class="block w-full bg-primary-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-700 transition shadow-lg">
                                <i class="fas fa-redo mr-2"></i>Coba Lagi
                            </a>
                        <?php endif; ?>

                        <a href="/campaigns"
                            class="block w-full py-3 border-2 border-primary-600 text-primary-600 rounded-lg font-semibold hover:bg-primary-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Campaign
                        </a>

                        <a href="/"
                            class="block w-full py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </div>

                    <!-- Help -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700 mb-2">
                            <i class="fas fa-headset mr-2 text-primary-600"></i>
                            <strong>Butuh Bantuan?</strong>
                        </p>
                        <div class="flex gap-3 justify-center">
                            <a href="/contact" class="text-primary-600 hover:underline text-sm">
                                <i class="fas fa-envelope mr-1"></i>Hubungi Kami
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-green-600 hover:underline text-sm">
                                <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>