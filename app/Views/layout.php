<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Stock Opname') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">Stock Opname System</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="<?= base_url('/dashboard') ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="<?= base_url('/stock-opname') ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-clipboard-list mr-2"></i> Stock Opname
                        </a>
                        <?php if (auth()->user() && auth()->user()->can('products.manage')): ?>
                            <a href="<?= base_url('/products') ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-box mr-2"></i> Products
                            </a>
                        <?php endif; ?>
                        <?php if (auth()->user() && auth()->user()->can('transactions.manage')): ?>
                            <a href="<?= base_url('/transactions') ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-exchange-alt mr-2"></i> Transactions
                            </a>
                        <?php endif; ?>
                        <?php if (auth()->user() && auth()->user()->can('locations.manage')): ?>
                            <a href="<?= base_url('/admin/location') ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-map-marker-alt mr-2"></i> Locations
                            </a>
                        <?php endif; ?>
                        <?php if (auth()->user() && auth()->user()->can('users.manage')): ?>
                            <a href="<?= base_url('/admin/user') ?>" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-users mr-2"></i> Users
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- User Menu -->
                <?php if (auth()->loggedIn()): ?>
                    <div class="flex items-center">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-700 mr-2">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    <?= esc(auth()->user()->username) ?>
                                </span>
                                <?php if (auth()->user()->inGroup('admin')): ?>
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Admin</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">User</span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= base_url('/logout') ?>" class="text-gray-500 hover:text-red-600 inline-flex items-center text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('success') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('error') ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-gray-500 text-sm">
                &copy; <?= date('Y') ?> Stock Opname System. All rights reserved.
            </p>
        </div>
    </footer>

    <?= $this->renderSection('scripts') ?>
</body>

</html>