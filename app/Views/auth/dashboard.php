<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
    <p class="mt-1 text-sm text-gray-600">Welcome back, <?= esc($user->username) ?>!</p>
</div>

<!-- User Info Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-xl font-semibold text-gray-900 mb-4">
        <i class="fas fa-user-circle mr-2"></i>Your Profile
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Username</label>
            <p class="mt-1 text-lg text-gray-900"><?= esc($user->username) ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Email</label>
            <p class="mt-1 text-lg text-gray-900"><?= esc($user->email) ?></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Role</label>
            <p class="mt-1">
                <?php if ($isAdmin): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-crown mr-2"></i>Administrator
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-user mr-2"></i>User
                    </span>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Last Login</label>
            <p class="mt-1 text-lg text-gray-900">
                <?= $user->last_active ? date('d M Y H:i', strtotime($user->last_active)) : 'Never' ?>
            </p>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Stock Opname -->
    <a href="<?= base_url('stock-opname') ?>" class="block bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-900">Stock Opname</h4>
                <p class="text-sm text-gray-600 mt-1">Manage stock counting</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clipboard-list text-2xl text-indigo-600"></i>
            </div>
        </div>
    </a>

    <?php if ($isAdmin): ?>
        <!-- Products -->
        <a href="<?= base_url('products') ?>" class="block bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Products</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage products</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-green-600"></i>
                </div>
            </div>
        </a>

        <!-- Locations -->
        <a href="<?= base_url('admin/location') ?>" class="block bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Locations</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage locations/racks</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-2xl text-yellow-600"></i>
                </div>
            </div>
        </a>

        <!-- Transactions -->
        <a href="<?= base_url('transactions') ?>" class="block bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Transactions</h4>
                    <p class="text-sm text-gray-600 mt-1">View transactions</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-2xl text-red-600"></i>
                </div>
            </div>
        </a>
    <?php endif; ?>
</div>

<!-- Permissions Info (Debug) -->
<?php if (ENVIRONMENT === 'development'): ?>
    <div class="mt-6 bg-gray-100 rounded-lg p-4">
        <h4 class="font-semibold text-gray-700 mb-2">Permissions (Dev Mode)</h4>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
            <?php
            $permissions = [
                'admin.access',
                'admin.settings',
                'users.manage',
                'locations.manage',
                'products.manage',
                'stockopname.create',
                'stockopname.edit',
                'stockopname.close',
                'transactions.manage',
            ];
            foreach ($permissions as $permission):
                $hasPermission = $user->can($permission);
            ?>
                <div class="flex items-center">
                    <i class="fas fa-<?= $hasPermission ? 'check-circle text-green-600' : 'times-circle text-red-600' ?> mr-2"></i>
                    <span class="<?= $hasPermission ? 'text-gray-700' : 'text-gray-400' ?>">
                        <?= $permission ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>