<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?> - DonasiKita</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
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

<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-gray-900 text-white transition-all duration-300 ease-in-out flex-shrink-0">
            <div class="p-4 flex items-center justify-between">
                <h1 :class="!sidebarOpen && 'hidden'" class="text-2xl font-bold">
                    <i class="fas fa-hands-holding-heart mr-2"></i>
                    <span>DonasiKita</span>
                </h1>
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-white hover:text-primary-400 transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <nav class="mt-8">
                <a href="/admin/dashboard"
                    class="flex items-center px-6 py-3 hover:bg-gray-800 transition <?= uri_string() == 'admin/dashboard' ? 'bg-gray-800 border-l-4 border-primary-500' : '' ?>">
                    <i class="fas fa-chart-line w-6"></i>
                    <span :class="!sidebarOpen && 'hidden'" class="ml-3">Dashboard</span>
                </a>
                <a href="/admin/campaigns"
                    class="flex items-center px-6 py-3 hover:bg-gray-800 transition <?= strpos(uri_string(), 'admin/campaigns') !== false ? 'bg-gray-800 border-l-4 border-primary-500' : '' ?>">
                    <i class="fas fa-bullhorn w-6"></i>
                    <span :class="!sidebarOpen && 'hidden'" class="ml-3">Campaign</span>
                </a>
                <a href="/admin/donations"
                    class="flex items-center px-6 py-3 hover:bg-gray-800 transition <?= strpos(uri_string(), 'admin/donations') !== false ? 'bg-gray-800 border-l-4 border-primary-500' : '' ?>">
                    <i class="fas fa-hand-holding-heart w-6"></i>
                    <span :class="!sidebarOpen && 'hidden'" class="ml-3">Donasi</span>
                </a>
                <a href="/"
                    target="_blank"
                    class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
                    <i class="fas fa-external-link-alt w-6"></i>
                    <span :class="!sidebarOpen && 'hidden'" class="ml-3">Lihat Website</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <?= esc($title ?? 'Admin Panel') ?>
                    </h2>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600">
                            <i class="fas fa-user-circle mr-2"></i>
                            Admin
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php if (session()->has('success')): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 alert-auto-hide">
                        <div class="flex">
                            <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                            <p class="text-green-700"><?= session('success') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 alert-auto-hide">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-1"></i>
                            <p class="text-red-700"><?= session('error') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script>
        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert-auto-hide').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>

</html>