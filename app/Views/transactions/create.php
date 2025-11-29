<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Add New Transaction</h2>
</div>

<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
    <?php if (session()->has('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc list-inside">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/transactions/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-4">
            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
            <select id="product_id" name="product_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">Select Product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id'] ?>" <?= old('product_id') == $product['id'] ? 'selected' : '' ?>>
                        <?= esc($product['code']) ?> - <?= esc($product['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select id="type" name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="purchase" <?= old('type') === 'purchase' ? 'selected' : '' ?>>Purchase (In)</option>
                    <option value="sale" <?= old('type') === 'sale' ? 'selected' : '' ?>>Sale (Out)</option>
                </select>
            </div>
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                <input type="date" id="transaction_date" name="transaction_date" value="<?= esc(old('transaction_date', date('Y-m-d'))) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="qty" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input type="number" step="0.01" id="qty" name="qty" value="<?= esc(old('qty')) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                <input type="number" step="0.01" id="price" name="price" value="<?= esc(old('price')) ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="reference_no" class="block text-sm font-medium text-gray-700 mb-2">Reference No</label>
            <input type="text" id="reference_no" name="reference_no" value="<?= esc(old('reference_no')) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Invoice/DO number...">
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea id="notes" name="notes" rows="3"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= esc(old('notes')) ?></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="<?= base_url('/transactions') ?>" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Save Transaction
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>