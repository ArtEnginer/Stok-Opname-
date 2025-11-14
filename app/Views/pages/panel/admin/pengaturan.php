<?= $this->extend('layouts/panel/main') ?>
<?= $this->section('main') ?>

<h1 class="page-title">Pengaturan Sistem</h1>
<div class="page-wrapper">
    <div class="page">
        <div class="container">
            <div class="row">
                <div class="col-12 text-end">
                    <button class="btn waves-effect waves-light green btn-popup rounded" data-target="add" type="button"><i class="material-icons left">add</i>Tambah Pengaturan</button>
                </div>
                <div class="col-12">
                    <!-- alert nominal yang digunakan adalah data terupdate -->
                    <div class="alert alert-info">
                        <strong>Info:</strong> Nominal yang digunakan adalah data terupdate pada table.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table class="striped highlight responsive-table" id="table-pengaturan" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('popup') ?>
<!-- Form Tambah Pengaturan -->
<div class="popup side" data-page="add">
    <h1>Tambah Pengaturan</h1>
    <br>
    <form id="form-add" class="row">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

        <div class="input-field col s12">
            <input type="number" name="nominal_per_kg" id="add-nominal_per_kg" min="0" step="0.01" required>
            <label for="add-nominal_per_kg">Nominal Per KG (Rp)</label>
            <span class="helper-text">Masukkan nominal biaya per kilogram</span>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>

<!-- Form Edit Pengaturan -->
<div class="popup side" data-page="edit">
    <h1>Edit Pengaturan</h1>
    <br>
    <form id="form-edit" class="row">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <input type="hidden" name="id" id="edit-id">

        <div class="input-field col s12">
            <input type="number" name="nominal_per_kg" id="edit-nominal_per_kg" min="0" step="0.01" required>
            <label for="edit-nominal_per_kg">Nominal Per KG (Rp)</label>
            <span class="helper-text">Masukkan nominal biaya per kilogram</span>
        </div>

        <div class="row">
            <div class="input-field col s12 center">
                <button class="btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Simpan</button>
            </div>
        </div>
    </form>
</div>

<!-- Popup Detail Pengaturan -->
<div class="popup side" data-page="detail">
    <h1>Detail Pengaturan</h1>
    <br>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <p><strong>ID:</strong> <span id="detail-id"></span></p>
                    <p><strong>Nominal Per KG:</strong> <span id="detail-nominal_per_kg"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const table = {
        pengaturan: $("#table-pengaturan").DataTable({
            responsive: true,
            ajax: {
                url: baseUrl + "/api/pengaturan",
                dataSrc: "",
            },
            order: [
                [0, "asc"]
            ],
            columns: [{
                    title: "#",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    title: "Nominal Per KG",
                    data: "nominal_per_kg",
                    render: function(data) {
                        return data ? 'Rp ' + parseFloat(data).toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 2
                        }) : '-';
                    }
                },
                {
                    // created_at
                    data: "created_at",
                    title: "Dibuat Pada",
                    render: function(data) {
                        return data ? new Date(data).toLocaleString('id-ID') : '-';
                    }
                },
                // updated_at
                {
                    data: "updated_at",
                    title: "Diperbarui Pada",
                    render: function(data) {
                        return data ? new Date(data).toLocaleString('id-ID') : '-';
                    }
                },
                {
                    title: "Aksi",
                    data: "id",
                    render: (data, type, row) => {
                        return `<div class="table-control">
            <a role="button" class="btn waves-effect waves-light btn-action btn-popup blue" data-target="detail" data-action="detail" data-id="${data}"><i class="material-icons">info</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action btn-popup orange darken-2" data-target="edit" data-action="edit" data-id="${data}"><i class="material-icons">edit</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}"><i class="material-icons">delete</i></a>
          </div>`;
                    },
                },
            ],
        }),
    };

    // Submit form tambah pengaturan
    $("form#form-add").on("submit", function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        const elements = form.elements;
        for (let i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "/api/pengaturan",
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                form.reset();
                table.pengaturan.ajax.reload();
                if (data.messages) {
                    $.each(data.messages, function(icon, text) {
                        Toast.fire({
                            icon: icon,
                            title: text,
                        });
                    });
                }
                $(".btn-popup-close").trigger("click");
            },
            complete: () => {
                for (let i = 0, len = elements.length; i < len; ++i) {
                    elements[i].readOnly = false;
                }
            },
            error: (xhr) => {
                const errors = xhr.responseJSON?.messages?.errors || {};
                for (const field in errors) {
                    Toast.fire({
                        icon: "error",
                        title: errors[field],
                    });
                }
            },
        });
    });

    // Submit form edit pengaturan
    $("form#form-edit").on("submit", function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const id = $("#edit-id").val();

        const elements = form.elements;
        for (let i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "/api/pengaturan/" + id,
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                form.reset();
                table.pengaturan.ajax.reload();
                if (data.messages) {
                    $.each(data.messages, function(icon, text) {
                        Toast.fire({
                            icon: icon,
                            title: text,
                        });
                    });
                }
                $(".btn-popup-close").trigger("click");
            },
            complete: () => {
                for (let i = 0, len = elements.length; i < len; ++i) {
                    elements[i].readOnly = false;
                }
            },
            error: (xhr) => {
                const errors = xhr.responseJSON?.messages?.errors || {};
                for (const field in errors) {
                    Toast.fire({
                        icon: "error",
                        title: errors[field],
                    });
                }
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
                    title: "Apakah anda yakin ingin menghapus data ini?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: baseUrl + "/api/pengaturan/" + id,
                            cache: false,
                            success: (data) => {
                                table.pengaturan.ajax.reload();
                                if (data.messages) {
                                    $.each(data.messages, function(icon, text) {
                                        Toast.fire({
                                            icon: icon,
                                            title: text,
                                        });
                                    });
                                }
                            },
                            error: (xhr) => {
                                const errors = xhr.responseJSON?.messages || {};
                                for (const field in errors) {
                                    Toast.fire({
                                        icon: "error",
                                        title: errors[field],
                                    });
                                }
                            }
                        });
                    }
                });
                break;

            case "edit":
                $.ajax({
                    url: baseUrl + "/api/pengaturan/" + id,
                    success: (data) => {
                        $("form#form-edit")[0].reset();

                        // Isi data ke form edit
                        $("#edit-id").val(data.id);
                        $("#edit-nominal_per_kg").val(data.nominal_per_kg);

                        // Inisialisasi ulang komponen Materialize
                        M.updateTextFields();
                    },
                    error: (xhr) => {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal mengambil data pengaturan",
                        });
                    }
                });
                break;

            case "detail":
                $.ajax({
                    url: baseUrl + "/api/pengaturan/" + id,
                    success: (data) => {
                        // Isi data ke popup detail
                        $("#detail-id").text(data.id || '-');
                        $("#detail-nominal_per_kg").text(data.nominal_per_kg ? 'Rp ' + parseFloat(data.nominal_per_kg).toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 2
                        }) : '-');
                    },
                    error: (xhr) => {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal mengambil data pengaturan",
                        });
                    }
                });
                break;
        }
    });

    $(document).ready(function() {
        // Load data awal
        table.pengaturan.ajax.reload();
        $(".preloader").slideUp();
    });
</script>
<?= $this->endSection() ?>