<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Campaign</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= number_format($totalCampaigns ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Campaign Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= number_format($activeCampaigns ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Donasi</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp <?= number_format($totalDonations ?? 0, 0, ',', '.') ?></h3>
            </div>
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-primary-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Pending Verifikasi</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= number_format($pendingDonations ?? 0) ?></h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Campaigns -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bullhorn text-primary-600 mr-2"></i>
            Campaign Terbaru
        </h3>
        <div class="space-y-3">
            <?php if (!empty($recentCampaigns)): ?>
                <?php foreach ($recentCampaigns as $campaign): ?>
                    <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800"><?= esc($campaign['title']) ?></h4>
                            <p class="text-sm text-gray-500">
                                Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?> /
                                Rp <?= number_format($campaign['target_amount'], 0, ',', '.') ?>
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                        <?= $campaign['status'] == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                            <?= ucfirst($campaign['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada campaign</p>
            <?php endif; ?>
        </div>
        <div class="mt-4">
            <a href="/admin/campaigns" class="text-primary-600 hover:text-primary-700 font-semibold">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Top Campaigns -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
            Campaign Terpopuler
        </h3>
        <div class="space-y-3">
            <?php if (!empty($topCampaigns)): ?>
                <?php foreach ($topCampaigns as $idx => $campaign):
                    $progress = ($campaign['target_amount'] > 0) ?
                        min(($campaign['collected_amount'] / $campaign['target_amount']) * 100, 100) : 0;
                ?>
                    <div class="py-3 border-b last:border-b-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    <?= $idx + 1 ?>
                                </span>
                                <h4 class="font-semibold text-gray-800"><?= esc($campaign['title']) ?></h4>
                            </div>
                        </div>
                        <div class="ml-8">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Rp <?= number_format($campaign['collected_amount'], 0, ',', '.') ?></span>
                                <span><?= number_format($progress, 0) ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">Belum ada data</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Donations -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-hand-holding-heart text-primary-600 mr-2"></i>
        Donasi Terbaru
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Donatur</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Campaign</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Jumlah</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentDonations)): ?>
                    <?php foreach ($recentDonations as $donation): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <?= $donation['is_anonymous'] ? 'Hamba Allah' : esc($donation['donor_name']) ?>
                            </td>
                            <td class="py-3 px-4"><?= esc($donation['campaign_title']) ?></td>
                            <td class="py-3 px-4 font-semibold text-primary-600">
                                Rp <?= number_format($donation['amount'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                <?php
                                if ($donation['status'] == 'verified') echo 'bg-green-100 text-green-700';
                                elseif ($donation['status'] == 'pending') echo 'bg-yellow-100 text-yellow-700';
                                else echo 'bg-red-100 text-red-700';
                                ?>">
                                    <?= ucfirst($donation['status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                <?= date('d M Y H:i', strtotime($donation['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">Belum ada donasi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <a href="/admin/donations" class="text-primary-600 hover:text-primary-700 font-semibold">
            Lihat Semua Donasi <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

<?= $this->endSection() ?>