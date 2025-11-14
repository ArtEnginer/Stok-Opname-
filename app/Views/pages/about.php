<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Tentang DonasiKita</h1>

            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Siapa Kami?</h2>
                <p class="text-gray-600 mb-4">
                    DonasiKita adalah platform donasi online yang memudahkan Anda untuk membantu sesama yang membutuhkan.
                    Kami menyediakan platform yang aman, transparan, dan terpercaya untuk menghubungkan donatur dengan
                    mereka yang membutuhkan bantuan.
                </p>
                <p class="text-gray-600">
                    Dengan DonasiKita, setiap orang dapat membuat campaign untuk berbagai kebutuhan seperti kesehatan,
                    pendidikan, bencana alam, dan banyak lagi. Kami percaya bahwa kebaikan dimulai dari hal-hal kecil
                    dan bersama-sama kita bisa membuat perubahan yang besar.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Visi & Misi</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-semibold text-primary-600 mb-2">Visi</h3>
                        <p class="text-gray-600">
                            Menjadi platform donasi online terdepan yang menghubungkan kebaikan dengan mereka yang membutuhkan.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-primary-600 mb-2">Misi</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Menyediakan platform donasi yang aman dan transparan</li>
                            <li>Memudahkan akses bantuan bagi yang membutuhkan</li>
                            <li>Menumbuhkan budaya berbagi dalam masyarakat</li>
                            <li>Memastikan setiap donasi sampai ke tujuan dengan benar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Kenapa DonasiKita?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-shield-alt text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Aman & Terpercaya</h3>
                            <p class="text-gray-600 text-sm">Sistem keamanan terjamin dan verifikasi ketat</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Transparan</h3>
                            <p class="text-gray-600 text-sm">Lacak kemana donasi Anda disalurkan</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bolt text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Cepat & Mudah</h3>
                            <p class="text-gray-600 text-sm">Proses donasi hanya dalam beberapa klik</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-heart text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Berdampak</h3>
                            <p class="text-gray-600 text-sm">Setiap donasi membuat perbedaan nyata</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>