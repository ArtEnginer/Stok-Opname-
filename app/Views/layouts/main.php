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

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
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

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="/" class="text-2xl font-bold text-primary-600">
                    <i class="fas fa-hands-holding-heart mr-2"></i>
                    DonasiKita
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-primary-600 transition">Beranda</a>
                    <a href="/campaign" class="text-gray-700 hover:text-primary-600 transition">Campaign</a>
                    <a href="/about" class="text-gray-700 hover:text-primary-600 transition">Tentang</a>
                    <a href="/contact" class="text-gray-700 hover:text-primary-600 transition">Kontak</a>
                    <a href="/admin" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen"
                x-transition
                class="md:hidden pb-4">
                <div class="flex flex-col space-y-3">
                    <a href="/" class="text-gray-700 hover:text-primary-600 transition py-2">Beranda</a>
                    <a href="/campaign" class="text-gray-700 hover:text-primary-600 transition py-2">Campaign</a>
                    <a href="/about" class="text-gray-700 hover:text-primary-600 transition py-2">Tentang</a>
                    <a href="/contact" class="text-gray-700 hover:text-primary-600 transition py-2">Kontak</a>
                    <a href="/admin" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-center">
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
                        <i class="fas fa-hands-holding-heart mr-2"></i>
                        DonasiKita
                    </h3>
                    <p class="text-gray-400">
                        Platform donasi online terpercaya untuk membantu sesama yang membutuhkan.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="/campaign" class="text-gray-400 hover:text-white transition">Campaign</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kategori</h4>
                    <ul class="space-y-2">
                        <li><a href="/campaign?category=kesehatan" class="text-gray-400 hover:text-white transition">Kesehatan</a></li>
                        <li><a href="/campaign?category=pendidikan" class="text-gray-400 hover:text-white transition">Pendidikan</a></li>
                        <li><a href="/campaign?category=bencana-alam" class="text-gray-400 hover:text-white transition">Bencana Alam</a></li>
                        <li><a href="/campaign?category=kemanusiaan" class="text-gray-400 hover:text-white transition">Kemanusiaan</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> info@donasikita.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +62 812-3456-7890</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram text-xl"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> DonasiKita. All rights reserved.</p>
            </div>
        </div>
    </footer>

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
        });
    </script>
</body>

</html>