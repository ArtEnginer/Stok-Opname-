<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 10px;
            }

            table {
                font-size: 9px;
            }

            .page-break {
                page-break-before: always;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }

        .variance-positive {
            background-color: #dcfce7 !important;
        }

        .variance-negative {
            background-color: #fee2e2 !important;
        }
    </style>
</head>

<body class="bg-gray-100 p-4">
    <!-- Action Buttons - No Print -->
    <div class="no-print mb-4 bg-white p-4 rounded-lg shadow flex flex-wrap gap-3 items-center">
        <a href="<?= base_url('/stock-opname/' . $session['id']) ?>" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Session
        </a>

        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <i class="fas fa-print mr-2"></i> Print Report
        </button>

        <a href="<?= base_url('/stock-opname/' . $session['id'] . '/export-report?' . http_build_query($filters)) ?>"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </a>

        <!-- Filters -->
        <form method="GET" class="flex flex-wrap gap-2 items-center ml-auto">
            <select name="department" class="text-sm rounded-md border-gray-300" onchange="this.form.submit()">
                <option value="">All Departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= esc($dept['department']) ?>" <?= ($filters['department'] ?? '') === $dept['department'] ? 'selected' : '' ?>>
                        <?= esc($dept['department']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="category" class="text-sm rounded-md border-gray-300" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc($cat['category']) ?>" <?= ($filters['category'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                        <?= esc($cat['category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="is_counted" class="text-sm rounded-md border-gray-300" onchange="this.form.submit()">
                <option value="">All Items</option>
                <option value="1" <?= ($filters['is_counted'] ?? '') === '1' ? 'selected' : '' ?>>Counted Only</option>
                <option value="0" <?= ($filters['is_counted'] ?? '') === '0' ? 'selected' : '' ?>>Not Counted</option>
            </select>

            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" name="show_variance_only" value="1"
                    <?= ($filters['show_variance_only'] ?? '') === '1' ? 'checked' : '' ?>
                    onchange="this.form.submit()">
                Variance Only
            </label>
        </form>
    </div>

    <!-- Report Header -->
    <div class="bg-white p-6 rounded-lg shadow mb-4">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">LAPORAN AKHIR STOCK OPNAME</h1>
            <h2 class="text-lg text-gray-700"><?= esc($session['session_code']) ?></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Tanggal SO:</span>
                <span class="font-semibold"><?= date('d M Y', strtotime($session['session_date'])) ?></span>
            </div>
            <div>
                <span class="text-gray-600">Tanggal Referensi:</span>
                <span class="font-semibold"><?= date('d M Y', strtotime($reference_date)) ?></span>
                <?php if ($is_frozen): ?>
                    <span class="text-xs text-blue-600">(Frozen)</span>
                <?php else: ?>
                    <span class="text-xs text-green-600">(Real-Time)</span>
                <?php endif; ?>
            </div>
            <div>
                <span class="text-gray-600">Tanggal Cetak:</span>
                <span class="font-semibold"><?= date('d M Y H:i', strtotime($print_date)) ?></span>
            </div>
            <div>
                <span class="text-gray-600">Status:</span>
                <span class="font-semibold <?= $session['status'] === 'open' ? 'text-green-600' : 'text-gray-600' ?>">
                    <?= ucfirst($session['status']) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Total Items</div>
            <div class="text-xl font-bold text-gray-900"><?= number_format($summary['total_items']) ?></div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Total Selisih (Qty)</div>
            <div class="text-xl font-bold <?= $summary['total_variance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                <?= $summary['total_variance'] >= 0 ? '+' : '' ?><?= number_format($summary['total_variance'], 2) ?>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Surplus (Rp)</div>
            <div class="text-xl font-bold text-green-600">
                +<?= number_format($summary['total_surplus_value'], 0, ',', '.') ?>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Shortage (Rp)</div>
            <div class="text-xl font-bold text-red-600">
                -<?= number_format($summary['total_shortage_value'], 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <!-- Net Variance -->
    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <div class="flex justify-between items-center">
            <span class="text-lg font-semibold text-gray-700">NET SELISIH (Nilai):</span>
            <span class="text-2xl font-bold <?= $summary['total_variance_value'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                Rp <?= number_format($summary['total_variance_value'], 0, ',', '.') ?>
            </span>
        </div>
    </div>

    <!-- Summary by Department -->
    <?php if (!empty($summary_by_dept)): ?>
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Ringkasan per Department</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Department</th>
                            <th class="px-3 py-2 text-right">Items</th>
                            <th class="px-3 py-2 text-right">Counted</th>
                            <th class="px-3 py-2 text-right">Stok Sistem</th>
                            <th class="px-3 py-2 text-right">Stok Fisik</th>
                            <th class="px-3 py-2 text-right">Selisih (Qty)</th>
                            <th class="px-3 py-2 text-right">Surplus (Rp)</th>
                            <th class="px-3 py-2 text-right">Shortage (Rp)</th>
                            <th class="px-3 py-2 text-right">Net (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($summary_by_dept as $dept => $data): ?>
                            <tr>
                                <td class="px-3 py-2 font-medium"><?= esc($dept) ?></td>
                                <td class="px-3 py-2 text-right"><?= number_format($data['total_items']) ?></td>
                                <td class="px-3 py-2 text-right"><?= number_format($data['counted_items']) ?></td>
                                <td class="px-3 py-2 text-right"><?= number_format($data['total_system'], 2) ?></td>
                                <td class="px-3 py-2 text-right"><?= number_format($data['total_physical'], 2) ?></td>
                                <td class="px-3 py-2 text-right <?= $data['total_variance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $data['total_variance'] >= 0 ? '+' : '' ?><?= number_format($data['total_variance'], 2) ?>
                                </td>
                                <td class="px-3 py-2 text-right text-green-600">+<?= number_format($data['surplus_value'], 0, ',', '.') ?></td>
                                <td class="px-3 py-2 text-right text-red-600">-<?= number_format($data['shortage_value'], 0, ',', '.') ?></td>
                                <td class="px-3 py-2 text-right font-semibold <?= $data['total_variance_value'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= number_format($data['total_variance_value'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Detail Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Detail Barang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">PLU</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dept</th>
                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga Beli</th>
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Stok Fisik</th>
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Selisih</th>
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Nilai Selisih</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="11" class="px-4 py-4 text-center text-gray-500">No items found</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($items as $item):
                            $rowClass = '';
                            if ($item['is_counted'] && $item['variance'] != 0) {
                                $rowClass = $item['variance'] > 0 ? 'variance-positive' : 'variance-negative';
                            }
                        ?>
                            <tr class="<?= $rowClass ?> hover:bg-gray-50">
                                <td class="px-2 py-2 text-sm"><?= $no++ ?></td>
                                <td class="px-2 py-2 text-sm font-medium"><?= esc($item['code']) ?></td>
                                <td class="px-2 py-2 text-sm"><?= esc($item['plu'] ?? '-') ?></td>
                                <td class="px-2 py-2 text-sm"><?= esc($item['name']) ?></td>
                                <td class="px-2 py-2 text-sm"><?= esc($item['department'] ?? '-') ?></td>
                                <td class="px-2 py-2 text-sm"><?= esc($item['category'] ?? '-') ?></td>
                                <td class="px-2 py-2 text-sm text-right"><?= number_format($item['buy_price'], 0, ',', '.') ?></td>
                                <td class="px-2 py-2 text-sm text-right font-medium"><?= number_format($item['system_stock'], 2) ?></td>
                                <td class="px-2 py-2 text-sm text-right">
                                    <?php if ($item['is_counted']): ?>
                                        <?= number_format($item['adjusted_physical'], 2) ?>
                                        <?php if ($item['mutation_after_count'] != 0): ?>
                                            <span class="text-xs text-gray-500" title="Original: <?= $item['physical_stock'] ?>, Adj: <?= $item['mutation_after_count'] ?>">*</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2 py-2 text-sm text-right font-semibold <?= ($item['variance'] ?? 0) > 0 ? 'text-green-600' : (($item['variance'] ?? 0) < 0 ? 'text-red-600' : '') ?>">
                                    <?php if ($item['is_counted']): ?>
                                        <?= $item['variance'] >= 0 ? '+' : '' ?><?= number_format($item['variance'], 2) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-2 py-2 text-sm text-right font-semibold <?= ($item['variance_value'] ?? 0) > 0 ? 'text-green-600' : (($item['variance_value'] ?? 0) < 0 ? 'text-red-600' : '') ?>">
                                    <?php if ($item['is_counted']): ?>
                                        <?= number_format($item['variance_value'], 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                        <td colspan="7" class="px-2 py-2 text-right">TOTAL:</td>
                        <td class="px-2 py-2 text-right"><?= number_format($summary['total_system_stock'], 2) ?></td>
                        <td class="px-2 py-2 text-right"><?= number_format($summary['total_physical_stock'], 2) ?></td>
                        <td class="px-2 py-2 text-right <?= $summary['total_variance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $summary['total_variance'] >= 0 ? '+' : '' ?><?= number_format($summary['total_variance'], 2) ?>
                        </td>
                        <td class="px-2 py-2 text-right <?= $summary['total_variance_value'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= number_format($summary['total_variance_value'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <p>Dicetak pada: <?= date('d M Y H:i:s', strtotime($print_date)) ?></p>
        <p class="mt-4 grid grid-cols-3 gap-4">
            <span>
                <br><br><br>
                ____________________<br>
                Dibuat Oleh
            </span>
            <span>
                <br><br><br>
                ____________________<br>
                Diperiksa Oleh
            </span>
            <span>
                <br><br><br>
                ____________________<br>
                Disetujui Oleh
            </span>
        </p>
    </div>
</body>

</html>