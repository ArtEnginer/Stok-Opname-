<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>

<h1 class="page-title">Kelola Pengiriman Paket</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-12 text-end">
                    <?php if (auth()->user()->inGroup('gudang1')) : ?>
                        <button class="btn waves-effect waves-light green btn-popup rounded" data-target="add-shipment" type="button">
                            <i class="material-icons left">add</i>Buat Pengiriman Baru
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table class="striped highlight responsive-table" id="table-shipment" width="100%">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('popup') ?>

<!-- Form Tambah Pengiriman -->
<div class="popup side" data-page="add-shipment">
    <h1>Buat Pengiriman Baru</h1>
    <br>
    <form id="form-add-shipment" class="row">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

        <div class="input-field col s12">
            <input type="text" name="nomor_kontainer" id="add-nomor_kontainer" required>
            <label for="add-nomor_kontainer">Nomor Kontainer</label>
        </div>

        <div class="input-field col s12">
            <input type="text" name="nama_kontainer" id="add-nama_kontainer">
            <label for="add-nama_kontainer">Nama Kontainer (Opsional)</label>
        </div>

        <div class="input-field col s12 m6">
            <p>Tanggal Pengiriman</p>
            <input type="datetime-local" name="tanggal_pengiriman" id="add-tanggal_pengiriman" required>
        </div>

        <div class="input-field col s12 m6">
            <p>Estimasi Sampai</p>
            <input type="datetime-local" name="estimasi_sampai" id="add-estimasi_sampai">
        </div>

        <div class="input-field col s12">
            <textarea name="keterangan" id="add-keterangan" class="materialize-textarea"></textarea>
            <label for="add-keterangan">Keterangan</label>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit">
                    <i class="material-icons left">save</i>Simpan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Form Edit Pengiriman -->
<div class="popup side" data-page="edit-shipment">
    <h1>Edit Pengiriman</h1>
    <br>
    <form id="form-edit-shipment" class="row">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <input type="hidden" name="id" id="edit-id">

        <div class="input-field col s12">
            <input type="text" name="nomor_kontainer" id="edit-nomor_kontainer" required>
            <label for="edit-nomor_kontainer">Nomor Kontainer</label>
        </div>

        <div class="input-field col s12">
            <input type="text" name="nama_kontainer" id="edit-nama_kontainer">
            <label for="edit-nama_kontainer">Nama Kontainer</label>
        </div>

        <div class="input-field col s12 m6">
            <p>Tanggal Pengiriman</p>
            <input type="datetime-local" name="tanggal_pengiriman" id="edit-tanggal_pengiriman" required>

        </div>

        <div class="input-field col s12 m6">
            <p>Estimasi Sampai</p>
            <input type="datetime-local" name="estimasi_sampai" id="edit-estimasi_sampai">
        </div>

        <div class="input-field col s12">
            <select name="status_pengiriman" id="edit-status_pengiriman" required>
                <option value="" disabled>Pilih Status</option>
                <option value="Persiapan">Persiapan</option>
                <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                <option value="Sampai Tujuan">Sampai Tujuan</option>
            </select>
            <label>Status Pengiriman</label>
        </div>

        <div class="input-field col s12">
            <textarea name="keterangan" id="edit-keterangan" class="materialize-textarea"></textarea>
            <label for="edit-keterangan">Keterangan</label>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit">
                    <i class="material-icons left">save</i>Update
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Popup Kelola Paket dalam Pengiriman -->
<div class="popup side" data-page="manage-packages">
    <h1 id="manage-packages-title">Kelola Paket Pengiriman</h1>
    <br>
    <input type="hidden" id="current-shipment-id">

    <div class="row">
        <div class="col s12 m6">
            <h5>Paket dalam Pengiriman</h5>
            <div class="table-wrapper">
                <table class="striped" id="table-shipment-packages">
                    <thead>
                        <tr>
                            <th>Resi</th>
                            <th>Penerima</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="col s12 m6">
            <h5>Paket Tersedia</h5>
            <div class="table-wrapper">
                <table class="striped" id="table-available-packages">
                    <thead>
                        <tr>
                            <th>
                                <label>
                                    <input type="checkbox" id="select-all-packages" />
                                    <span></span>
                                </label>
                            </th>
                            <th>Resi</th>
                            <th>Penerima</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="center" style="margin-top: 20px;">
                <button class="btn waves-effect waves-light blue" id="btn-add-packages">
                    <i class="material-icons left">add</i>Tambahkan Paket Terpilih
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Popup Proses Pengiriman -->
<div class="popup side" data-page="process-shipment">
    <h1>Proses Pengiriman</h1>
    <br>
    <form id="form-process-shipment" class="row">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <input type="hidden" id="process-shipment-id">

        <div class="col s12">
            <p><strong>Nomor Kontainer:</strong> <span id="process-nomor_kontainer"></span></p>
            <p><strong>Total Paket:</strong> <span id="process-total_paket"></span></p>
        </div>

        <div class="input-field col s12">
            <select name="status" id="process-status" required>
                <option value="" disabled selected>Pilih Status Baru</option>
                <option value="Persiapan">Persiapan</option>
                <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                <option value="Sampai Tujuan">Sampai Tujuan</option>
                <option value="Selesai">Selesai</option>
            </select>
            <label>Status Pengiriman</label>
        </div>

        <!-- <div class="input-field col s12">
            <input type="date" name="estimasi_sampai" id="process-estimasi_sampai">
            <label for="process-estimasi_sampai">Update Estimasi Sampai</label>
        </div> -->

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit">
                    <i class="material-icons left">send</i>Proses Pengiriman
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const table = {
        shipment: $("#table-shipment").DataTable({
            responsive: true,
            ajax: {
                url: baseUrl + "/api/shipment",
                dataSrc: "",
            },
            order: [
                [5, "desc"]
            ],
            columns: [{
                    title: "#",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    title: "Nomor Kontainer",
                    data: "nomor_kontainer"
                },
                {
                    title: "Tanggal Kirim",
                    data: "tanggal_pengiriman",
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    }
                },
                {
                    title: "Est. Sampai",
                    data: "estimasi_sampai",
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                    }
                },
                {
                    title: "Status",
                    data: "status_pengiriman",
                    render: function(data) {
                        const statusMap = {
                            'Persiapan': '<span class="badge orange">Persiapan</span>',
                            'Dalam Perjalanan': '<span class="badge blue">Dalam Perjalanan</span>',
                            'Sampai Tujuan': '<span class="badge teal">Sampai Tujuan</span>',
                        };
                        return statusMap[data] || `<span class="badge grey">${data}</span>`;
                    }
                },
                {
                    title: "Paket",
                    data: "total_paket"
                },
                {
                    title: "Bobot",
                    data: "total_bobot",
                    render: function(data) {
                        return data + ' kg';
                    }
                },
                {
                    title: "Aksi",
                    data: "id",
                    render: (data, type, row) => {
                        return `<div class="table-control">
                            <a role="button" class="btn waves-effect waves-light btn-action btn-popup blue" data-action="manage-packages" data-id="${data}" title="Kelola Paket"><i class="material-icons">inventory</i></a>
                            <a role="button" class="btn waves-effect waves-light btn-action btn-popup teal" data-action="process" data-id="${data}" title="Proses Pengiriman"><i class="material-icons">local_shipping</i></a>
                            <a class="btn waves-effect waves-light purple" href="${baseUrl}/shipment/${data}/delivery-note" target="_blank" title="Cetak Surat Jalan"><i class="material-icons">print</i></a>
                            <a role="button" class="btn waves-effect waves-light btn-action btn-popup orange darken-2" data-target="edit-shipment" data-action="edit" data-id="${data}" title="Edit"><i class="material-icons">edit</i></a>
                    <?php if (auth()->user()->inGroup('gudang1')) : ?>
                            
                            <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}" title="Hapus"><i class="material-icons">delete</i></a>
                    <?php endif; ?>
                            </div>`;
                    },
                },
            ],
        }),
    };

    // Submit form tambah pengiriman
    $("form#form-add-shipment").on("submit", function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);


        $.ajax({
            type: "POST",
            url: baseUrl + "/api/shipment",
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                form.reset();
                table.shipment.ajax.reload();
                Toast.fire({
                    icon: "success",
                    title: data.messages?.success || "Pengiriman berhasil dibuat",
                });
                $(".btn-popup-close").trigger("click");
            },
            error: (xhr) => {
                const errors = xhr.responseJSON?.messages || {};
                for (const field in errors) {
                    Toast.fire({
                        icon: "error",
                        title: errors[field],
                    });
                }
            },
        });
    });

    // Submit form edit pengiriman
    $("form#form-edit-shipment").on("submit", function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const id = $("#edit-id").val();

        $.ajax({
            type: "POST",
            url: baseUrl + "/api/shipment/" + id,
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                table.shipment.ajax.reload();
                Toast.fire({
                    icon: "success",
                    title: data.messages?.success || "Pengiriman berhasil diupdate",
                });
                $(".btn-popup-close").trigger("click");
            },
            error: (xhr) => {
                const errors = xhr.responseJSON?.messages || {};
                for (const field in errors) {
                    Toast.fire({
                        icon: "error",
                        title: errors[field],
                    });
                }
            },
        });
    });

    // Submit form proses pengiriman
    $("form#form-process-shipment").on("submit", function(e) {
        e.preventDefault();
        const form = this;
        const id = $("#process-shipment-id").val();

        const formData = {
            status: $("#process-status").val(),
            estimasi_sampai: $("#process-estimasi_sampai").val() || null
        };
        $.ajax({
            type: "POST",
            url: baseUrl + "/api/shipment/" + id + "/process",
            data: formData,
            success: (data) => {
                form.reset();
                table.shipment.ajax.reload();
                Toast.fire({
                    icon: "success",
                    title: data.messages?.success || "Status pengiriman berhasil diupdate",
                });
                $(".btn-popup-close").trigger("click");
            },
            error: (xhr) => {
                Toast.fire({
                    icon: "error",
                    title: xhr.responseJSON?.messages || "Gagal memproses pengiriman",
                });
            },
        });
    });

    // Handle action buttons
    $("body").on("click", ".btn-action", function(e) {
        e.preventDefault();
        const action = $(this).data("action");
        const id = $(this).data("id");

        switch (action) {
            case "delete":
                Swal.fire({
                    title: "Apakah anda yakin ingin menghapus pengiriman ini?",
                    text: "Paket dalam pengiriman akan dikembalikan ke status pending",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: baseUrl + "/api/shipment/" + id,
                            success: (data) => {
                                table.shipment.ajax.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: data.messages?.success || "Pengiriman berhasil dihapus",
                                });
                            },
                        });
                    }
                });
                break;

            case "edit":
                $.ajax({
                    url: baseUrl + "/api/shipment/" + id,
                    success: (data) => {
                        $("#edit-id").val(data.id);
                        $("#edit-nomor_kontainer").val(data.nomor_kontainer);
                        $("#edit-nama_kontainer").val(data.nama_kontainer);
                        $("#edit-status_pengiriman").val(data.status_pengiriman);
                        $("#edit-keterangan").val(data.keterangan);

                        if (data.tanggal_pengiriman) {
                            const tglKirim = new Date(data.tanggal_pengiriman);
                            const offset = tglKirim.getTimezoneOffset();
                            const localTime = new Date(tglKirim.getTime() - (offset * 60 * 1000));
                            $("#edit-tanggal_pengiriman").val(localTime.toISOString().slice(0, 16));
                        } else {
                            $("#edit-tanggal_pengiriman").val("");
                        }
                        if (data.estimasi_sampai) {
                            const tglEst = new Date(data.estimasi_sampai);
                            const offset = tglEst.getTimezoneOffset();
                            const localTime = new Date(tglEst.getTime() - (offset * 60 * 1000));
                            $("#edit-estimasi_sampai").val(localTime.toISOString().slice(0, 16));
                        } else {
                            $("#edit-estimasi_sampai").val("");
                        }
                        M.updateTextFields();
                        M.textareaAutoResize($("#edit-keterangan"));
                        M.FormSelect.init($("#edit-status_pengiriman"));
                    },
                });
                break;

            case "manage-packages":
                openManagePackages(id);
                break;

            case "process":
                $.ajax({
                    url: baseUrl + "/api/shipment/" + id,
                    success: (data) => {
                        $("#process-shipment-id").val(data.id);
                        $("#process-nomor_kontainer").text(data.nomor_kontainer);
                        $("#process-total_paket").text(data.total_paket + " paket");
                        $("#process-status").val("");

                        M.FormSelect.init($("#process-status"));
                        M.updateTextFields();

                        // Open popup
                        $(".popup[data-page='process-shipment']").addClass("active");
                        $("body").addClass("popup-open");
                    },
                });
                break;
        }
    });

    // Manage packages functions
    function openManagePackages(shipmentId) {
        $("#current-shipment-id").val(shipmentId);

        // Load shipment info
        $.ajax({
            url: baseUrl + "/api/shipment/" + shipmentId,
            success: (data) => {
                $("#manage-packages-title").text("Kelola Paket: " + data.nomor_kontainer);
                loadShipmentPackages(shipmentId);
                loadAvailablePackages();

                $(".popup[data-page='manage-packages']").addClass("active");
                $("body").addClass("popup-open");
            },
        });
    }

    function loadShipmentPackages(shipmentId) {
        $.ajax({
            url: baseUrl + "/api/shipment/" + shipmentId,
            success: (data) => {
                const tbody = $("#table-shipment-packages tbody");
                tbody.empty();

                if (data.packages && data.packages.length > 0) {
                    data.packages.forEach(pkg => {
                        tbody.append(`
                            <tr>
                                <td>${pkg.nomor_resi}</td>
                                <td>${pkg.nama_penerima}</td>
                                <td>${pkg.bobot} kg</td>
                                <td>
                                    <button class="btn-small red btn-remove-package" data-shipment-id="${shipmentId}" data-jastip-id="${pkg.id}">
                                        <i class="material-icons">remove</i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="4" class="center">Belum ada paket</td></tr>');
                }
            },
        });
    }

    function loadAvailablePackages() {
        $.ajax({
            url: baseUrl + "/api/shipment/available-packages",
            success: (data) => {
                const tbody = $("#table-available-packages tbody");
                tbody.empty();

                if (data && data.length > 0) {
                    data.forEach(pkg => {
                        tbody.append(`
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="package-checkbox" value="${pkg.id}" />
                                        <span></span>
                                    </label>
                                </td>
                                <td>${pkg.nomor_resi}</td>
                                <td>${pkg.nama_penerima}</td>
                                <td>${pkg.bobot} kg</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="4" class="center">Tidak ada paket tersedia</td></tr>');
                }
            },
        });
    }

    // Select all packages
    $(document).on("change", "#select-all-packages", function() {
        $(".package-checkbox").prop("checked", $(this).is(":checked"));
    });

    // Add packages to shipment
    $(document).on("click", "#btn-add-packages", function() {
        const shipmentId = $("#current-shipment-id").val();
        const packageIds = [];

        $(".package-checkbox:checked").each(function() {
            packageIds.push($(this).val());
        });

        if (packageIds.length === 0) {
            Toast.fire({
                icon: "warning",
                title: "Pilih minimal satu paket",
            });
            return;
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "/api/shipment/" + shipmentId + "/add-packages",
            data: {
                package_ids: packageIds
            },
            success: (data) => {
                Toast.fire({
                    icon: "success",
                    title: data.messages?.success || "Paket berhasil ditambahkan",
                });
                loadShipmentPackages(shipmentId);
                loadAvailablePackages();
                table.shipment.ajax.reload();
                $("#select-all-packages").prop("checked", false);
            },
            error: (xhr) => {
                Toast.fire({
                    icon: "error",
                    title: xhr.responseJSON?.messages || "Gagal menambahkan paket",
                });
            },
        });
    });

    // Remove package from shipment
    $(document).on("click", ".btn-remove-package", function() {
        const shipmentId = $(this).data("shipment-id");
        const jastipId = $(this).data("jastip-id");

        Swal.fire({
            title: "Hapus paket dari pengiriman?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: baseUrl + "/api/shipment/" + shipmentId + "/remove-package/" + jastipId,
                    success: (data) => {
                        Toast.fire({
                            icon: "success",
                            title: data.messages?.success || "Paket berhasil dihapus",
                        });
                        loadShipmentPackages(shipmentId);
                        loadAvailablePackages();
                        table.shipment.ajax.reload();
                    },
                });
            }
        });
    });

    // Helper function to format date for database
    function formatDateForDB(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day} 00:00:00`;
    }

    $(document).ready(function() {
        // Initialize components
        M.FormSelect.init(document.querySelectorAll("select"));
        M.textareaAutoResize($("textarea"));

        // Initialize datepickers
        M.Datepicker.init(document.querySelectorAll(".datepicker"), {
            format: 'dd/mm/yyyy',
            autoClose: true,
        });

        // Load data
        table.shipment.ajax.reload();
        $(".preloader").slideUp();
    });
</script>
<?= $this->endSection() ?>