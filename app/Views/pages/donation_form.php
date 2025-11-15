<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Campaign Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                <div class="flex items-start gap-6">
                    <!-- Campaign Image Gallery -->
                    <div class="w-48 flex-shrink-0">
                        <?php
                        $additionalImages = !empty($campaign['images']) ? json_decode($campaign['images'], true) : [];
                        $allImages = array_merge([$campaign['image'] ?? 'default.jpg'], $additionalImages);
                        ?>

                        <?php if (count($allImages) > 1): ?>
                            <!-- Image Slider -->
                            <div class="relative campaign-gallery-small">
                                <div class="gallery-container-small overflow-hidden rounded-lg">
                                    <?php foreach ($allImages as $index => $image): ?>
                                        <img src="<?= base_url('uploads/campaigns/' . $image) ?>"
                                            alt="<?= esc($campaign['title']) ?>"
                                            class="gallery-image-small w-48 h-32 object-cover rounded-lg <?= $index === 0 ? '' : 'hidden' ?>"
                                            data-index="<?= $index ?>">
                                    <?php endforeach; ?>
                                </div>

                                <!-- Navigation Arrows -->
                                <?php if (count($allImages) > 1): ?>
                                    <button type="button" onclick="changeImageSmall(-1)"
                                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-8 h-8 rounded-full flex items-center justify-center transition">
                                        <i class="fas fa-chevron-left text-sm"></i>
                                    </button>
                                    <button type="button" onclick="changeImageSmall(1)"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white w-8 h-8 rounded-full flex items-center justify-center transition">
                                        <i class="fas fa-chevron-right text-sm"></i>
                                    </button>

                                    <!-- Image Counter -->
                                    <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                        <span class="current-image-small">1</span> / <?= count($allImages) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Thumbnail Navigation -->
                            <?php if (count($allImages) > 1): ?>
                                <div class="flex gap-2 mt-2 overflow-x-auto pb-2">
                                    <?php foreach ($allImages as $index => $image): ?>
                                        <img src="<?= base_url('uploads/campaigns/' . $image) ?>"
                                            alt="Thumbnail <?= $index + 1 ?>"
                                            class="thumbnail-small w-12 h-12 object-cover rounded cursor-pointer border-2 <?= $index === 0 ? 'border-primary-600' : 'border-gray-300' ?> hover:border-primary-400 transition flex-shrink-0"
                                            onclick="goToImageSmall(<?= $index ?>)"
                                            data-thumb-index="<?= $index ?>">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Single Image -->
                            <img src="<?= base_url('uploads/campaigns/' . ($campaign['image'] ?? 'default.jpg')) ?>"
                                alt="<?= esc($campaign['title']) ?>"
                                class="w-48 h-32 object-cover rounded-lg">
                        <?php endif; ?>
                    </div>

                    <div class="flex-1">
                        <a href="/campaign/<?= esc($campaign['slug']) ?>" class="block group">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2 group-hover:text-primary-600 transition cursor-pointer">
                                <?= esc($campaign['title']) ?>
                                <i class="fas fa-external-link-alt text-sm ml-1 opacity-0 group-hover:opacity-100 transition"></i>
                            </h2>
                        </a>
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

                <form id="donationForm" action="/donate/process" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                            Metode Pembayaran
                        </label>
                        <!-- Hidden input for payment method -->
                        <input type="hidden" name="payment_method" value="midtrans">

                        <!-- Midtrans Payment Card -->
                        <!-- <div class="payment-method-card border-3 border-primary-600 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-14 h-14 bg-gradient-to-r from-primary-500 to-primary-700 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                        <i class="fas fa-credit-card text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg">Payment Gateway</h3>
                                        <p class="text-sm text-gray-600">Pembayaran Aman & Otomatis</p>
                                    </div>
                                </div>

                                <div class="bg-white/70 rounded-lg p-4 mb-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-3">Metode Pembayaran yang Tersedia:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        <div class="flex items-center text-xs px-3 py-2 bg-blue-50 text-blue-700 rounded-lg border border-blue-200">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            <span>Kartu Kredit</span>
                                        </div>
                                        <div class="flex items-center text-xs px-3 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200">
                                            <i class="fab fa-google-pay mr-2"></i>
                                            <span>GoPay</span>
                                        </div>
                                        <div class="flex items-center text-xs px-3 py-2 bg-orange-50 text-orange-700 rounded-lg border border-orange-200">
                                            <i class="fas fa-shopping-bag mr-2"></i>
                                            <span>ShopeePay</span>
                                        </div>
                                        <div class="flex items-center text-xs px-3 py-2 bg-purple-50 text-purple-700 rounded-lg border border-purple-200">
                                            <i class="fas fa-qrcode mr-2"></i>
                                            <span>QRIS</span>
                                        </div>
                                        <div class="flex items-center text-xs px-3 py-2 bg-gray-50 text-gray-700 rounded-lg border border-gray-200">
                                            <i class="fas fa-university mr-2"></i>
                                            <span>Virtual Account</span>
                                        </div>
                                        <div class="flex items-center text-xs px-3 py-2 bg-red-50 text-red-700 rounded-lg border border-red-200">
                                            <i class="fas fa-store mr-2"></i>
                                            <span>Indomaret</span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div> -->
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
                        <strong>Pembayaran Aman:</strong> Semua transaksi dilindungi dengan enkripsi SSL 256-bit
                    </p>
                    <p>
                        <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                        <strong>Proses Cepat:</strong> Verifikasi otomatis dan notifikasi real-time
                    </p>
                    <p>
                        <i class="fas fa-headset mr-2 text-purple-600"></i>
                        <strong>Customer Support:</strong> Tim support Midtrans siap membantu 24/7
                    </p>
                    <?php if (ENVIRONMENT === 'development' || !$midtransIsProduction): ?>
                        <p class="mt-3 pt-3 border-t border-blue-300">
                            <i class="fas fa-info-circle mr-2 text-orange-600"></i>
                            <strong class="text-orange-700">Mode:</strong>
                            <span class="text-orange-600 font-semibold">
                                <?= $midtransIsProduction ? 'Production' : 'Sandbox (Testing)' ?>
                            </span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Payment Method Card */
    .payment-method-card {
        position: relative;
        transition: all 0.3s ease;
    }

    .payment-method-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
    }

    /* Gallery Styles */
    .campaign-gallery-small {
        position: relative;
    }

    .gallery-image-small {
        transition: opacity 0.3s ease;
    }

    .thumbnail-small {
        transition: all 0.3s ease;
    }

    .thumbnail-small:hover {
        transform: scale(1.05);
    }

    /* Amount Button Active State */
    .amount-btn.active {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
</style>
<script src="<?= base_url('js/campaign-gallery.js') ?>?v=<?= time() ?>"></script>
<script type="text/javascript" src="<?= $midtransSnapUrl ?>" data-client-key="<?= $midtransClientKey ?>"></script>
<script>
    function setAmount(amount) {
        document.getElementById('amount').value = amount;

        // Visual feedback
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.classList.remove('border-primary-600', 'bg-primary-50');
        });
        event.target.classList.add('border-primary-600', 'bg-primary-50');
    }

    // Handle form submission
    document.getElementById('donationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        try {
            // Submit form via AJAX
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server tidak mengembalikan response JSON. Periksa error log.');
            }

            const result = await response.json();

            if (result.success && result.snap_token) {
                // Open Midtrans Snap popup
                window.snap.pay(result.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = '/payment/finish?order_id=' + result.order_id;
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = '/payment/unfinish?order_id=' + result.order_id;
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        window.location.href = '/payment/error?order_id=' + result.order_id;
                    },
                    onClose: function() {
                        console.log('Customer closed the popup without finishing payment');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                });
            } else {
                // Show validation errors if available
                let errorMessage = result.message || 'Gagal membuat pembayaran';
                if (result.errors) {
                    errorMessage += '\n\n' + Object.values(result.errors).join('\n');
                }
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
</script>

<?= $this->endSection() ?>