<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Hubungi Kami</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Email</h3>
                    <p class="text-gray-600">info@donasikita.com</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Telepon</h3>
                    <p class="text-gray-600">+62 812-3456-7890</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-primary-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Alamat</h3>
                    <p class="text-gray-600">Jakarta, Indonesia</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                            <input type="text"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                                placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                                placeholder="email@example.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Subjek</label>
                        <input type="text"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                            placeholder="Subjek pesan">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Pesan</label>
                        <textarea rows="6"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-600 focus:outline-none"
                            placeholder="Tulis pesan Anda..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-primary-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Ikuti Kami</h3>
                <div class="flex justify-center gap-4">
                    <a href="#" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>