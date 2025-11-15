<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-16" x-data="{
    aboutSettings: null,
    loading: true
}" x-init="
    fetch('/api/settings/public')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                aboutSettings = data.data;
            }
            loading = false;
        })
        .catch(() => loading = false);
">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">
                Tentang <span x-text="aboutSettings?.app_name || 'Platform Donasi'"></span>
            </h1>

            <!-- Organization Description -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8" x-show="!loading">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Siapa Kami?</h2>
                <div class="text-gray-600 whitespace-pre-line" x-text="aboutSettings?.about_organization || 'Platform donasi online yang memudahkan Anda untuk membantu sesama yang membutuhkan.'"></div>
            </div>

            <!-- Vision & Mission -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8" x-show="!loading">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Visi & Misi</h2>
                <div class="space-y-6">
                    <!-- Vision -->
                    <div>
                        <h3 class="text-xl font-semibold text-primary-600 mb-3 flex items-center">
                            <i class="fas fa-bullseye mr-2"></i>Visi
                        </h3>
                        <p class="text-gray-600 pl-8" x-text="aboutSettings?.about_vision || 'Menjadi platform donasi online terdepan yang menghubungkan kebaikan dengan mereka yang membutuhkan.'"></p>
                    </div>

                    <!-- Mission -->
                    <div>
                        <h3 class="text-xl font-semibold text-primary-600 mb-3 flex items-center">
                            <i class="fas fa-list-check mr-2"></i>Misi
                        </h3>
                        <template x-if="aboutSettings?.about_mission">
                            <ul class="list-none text-gray-600 space-y-2 pl-8">
                                <template x-for="(mission, index) in (typeof aboutSettings.about_mission === 'string' ? JSON.parse(aboutSettings.about_mission) : aboutSettings.about_mission)" :key="index">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-primary-600 mr-3 mt-1"></i>
                                        <span x-text="mission"></span>
                                    </li>
                                </template>
                            </ul>
                        </template>
                        <template x-if="!aboutSettings?.about_mission">
                            <ul class="list-none text-gray-600 space-y-2 pl-8">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary-600 mr-3 mt-1"></i>
                                    <span>Menyediakan platform donasi yang aman dan transparan</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary-600 mr-3 mt-1"></i>
                                    <span>Memudahkan akses bantuan bagi yang membutuhkan</span>
                                </li>
                            </ul>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Values/Why Choose Us -->
            <div class="bg-white rounded-xl shadow-lg p-8" x-show="!loading">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Kenapa Memilih <span x-text="aboutSettings?.app_name || 'Kami'"></span>?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-if="aboutSettings?.about_values">
                        <template x-for="(value, index) in (typeof aboutSettings.about_values === 'string' ? JSON.parse(aboutSettings.about_values) : aboutSettings.about_values)" :key="index">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas text-primary-600 text-xl" :class="value.icon || 'fa-check'"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-2" x-text="value.title"></h3>
                                    <p class="text-gray-600 text-sm" x-text="value.description"></p>
                                </div>
                            </div>
                        </template>
                    </template>
                    <template x-if="!aboutSettings?.about_values">
                        <template x-for="value in [
                            {title: 'Aman & Terpercaya', description: 'Sistem keamanan terjamin dan verifikasi ketat', icon: 'fa-shield-alt'},
                            {title: 'Transparan', description: 'Lacak kemana donasi Anda disalurkan', icon: 'fa-eye'},
                            {title: 'Cepat & Mudah', description: 'Proses donasi hanya dalam beberapa klik', icon: 'fa-bolt'},
                            {title: 'Berdampak', description: 'Setiap donasi membuat perbedaan nyata', icon: 'fa-heart'}
                        ]">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas text-primary-600 text-xl" :class="value.icon"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-2" x-text="value.title"></h3>
                                    <p class="text-gray-600 text-sm" x-text="value.description"></p>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-primary-600"></i>
                <p class="text-gray-600 mt-4">Memuat informasi...</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>