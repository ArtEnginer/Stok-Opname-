<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Products</h2>
    <p class="mt-1 text-sm text-gray-600">Manage your product inventory</p>
</div>

<!-- Actions -->
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="<?= base_url('/products/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Add Product
        </a>
        <a href="<?= base_url('/products/import') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <i class="fas fa-file-import mr-2"></i> Import Products
        </a>
        <a href="<?= base_url('/products/import-price') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
            <i class="fas fa-tags mr-2"></i> Import Prices
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Search..." class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <select name="category" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= esc($cat['category']) ?>" <?= ($filters['category'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                    <?= esc($cat['category']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?= base_url('/products') ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
            Reset
        </a>
    </form>
</div>

<!-- Products Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Buy Price</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sell Price</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                            No products found. <a href="<?= base_url('/products/create') ?>" class="text-blue-600 hover:underline">Add one now</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= esc($product['code']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-900"><?= esc($product['name']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500"><?= esc($product['category']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500"><?= esc($product['unit']) ?></td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900"><?= number_format($product['stock'], 2) ?></td>
                            <td class="px-4 py-3 text-sm text-right text-gray-500"><?= number_format($product['buy_price'], 0) ?></td>
                            <td class="px-4 py-3 text-sm text-right text-gray-500"><?= number_format($product['sell_price'], 0) ?></td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="<?= base_url('/products/edit/' . $product['id']) ?>" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/products/delete/' . $product['id']) ?>"
                                    onclick="return confirm('Are you sure?')"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (!empty($products)): ?>
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-sm text-gray-600">
            Showing <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?>
            to <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?>
            of <?= number_format($pager->getTotal()) ?> products
        </div>

        <div class="flex items-center gap-2">
            <!-- Items per page -->
            <form method="GET" class="flex items-center gap-2">
                <?php if (!empty($filters['search'])): ?>
                    <input type="hidden" name="search" value="<?= esc($filters['search']) ?>">
                <?php endif; ?>
                <?php if (!empty($filters['category'])): ?>
                    <input type="hidden" name="category" value="<?= esc($filters['category']) ?>">
                <?php endif; ?>
                <label class="text-sm text-gray-600">Per page:</label>
                <select name="per_page" onchange="this.form.submit()" class="text-sm rounded-md border-gray-300">
                    <option value="25" <?= ($filters['per_page'] ?? 50) == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($filters['per_page'] ?? 50) == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($filters['per_page'] ?? 50) == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= ($filters['per_page'] ?? 50) == 200 ? 'selected' : '' ?>>200</option>
                </select>
            </form>

            <!-- Pagination links -->
            <div class="flex gap-1">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>