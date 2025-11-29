<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-900">Stock Opname Sessions</h2>
    <p class="mt-1 text-sm text-gray-600">Manage your stock opname sessions</p>
</div>

<!-- Actions -->
<div class="mb-6 flex justify-between items-center">
    <?php if (auth()->user() && auth()->user()->can('stockopname.create')): ?>
        <a href="<?= base_url('/stock-opname/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> New Stock Opname
        </a>
    <?php else: ?>
        <div></div>
    <?php endif; ?>

    <!-- Filters -->
    <form method="GET" class="flex gap-2">
        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="open" <?= ($filters['status'] ?? '') === 'open' ? 'selected' : '' ?>>Open</option>
            <option value="closed" <?= ($filters['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
        </select>
        <input type="date" name="date_from" value="<?= esc($filters['date_from'] ?? '') ?>" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="From Date">
        <input type="date" name="date_to" value="<?= esc($filters['date_to'] ?? '') ?>" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="To Date">
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?= base_url('/stock-opname') ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
            Reset
        </a>
    </form>
</div>

<!-- Sessions Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Closed At</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($sessions)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No sessions found. <a href="<?= base_url('/stock-opname/create') ?>" class="text-blue-600 hover:underline">Create one now</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($sessions as $session): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="<?= base_url('/stock-opname/' . $session['id']) ?>" class="text-blue-600 hover:underline font-medium">
                                <?= esc($session['session_code']) ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('d M Y', strtotime($session['session_date'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($session['status'] === 'open'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Open
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Closed
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= esc($session['notes'] ?: '-') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $session['closed_at'] ? date('d M Y H:i', strtotime($session['closed_at'])) : '-' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?= base_url('/stock-opname/' . $session['id']) ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <?php if ($session['status'] === 'open'): ?>
                                <?php if (auth()->user() && auth()->user()->can('stockopname.close')): ?>
                                    <a href="<?= base_url('/stock-opname/' . $session['id'] . '/close') ?>"
                                        onclick="return confirm('Are you sure you want to close this session? This will update the system stock.')"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-lock"></i> Close
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?= base_url('/stock-opname/' . $session['id'] . '/export') ?>" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-download"></i> Export
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>