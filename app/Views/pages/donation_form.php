<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Campaign Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <div class="flex items-start gap-6">
                    <img src="<?= $campaign['image'] ? base_url('writable/uploads/campaigns/' . $campaign['image']) : 'https://via.placeholder.com/200x150' ?>"
                        alt="<?= esc($campaign['title']) ?>"
                        class="w-48 h-32 object-cover rounded-lg">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">
                            <?= esc($campaign['title']) ?>
                        </h2>
                        <p class="text-gray-600 mb-3">
                            <?= esc($campaign['short_description']) ?>
                        </p>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-primary-600 font-semibold">
                                <i class="fas fa-tag mr-1"></i>
                                <?= esc($campaign['category_name']) ?>
                            </span>
                            <span class="text-gray-600">
                                <i class="fas fa-user mr-1"></i>
                                <?= esc($campaign['organizer_name']) ?>
                            </span>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                <span class="font-bold text-primary-600">Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></span>
                                terkumpul dari Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donation Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-hand-holding-heart text-primary-600 mr-2"></i>
                    Form Donasi
                </h1>

                <?php if (session()->has('errors')): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <?php foreach (session('errors') as $error): ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach; ?>
                            </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="/donate/process" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="campaign_id" value="<?= $campaign['id'] ?>">

                    <!-- Amount Selection -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-3">Jumlah Donasi</label>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <button type="button" onclick="setAmount(50000)"
                                class="amount-btn py-3 border-2 border-gray-300 rounded-lg hover:border-primary-600 hover:bg-primary-50 transition font-semibold">
                                Rp 50.000
                            </button>
                            <button type="button" onclick="setAmount(100000)"
                                class="amount-btn py-3 border-2 border-gray-300 rounded-lg hover:border-primary-600 hover:bg-primary-50 transition font-semibold">
                                Rp 100.000
                            </button>
                            <button type="button" onclick="setAmount(200000)"
                                class="amount-btn py-3 border-2 border-gray-300 rounded-lg hover:border-primary-600 hover:bg-primary-50 transition font-semibold">
                                Rp 200.000
                            </button>
                            <button type="button" onclick="setAmount(500000)"
                                class="amount-btn py-3 border-2 border-gray-300 rounded-lg hover:border-primary-600 hover:bg-primary-50 transition font-semibold">
                                Rp 500.000
                            </button>
                            <button type="button" onclick="setAmount(1000000)"
                                class="amount-btn py-3 border-2 border-gray-300 rounded-lg hover:border-primary-600 hover:bg-primary-50 transition font-semibold">
                                Rp 1.000.000
                            </button>
                            <button type="button" onclick="document.getElementById('amount').focus()"
                                class="amount-btn py-3 border-2 border-gray-300 rounded-lg hover:border-primary-600 hover:bg-primary-50 transition font-semibold">
                                Lainnya
                            </button>
                        </div>
                        <input type="number"
                            name="amount"
                            id="amount"
                            required
                            min="10000"
                            placeholder="Atau masukkan nominal sendiri (min. Rp 10.000)"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none">
                    </div>

                    <!-- Donor Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="donor_name" class="block text-gray-700 font-semibold mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="donor_name"
                                id="donor_name"
                                required
                                value="<?= old('donor_name') ?>"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                                placeholder="Nama lengkap Anda">
                        </div>

                        <div>
                            <label for="donor_email" class="block text-gray-700 font-semibold mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                name="donor_email"
                                id="donor_email"
                                required
                                value="<?= old('donor_email') ?>"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                                placeholder="email@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="donor_phone" class="block text-gray-700 font-semibold mb-2">
                            Nomor Telepon (Opsional)
                        </label>
                        <input type="tel"
                            name="donor_phone"
                            id="donor_phone"
                            value="<?= old('donor_phone') ?>"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                            placeholder="08123456789">
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-gray-700 font-semibold mb-2">
                            Pesan Dukungan (Opsional)
                        </label>
                        <textarea name="message"
                            id="message"
                            rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                            placeholder="Tulis pesan atau doa Anda..."><?= old('message') ?></textarea>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-3">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Midtrans Option -->
                            <label class="payment-method-card border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary-600 transition">
                                <input type="radio" name="payment_method" value="midtrans" required class="hidden" onchange="togglePaymentProof()">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-700 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-credit-card text-white"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">Midtrans Payment</h3>
                                                <p class="text-xs text-gray-500">Pembayaran Otomatis</p>
                                            </div>
                                        </div>
                                        <div class="radio-circle"></div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded">Kartu Kredit</span>
                                        <span class="text-xs px-2 py-1 bg-green-50 text-green-700 rounded">GoPay</span>
                                        <span class="text-xs px-2 py-1 bg-orange-50 text-orange-700 rounded">ShopeePay</span>
                                        <span class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded">QRIS</span>
                                        <span class="text-xs px-2 py-1 bg-gray-50 text-gray-700 rounded">Virtual Account</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-3">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                        Proses otomatis, donasi langsung masuk
                                    </p>
                                </div>
                            </label>

                            <!-- Manual Transfer Option -->
                            <label class="payment-method-card border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary-600 transition">
                                <input type="radio" name="payment_method" value="manual" required class="hidden" onchange="togglePaymentProof()">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-gray-500 to-gray-700 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-university text-white"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800">Transfer Manual</h3>
                                                <p class="text-xs text-gray-500">Upload Bukti Transfer</p>
                                            </div>
                                        </div>
                                        <div class="radio-circle"></div>
                                    </div>
                                    <div class="text-xs text-gray-600 space-y-1 mt-3">
                                        <p><strong>BCA:</strong> 1234567890</p>
                                        <p><strong>Mandiri:</strong> 9876543210</p>
                                        <p class="text-gray-500">a.n. DonasiKita</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-3">
                                        <i class="fas fa-clock text-orange-500 mr-1"></i>
                                        Verifikasi manual dalam 1x24 jam
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Proof (for manual only) -->
                    <div id="payment-proof-section" class="hidden">
                        <label for="payment_proof" class="block text-gray-700 font-semibold mb-2">
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                            name="payment_proof"
                            id="payment_proof"
                            accept="image/*"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none">
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Upload screenshot atau foto bukti transfer Anda (Max 2MB)
                        </p>
                    </div>

                    <!-- Anonymous Option -->
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                name="is_anonymous"
                                value="1"
                                class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="ml-3 text-gray-700">
                                <i class="fas fa-user-secret mr-1"></i>
                                Sembunyikan nama saya (Donasi Anonim)
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <a href="/campaign/<?= esc($campaign['slug']) ?>"
                            class="flex-1 text-center py-4 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit"
                            class="flex-1 bg-primary-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-700 transition shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Donasi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Payment Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-6 rounded-lg mt-6">
                <h3 class="font-bold text-blue-800 mb-3">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Keamanan & Informasi Pembayaran
                </h3>
                <div class="text-blue-700 space-y-2 text-sm">
                    <p>
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        <strong>Pembayaran Aman:</strong> Semua transaksi dilindungi dengan enkripsi SSL
                    </p>
                    <p>
                        <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                        <strong>Proses Cepat:</strong> Midtrans - verifikasi otomatis, Manual - maksimal 1x24 jam
                    </p>
                    <p>
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                        <strong>Konfirmasi Email:</strong> Anda akan menerima bukti donasi via email
                    </p>
                    <p class="mt-3 text-xs bg-white/50 p-2 rounded">
                        <i class="fas fa-info-circle mr-1"></i>
                        Untuk pembayaran manual, transfer ke: <strong>BCA 1234567890</strong> atau <strong>Mandiri 9876543210</strong> a.n. DonasiKita
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .payment-method-card {
        position: relative;
    }

    .payment-method-card input:checked~div {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    }

    .payment-method-card input:checked~div .radio-circle {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .payment-method-card input:checked~div .radio-circle::after {
        content: '✓';
        color: white;
        font-weight: bold;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .radio-circle {
        width: 24px;
        height: 24px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        position: relative;
        transition: all 0.3s ease;
    }

    .payment-method-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    function setAmount(amount) {
        document.getElementById('amount').value = amount;

        // Visual feedback
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.classList.remove('border-primary-600', 'bg-primary-50');
        });
        event.target.classList.add('border-primary-600', 'bg-primary-50');
    }

    function togglePaymentProof() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const proofSection = document.getElementById('payment-proof-section');
        const proofInput = document.getElementById('payment_proof');

        if (paymentMethod === 'manual') {
            proofSection.classList.remove('hidden');
            proofInput.required = true;
        } else {
            proofSection.classList.add('hidden');
            proofInput.required = false;
            proofInput.value = '';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedMethod) {
            togglePaymentProof();
        }
    });
</script>

<?= $this->endSection() ?>