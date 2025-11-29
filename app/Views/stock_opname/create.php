<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Create New Stock Opname Session</h2>
    <p class="mt-1 text-sm text-gray-600">Start a new stock counting session</p>
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

    <form action="<?= base_url('/stock-opname/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-4">
            <label for="session_code" class="block text-sm font-medium text-gray-700 mb-2">Session Code *</label>
            <input type="text"
                id="session_code"
                name="session_code"
                value="<?= esc(old('session_code', $suggestedCode)) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required>
            <p class="mt-1 text-sm text-gray-500">Unique identifier for this session</p>
        </div>

        <div class="mb-4">
            <label for="session_date" class="block text-sm font-medium text-gray-700 mb-2">Session Date *</label>
            <input type="date"
                id="session_date"
                name="session_date"
                value="<?= esc(old('session_date', date('Y-m-d'))) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required>
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea id="notes"
                name="notes"
                rows="3"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= esc(old('notes')) ?></textarea>
            <p class="mt-1 text-sm text-gray-500">Optional notes about this session</p>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> When you create this session, the system will automatically calculate baseline stock for all products based on:
                    </p>
                    <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                        <li>Previous SO results (if counted) + mutations</li>
                        <li>System stock (if not counted) + mutations</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="<?= base_url('/stock-opname') ?>" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Create Session
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>