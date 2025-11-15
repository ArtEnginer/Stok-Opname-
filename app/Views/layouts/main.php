<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Platform Donasi Online') ?></title>
    <meta name="description" content="<?= esc($metaDescription ?? 'Platform donasi online terpercaya') ?>">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Campaign Gallery CSS -->
    <link rel="stylesheet" href="<?= base_url('css/campaign-gallery.css') ?>?v=<?= time() ?>">

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.6s ease-out;
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }

        /* Line clamp utilities */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Image loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #16a34a;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #15803d;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50" x-data="{ appSettings: null, loading: true }" x-init="
    fetch('/api/settings/public')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                appSettings = data.data;
            }
            loading = false;
        })
        .catch(() => loading = false);
">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false, scrolled: false }"
        @scroll.window="scrolled = window.pageYOffset > 10">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4 transition-all"
                :class="scrolled ? 'py-3' : 'py-4'">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 group">
                    <template x-if="appSettings?.app_logo">
                        <img :src="`${appSettings.app_logo}`"
                            :alt="appSettings?.app_name || 'DonasiKita'"
                            class="h-10 w-auto object-contain">
                    </template>
                    <template x-if="!appSettings?.app_logo">
                        <i class="fas fa-hands-holding-heart text-3xl text-primary-600 group-hover:scale-110 transition-transform"></i>
                    </template>
                    <span class="text-2xl font-bold text-primary-600 group-hover:text-primary-700 transition"
                        x-text="appSettings?.app_name || 'DonasiKita'"></span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-primary-600 transition font-medium relative group">
                        Beranda
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all"></span>
                    </a>
                    <a href="/campaign" class="text-gray-700 hover:text-primary-600 transition font-medium relative group">
                        Campaign
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all"></span>
                    </a>
                    <a href="/about" class="text-gray-700 hover:text-primary-600 transition font-medium relative group">
                        Tentang
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all"></span>
                    </a>
                    <a href="/contact" class="text-gray-700 hover:text-primary-600 transition font-medium relative group">
                        Kontak
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all"></span>
                    </a>
                    <a href="/admin" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition transform hover:scale-105 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded p-2">
                    <i class="fas text-2xl" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="md:hidden pb-4 border-t border-gray-100"
                x-cloak>
                <div class="flex flex-col space-y-3 pt-4">
                    <a href="/" class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition py-2 px-3 rounded-lg">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="/campaign" class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition py-2 px-3 rounded-lg">
                        <i class="fas fa-hand-holding-heart mr-2"></i>Campaign
                    </a>
                    <a href="/about" class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition py-2 px-3 rounded-lg">
                        <i class="fas fa-info-circle mr-2"></i>Tentang
                    </a>
                    <a href="/contact" class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition py-2 px-3 rounded-lg">
                        <i class="fas fa-envelope mr-2"></i>Kontak
                    </a>
                    <a href="/admin" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-center shadow-md">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-xl font-bold mb-4">
                        <template x-if="appSettings?.app_logo">
                            <img :src="`${appSettings.app_logo}`"
                                :alt="appSettings?.app_name || 'DonasiKita'"
                                class="h-10 w-auto object-contain mb-2 brightness-0 invert">
                        </template>
                        <template x-if="!appSettings?.app_logo">
                            <span>
                                <i class="fas fa-hands-holding-heart mr-2"></i>
                                <span x-text="appSettings?.app_name || 'DonasiKita'"></span>
                            </span>
                        </template>
                    </h3>
                    <p class="text-gray-400" x-text="appSettings?.app_description || 'Platform donasi online terpercaya untuk membantu sesama yang membutuhkan.'">
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-chevron-right mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Beranda
                            </a></li>
                        <li><a href="/campaign" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-chevron-right mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Campaign
                            </a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-chevron-right mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Tentang Kami
                            </a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-chevron-right mr-2 text-xs group-hover:translate-x-1 transition-transform"></i>Kontak
                            </a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kategori</h4>
                    <ul class="space-y-2">
                        <li><a href="/campaign?category=kesehatan" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-heart mr-2 text-sm group-hover:scale-110 transition-transform"></i>Kesehatan
                            </a></li>
                        <li><a href="/campaign?category=pendidikan" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-graduation-cap mr-2 text-sm group-hover:scale-110 transition-transform"></i>Pendidikan
                            </a></li>
                        <li><a href="/campaign?category=bencana-alam" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-house-damage mr-2 text-sm group-hover:scale-110 transition-transform"></i>Bencana Alam
                            </a></li>
                        <li><a href="/campaign?category=kemanusiaan" class="text-gray-400 hover:text-white transition flex items-center group">
                                <i class="fas fa-hands-helping mr-2 text-sm group-hover:scale-110 transition-transform"></i>Kemanusiaan
                            </a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-primary-500"></i>
                            <span x-text="appSettings?.app_email || 'info@donasikita.com'"></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone mt-1 mr-3 text-primary-500"></i>
                            <span x-text="appSettings?.app_phone || '+62 812-3456-7890'"></span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-500"></i>
                            <span x-text="appSettings?.app_address || 'Jakarta, Indonesia'"></span>
                        </li>
                    </ul>
                    <div class="flex space-x-3 mt-6">
                        <a :href="appSettings?.social_facebook || '#'"
                            x-show="appSettings?.social_facebook"
                            class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a :href="appSettings?.social_twitter || '#'"
                            x-show="appSettings?.social_twitter"
                            class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a :href="appSettings?.social_instagram || '#'"
                            x-show="appSettings?.social_instagram"
                            class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a :href="appSettings?.social_youtube || '#'"
                            x-show="appSettings?.social_youtube"
                            class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a :href="appSettings?.social_linkedin || '#'"
                            x-show="appSettings?.social_linkedin"
                            class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-all transform hover:scale-110">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> <span x-text="appSettings?.app_name || 'DonasiKita'"></span>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        x-show="scrolled"
        x-transition
        class="fixed bottom-8 right-8 bg-primary-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-primary-700 transition-all transform hover:scale-110 z-40"
        x-cloak>
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-auto-hide');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // Lazy load images
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('skeleton');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        });
    </script>
</body>

</html>