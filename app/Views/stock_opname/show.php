<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900"><?= esc($session['session_code']) ?></h2>
            <p class="mt-1 text-sm text-gray-600">
                Date: <?= date('d M Y', strtotime($session['session_date'])) ?> |
                Status:
                <?php if ($session['status'] === 'open'): ?>
                    <span class="text-green-600 font-semibold">Open</span>
                <?php else: ?>
                    <span class="text-gray-600 font-semibold">Closed</span>
                <?php endif; ?>
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <?php if ($session['status'] === 'open'): ?>
                <a href="<?= base_url('/stock-opname/' . $session['id'] . '/batch-input') ?>"
                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    <i class="fas fa-layer-group mr-2"></i> Batch Input
                </a>
                <?php if (auth()->user() && auth()->user()->can('stockopname.close')): ?>
                    <a href="<?= base_url('/stock-opname/' . $session['id'] . '/close') ?>"
                        onclick="return confirm('Are you sure you want to close this session? This will update the system stock for all counted items.')"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <i class="fas fa-lock mr-2"></i> Close Session
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <?php if (auth()->user() && auth()->user()->can('stockopname.close')): ?>
                    <a href="<?= base_url('/stock-opname/' . $session['id'] . '/reopen') ?>"
                        onclick="return confirm('Are you sure you want to reopen this session?')"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        <i class="fas fa-unlock mr-2"></i> Reopen Session
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Print & Export - Available for all status -->
            <a href="<?= base_url('/stock-opname/' . $session['id'] . '/print-report') ?>" target="_blank"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-print mr-2"></i> Print
            </a>
            <a href="<?= base_url('/stock-opname/' . $session['id'] . '/export-report') ?>"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <a href="<?= base_url('/stock-opname') ?>" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm text-gray-600">Total Items</div>
        <div class="text-2xl font-bold text-gray-900"><?= number_format($summary['total_items']) ?></div>
        <div class="text-xs text-gray-500 mt-1">termasuk multiple locations</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm text-gray-600">Counted</div>
        <div class="text-2xl font-bold text-green-600"><?= number_format($summary['counted_items']) ?></div>
        <div class="text-xs text-gray-500 mt-1">entries yang sudah dihitung</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm text-gray-600">Not Counted</div>
        <div class="text-2xl font-bold text-orange-600"><?= number_format($summary['uncounted_items']) ?></div>
        <div class="text-xs text-gray-500 mt-1">entries belum dihitung</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm text-gray-600">Net Variance</div>
        <?php
        $netVariance = isset($summary['net_variance']) ? $summary['net_variance'] : 0;
        $variantColor = $netVariance >= 0 ? 'text-green-600' : 'text-red-600';
        ?>
        <div class="text-2xl font-bold <?= $variantColor ?>"><?= number_format($netVariance, 2) ?></div>
        <div class="text-xs text-gray-500 mt-1">selisih bersih (+ lebih / - kurang)</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm text-gray-600">Total Absolute Variance</div>
        <div class="text-2xl font-bold text-purple-600"><?= number_format($summary['total_variance'], 2) ?></div>
        <div class="text-xs text-gray-500 mt-1">total perbedaan (nilai mutlak)</div>
    </div>
</div>

<!-- Baseline Control & Transaction Summary -->
<?php if ($session['status'] === 'open'): ?>
    <?php
    // Fallback untuk backward compatibility
    $isBaselineFrozen = isset($session['is_baseline_frozen']) ? $session['is_baseline_frozen'] : false;
    $baselineFreezeDate = isset($session['baseline_freeze_date']) ? $session['baseline_freeze_date'] : null;
    ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <!-- Baseline Control -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">
                <i class="fas fa-sliders-h mr-2"></i> Baseline Control
            </h4>
            <?php if ($isBaselineFrozen): ?>
                <div class="alert alert-info mb-3 p-3 bg-blue-50 border border-blue-200 rounded">
                    <i class="fas fa-snowflake mr-2 text-blue-600"></i>
                    <strong class="text-blue-800">Baseline Frozen</strong><br>
                    <span class="text-sm text-blue-700">
                        Locked at: <strong><?= $baselineFreezeDate ? date('d M Y', strtotime($baselineFreezeDate)) : '-' ?></strong><br>
                        Baseline will not update with new transactions.
                    </span>
                </div>
                <form method="POST" action="<?= base_url('stock-opname/' . $session['id'] . '/unfreeze-baseline') ?>">
                    <button type="submit" class="w-full px-4 py-2 bg-warning text-white rounded-md hover:bg-yellow-600"
                        onclick="return confirm('Unfreeze baseline? It will update in real-time based on transactions.')">
                        <i class="fas fa-unlock mr-2"></i> Unfreeze Baseline (Use Real-Time)
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <i class="fas fa-sync-alt mr-2 text-yellow-600"></i>
                    <strong class="text-yellow-800">Real-Time Baseline</strong><br>
                    <span class="text-sm text-yellow-700">
                        Baseline is updating automatically based on transactions.<br>
                        Reference date: <strong><?= date('d M Y', strtotime($reference_date)) ?></strong>
                    </span>
                </div>
                <form method="POST" action="<?= base_url('stock-opname/' . $session['id'] . '/freeze-baseline') ?>">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Freeze Baseline at Date:</label>
                    <div class="flex gap-2">
                        <input type="date" name="freeze_date" value="<?= date('Y-m-d') ?>"
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fas fa-lock mr-2"></i> Freeze
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Transaction Summary -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">
                <i class="fas fa-chart-line mr-2"></i> Transaction Activity
            </h4>
            <?php if (empty($mutation_summary)): ?>
                <p class="text-sm text-gray-500 italic">No transactions since session started</p>
            <?php else: ?>
                <div class="overflow-y-auto max-h-48">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-2 py-1 text-right text-xs font-medium text-gray-500">Buy</th>
                                <th class="px-2 py-1 text-right text-xs font-medium text-gray-500">Sell</th>
                                <th class="px-2 py-1 text-right text-xs font-medium text-gray-500">Net</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            $totalBuy = 0;
                            $totalSell = 0;
                            $totalNet = 0;
                            foreach ($mutation_summary as $day):
                                $totalBuy += $day['total_purchase'];
                                $totalSell += $day['total_sale'];
                                $totalNet += $day['net_mutation'];
                            ?>
                                <tr>
                                    <td class="px-2 py-1 text-gray-900"><?= date('d M', strtotime($day['date'])) ?></td>
                                    <td class="px-2 py-1 text-right text-green-600">+<?= number_format($day['total_purchase']) ?></td>
                                    <td class="px-2 py-1 text-right text-red-600">-<?= number_format($day['total_sale']) ?></td>
                                    <td class="px-2 py-1 text-right font-semibold <?= $day['net_mutation'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $day['net_mutation'] >= 0 ? '+' : '' ?><?= number_format($day['net_mutation']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                            <tr>
                                <td class="px-2 py-1 text-gray-900">TOTAL</td>
                                <td class="px-2 py-1 text-right text-green-600">+<?= number_format($totalBuy) ?></td>
                                <td class="px-2 py-1 text-right text-red-600">-<?= number_format($totalSell) ?></td>
                                <td class="px-2 py-1 text-right <?= $totalNet >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $totalNet >= 0 ? '+' : '' ?><?= number_format($totalNet) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Uncounted Locations Section -->
<?php
$totalUncountedLocations = count($uncounted_locations ?? []);
$totalLocationsWithoutItems = count($locations_without_items ?? []);
$totalPendingLocations = $totalUncountedLocations + $totalLocationsWithoutItems;
?>
<?php if ($totalPendingLocations > 0): ?>
    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                Lokasi Belum Dihitung
                <span class="ml-2 px-2 py-1 text-sm bg-orange-100 text-orange-800 rounded-full"><?= $totalPendingLocations ?> lokasi</span>
            </h4>
            <button onclick="toggleUncountedLocations()" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-chevron-down" id="uncountedToggleIcon"></i>
                <span id="uncountedToggleText">Tampilkan</span>
            </button>
        </div>

        <div id="uncountedLocationsContent" class="hidden">
            <?php if (!empty($uncounted_locations)): ?>
                <!-- Locations with partial items counted -->
                <div class="mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock text-yellow-500 mr-1"></i> Lokasi dengan item belum selesai dihitung (<?= count($uncounted_locations) ?>)
                    </h5>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded">
                            <thead class="bg-yellow-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-600">Kode Lokasi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-600">Nama Lokasi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-600">Departemen</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-600">Total Item</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-600">Sudah Dihitung</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-600">Belum Dihitung</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-600">Progress</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($uncounted_locations as $loc): ?>
                                    <?php
                                    $progress = $loc['total_items'] > 0 ? round(($loc['counted_items'] / $loc['total_items']) * 100) : 0;
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 font-medium text-gray-900"><?= esc($loc['kode_lokasi']) ?></td>
                                        <td class="px-3 py-2 text-gray-700"><?= esc($loc['nama_lokasi']) ?></td>
                                        <td class="px-3 py-2 text-gray-600"><?= esc($loc['departemen'] ?? '-') ?></td>
                                        <td class="px-3 py-2 text-center"><?= $loc['total_items'] ?></td>
                                        <td class="px-3 py-2 text-center text-green-600 font-semibold"><?= $loc['counted_items'] ?></td>
                                        <td class="px-3 py-2 text-center text-red-600 font-semibold"><?= $loc['uncounted_items'] ?></td>
                                        <td class="px-3 py-2">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600"><?= $progress ?>%</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($locations_without_items)): ?>
                <!-- Locations with no items counted at all -->
                <div>
                    <h5 class="text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500 mr-1"></i> Lokasi belum ada item yang dihitung (<?= count($locations_without_items) ?>)
                    </h5>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php foreach ($locations_without_items as $loc): ?>
                            <div class="bg-red-50 border border-red-200 rounded p-2 text-center hover:bg-red-100 cursor-pointer"
                                title="<?= esc($loc['nama_lokasi']) ?> - <?= esc($loc['departemen'] ?? 'No Dept') ?>">
                                <div class="font-semibold text-red-800 text-sm"><?= esc($loc['kode_lokasi']) ?></div>
                                <div class="text-xs text-red-600 truncate"><?= esc($loc['nama_lokasi']) ?></div>
                                <?php if (!empty($loc['departemen'])): ?>
                                    <div class="text-xs text-gray-500"><?= esc($loc['departemen']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="bg-white p-4 rounded-lg shadow mb-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3" id="filterForm">
        <input type="text"
            name="search"
            value="<?= esc($filters['search'] ?? '') ?>"
            placeholder="Search by code, PLU, or name..."
            class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

        <select name="category" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= esc($cat['category']) ?>" <?= ($filters['category'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                    <?= esc($cat['category']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="department" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Departments</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= esc($dept['department']) ?>" <?= ($filters['department'] ?? '') === $dept['department'] ? 'selected' : '' ?>>
                    <?= esc($dept['department']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="is_counted" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All Items</option>
            <option value="1" <?= ($filters['is_counted'] ?? '') === '1' ? 'selected' : '' ?>>Counted Only</option>
            <option value="0" <?= ($filters['is_counted'] ?? '') === '0' ? 'selected' : '' ?>>Not Counted Only</option>
        </select>

        <!-- Preserve per_page in filter -->
        <input type="hidden" name="per_page" value="<?= esc($filters['per_page'] ?? 50) ?>">

        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="<?= base_url('/stock-opname/' . $session['id']) ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

    <!-- Info Badge untuk Multiple Location -->
    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm">
        <div class="flex items-start gap-2">
            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
            <div class="text-blue-800">
                <strong>Multiple Location Support:</strong> Item yang sama bisa dihitung di beberapa lokasi berbeda.
                Setiap baris = qty di lokasi tertentu. Total physical stock = SUM dari semua lokasi.
            </div>
        </div>
    </div>
</div>

<!-- Summary Grouped by Product (untuk products di multiple locations) -->
<?php
// Buat summary per product dari items yang counted
$productSummary = [];
foreach ($items as $item) {
    if ($item['is_counted']) {
        $pid = $item['product_id'];
        if (!isset($productSummary[$pid])) {
            $productSummary[$pid] = [
                'code' => $item['code'],
                'plu' => $item['plu'],
                'name' => $item['name'],
                'category' => $item['category'],
                'baseline_stock' => $item['baseline_stock'],
                'total_physical' => 0,
                'locations' => [],
                'location_details' => []
            ];
        }
        $productSummary[$pid]['total_physical'] += (float)$item['physical_stock'];
        if ($item['location_id']) {
            $locName = $item['nama_lokasi'] ?? ($item['location'] ?? 'Unknown');
            $productSummary[$pid]['location_details'][] = [
                'name' => $locName,
                'qty' => $item['physical_stock']
            ];
            if (!in_array($item['location_id'], $productSummary[$pid]['locations'])) {
                $productSummary[$pid]['locations'][] = $item['location_id'];
            }
        }
    }
}
// Filter hanya yang ada di multiple locations
$multiLocationSummary = array_filter($productSummary, function ($p) {
    return count($p['locations']) > 1;
});
?>

<?php if (count($multiLocationSummary) > 0): ?>
    <div class="bg-white rounded-lg shadow mb-4">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-layer-group text-blue-600"></i>
                Summary: Products di Multiple Locations
                <span class="ml-2 px-2 py-1 text-sm bg-blue-100 text-blue-800 rounded-full"><?= count($multiLocationSummary) ?> products</span>
            </h4>
            <p class="text-sm text-gray-600 mt-1">Berikut produk yang dihitung di lebih dari 1 lokasi dengan total physical stock</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Lokasi</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Physical</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Baseline</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Difference</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Lokasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($multiLocationSummary as $product):
                        $diff = $product['total_physical'] - $product['baseline_stock'];
                    ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= esc($product['code']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <?= esc($product['name']) ?>
                                <?php if ($product['category']): ?>
                                    <span class="text-xs text-gray-500">(<?= esc($product['category']) ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    <?= count($product['locations']) ?> lokasi
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-bold text-blue-700">
                                <?= number_format($product['total_physical'], 2) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-700">
                                <?= number_format($product['baseline_stock'], 2) ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <span class="<?= $diff > 0 ? 'text-green-600 font-semibold' : ($diff < 0 ? 'text-red-600 font-semibold' : 'text-gray-500') ?>">
                                    <?= $diff > 0 ? '+' : '' ?><?= number_format($diff, 2) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <?php foreach ($product['location_details'] as $loc): ?>
                                    <div class="flex justify-between items-center py-0.5">
                                        <span class="text-xs"><?= esc($loc['name']) ?>:</span>
                                        <span class="text-xs font-semibold ml-2"><?= number_format($loc['qty'], 2) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Location Grouping Info -->
<?php
// Hitung berapa products yang ada di multiple locations
$multiLocationProducts = [];
foreach ($items as $item) {
    if ($item['is_counted']) {
        if (!isset($multiLocationProducts[$item['product_id']])) {
            $multiLocationProducts[$item['product_id']] = [
                'code' => $item['code'],
                'name' => $item['name'],
                'locations' => []
            ];
        }
        if ($item['location_id'] && !in_array($item['location_id'], $multiLocationProducts[$item['product_id']]['locations'])) {
            $multiLocationProducts[$item['product_id']]['locations'][] = $item['location_id'];
        }
    }
}
$multiLocationCount = count(array_filter($multiLocationProducts, function ($p) {
    return count($p['locations']) > 1;
}));
?>
<?php if ($multiLocationCount > 0): ?>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-green-600"></i>
            <span class="text-green-800 font-semibold">
                <?= $multiLocationCount ?> produk dihitung di multiple locations
            </span>
        </div>
    </div>
<?php endif; ?>
</form>
</div>

<!-- Items Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <h4 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="fas fa-list text-gray-600"></i>
            Detail Items per Location
        </h4>
        <p class="text-sm text-gray-600 mt-1">Menampilkan setiap item per lokasi. Jika item yang sama ada di beberapa lokasi, akan muncul di baris berbeda.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">PLU</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase" title="Location where this item was counted">
                        Location
                        <i class="fas fa-question-circle text-gray-400 ml-1" title="Item bisa ada di multiple locations"></i>
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase" title="Original baseline when session created">
                        Original<br>Baseline
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase" title="Mutation since session started">
                        Mutation
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase" title="Real-time baseline (Original + Mutation)">
                        Real-Time<br>Baseline
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase" title="Physical stock at this specific location">
                        Physical<br>(Lokasi Ini)
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase" title="Difference before adjustment">Difference</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase" title="Difference after mutation adjustment">Diff After Adj</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Counted Date</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="12" class="px-4 py-4 text-center text-gray-500">No items found</td>
                    </tr>
                <?php else: ?>
                    <?php
                    // Track untuk highlighting multiple location products dan subtotal
                    $prevProductId = null;
                    $productRows = [];

                    // Group items by product untuk tracking
                    foreach ($items as $item) {
                        $pid = $item['product_id'];
                        if (!isset($productRows[$pid])) {
                            $productRows[$pid] = [];
                        }
                        $productRows[$pid][] = $item;
                    }

                    foreach ($items as $index => $item):
                        // Cek apakah product ini counted di multiple locations
                        $productLocations = array_filter($items, function ($i) use ($item) {
                            return $i['product_id'] == $item['product_id'] && $i['is_counted'] && $i['location_id'];
                        });
                        $hasMultipleLocations = count($productLocations) > 1;
                        $isFirstEntry = $prevProductId != $item['product_id'];

                        // Cek apakah ini entry terakhir untuk product ini
                        $isLastEntry = !isset($items[$index + 1]) || $items[$index + 1]['product_id'] != $item['product_id'];

                        $prevProductId = $item['product_id'];
                    ?>
                        <tr class="hover:bg-gray-50 <?= $hasMultipleLocations ? 'bg-blue-50' : '' ?>" id="row-<?= $item['id'] ?>">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                <?= esc($item['code']) ?>
                                <?php if ($hasMultipleLocations && $isFirstEntry): ?>
                                    <span class="ml-1 px-1.5 py-0.5 text-xs bg-blue-200 text-blue-800 rounded" title="Product ini ada di <?= count($productLocations) ?> lokasi">
                                        <i class="fas fa-layer-group"></i> <?= count($productLocations) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= esc($item['plu'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <?= esc($item['name']) ?>
                                <?php if ($item['category']): ?>
                                    <span class="text-xs text-gray-500">(<?= esc($item['category']) ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <?php if (!empty($item['kode_lokasi'])): ?>
                                    <div class="font-medium text-gray-900"><?= esc($item['kode_lokasi']) ?></div>
                                    <div class="text-xs text-gray-500"><?= esc($item['nama_lokasi']) ?></div>
                                    <?php if (!empty($item['location_department'])): ?>
                                        <div class="text-xs text-blue-600"><?= esc($item['location_department']) ?></div>
                                    <?php endif; ?>
                                <?php elseif (!empty($item['location'])): ?>
                                    <span class="text-gray-600"><?= esc($item['location']) ?></span>
                                    <span class="text-xs text-orange-500">(manual)</span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Original Baseline -->
                            <td class="px-4 py-3 text-sm text-right text-gray-700 font-medium">
                                <?= number_format($item['original_baseline_stock'], 2) ?>
                            </td>

                            <!-- Mutation -->
                            <td class="px-4 py-3 text-sm text-right">
                                <?php
                                $mutation = $item['current_mutation'] ?? 0;
                                if ($mutation > 0): ?>
                                    <span class="text-green-600 font-semibold" title="Purchases more than sales">
                                        <i class="fas fa-arrow-up"></i> +<?= number_format($mutation, 2) ?>
                                    </span>
                                <?php elseif ($mutation < 0): ?>
                                    <span class="text-red-600 font-semibold" title="Sales more than purchases">
                                        <i class="fas fa-arrow-down"></i> <?= number_format($mutation, 2) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">0</span>
                                <?php endif; ?>
                            </td>

                            <!-- Real-Time Baseline -->
                            <td class="px-4 py-3 text-sm text-right text-blue-700 font-semibold" title="Original Baseline + Mutation">
                                <?= number_format($item['baseline_stock'], 2) ?>
                                <?php if ($mutation != 0): ?>
                                    <i class="fas fa-info-circle text-blue-400 cursor-pointer"
                                        onclick="showMutationDetail(<?= $item['product_id'] ?>, '<?= esc($item['name']) ?>')"
                                        title="Click to see mutation details"></i>
                                <?php endif; ?>
                            </td>

                            <!-- Physical Stock -->
                            <td class="px-4 py-3 text-sm text-right">
                                <span id="physical-<?= $item['id'] ?>">
                                    <?= $item['physical_stock'] !== null ? number_format($item['physical_stock'], 2) : '-' ?>
                                </span>
                                <?php if ($item['is_counted'] && isset($item['mutation_after_count']) && $item['mutation_after_count'] != 0): ?>
                                    <br>
                                    <span class="text-xs text-gray-500" title="Mutation after counted">
                                        (Adj: <?= number_format($item['adjusted_physical'], 2) ?>)
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Difference -->
                            <td class="px-4 py-3 text-sm text-right">
                                <span id="difference-<?= $item['id'] ?>" class="<?= $item['difference'] > 0 ? 'text-green-600 font-semibold' : ($item['difference'] < 0 ? 'text-red-600 font-semibold' : 'text-gray-500') ?>">
                                    <?= $item['is_counted'] ? number_format($item['difference'], 2) : '-' ?>
                                </span>
                            </td>

                            <!-- Difference After Adjustment -->
                            <td class="px-4 py-3 text-sm text-right">
                                <?php if ($item['is_counted']): ?>
                                    <?php
                                    // Calculate difference after adjustment
                                    // Diff After Adj = Adjusted Physical - Real-Time Baseline
                                    $adjustedPhysical = $item['adjusted_physical'] ?? $item['physical_stock'];
                                    $diffAfterAdj = $adjustedPhysical - $item['baseline_stock'];
                                    ?>
                                    <span class="<?= $diffAfterAdj > 0 ? 'text-green-600 font-semibold' : ($diffAfterAdj < 0 ? 'text-red-600 font-semibold' : 'text-blue-600 font-semibold') ?>">
                                        <?= number_format($diffAfterAdj, 2) ?>
                                    </span>
                                    <?php if ($diffAfterAdj == 0): ?>
                                        <i class="fas fa-check-circle text-green-500 ml-1" title="Exact match!"></i>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3 text-sm text-center">
                                <?php if ($item['is_counted'] && $item['counted_date']): ?>
                                    <span class="text-gray-700"><?= date('d/m/Y', strtotime($item['counted_date'])) ?></span>
                                    <?php if ($item['counted_by']): ?>
                                        <br><span class="text-xs text-gray-500">by <?= esc($item['counted_by']) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <?php if ($session['status'] === 'open'): ?>
                                    <button onclick="editItem(<?= $item['id'] ?>, '<?= esc($item['name']) ?>', <?= $item['baseline_stock'] ?>, <?= $item['physical_stock'] ?? 'null' ?>)"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php
                        // Jika ini last entry dari product yang ada di multiple locations, tampilkan subtotal row
                        if ($isLastEntry && $hasMultipleLocations):
                            // Calculate subtotal untuk product ini
                            $subtotalPhysical = 0;
                            $subtotalDifference = 0;
                            $subtotalDiffAfterAdj = 0;
                            $locationCount = 0;

                            foreach ($productRows[$item['product_id']] as $row) {
                                if ($row['is_counted']) {
                                    $subtotalPhysical += (float)$row['physical_stock'];
                                    $adjustedPhys = $row['adjusted_physical'] ?? $row['physical_stock'];
                                    $diffAfter = $adjustedPhys - $row['baseline_stock'];
                                    $subtotalDiffAfterAdj += $diffAfter;
                                    $locationCount++;
                                }
                            }

                            // Recalculate difference: Total Physical - Baseline (bukan sum of individual differences)
                            // Karena baseline adalah baseline produk secara keseluruhan, bukan per lokasi
                            $subtotalDifference = $subtotalPhysical - $item['baseline_stock'];
                            $subtotalDiffAfterAdj = $subtotalPhysical - $item['baseline_stock'];
                        ?>
                            <!-- Subtotal Row untuk Multiple Location Product -->
                            <tr class="bg-gradient-to-r from-blue-100 to-blue-50 border-t-2 border-b-2 border-blue-300 font-bold">
                                <td colspan="3" class="px-4 py-3 text-sm text-gray-900">
                                    <i class="fas fa-calculator text-blue-600 mr-2"></i>
                                    <strong>SUBTOTAL - <?= esc($item['code']) ?> (<?= $locationCount ?> lokasi)</strong>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-600">
                                    <span class="text-xs"><?= $locationCount ?> locations counted</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-700">
                                    <?= number_format($item['original_baseline_stock'], 2) ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span class="text-gray-500">-</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-blue-700 font-bold">
                                    <?= number_format($item['baseline_stock'], 2) ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span class="text-blue-900 font-bold text-base">
                                        <?= number_format($subtotalPhysical, 2) ?>
                                    </span>
                                    <div class="text-xs text-gray-600 font-normal">Total Physical</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span class="<?= $subtotalDifference > 0 ? 'text-green-700' : ($subtotalDifference < 0 ? 'text-red-700' : 'text-gray-700') ?> font-bold text-base">
                                        <?= number_format($subtotalDifference, 2) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span class="<?= $subtotalDiffAfterAdj > 0 ? 'text-green-700' : ($subtotalDiffAfterAdj < 0 ? 'text-red-700' : 'text-blue-700') ?> font-bold text-base">
                                        <?= number_format($subtotalDiffAfterAdj, 2) ?>
                                    </span>
                                </td>
                                <td colspan="2" class="px-4 py-3 text-center text-xs text-gray-600">
                                    <i class="fas fa-sigma mr-1"></i> Summary
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (!empty($items)): ?>
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <!-- Info text -->
            <div class="text-sm text-gray-600">
                Menampilkan
                <span class="font-semibold text-gray-900"><?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?></span>
                sampai
                <span class="font-semibold text-gray-900"><?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?></span>
                dari
                <span class="font-semibold text-gray-900"><?= number_format($pager->getTotal()) ?></span> items
            </div>

            <!-- Pagination controls -->
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Items per page -->
                <form method="GET" class="flex items-center gap-2" id="perPageForm">
                    <?php if (!empty($filters['search'])): ?>
                        <input type="hidden" name="search" value="<?= esc($filters['search']) ?>">
                    <?php endif; ?>
                    <?php if (!empty($filters['category'])): ?>
                        <input type="hidden" name="category" value="<?= esc($filters['category']) ?>">
                    <?php endif; ?>
                    <?php if (!empty($filters['department'])): ?>
                        <input type="hidden" name="department" value="<?= esc($filters['department']) ?>">
                    <?php endif; ?>
                    <?php if (isset($filters['is_counted'])): ?>
                        <input type="hidden" name="is_counted" value="<?= esc($filters['is_counted']) ?>">
                    <?php endif; ?>
                    <label class="text-sm text-gray-600 whitespace-nowrap">Per halaman:</label>
                    <select name="per_page" onchange="this.form.submit()"
                        class="text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                        <option value="25" <?= ($filters['per_page'] ?? 50) == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($filters['per_page'] ?? 50) == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= ($filters['per_page'] ?? 50) == 100 ? 'selected' : '' ?>>100</option>
                        <option value="200" <?= ($filters['per_page'] ?? 50) == 200 ? 'selected' : '' ?>>200</option>
                    </select>
                </form>

                <!-- Pagination links -->
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Edit Stock Count</h3>
            <form id="editForm">
                <input type="hidden" id="itemId">

                <div class="bg-blue-50 p-3 rounded-md mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>Tanggal SO:</strong> <?= date('d M Y', strtotime($session['session_date'])) ?><br>
                        <small>Baseline akan otomatis disesuaikan dengan mutasi dari tanggal SO sampai tanggal hitung</small>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Baseline Stock (Awal)</label>
                        <input type="text" id="baselineStock" readonly class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Stok acuan tanggal SO</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Physical Stock *</label>
                        <input type="number" step="0.01" id="physicalStock" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Hasil hitung fisik</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Hitung *</label>
                        <input type="date" id="countedDate" value="<?= date('Y-m-d') ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Kapan barang ini dihitung</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location / Rack</label>
                        <div class="relative">
                            <input type="text"
                                id="locationSearch"
                                placeholder="Type to search location..."
                                autocomplete="off"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <input type="hidden" id="location_id" name="location_id">
                            <div id="locationResults" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto hidden"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Search by code or name</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penghitung <span class="text-red-500">*</span></label>
                    <input type="text" id="countedBy" value="<?= esc(auth()->user() ? auth()->user()->username : '') ?>" placeholder="Nama penghitung" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Otomatis diisi dari user login</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div id="mutationInfo" class="hidden bg-yellow-50 border border-yellow-200 p-3 rounded-md mb-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle"></i>
                        <strong>Mutasi terdeteksi:</strong> <span id="mutationValue"></span><br>
                        <small>Baseline akan disesuaikan otomatis saat menyimpan</small>
                    </p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Toggle uncounted locations section
    function toggleUncountedLocations() {
        const content = document.getElementById('uncountedLocationsContent');
        const icon = document.getElementById('uncountedToggleIcon');
        const text = document.getElementById('uncountedToggleText');

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            text.textContent = 'Sembunyikan';
        } else {
            content.classList.add('hidden');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            text.textContent = 'Tampilkan';
        }
    }

    // Show loading indicator on form submit and pagination
    document.addEventListener('DOMContentLoaded', function() {
        // Filter form loading
        document.getElementById('filterForm')?.addEventListener('submit', function() {
            showLoading();
        });

        // Per page form loading
        document.getElementById('perPageForm')?.addEventListener('submit', function() {
            showLoading();
        });

        // Pagination links loading
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function() {
                showLoading();
            });
        });
    });

    function showLoading() {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loadingIndicator';
        loadingDiv.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        loadingDiv.innerHTML = `
            <div class="bg-white rounded-lg p-6 shadow-xl">
                <div class="flex items-center space-x-3">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-lg font-medium text-gray-700">Loading...</span>
                </div>
            </div>
        `;
        document.body.appendChild(loadingDiv);
    }

    function editItem(id, name, baseline, physical) {
        document.getElementById('itemId').value = id;
        document.getElementById('modalTitle').textContent = 'Edit: ' + name;
        document.getElementById('baselineStock').value = baseline;
        document.getElementById('physicalStock').value = physical || '';
        document.getElementById('countedDate').value = '<?= date('Y-m-d') ?>';
        document.getElementById('locationSearch').value = '';
        document.getElementById('location_id').value = '';
        document.getElementById('locationResults').classList.add('hidden');
        document.getElementById('countedBy').value = '<?= esc(auth()->user() ? auth()->user()->username : '') ?>';
        document.getElementById('notes').value = '';
        document.getElementById('mutationInfo').classList.add('hidden');
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Location search with debouncing
    let locationSearchTimeout;
    document.getElementById('locationSearch')?.addEventListener('input', function() {
        clearTimeout(locationSearchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            document.getElementById('locationResults').classList.add('hidden');
            return;
        }

        locationSearchTimeout = setTimeout(async () => {
            try {
                const response = await fetch('<?= base_url('admin/location/api/search') ?>?q=' + encodeURIComponent(query));
                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    const resultsHtml = result.data.map(loc => `
                        <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b" 
                             onclick="selectLocation(${loc.id}, '${loc.label}')">
                            <div class="font-medium text-sm">${loc.code} - ${loc.name}</div>
                            <div class="text-xs text-gray-500">${loc.department || '-'}</div>
                        </div>
                    `).join('');

                    document.getElementById('locationResults').innerHTML = resultsHtml;
                    document.getElementById('locationResults').classList.remove('hidden');
                } else {
                    document.getElementById('locationResults').innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No locations found</div>';
                    document.getElementById('locationResults').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error searching locations:', error);
            }
        }, 500);
    });

    function selectLocation(id, label) {
        document.getElementById('location_id').value = id;
        document.getElementById('locationSearch').value = label;
        document.getElementById('locationResults').classList.add('hidden');
    }

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#locationSearch') && !e.target.closest('#locationResults')) {
            document.getElementById('locationResults')?.classList.add('hidden');
        }
    });

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const itemId = document.getElementById('itemId').value;
        const physicalStock = document.getElementById('physicalStock').value;
        const countedDate = document.getElementById('countedDate').value;
        const locationId = document.getElementById('location_id').value;
        const countedBy = document.getElementById('countedBy').value;
        const notes = document.getElementById('notes').value;

        console.log('Submitting form with location_id:', locationId);

        try {
            const response = await fetch('<?= base_url('/stock-opname/update-item/') ?>' + itemId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'physical_stock': physicalStock,
                    'counted_date': countedDate,
                    'location_id': locationId,
                    'counted_by': countedBy,
                    'notes': notes,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            });

            const result = await response.json();

            if (result.success) {
                const mutationMsg = result.mutation_at_count != 0 ?
                    '\n\nMutasi terdeteksi: ' + result.mutation_at_count.toFixed(2) +
                    '\nBaseline telah disesuaikan otomatis.' : '';
                alert('Data berhasil disimpan!' + mutationMsg);
                closeModal();
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Error updating item: ' + error.message);
        }
    });

    // Show mutation detail modal
    async function showMutationDetail(productId, productName) {
        try {
            const url = '<?= base_url('/stock-opname/' . $session['id'] . '/mutation-detail/') ?>' + productId;
            console.log('Fetching mutation detail from:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Result:', result);

            if (result.success) {
                let detailHtml = `
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="mutationDetailModal">
                        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-gray-900">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Mutation Detail: ${productName}
                                </h3>
                                <button onclick="document.getElementById('mutationDetailModal').remove()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times text-2xl"></i>
                                </button>
                            </div>
                            
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                                <p class="text-sm text-gray-700">
                                    <strong>Period:</strong> ${new Date(result.session_date).toLocaleDateString()} - ${new Date(result.reference_date).toLocaleDateString()}<br>
                                    ${result.is_frozen ? '<span class="text-blue-600"><i class="fas fa-snowflake"></i> Baseline Frozen</span>' : '<span class="text-yellow-600"><i class="fas fa-sync"></i> Real-Time</span>'}<br>
                                    <strong>Total Mutation:</strong> <span class="font-semibold ${result.total_mutation >= 0 ? 'text-green-600' : 'text-red-600'}">${result.total_mutation >= 0 ? '+' : ''}${result.total_mutation.toFixed(2)}</span>
                                </p>
                            </div>

                            <div class="overflow-y-auto max-h-96">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500">Qty</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Ref No</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500">Cumulative</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                `;

                result.transactions.forEach(trans => {
                    const typeClass = trans.type === 'purchase' ? 'text-green-600' : 'text-red-600';
                    const typeIcon = trans.type === 'purchase' ? '▲' : '▼';
                    const qtyPrefix = trans.type === 'purchase' ? '+' : '-';
                    const cumulativeClass = trans.cumulative_mutation >= 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold';

                    detailHtml += `
                        <tr>
                            <td class="px-3 py-2">${new Date(trans.date).toLocaleDateString()}</td>
                            <td class="px-3 py-2 ${typeClass}">${typeIcon} ${trans.type.toUpperCase()}</td>
                            <td class="px-3 py-2 text-right ${typeClass}">${qtyPrefix}${trans.qty}</td>
                            <td class="px-3 py-2 text-gray-600">${trans.reference_no || '-'}</td>
                            <td class="px-3 py-2 text-right ${cumulativeClass}">
                                ${trans.cumulative_mutation >= 0 ? '+' : ''}${trans.cumulative_mutation.toFixed(2)}
                            </td>
                        </tr>
                    `;
                });

                detailHtml += `
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 flex justify-end">
                                <button onclick="document.getElementById('mutationDetailModal').remove()" 
                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', detailHtml);
            } else {
                console.error('Failed to load mutation details:', result);
                alert('Failed to load mutation details: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error loading mutation detail:', error);
            alert('Error: ' + error.message);
        }
    }
</script>
<?= $this->endSection() ?>