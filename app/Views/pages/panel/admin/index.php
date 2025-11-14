<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .dashboard-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 6px 20px rgba(255, 140, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 30px;
        background: #fff;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 140, 0, 0.15);
    }

    .stat-card {
        background: linear-gradient(135deg, #fff 0%, #ffb347 100%);
        color: #ff6f00;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        border: 1px solid #ffeaa7;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 140, 0, 0.08);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .stat-card-blue {
        background: linear-gradient(135deg, #fff 0%, #ffb347 100%);
        color: #ff6f00;
    }

    .stat-card-green {
        background: linear-gradient(135deg, #fff 0%, #ffeaa7 100%);
        color: #00b894;
    }

    .stat-card-orange {
        background: linear-gradient(135deg, #fff 0%, #ffb347 100%);
        color: #ff6f00;
    }

    .stat-card-red {
        background: linear-gradient(135deg, #fff 0%, #fab1a0 100%);
        color: #d63031;
    }

    .stat-card-purple {
        background: linear-gradient(135deg, #fff 0%, #ffeaa7 100%);
        color: #6c5ce7;
    }

    .stat-card-teal {
        background: linear-gradient(135deg, #fff 0%, #ffeaa7 100%);
        color: #0984e3;
    }

    .stat-icon {
        font-size: 40px;
        opacity: 0.8;
        margin-bottom: 15px;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
    }

    .chart-container {
        position: relative;
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(255, 140, 0, 0.08);
        margin-bottom: 30px;
        height: 350px;
        min-height: 350px;
        max-height: 350px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid #ffeaa7;
    }

    .chart-container canvas {
        height: 100% !important;
        width: 100% !important;
        max-height: 100% !important;
        max-width: 100% !important;
        display: block;
        margin: 0 auto;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: #ff6f00;
        margin-bottom: 20px;
        text-align: center;
    }

    .page-header {
        background: linear-gradient(135deg, #fff 0%, #ffb347 100%);
        color: #ff6f00;
        padding: 40px 0;
        margin: -20px -20px 30px -20px;
        border-radius: 0 0 20px 20px;
        border-bottom: 2px solid #ffeaa7;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        text-align: center;
        color: #ff6f00;
    }

    .page-subtitle {
        font-size: 16px;
        opacity: 0.9;
        text-align: center;
        margin-top: 5px;
        color: #ff6f00;
    }

    .trend-indicator {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 20px;
        background: rgba(255, 140, 0, 0.08);
        margin-top: 10px;
        display: inline-block;
        color: #ff6f00;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #ffeaa7;
        border-radius: 50%;
        border-top-color: #ff6f00;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .welcome-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.5s ease-out;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .welcome-alert.superadmin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .welcome-alert.gudang1 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .welcome-alert.gudang2 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .welcome-alert-icon {
        font-size: 32px;
        flex-shrink: 0;
    }

    .welcome-alert-content h5 {
        margin: 0 0 5px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .welcome-alert-content p {
        margin: 0;
        font-size: 14px;
        opacity: 0.95;
    }

    .welcome-alert-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.3s;
    }

    .welcome-alert-close:hover {
        opacity: 1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>

<!-- Welcome Alert -->
<?php
$userGroup = '';
$welcomeTitle = '';
$welcomeMessage = '';
$alertIcon = '';
$alertClass = '';

if (auth()->user()->inGroup('superadmin')) {
    $userGroup = 'superadmin';
    $welcomeTitle = 'Selamat Datang, Super Admin! 👑';
    $welcomeMessage = 'Anda memiliki akses penuh ke semua fitur sistem JASTIP';
    $alertIcon = '👑';
    $alertClass = 'superadmin';
} elseif (auth()->user()->inGroup('gudang1')) {
    $userGroup = 'gudang1';
    $welcomeTitle = 'Selamat Datang, Gudang Jakarta! 📦';
    $welcomeMessage = 'Pantau dan kelola pengiriman dari Jakarta';
    $alertIcon = '🏢';
    $alertClass = 'gudang1';
} elseif (auth()->user()->inGroup('gudang2')) {
    $userGroup = 'gudang2';
    $welcomeTitle = 'Selamat Datang, Gudang Papua! 🌴';
    $welcomeMessage = 'Pantau dan kelola pengiriman dari Papua';
    $alertIcon = '🏝️';
    $alertClass = 'gudang2';
}
?>

<?php if ($userGroup): ?>
    <div class="welcome-alert <?= $alertClass ?>" id="welcomeAlert">
        <div class="welcome-alert-icon"><?= $alertIcon ?></div>
        <div class="welcome-alert-content">
            <h5><?= $welcomeTitle ?></h5>
            <p><?= $welcomeMessage ?></p>
        </div>
        <button class="welcome-alert-close" onclick="closeWelcomeAlert()">×</button>
    </div>
<?php endif; ?>

<div class="page-wrapper">
    <div class="page">
        <div class="page-header">
            <div class="container">
                <h1 class="page-title">Dashboard Monitoring JASTIP</h1>
                <p class="page-subtitle">Pantau performa dan statistik sistem pengiriman secara real-time</p>
            </div>
        </div>

        <div class="container">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col s12 m6 l4">
                    <div class="stat-card stat-card-blue">
                        <div class="stat-icon">📦</div>
                        <div class="stat-title">Total Paket</div>
                        <div class="stat-value" id="total-paket">-</div>
                        <div class="trend-indicator">📈 Total keseluruhan</div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="stat-card stat-card-orange">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-title">Sedang Proses</div>
                        <div class="stat-value" id="total-proses">-</div>
                        <div class="trend-indicator">🔄 Dalam pengiriman</div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="stat-card stat-card-green">
                        <div class="stat-icon">✅</div>
                        <div class="stat-title">Selesai</div>
                        <div class="stat-value" id="total-selesai">-</div>
                        <div class="trend-indicator">✨ Berhasil dikirim</div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="stat-card stat-card-red">
                        <div class="stat-icon">⏸️</div>
                        <div class="stat-title">Pending</div>
                        <div class="stat-value" id="total-pending">-</div>
                        <div class="trend-indicator">⌛ Menunggu proses</div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="stat-card stat-card-purple">
                        <div class="stat-icon">💰</div>
                        <div class="stat-title">Total Pendapatan</div>
                        <div class="stat-value" id="total-pendapatan">Rp 0</div>
                        <div class="trend-indicator">💎 Akumulasi biaya</div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="stat-card stat-card-teal">
                        <div class="stat-icon">⚖️</div>
                        <div class="stat-title">Total Bobot</div>
                        <div class="stat-value" id="total-bobot">0 kg</div>
                        <div class="trend-indicator">📊 Akumulasi bobot</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Status Distribution Chart -->
                <div class="col s12 l6">
                    <div class="chart-container">
                        <h5 class="chart-title">Distribusi Status Paket</h5>
                        <canvas id="statusChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Monthly Revenue Chart -->
                <div class="col s12 l6">
                    <div class="chart-container">
                        <h5 class="chart-title">Pendapatan Bulanan</h5>
                        <canvas id="revenueChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Package Trends Chart -->
                <div class="col s12 l6">
                    <div class="chart-container">
                        <h5 class="chart-title">Tren Pengiriman Mingguan</h5>
                        <canvas id="trendsChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Weight Analysis Chart -->
                <div class="col s12 l6">
                    <div class="chart-container">
                        <h5 class="chart-title">Analisis Bobot Paket</h5>
                        <canvas id="weightChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Global variables for charts
    let statusChart, revenueChart, trendsChart, weightChart;

    // Welcome Alert Functions
    function closeWelcomeAlert() {
        const alert = document.getElementById('welcomeAlert');
        if (alert) {
            alert.style.animation = 'slideOut 0.5s ease-out';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }
    }

    // Auto close welcome alert after 5 seconds
    setTimeout(() => {
        closeWelcomeAlert();
    }, 5000);

    // Helper function to format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Helper function to format weight
    function formatWeight(weight) {
        return parseFloat(weight).toFixed(2) + ' kg';
    }

    // Initialize dashboard
    $(document).ready(function() {


        loadDashboardData();
        setInterval(loadDashboardData, 30000); // Refresh every 30 seconds
    });

    function loadDashboardData() {
        $.ajax({
            url: origin + "/api/jastip",
            method: 'GET',
            success: function(data) {
                updateStatistics(data);
                updateCharts(data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading dashboard data:', error);
                showLoadingSpinners();
            }
        });
    }

    function showLoadingSpinners() {
        $('.stat-value').html('<div class="loading-spinner"></div>');
    }

    function updateStatistics(data) {
        // Calculate statistics
        const totalPaket = data.length;
        const totalPending = data.filter(item => item.status === "Pending").length;
        const totalProses = data.filter(item => item.status === "Proses Pengiriman").length;
        const totalSampai = data.filter(item => item.status === "Sampai di Tujuan").length;
        const totalSelesai = data.filter(item => item.status === "Selesai").length;
        const totalBatal = data.filter(item => item.status === "Batal").length;

        // Calculate total revenue (from completed packages)
        const totalPendapatan = data
            .filter(item => item.status === "Selesai")
            .reduce((sum, item) => sum + parseFloat(item.biaya || 0), 0);

        // Calculate total weight
        const totalBobot = data
            .reduce((sum, item) => sum + parseFloat(item.bobot || 0), 0);

        // Update DOM elements
        $('#total-paket').text(totalPaket.toLocaleString());
        $('#total-pending').text(totalPending.toLocaleString());
        $('#total-proses').text(totalProses.toLocaleString());
        $('#total-selesai').text(totalSelesai.toLocaleString());
        $('#total-pendapatan').text(formatCurrency(totalPendapatan));
        $('#total-bobot').text(formatWeight(totalBobot));
        // Optionally, you can add display for totalSampai and totalBatal if needed
    }

    function updateCharts(data) {
        updateStatusChart(data);
        updateRevenueChart(data);
        updateTrendsChart(data);
        updateWeightChart(data);
    }

    function updateStatusChart(data) {
        const statusCounts = {
            pending: data.filter(item => item.status === "Pending").length,
            proses: data.filter(item => item.status === "Proses Pengiriman").length,
            sampai: data.filter(item => item.status === "Sampai di Tujuan").length,
            selesai: data.filter(item => item.status === "Selesai").length,
            batal: data.filter(item => item.status === "Batal").length
        };

        const ctx = document.getElementById('statusChart').getContext('2d');

        if (statusChart) {
            statusChart.destroy();
        }

        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Proses Pengiriman', 'Sampai di Tujuan', 'Selesai', 'Batal'],
                datasets: [{
                    data: [
                        statusCounts.pending,
                        statusCounts.proses,
                        statusCounts.sampai,
                        statusCounts.selesai,
                        statusCounts.batal
                    ],
                    backgroundColor: [
                        '#fd79a8', // Pending
                        '#ffeaa7', // Proses Pengiriman
                        '#00cec9', // Sampai di Tujuan
                        '#06ffa5', // Selesai
                        '#636e72' // Batal
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    function updateRevenueChart(data) {
        // Group revenue by month
        const monthlyRevenue = {};
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Initialize months
        months.forEach(month => {
            monthlyRevenue[month] = 0;
        });

        // Calculate monthly revenue from completed packages
        data.filter(item => item.status === "Selesai").forEach(item => {
            if (item.created_at) {
                const date = new Date(item.created_at);
                const month = months[date.getMonth()];
                monthlyRevenue[month] += parseFloat(item.biaya || 0);
            }
        });

        const ctx = document.getElementById('revenueChart').getContext('2d');

        if (revenueChart) {
            revenueChart.destroy();
        }

        revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: Object.values(monthlyRevenue),
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatCurrency(value);
                            }
                        }
                    }
                }
            }
        });
    }

    function updateTrendsChart(data) {
        // Get last 7 days data
        const last7Days = [];
        const dailyCounts = {};

        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            const dayName = date.toLocaleDateString('id-ID', {
                weekday: 'short'
            });
            last7Days.push(dayName);
            dailyCounts[dateStr] = 0;
        }

        // Count packages by day
        data.forEach(item => {
            if (item.created_at) {
                const itemDate = new Date(item.created_at).toISOString().split('T')[0];
                if (dailyCounts.hasOwnProperty(itemDate)) {
                    dailyCounts[itemDate]++;
                }
            }
        });

        const ctx = document.getElementById('trendsChart').getContext('2d');

        if (trendsChart) {
            trendsChart.destroy();
        }

        trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [{
                    label: 'Jumlah Paket',
                    data: Object.values(dailyCounts),
                    borderColor: 'rgba(0, 206, 201, 1)',
                    backgroundColor: 'rgba(0, 206, 201, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(0, 206, 201, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    function updateWeightChart(data) {
        // Group packages by weight ranges
        const weightRanges = {
            '0-1 kg': 0,
            '1-5 kg': 0,
            '5-10 kg': 0,
            '10+ kg': 0
        };

        data.forEach(item => {
            const weight = parseFloat(item.bobot || 0);
            if (weight <= 1) {
                weightRanges['0-1 kg']++;
            } else if (weight <= 5) {
                weightRanges['1-5 kg']++;
            } else if (weight <= 10) {
                weightRanges['5-10 kg']++;
            } else {
                weightRanges['10+ kg']++;
            }
        });

        const ctx = document.getElementById('weightChart').getContext('2d');

        if (weightChart) {
            weightChart.destroy();
        }

        weightChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: Object.keys(weightRanges),
                datasets: [{
                    data: Object.values(weightRanges),
                    backgroundColor: [
                        'rgba(162, 155, 254, 0.8)',
                        'rgba(108, 92, 231, 0.8)',
                        'rgba(255, 234, 167, 0.8)',
                        'rgba(250, 177, 160, 0.8)'
                    ],
                    borderColor: [
                        'rgba(162, 155, 254, 1)',
                        'rgba(108, 92, 231, 1)',
                        'rgba(255, 234, 167, 1)',
                        'rgba(250, 177, 160, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
</script>
<?= $this->endSection() ?>