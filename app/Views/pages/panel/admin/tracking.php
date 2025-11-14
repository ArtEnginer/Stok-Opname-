<?= $this->extend('layouts/panel/main') ?>

<?= $this->section('head') ?>
<title>Tracking Jastip - JASTIP</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white text-center py-5">
                    <h1 class="display-4 mb-3">
                        <i class="fas fa-shipping-fast me-3"></i>
                        Tracking Jastip
                    </h1>
                    <p class="lead mb-4">Lacak status pengiriman jastip Anda dengan mudah</p>

                    <!-- Search Form -->
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <input type="text"
                                    class="form-control"
                                    id="trackingInput"
                                    placeholder="Masukkan nomor resi..."
                                    style="border-radius: 25px 0 0 25px;">
                                <button class="btn btn-warning"
                                    type="button"
                                    id="trackButton"
                                    style="border-radius: 0 25px 25px 0;">
                                    <i class="fas fa-search"></i> Lacak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Section -->
    <div id="loadingSection" class="row justify-content-center d-none">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5>Mencari data pengiriman...</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Section -->
    <div id="errorSection" class="row justify-content-center d-none">
        <div class="col-md-8">
            <div class="alert alert-danger shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Data Tidak Ditemukan</h5>
                        <p class="mb-0" id="errorMessage">Nomor resi yang Anda masukkan tidak ditemukan. Pastikan nomor resi benar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="d-none">
        <!-- Package Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2"></i>
                            Informasi Pengiriman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Nomor Resi</label>
                                    <div class="fw-bold" id="displayResi">-</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Nama Penerima</label>
                                    <div class="fw-bold" id="displayNama">-</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small">No. Telepon</label>
                                    <div class="fw-bold" id="displayTelepon">-</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Biaya</label>
                                    <div class="fw-bold text-success" id="displayBiaya">-</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Alamat Penerima</label>
                                    <div class="fw-bold" id="displayAlamat">-</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Bobot</label>
                                    <div class="fw-bold" id="displayBobot">-</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Keterangan</label>
                                    <div class="fw-bold" id="displayKeterangan">-</div>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="text-muted small">Catatan</label>
                                    <div class="fw-bold" id="displayCatatan">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Status Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="status-icon mb-3">
                            <i class="fas fa-truck fa-3x text-primary" id="statusIcon"></i>
                        </div>
                        <h4 class="text-primary mb-2" id="currentStatus">Status Terkini</h4>
                        <p class="text-muted" id="lastUpdate">Terakhir diupdate: -</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline/History Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Riwayat Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline" id="statusTimeline">
                            <!-- Timeline items will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .info-item label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #28a745;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .timeline-item:last-child::after {
        content: '';
        position: absolute;
        left: -30px;
        bottom: -10px;
        width: 18px;
        height: 2px;
        background: white;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
    }

    .status-icon {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .input-group-lg .form-control {
        font-size: 1.1rem;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trackingInput = document.getElementById('trackingInput');
        const trackButton = document.getElementById('trackButton');
        const loadingSection = document.getElementById('loadingSection');
        const errorSection = document.getElementById('errorSection');
        const resultSection = document.getElementById('resultSection');

        // Event listeners
        trackButton.addEventListener('click', performTracking);
        trackingInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performTracking();
            }
        });

        function performTracking() {
            const resi = trackingInput.value.trim();

            if (!resi) {
                alert('Silakan masukkan nomor resi');
                return;
            }

            // Hide all sections
            hideAllSections();

            // Show loading
            loadingSection.classList.remove('d-none');

            // Call API
            fetch(`<?= base_url() ?>/api/v2/jastip/track/${encodeURIComponent(resi)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Data tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    hideAllSections();
                    displayTrackingResult(data);
                })
                .catch(error => {
                    hideAllSections();
                    showError(error.message);
                });
        }

        function hideAllSections() {
            loadingSection.classList.add('d-none');
            errorSection.classList.add('d-none');
            resultSection.classList.add('d-none');
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            errorSection.classList.remove('d-none');
        }

        function displayTrackingResult(data) {
            // Populate package info
            document.getElementById('displayResi').textContent = data.nomor_resi;
            document.getElementById('displayNama').textContent = data.nama_penerima;
            document.getElementById('displayTelepon').textContent = data.no_telp_penerima;
            document.getElementById('displayBiaya').textContent = `Rp ${formatNumber(data.biaya)}`;
            document.getElementById('displayAlamat').textContent = data.alamat_penerima;
            document.getElementById('displayBobot').textContent = `${data.bobot} kg`;
            document.getElementById('displayKeterangan').textContent = data.keterangan || '-';
            document.getElementById('displayCatatan').textContent = data.catatan || '-';

            // Update current status
            document.getElementById('currentStatus').textContent = data.status;
            document.getElementById('lastUpdate').textContent = `Terakhir diupdate: ${formatDate(data.updated_at)}`;

            // Update status icon based on status
            updateStatusIcon(data.status);

            // Populate timeline
            populateTimeline(data.status_history);

            // Show result section
            resultSection.classList.remove('d-none');
        }

        function updateStatusIcon(status) {
            const statusIcon = document.getElementById('statusIcon');
            const iconMap = {
                'Proses Pengiriman': 'fas fa-truck',
                'Dalam Perjalanan': 'fas fa-shipping-fast',
                'Tiba di Tujuan': 'fas fa-map-marker-alt',
                'Selesai': 'fas fa-check-circle',
                'Dibatalkan': 'fas fa-times-circle'
            };

            const colorMap = {
                'Proses Pengiriman': 'text-warning',
                'Dalam Perjalanan': 'text-info',
                'Tiba di Tujuan': 'text-primary',
                'Selesai': 'text-success',
                'Dibatalkan': 'text-danger'
            };

            statusIcon.className = `${iconMap[status] || 'fas fa-question-circle'} fa-3x ${colorMap[status] || 'text-secondary'}`;
        }

        function populateTimeline(statusHistory) {
            const timeline = document.getElementById('statusTimeline');
            timeline.innerHTML = '';

            // Sort by created_at descending (newest first)
            const sortedHistory = statusHistory.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            sortedHistory.forEach((item, index) => {
                const timelineItem = document.createElement('div');
                timelineItem.className = 'timeline-item';

                timelineItem.innerHTML = `
                <div class="timeline-content">
                    <h6 class="mb-1">${item.status}</h6>
                    <small class="text-muted">${formatDate(item.created_at)}</small>
                </div>
            `;

                timeline.appendChild(timelineItem);
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    });
</script>
<?= $this->endSection() ?>