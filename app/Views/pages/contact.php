<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-16" x-data="{
    contactSettings: null,
    loading: true,
    formData: {
        name: '',
        email: '',
        subject: '',
        message: ''
    },
    submitForm() {
        alert('Form submission feature will be implemented soon!');
    }
}" x-init="
    fetch('/api/settings/public')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                contactSettings = data.data;
            }
            loading = false;
        })
        .catch(() => loading = false);
">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Hubungi Kami</h1>

            <!-- Contact Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12" x-show="!loading">
                <!-- Email -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Email</h3>
                    <a :href="'mailto:' + (contactSettings?.app_email || 'info@donasi.com')"
                        class="text-gray-600 hover:text-primary-600 transition"
                        x-text="contactSettings?.app_email || 'info@donasi.com'"></a>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Telepon</h3>
                    <a :href="'tel:' + (contactSettings?.app_phone || '')"
                        class="text-gray-600 hover:text-primary-600 transition"
                        x-text="contactSettings?.app_phone || '-'"></a>
                </div>

                <!-- WhatsApp -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1"
                    x-show="contactSettings?.contact_whatsapp">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-whatsapp text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">WhatsApp</h3>
                    <a :href="'https://wa.me/' + contactSettings?.contact_whatsapp"
                        target="_blank"
                        class="text-gray-600 hover:text-primary-600 transition"
                        x-text="contactSettings?.contact_whatsapp || '-'"></a>
                </div>

                <!-- Address (full width if no WhatsApp) -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1"
                    :class="contactSettings?.contact_whatsapp ? '' : 'md:col-span-1'">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Alamat</h3>
                    <p class="text-gray-600" x-text="contactSettings?.app_address || 'Indonesia'"></p>
                </div>
            </div>

            <!-- Working Hours -->
            <div class="bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl p-6 mb-12 text-center"
                x-show="!loading && contactSettings?.contact_working_hours">
                <div class="flex items-center justify-center">
                    <i class="fas fa-clock text-primary-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-gray-800">Jam Operasional</h3>
                        <p class="text-gray-600" x-text="contactSettings?.contact_working_hours"></p>
                    </div>
                </div>
            </div>

            <!-- Google Maps -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8"
                x-show="!loading && contactSettings?.contact_map_embed">
                <div class="aspect-video" x-html="contactSettings?.contact_map_embed"></div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-lg p-8" x-show="!loading">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text"
                                x-model="formData.name"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none transition"
                                placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email"
                                x-model="formData.email"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none transition"
                                placeholder="email@example.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Subjek <span class="text-red-500">*</span></label>
                        <input type="text"
                            x-model="formData.subject"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none transition"
                            placeholder="Subjek pesan">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Pesan <span class="text-red-500">*</span></label>
                        <textarea rows="6"
                            x-model="formData.message"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none transition"
                            placeholder="Tulis pesan Anda..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-700 transition transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Social Media -->
            <div class="mt-8 text-center" x-show="!loading">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Ikuti Kami di Media Sosial</h3>
                <div class="flex justify-center gap-4 flex-wrap">
                    <a :href="contactSettings?.social_facebook || '#'"
                        x-show="contactSettings?.social_facebook"
                        target="_blank"
                        class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition transform hover:scale-110 shadow-lg">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a :href="contactSettings?.social_twitter || '#'"
                        x-show="contactSettings?.social_twitter"
                        target="_blank"
                        class="w-12 h-12 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition transform hover:scale-110 shadow-lg">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a :href="contactSettings?.social_instagram || '#'"
                        x-show="contactSettings?.social_instagram"
                        target="_blank"
                        class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition transform hover:scale-110 shadow-lg">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a :href="contactSettings?.social_youtube || '#'"
                        x-show="contactSettings?.social_youtube"
                        target="_blank"
                        class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition transform hover:scale-110 shadow-lg">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a :href="contactSettings?.social_linkedin || '#'"
                        x-show="contactSettings?.social_linkedin"
                        target="_blank"
                        class="w-12 h-12 bg-blue-700 text-white rounded-full flex items-center justify-center hover:bg-blue-800 transition transform hover:scale-110 shadow-lg">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-primary-600"></i>
                <p class="text-gray-600 mt-4">Memuat informasi kontak...</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>