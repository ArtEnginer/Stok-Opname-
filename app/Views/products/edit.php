<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Edit Product</h2>
</div>

<div class="bg-white shadow-md rounded-lg p-6 max-w-3xl">
    <?php if (session()->has('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc list-inside">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/products/update/' . $product['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Product Code *</label>
                <input type="text" id="code" name="code" value="<?= esc(old('code', $product['code'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="plu" class="block text-sm font-medium text-gray-700 mb-2">PLU</label>
                <input type="text" id="plu" name="plu" value="<?= esc(old('plu', $product['plu'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
            <input type="text" id="name" name="name" value="<?= esc(old('name', $product['name'])) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                <input type="text" id="unit" name="unit" value="<?= esc(old('unit', $product['unit'])) ?>" placeholder="pcs, box, kg..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" id="category" name="category" value="<?= esc(old('category', $product['category'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <input type="text" id="department" name="department" value="<?= esc(old('department', $product['department'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="buy_price" class="block text-sm font-medium text-gray-700 mb-2">Buy Price</label>
                <input type="number" step="0.01" id="buy_price" name="buy_price" value="<?= esc(old('buy_price', $product['buy_price'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="sell_price" class="block text-sm font-medium text-gray-700 mb-2">Sell Price</label>
                <input type="number" step="0.01" id="sell_price" name="sell_price" value="<?= esc(old('sell_price', $product['sell_price'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                <input type="number" step="0.01" id="stock" name="stock" value="<?= esc(old('stock', $product['stock'])) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Current system stock</p>
            </div>
        </div>

        <div class="mb-6">
            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
            <input type="text" id="supplier" name="supplier" value="<?= esc(old('supplier', $product['supplier'])) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="flex justify-end gap-2">
            <a href="<?= base_url('/products') ?>" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Update Product
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>