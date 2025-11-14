<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="min-h-screen py-12 bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Payment Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-800 p-8 text-white text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-credit-card text-primary-600 text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Pembayaran Donasi</h1>
                    <p class="text-blue-100">Selesaikan pembayaran Anda dengan aman</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Debug Info (only in development) -->
                    <?php if (ENVIRONMENT === 'development'): ?>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                            <p class="text-xs font-mono text-yellow-800">
                                <strong>Debug Info:</strong><br>
                                Snap Token: <?= isset($snapToken) && !empty($snapToken) ? '✓ Available (' . strlen($snapToken) . ' chars)' : '✗ Missing/Empty' ?><br>
                                Client Key: <?= isset($clientKey) && !empty($clientKey) ? '✓ ' . esc($clientKey) : '✗ Missing' ?><br>
                                Transaction ID: <?= isset($donation['transaction_id']) ? esc($donation['transaction_id']) : 'N/A' ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Donation Info -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <h2 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            Detail Donasi
                        </h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Campaign:</span>
                                <span class="font-semibold text-gray-800"><?= esc($campaign['title']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID Transaksi:</span>
                                <span class="font-mono text-gray-800"><?= esc($donation['transaction_id']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Donatur:</span>
                                <span class="font-semibold text-gray-800"><?= esc($donation['donor_name']) ?></span>
                            </div>
                            <div class="flex justify-between border-t pt-3 mt-3">
                                <span class="text-gray-600 font-semibold">Total Donasi:</span>
                                <span class="font-bold text-2xl text-primary-600">
                                    Rp <?= number_format($donation['amount'], 0, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button id="pay-button"
                        class="w-full bg-gradient-to-r from-primary-600 to-primary-800 text-white py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all transform hover:-translate-y-1 mb-4">
                        <i class="fas fa-lock mr-2"></i>
                        Bayar Sekarang
                    </button>

                    <div class="text-center">
                        <a href="/campaign/<?= esc($campaign['slug']) ?>"
                            class="inline-block text-gray-600 hover:text-primary-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Campaign
                        </a>
                    </div>

                    <!-- Security Info -->
                    <div class="mt-8 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-600 text-xl mr-3 mt-1"></i>
                            <div class="text-sm text-green-800">
                                <p class="font-semibold mb-1">Transaksi Aman & Terenkripsi</p>
                                <p class="text-green-700">
                                    Pembayaran Anda diproses oleh Midtrans dengan standar keamanan internasional PCI-DSS Level 1
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Info -->
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500 mb-3">Metode Pembayaran yang Tersedia:</p>
                        <div class="flex justify-center items-center gap-3 flex-wrap">
                            <div class="px-3 py-1 bg-gray-100 rounded text-xs">
                                <i class="fas fa-credit-card mr-1 text-blue-600"></i>Kartu Kredit
                            </div>
                            <div class="px-3 py-1 bg-gray-100 rounded text-xs">
                                <i class="fas fa-wallet mr-1 text-green-600"></i>GoPay
                            </div>
                            <div class="px-3 py-1 bg-gray-100 rounded text-xs">
                                <i class="fas fa-shopping-bag mr-1 text-orange-600"></i>ShopeePay
                            </div>
                            <div class="px-3 py-1 bg-gray-100 rounded text-xs">
                                <i class="fas fa-qrcode mr-1 text-purple-600"></i>QRIS
                            </div>
                            <div class="px-3 py-1 bg-gray-100 rounded text-xs">
                                <i class="fas fa-university mr-1 text-gray-600"></i>Virtual Account
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-question-circle mr-1"></i>
                    Butuh bantuan? <a href="/contact" class="text-primary-600 hover:underline">Hubungi Kami</a>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="<?= esc($clientKey) ?>"></script>

<script type="text/javascript">
    console.log('Snap Token:', '<?= esc($snapToken) ?>');
    console.log('Client Key:', '<?= esc($clientKey) ?>');

    // Check if snap is loaded
    if (typeof snap === 'undefined') {
        console.error('Midtrans Snap library not loaded!');
        alert('Error: Midtrans Snap library gagal dimuat. Silakan refresh halaman.');
    }

    document.getElementById('pay-button').onclick = function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        // Validate snap token
        const snapToken = '<?= esc($snapToken) ?>';
        if (!snapToken || snapToken === '') {
            alert('Error: Token pembayaran tidak ditemukan. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock mr-2"></i>Bayar Sekarang';
            return;
        }

        try {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '/payment/finish?order_id=<?= esc($donation['transaction_id']) ?>&status_code=200&transaction_status=settlement';
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '/payment/finish?order_id=<?= esc($donation['transaction_id']) ?>&status_code=201&transaction_status=pending';
                },
                onError: function(result) {
                    console.error('Payment error:', result);
                    alert('Terjadi kesalahan pembayaran: ' + (result.status_message || 'Unknown error'));
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-lock mr-2"></i>Bayar Sekarang';
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-lock mr-2"></i>Bayar Sekarang';
                }
            });
        } catch (error) {
            console.error('Snap error:', error);
            alert('Error: ' + error.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock mr-2"></i>Bayar Sekarang';
        }
    };
</script>

<?= $this->endSection() ?>