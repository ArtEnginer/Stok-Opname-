<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="min-h-screen py-12 bg-gradient-to-br from-green-50 via-white to-blue-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <?php
            $isPending = isset($transactionStatus) && in_array($transactionStatus, ['pending', 'challenge']);
            $isSuccess = isset($transactionStatus) && in_array($transactionStatus, ['capture', 'settlement']);
            ?>

            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-center">
                <!-- Icon -->
                <div class="<?= $isSuccess ? 'bg-gradient-to-r from-green-500 to-green-700' : 'bg-gradient-to-r from-yellow-500 to-orange-600' ?> p-12">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg animate-bounce">
                        <i class="fas fa-<?= $isSuccess ? 'check' : 'clock' ?> <?= $isSuccess ? 'text-green-600' : 'text-yellow-600' ?> text-5xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        <?= $isSuccess ? 'Pembayaran Berhasil!' : 'Menunggu Pembayaran' ?>
                    </h1>
                    <p class="text-white/90">
                        <?= $isSuccess ? 'Terima kasih atas donasi Anda' : 'Silakan selesaikan pembayaran Anda' ?>
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
                                <?php if (isset($paymentType)): ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Metode:</span>
                                        <span class="font-semibold"><?= ucwords(str_replace('_', ' ', $paymentType)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($isPending): ?>
                            <!-- Payment Instructions -->
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-6">
                                <h3 class="font-bold text-yellow-800 mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Instruksi Pembayaran
                                </h3>

                                <?php if (isset($vaNumbers) && !empty($vaNumbers)): ?>
                                    <div class="text-yellow-800">
                                        <p class="mb-2">Nomor Virtual Account:</p>
                                        <div class="bg-white p-4 rounded font-mono text-xl font-bold text-center mb-3">
                                            <?= $vaNumbers[0]->va_number ?>
                                        </div>
                                        <p class="text-sm">Bank: <?= ucwords($vaNumbers[0]->bank) ?></p>
                                    </div>
                                <?php elseif (isset($billKey) && isset($billerCode)): ?>
                                    <div class="text-yellow-800">
                                        <p class="mb-2">Kode Pembayaran:</p>
                                        <div class="bg-white p-4 rounded mb-3">
                                            <p class="font-mono"><strong>Biller Code:</strong> <?= $billerCode ?></p>
                                            <p class="font-mono"><strong>Bill Key:</strong> <?= $billKey ?></p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-yellow-800">
                                        Silakan cek email atau SMS Anda untuk instruksi pembayaran lengkap.
                                    </p>
                                <?php endif; ?>

                                <p class="text-sm text-yellow-700 mt-3">
                                    <i class="fas fa-clock mr-1"></i>
                                    Selesaikan pembayaran dalam 24 jam
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($isSuccess): ?>
                            <!-- Success Message -->
                            <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                                    <div class="text-sm text-green-800">
                                        <p class="font-semibold mb-2">Donasi Anda Telah Diterima!</p>
                                        <ul class="space-y-1 text-green-700">
                                            <li><i class="fas fa-envelope mr-2"></i>Bukti donasi telah dikirim ke email Anda</li>
                                            <li><i class="fas fa-bell mr-2"></i>Donasi Anda akan segera membantu campaign ini</li>
                                            <li><i class="fas fa-heart mr-2"></i>Terima kasih atas kebaikan Anda!</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <a href="/"
                            class="flex-1 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                        <a href="/campaigns"
                            class="flex-1 bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                            <i class="fas fa-heart mr-2"></i>Campaign Lainnya
                        </a>
                    </div>

                    <!-- Share -->
                    <div class="mt-8 pt-6 border-t">
                        <p class="text-sm text-gray-600 mb-3">Bagikan kebaikan Anda:</p>
                        <div class="flex justify-center gap-3">
                            <button class="w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button class="w-10 h-10 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="w-10 h-10 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>