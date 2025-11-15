<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div x-data="settingsManager()">
    <!-- Alert Container -->
    <div id="alert-container" class="mb-6"></div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto" aria-label="Tabs">
                <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-info-circle mr-2"></i>
                    General
                </button>
                <button @click="activeTab = 'payment'"
                    :class="activeTab === 'payment' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-credit-card mr-2"></i>
                    Payment Gateway
                </button>
                <button @click="activeTab = 'social'"
                    :class="activeTab === 'social' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-share-alt mr-2"></i>
                    Social Media
                </button>
                <button @click="activeTab = 'seo'"
                    :class="activeTab === 'seo' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-search mr-2"></i>
                    SEO
                </button>
                <button @click="activeTab = 'email'"
                    :class="activeTab === 'email' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-envelope mr-2"></i>
                    Email
                </button>
                <button @click="activeTab = 'system'"
                    :class="activeTab === 'system' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-server mr-2"></i>
                    System
                </button>
            </nav>
        </div>
    </div>

    <!-- General Settings -->
    <div x-show="activeTab === 'general'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                Informasi Umum
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                    <input type="text" x-model="settings.app_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Nama aplikasi yang ditampilkan</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea x-model="settings.app_description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    <p class="mt-1 text-sm text-gray-500">Deskripsi singkat aplikasi</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Kontak</label>
                    <input type="email" x-model="settings.app_email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" x-model="settings.app_phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea x-model="settings.app_address" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo Aplikasi</label>
                    <input type="file" id="app_logo" accept="image/*" @change="handleLogoChange"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="mt-1 text-sm text-gray-500">Format: PNG, JPG, SVG. Ukuran maksimal 2MB</p>
                    <div id="logo_preview" class="mt-2"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <input type="file" id="app_favicon" accept="image/*" @change="handleFaviconChange"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="mt-1 text-sm text-gray-500">Format: ICO, PNG. Ukuran: 16x16 atau 32x32px</p>
                    <div id="favicon_preview" class="mt-2"></div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="saveGeneralSettings()" :disabled="loading"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Settings -->
    <div x-show="activeTab === 'payment'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-credit-card text-primary-600 mr-2"></i>
                    Midtrans Payment Gateway
                </h3>
                <span x-show="settings.midtrans_is_production == '1'"
                    class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    Production
                </span>
                <span x-show="settings.midtrans_is_production != '1'"
                    class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    Sandbox
                </span>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                    <div>
                        <p class="text-sm text-blue-700">
                            <strong>Catatan:</strong> Dapatkan API keys Anda dari
                            <a href="https://dashboard.midtrans.com" target="_blank" class="underline">Midtrans Dashboard</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Server Key</label>
                    <input type="text" x-model="settings.midtrans_server_key" placeholder="Mid-server-xxxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm">
                    <p class="mt-1 text-sm text-gray-500">Format: Mid-server-xxxxx</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Key</label>
                    <input type="text" x-model="settings.midtrans_client_key" placeholder="Mid-client-xxxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm">
                    <p class="mt-1 text-sm text-gray-500">Format: Mid-client-xxxxx</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID (Opsional)</label>
                    <input type="text" x-model="settings.midtrans_merchant_id" placeholder="G123456789"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div class="border-t pt-4">
                    <label class="flex items-center">
                        <input type="checkbox" x-model="settings.midtrans_is_production" true-value="1" false-value="0"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Gunakan Mode Production</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500 ml-6">⚠️ Nonaktifkan untuk mode Sandbox/Testing</p>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" x-model="settings.midtrans_is_sanitized" true-value="1" false-value="0"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Enable Sanitization</span>
                    </label>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" x-model="settings.midtrans_is_3ds" true-value="1" false-value="0"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Enable 3D Secure</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="savePaymentSettings()" :disabled="loading"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Pengaturan'"></span>
                </button>
                <button @click="testMidtransConnection()" :disabled="loading"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                    <i class="fas fa-plug mr-2"></i>
                    Test Koneksi
                </button>
            </div>
        </div>
    </div>

    <!-- Social Media Settings -->
    <div x-show="activeTab === 'social'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-share-alt text-primary-600 mr-2"></i>
                Social Media Links
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                    </label>
                    <input type="url" x-model="settings.social_facebook" placeholder="https://facebook.com/yourpage"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter / X
                    </label>
                    <input type="url" x-model="settings.social_twitter" placeholder="https://twitter.com/yourhandle"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram
                    </label>
                    <input type="url" x-model="settings.social_instagram" placeholder="https://instagram.com/yourhandle"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn
                    </label>
                    <input type="url" x-model="settings.social_linkedin" placeholder="https://linkedin.com/company/yourcompany"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-youtube text-red-600 mr-2"></i>YouTube
                    </label>
                    <input type="url" x-model="settings.social_youtube" placeholder="https://youtube.com/c/yourchannel"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            <div class="mt-6">
                <button @click="saveSocialSettings()" :disabled="loading"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Social Links'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- SEO Settings -->
    <div x-show="activeTab === 'seo'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-search text-primary-600 mr-2"></i>
                SEO Settings
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" x-model="settings.seo_meta_title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Default meta title untuk halaman</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea x-model="settings.seo_meta_description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    <p class="mt-1 text-sm text-gray-500">Default meta description untuk halaman</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" x-model="settings.seo_meta_keywords"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Pisahkan dengan koma</p>
                </div>
            </div>

            <div class="mt-6">
                <button @click="saveSEOSettings()" :disabled="loading"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan SEO Settings'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Email Settings -->
    <div x-show="activeTab === 'email'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-envelope text-primary-600 mr-2"></i>
                Email Configuration
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" x-model="settings.email_from_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Nama pengirim untuk email keluar</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                    <input type="email" x-model="settings.email_from_address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-sm text-gray-500">Alamat email pengirim</p>
                </div>
            </div>

            <div class="mt-6">
                <button @click="saveEmailSettings()" :disabled="loading"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Email Settings'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div x-show="activeTab === 'system'" class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-server text-primary-600 mr-2"></i>
                System Settings
            </h3>

            <div class="space-y-6">
                <div class="border-b pb-4">
                    <label class="flex items-center">
                        <input type="checkbox" x-model="settings.maintenance_mode" true-value="1" false-value="0"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-3">
                            <span class="text-sm font-medium text-gray-700">Enable Maintenance Mode</span>
                            <p class="text-sm text-gray-500">⚠️ Saat aktif, website akan menampilkan halaman maintenance</p>
                        </span>
                    </label>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" x-model="settings.enable_registration" true-value="1" false-value="0"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-3">
                            <span class="text-sm font-medium text-gray-700">Allow User Registration</span>
                            <p class="text-sm text-gray-500">Kontrol apakah user baru dapat mendaftar</p>
                        </span>
                    </label>
                </div>
            </div>

            <div class="mt-6">
                <button @click="saveSystemSettings()" :disabled="loading"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan System Settings'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center">
            <i class="fas fa-spinner fa-spin text-2xl text-primary-600 mr-3"></i>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>
</div>

<script>
    function settingsManager() {
        return {
            activeTab: 'general',
            loading: false,
            settings: {
                app_name: '',
                app_description: '',
                app_email: '',
                app_phone: '',
                app_address: '',
                midtrans_server_key: '',
                midtrans_client_key: '',
                midtrans_merchant_id: '',
                midtrans_is_production: '0',
                midtrans_is_sanitized: '1',
                midtrans_is_3ds: '1',
                social_facebook: '',
                social_twitter: '',
                social_instagram: '',
                social_linkedin: '',
                social_youtube: '',
                seo_meta_title: '',
                seo_meta_description: '',
                seo_meta_keywords: '',
                email_from_name: '',
                email_from_address: '',
                maintenance_mode: '0',
                enable_registration: '1'
            },

            init() {
                this.loadSettings();
            },

            async loadSettings() {
                this.loading = true;
                try {
                    const response = await fetch('/admin/settings/grouped');
                    const data = await response.json();

                    if (data.status === 'success') {
                        // Populate settings from grouped data
                        Object.keys(data.data).forEach(group => {
                            Object.keys(data.data[group]).forEach(key => {
                                this.settings[key] = data.data[group][key].value || '';
                            });
                        });

                        // Load logo/favicon preview
                        this.loadFilePreview('app_logo', 'logo_preview');
                        this.loadFilePreview('app_favicon', 'favicon_preview');
                    }
                } catch (error) {
                    this.showAlert('error', 'Gagal memuat pengaturan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            loadFilePreview(key, previewId) {
                if (this.settings[key]) {
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        preview.innerHTML = `<img src="/uploads/settings/${this.settings[key]}" alt="Preview" class="max-w-xs rounded border">`;
                    }
                }
            },

            handleLogoChange(event) {
                this.previewFile(event.target.files[0], 'logo_preview');
            },

            handleFaviconChange(event) {
                this.previewFile(event.target.files[0], 'favicon_preview');
            },

            previewFile(file, previewId) {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById(previewId).innerHTML =
                            `<img src="${e.target.result}" alt="Preview" class="max-w-xs rounded border">`;
                    };
                    reader.readAsDataURL(file);
                }
            },

            async saveGeneralSettings() {
                this.loading = true;
                try {
                    const settingsData = [{
                            setting_key: 'app_name',
                            setting_value: this.settings.app_name
                        },
                        {
                            setting_key: 'app_description',
                            setting_value: this.settings.app_description
                        },
                        {
                            setting_key: 'app_email',
                            setting_value: this.settings.app_email
                        },
                        {
                            setting_key: 'app_phone',
                            setting_value: this.settings.app_phone
                        },
                        {
                            setting_key: 'app_address',
                            setting_value: this.settings.app_address
                        }
                    ];

                    await this.updateBatch(settingsData);

                    // Upload files if selected
                    await this.uploadFileIfSelected('app_logo');
                    await this.uploadFileIfSelected('app_favicon');

                    this.showAlert('success', 'Pengaturan umum berhasil disimpan!');
                    await this.loadSettings();
                } catch (error) {
                    this.showAlert('error', 'Gagal menyimpan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async savePaymentSettings() {
                this.loading = true;
                try {
                    const response = await fetch('/admin/settings/payment/midtrans', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            midtrans_server_key: this.settings.midtrans_server_key,
                            midtrans_client_key: this.settings.midtrans_client_key,
                            midtrans_merchant_id: this.settings.midtrans_merchant_id,
                            midtrans_is_production: this.settings.midtrans_is_production,
                            midtrans_is_sanitized: this.settings.midtrans_is_sanitized,
                            midtrans_is_3ds: this.settings.midtrans_is_3ds
                        })
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        this.showAlert('success', 'Pengaturan payment berhasil disimpan!');
                        await this.loadSettings();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    this.showAlert('error', 'Gagal menyimpan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async saveSocialSettings() {
                this.loading = true;
                try {
                    const settingsData = [{
                            setting_key: 'social_facebook',
                            setting_value: this.settings.social_facebook
                        },
                        {
                            setting_key: 'social_twitter',
                            setting_value: this.settings.social_twitter
                        },
                        {
                            setting_key: 'social_instagram',
                            setting_value: this.settings.social_instagram
                        },
                        {
                            setting_key: 'social_linkedin',
                            setting_value: this.settings.social_linkedin
                        },
                        {
                            setting_key: 'social_youtube',
                            setting_value: this.settings.social_youtube
                        }
                    ];

                    await this.updateBatch(settingsData);
                    this.showAlert('success', 'Social media links berhasil disimpan!');
                    await this.loadSettings();
                } catch (error) {
                    this.showAlert('error', 'Gagal menyimpan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async saveSEOSettings() {
                this.loading = true;
                try {
                    const settingsData = [{
                            setting_key: 'seo_meta_title',
                            setting_value: this.settings.seo_meta_title
                        },
                        {
                            setting_key: 'seo_meta_description',
                            setting_value: this.settings.seo_meta_description
                        },
                        {
                            setting_key: 'seo_meta_keywords',
                            setting_value: this.settings.seo_meta_keywords
                        }
                    ];

                    await this.updateBatch(settingsData);
                    this.showAlert('success', 'SEO settings berhasil disimpan!');
                    await this.loadSettings();
                } catch (error) {
                    this.showAlert('error', 'Gagal menyimpan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async saveEmailSettings() {
                this.loading = true;
                try {
                    const settingsData = [{
                            setting_key: 'email_from_name',
                            setting_value: this.settings.email_from_name
                        },
                        {
                            setting_key: 'email_from_address',
                            setting_value: this.settings.email_from_address
                        }
                    ];

                    await this.updateBatch(settingsData);
                    this.showAlert('success', 'Email settings berhasil disimpan!');
                    await this.loadSettings();
                } catch (error) {
                    this.showAlert('error', 'Gagal menyimpan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async saveSystemSettings() {
                this.loading = true;
                try {
                    const settingsData = [{
                            setting_key: 'maintenance_mode',
                            setting_value: this.settings.maintenance_mode
                        },
                        {
                            setting_key: 'enable_registration',
                            setting_value: this.settings.enable_registration
                        }
                    ];

                    await this.updateBatch(settingsData);
                    this.showAlert('success', 'System settings berhasil disimpan!');
                    await this.loadSettings();
                } catch (error) {
                    this.showAlert('error', 'Gagal menyimpan: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async testMidtransConnection() {
                this.loading = true;
                try {
                    const response = await fetch('/admin/midtrans/test-connection', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            server_key: this.settings.midtrans_server_key,
                            client_key: this.settings.midtrans_client_key,
                            is_production: this.settings.midtrans_is_production == '1'
                        })
                    });

                    const data = await response.json();
                    if (data.status === 'success' && data.data.connected) {
                        this.showAlert('success', `✅ Koneksi Midtrans berhasil! (${data.data.environment})`);
                    } else {
                        this.showAlert('error', '❌ Koneksi gagal: ' + (data.error || 'Unknown error'));
                    }
                } catch (error) {
                    this.showAlert('error', 'Test koneksi gagal: ' + error.message);
                } finally {
                    this.loading = false;
                }
            },

            async updateBatch(settings) {
                const response = await fetch('/admin/settings/batch', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        settings
                    })
                });

                const data = await response.json();
                if (data.status !== 'success') {
                    throw new Error(data.message || 'Failed to update settings');
                }
                return data;
            },

            async uploadFileIfSelected(settingKey) {
                const fileInput = document.getElementById(settingKey);
                if (fileInput && fileInput.files && fileInput.files[0]) {
                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);

                    const response = await fetch(`/admin/settings/${settingKey}/upload`, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();
                    if (data.status !== 'success') {
                        throw new Error(data.message || 'Failed to upload file');
                    }
                }
            },

            showAlert(type, message) {
                const container = document.getElementById('alert-container');
                const bgColor = type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500';
                const iconColor = type === 'success' ? 'text-green-500' : 'text-red-500';
                const textColor = type === 'success' ? 'text-green-700' : 'text-red-700';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                container.innerHTML = `
                <div class="${bgColor} border-l-4 p-4 rounded">
                    <div class="flex">
                        <i class="fas ${icon} ${iconColor} mr-3 mt-1"></i>
                        <p class="${textColor}">${message}</p>
                    </div>
                </div>
            `;

                setTimeout(() => {
                    container.innerHTML = '';
                }, 5000);

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }
    }
</script>

<?= $this->endSection() ?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header h1 {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .header p {
        color: #666;
    }

    .tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        background: white;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .tab {
        padding: 10px 20px;
        border: none;
        background: transparent;
        cursor: pointer;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        transition: all 0.3s;
    }

    .tab:hover {
        background: #f0f0f0;
    }

    .tab.active {
        background: #007bff;
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .settings-group {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .settings-group h2 {
        font-size: 18px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .setting-item {
        margin-bottom: 20px;
    }

    .setting-item label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
    }

    .setting-item .description {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }

    .setting-item input[type="text"],
    .setting-item input[type="email"],
    .setting-item input[type="tel"],
    .setting-item textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .setting-item textarea {
        min-height: 80px;
        resize: vertical;
    }

    .setting-item input[type="file"] {
        padding: 5px;
    }

    .setting-item .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .setting-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .file-preview {
        margin-top: 10px;
    }

    .file-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .alert {
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .loading {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .loading.active {
        display: block;
    }

    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #007bff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .help-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        font-size: 11px;
        border-radius: 12px;
        background: #6c757d;
        color: white;
    }

    .badge.badge-success {
        background: #28a745;
    }

    .badge.badge-warning {
        background: #ffc107;
        color: #333;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Settings Management</h1>
            <p>Manage your application settings, payment gateway, and configurations</p>
        </div>

        <div id="alert-container"></div>

        <div class="tabs">
            <button class="tab active" data-tab="general">General</button>
            <button class="tab" data-tab="payment">Payment Gateway</button>
            <button class="tab" data-tab="social">Social Media</button>
            <button class="tab" data-tab="seo">SEO</button>
            <button class="tab" data-tab="email">Email</button>
            <button class="tab" data-tab="system">System</button>
        </div>

        <!-- General Settings -->
        <div class="tab-content active" id="general">
            <div class="settings-group">
                <h2>General Settings</h2>

                <div class="setting-item">
                    <label for="app_name">Application Name</label>
                    <div class="description">Name of your application</div>
                    <input type="text" id="app_name" name="app_name">
                </div>

                <div class="setting-item">
                    <label for="app_description">Description</label>
                    <div class="description">Brief description of your application</div>
                    <textarea id="app_description" name="app_description"></textarea>
                </div>

                <div class="setting-item">
                    <label for="app_email">Contact Email</label>
                    <div class="description">Primary contact email address</div>
                    <input type="email" id="app_email" name="app_email">
                </div>

                <div class="setting-item">
                    <label for="app_phone">Phone Number</label>
                    <div class="description">Contact phone number</div>
                    <input type="tel" id="app_phone" name="app_phone">
                </div>

                <div class="setting-item">
                    <label for="app_address">Address</label>
                    <div class="description">Physical address</div>
                    <textarea id="app_address" name="app_address"></textarea>
                </div>

                <div class="setting-item">
                    <label for="app_logo">Application Logo</label>
                    <div class="description">Upload your application logo (PNG, JPG, SVG)</div>
                    <input type="file" id="app_logo" accept="image/*">
                    <div class="file-preview" id="logo_preview"></div>
                </div>

                <div class="setting-item">
                    <label for="app_favicon">Favicon</label>
                    <div class="description">Upload favicon (ICO, PNG - 16x16 or 32x32)</div>
                    <input type="file" id="app_favicon" accept="image/*">
                    <div class="file-preview" id="favicon_preview"></div>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="saveGeneralSettings()">Save Changes</button>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="tab-content" id="payment">
            <div class="settings-group">
                <h2>Midtrans Payment Gateway
                    <span class="badge" id="midtrans-env-badge">Sandbox</span>
                </h2>

                <div class="alert alert-info">
                    <strong>📝 Note:</strong> Get your API keys from
                    <a href="https://dashboard.midtrans.com" target="_blank">Midtrans Dashboard</a>
                </div>

                <div class="setting-item">
                    <label for="midtrans_server_key">Server Key</label>
                    <div class="description">Midtrans Server Key (starts with Mid-server-)</div>
                    <input type="text" id="midtrans_server_key" name="midtrans_server_key" placeholder="Mid-server-xxxxx">
                </div>

                <div class="setting-item">
                    <label for="midtrans_client_key">Client Key</label>
                    <div class="description">Midtrans Client Key (starts with Mid-client-)</div>
                    <input type="text" id="midtrans_client_key" name="midtrans_client_key" placeholder="Mid-client-xxxxx">
                </div>

                <div class="setting-item">
                    <label for="midtrans_merchant_id">Merchant ID</label>
                    <div class="description">Your Midtrans Merchant ID (optional)</div>
                    <input type="text" id="midtrans_merchant_id" name="midtrans_merchant_id" placeholder="G123456789">
                </div>

                <div class="setting-item">
                    <label>Environment</label>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="midtrans_is_production" name="midtrans_is_production">
                        <label for="midtrans_is_production" style="margin: 0;">Use Production Mode</label>
                    </div>
                    <div class="help-text">⚠️ Uncheck for Sandbox/Testing mode</div>
                </div>

                <div class="setting-item">
                    <label>Security Settings</label>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="midtrans_is_sanitized" name="midtrans_is_sanitized" checked>
                        <label for="midtrans_is_sanitized" style="margin: 0;">Enable Sanitization</label>
                    </div>
                    <div class="checkbox-wrapper" style="margin-top: 10px;">
                        <input type="checkbox" id="midtrans_is_3ds" name="midtrans_is_3ds" checked>
                        <label for="midtrans_is_3ds" style="margin: 0;">Enable 3D Secure</label>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="savePaymentSettings()">Save Payment Settings</button>
                    <button class="btn btn-success" onclick="testMidtransConnection()">Test Connection</button>
                </div>
            </div>
        </div>

        <!-- Social Media Settings -->
        <div class="tab-content" id="social">
            <div class="settings-group">
                <h2>Social Media Links</h2>

                <div class="setting-item">
                    <label for="social_facebook">Facebook</label>
                    <input type="text" id="social_facebook" name="social_facebook" placeholder="https://facebook.com/yourpage">
                </div>

                <div class="setting-item">
                    <label for="social_twitter">Twitter / X</label>
                    <input type="text" id="social_twitter" name="social_twitter" placeholder="https://twitter.com/yourhandle">
                </div>

                <div class="setting-item">
                    <label for="social_instagram">Instagram</label>
                    <input type="text" id="social_instagram" name="social_instagram" placeholder="https://instagram.com/yourhandle">
                </div>

                <div class="setting-item">
                    <label for="social_linkedin">LinkedIn</label>
                    <input type="text" id="social_linkedin" name="social_linkedin" placeholder="https://linkedin.com/company/yourcompany">
                </div>

                <div class="setting-item">
                    <label for="social_youtube">YouTube</label>
                    <input type="text" id="social_youtube" name="social_youtube" placeholder="https://youtube.com/c/yourchannel">
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="saveSocialSettings()">Save Social Links</button>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="tab-content" id="seo">
            <div class="settings-group">
                <h2>SEO Settings</h2>

                <div class="setting-item">
                    <label for="seo_meta_title">Meta Title</label>
                    <div class="description">Default meta title for pages</div>
                    <input type="text" id="seo_meta_title" name="seo_meta_title">
                </div>

                <div class="setting-item">
                    <label for="seo_meta_description">Meta Description</label>
                    <div class="description">Default meta description for pages</div>
                    <textarea id="seo_meta_description" name="seo_meta_description"></textarea>
                </div>

                <div class="setting-item">
                    <label for="seo_meta_keywords">Meta Keywords</label>
                    <div class="description">Comma-separated keywords</div>
                    <input type="text" id="seo_meta_keywords" name="seo_meta_keywords">
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="saveSEOSettings()">Save SEO Settings</button>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="tab-content" id="email">
            <div class="settings-group">
                <h2>Email Configuration</h2>

                <div class="setting-item">
                    <label for="email_from_name">From Name</label>
                    <div class="description">Sender name for outgoing emails</div>
                    <input type="text" id="email_from_name" name="email_from_name">
                </div>

                <div class="setting-item">
                    <label for="email_from_address">From Address</label>
                    <div class="description">Sender email address</div>
                    <input type="email" id="email_from_address" name="email_from_address">
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="saveEmailSettings()">Save Email Settings</button>
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="tab-content" id="system">
            <div class="settings-group">
                <h2>System Settings</h2>

                <div class="setting-item">
                    <label>Maintenance Mode</label>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="maintenance_mode" name="maintenance_mode">
                        <label for="maintenance_mode" style="margin: 0;">Enable Maintenance Mode</label>
                    </div>
                    <div class="help-text">⚠️ When enabled, the site will show a maintenance page to visitors</div>
                </div>

                <div class="setting-item">
                    <label>User Registration</label>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="enable_registration" name="enable_registration" checked>
                        <label for="enable_registration" style="margin: 0;">Allow User Registration</label>
                    </div>
                    <div class="help-text">Controls whether new users can register on the site</div>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="saveSystemSettings()">Save System Settings</button>
                </div>
            </div>
        </div>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    </div>

    <script src="<?= base_url('assets/js/admin-settings.js') ?>"></script>
</body>

</html>