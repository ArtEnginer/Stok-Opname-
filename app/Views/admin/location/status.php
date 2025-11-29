<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Lokasi - Stock Opname</h1>
    </div>

    <!-- Filter SO Session -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Sesi Stock Opname</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="soSessionSelect">
                        <option value="">-- Pilih Sesi SO --</option>
                        <!-- Will be populated via AJAX -->
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary" id="btnLoadStatus">
                        <i class="fas fa-search"></i> Tampilkan Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Status Cards -->
    <div class="row" id="locationStatusCards">
        <!-- Will be populated dynamically -->
    </div>

    <!-- Location Status Table -->
    <div class="card shadow mb-4" id="locationStatusTable" style="display:none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Status Lokasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode Lokasi</th>
                            <th>Nama Lokasi</th>
                            <th>Departemen</th>
                            <th>Total Item SO</th>
                            <th>Sudah Dihitung</th>
                            <th>Belum Dihitung</th>
                            <th>Progress</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="locationTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Load SO sessions
        loadSOSessions();

        // Load status button
        $('#btnLoadStatus').click(function() {
            const sessionId = $('#soSessionSelect').val();
            if (sessionId) {
                loadLocationStatus(sessionId);
            } else {
                Swal.fire('Peringatan', 'Pilih sesi SO terlebih dahulu', 'warning');
            }
        });

        function loadSOSessions() {
            // You need to create an API endpoint to get SO sessions
            // For now, this is a placeholder
            $.get('<?= base_url('api/so-sessions') ?>', function(response) {
                if (response.success) {
                    const select = $('#soSessionSelect');
                    select.empty();
                    select.append('<option value="">-- Pilih Sesi SO --</option>');

                    response.data.forEach(function(session) {
                        select.append(`<option value="${session.id}">${session.session_code} - ${session.session_date}</option>`);
                    });
                }
            }).fail(function() {
                console.error('Failed to load SO sessions');
            });
        }

        function loadLocationStatus(sessionId) {
            $.get('<?= base_url('admin/location/getSOStatus/') ?>' + sessionId, function(response) {
                if (response.success) {
                    displayLocationCards(response.data);
                    displayLocationTable(response.data);
                }
            }).fail(function() {
                Swal.fire('Error', 'Gagal memuat status lokasi', 'error');
            });
        }

        function displayLocationCards(locations) {
            const container = $('#locationStatusCards');
            container.empty();

            // Summary cards
            const totalLocations = locations.length;
            const completedLocations = locations.filter(loc => loc.pending_so == 0 && loc.total_so > 0).length;
            const inProgressLocations = locations.filter(loc => loc.completed_so > 0 && loc.pending_so > 0).length;
            const notStartedLocations = locations.filter(loc => loc.total_so == 0).length;

            const cards = `
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Lokasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${totalLocations}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${completedLocations}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Dalam Proses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${inProgressLocations}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-spinner fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Dimulai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${notStartedLocations}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            container.html(cards);
        }

        function displayLocationTable(locations) {
            const tbody = $('#locationTableBody');
            tbody.empty();

            locations.forEach(function(loc) {
                const total = parseInt(loc.total_so) || 0;
                const completed = parseInt(loc.completed_so) || 0;
                const pending = parseInt(loc.pending_so) || 0;
                const progress = total > 0 ? Math.round((completed / total) * 100) : 0;

                let statusBadge = '';
                if (total === 0) {
                    statusBadge = '<span class="badge badge-secondary">Belum Dimulai</span>';
                } else if (pending === 0) {
                    statusBadge = '<span class="badge badge-success">Selesai</span>';
                } else if (completed > 0) {
                    statusBadge = '<span class="badge badge-warning">Dalam Proses</span>';
                }

                const progressBar = `
                <div class="progress">
                    <div class="progress-bar ${progress === 100 ? 'bg-success' : 'bg-primary'}" 
                         role="progressbar" 
                         style="width: ${progress}%">
                        ${progress}%
                    </div>
                </div>
            `;

                const row = `
                <tr>
                    <td>${loc.kode_lokasi}</td>
                    <td>${loc.nama_lokasi}</td>
                    <td>${loc.departemen || '-'}</td>
                    <td class="text-center">${total}</td>
                    <td class="text-center text-success">${completed}</td>
                    <td class="text-center text-danger">${pending}</td>
                    <td>${progressBar}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;

                tbody.append(row);
            });

            $('#locationStatusTable').show();
        }
    });
</script>
<?= $this->endSection() ?>