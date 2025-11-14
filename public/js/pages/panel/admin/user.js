// const url = `${baseURL}`;
const table = {
  user: $("#table-user").DataTable({
    responsive: true,
    ajax: {
      url: origin + "/api/user",
      dataSrc: "",
    },
    columns: [
      {
        title: "#",
        data: "id",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      { title: "Nama", data: "name" },
      { title: "Username", data: "username" },
      {
        title: "Status",
        data: "active",
        render: (data) => {
          return data == 1
            ? `<span class="green">Aktif</span>`
            : `<span class="red">Tidak Aktif</span>`;
        },
      },

      {
        title: "#",
        data: "id",
        render: (data, type, row) => {
          let actionButton;

          if (row.active == 1) {
            actionButton = `<a role="button" class="btn waves-effect waves-light btn-action red" data-action="deactivate" data-id="${data}"><i class="material-icons">block</i></a>`;
          } else {
            actionButton = `<a role="button" class="btn waves-effect waves-light btn-action blue" data-action="activate" data-id="${data}"><i class="material-icons">check</i></a>`;
          }

          return `<div class="table-control">
            ${actionButton}
            <a role="button" class="btn waves-effect waves-light btn-popup btn-action blue" data-action="edit" data-target="edit" data-id="${data}"><i class="material-icons">edit</i></a>
            <a role="button" class="btn waves-effect waves-light btn-popup btn-action orange darken-2" data-action="password" data-target="password" data-id="${data}"><i class="material-icons">key</i></a>
            <a role="button" class="btn waves-effect waves-light btn-action red" data-action="delete" data-id="${data}"><i class="material-icons">delete</i></a>
        </div>`;
        },
      },
    ],
  }),
};

$("form#form-user").on("submit", function (e) {
  e.preventDefault();
  const data = {};
  $(this)
    .serializeArray()
    .map(function (x) {
      data[x.name] = x.value;
    });

  const form = $(this)[0];
  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }

  $.ajax({
    type: "POST",
    url: origin + "/api/user",
    data: data,
    cache: false,
    success: (data) => {
      $(this)[0].reset();
      cloud.pull("user");
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      $(this).closest(".page.slider").removeClass("active");
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
  });
});
$("form#form-edit").on("submit", function (e) {
  e.preventDefault();
  const data = {};
  $(this)
    .serializeArray()
    .map(function (x) {
      data[x.name] = x.value;
    });

  const form = $(this)[0];
  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }

  $.ajax({
    type: "POST",
    url: origin + "api/user/update/" + data.id,
    data: data,
    cache: false,
    success: (data) => {
      $(this)[0].reset();
      cloud.pull("user");
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      $(this).closest(".popup").find(".btn-popup-close").trigger("click");
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
  });
});
$("form#form-password").on("submit", function (e) {
  e.preventDefault();
  const data = {};
  $(this)
    .serializeArray()
    .map(function (x) {
      data[x.name] = x.value;
    });
  if (data.password !== data.password_confirm) {
    Toast.fire({
      icon: "error",
      title: "Konfirmasi Password tidak sesuai",
    });
    return false;
  }

  const form = $(this)[0];
  const elements = form.elements;
  for (let i = 0, len = elements.length; i < len; ++i) {
    elements[i].readOnly = true;
  }
  const origin = window.location.origin + "/"; // pastikan origin valid
  const url = origin + "api/user/update/" + data.id;
  $.ajax({
    type: "POST",
    url: url,
    data: data,
    cache: false,
    success: (data) => {
      $(this)[0].reset();
      cloud.pull("user");
      if (data.messages) {
        $.each(data.messages, function (icon, text) {
          Toast.fire({
            icon: icon,
            title: text,
          });
        });
      }
      $(this).closest(".popup").find(".btn-popup-close").trigger("click");
    },
    complete: () => {
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].readOnly = false;
      }
    },
  });
});

$("body").on("click", ".btn-action", function (e) {
  e.preventDefault();
  const action = $(this).data("action");
  const id = $(this).data("id");
  switch (action) {
    case "delete":
      Swal.fire({
        title: "Apakah anda yakin ingin menghapus data ini ?",
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
            url: origin + "/api/user/" + id,
            cache: false,
            success: (data) => {
              table.user.ajax.reload();
              if (data.messages) {
                $.each(data.messages, function (icon, text) {
                  Toast.fire({
                    icon: icon,
                    title: text,
                  });
                });
              }
            },
          });
        }
      });
      break;
    case "edit":
      let dataEdit = cloud.get("user").find((x) => x.id == id);
      $("form#form-edit").find("input[name=id]").val(dataEdit.id);
      $("form#form-edit").find("input[name=name]").val(dataEdit.name);
      $("form#form-edit").find("[name=address]").val(dataEdit.address);
      M.updateTextFields();
      M.textareaAutoResize($("textarea"));
      break;
    case "password":
      let dataPassword = cloud.get("user").find((x) => x.id == id);
      $("form#form-password").find("input[name=id]").val(dataPassword.id);
      M.updateTextFields();
      break;
    case "activate":
      data = { id: id };
      $.ajax({
        type: "POST",
        url: origin + "/api/user/activate",
        data: data,
        cache: false,
        success: (data) => {
          table.user.ajax.reload();
          if (data.messages) {
            $.each(data.messages, function (icon, text) {
              Toast.fire({
                icon: icon,
                title: text,
              });
            });
          }
        },
      });
      break;
    case "deactivate":
      data = { id: id };
      $.ajax({
        type: "POST",
        url: origin + "/api/user/deactivate",
        data: data,
        cache: false,
        success: (data) => {
          table.user.ajax.reload();
          if (data.messages) {
            $.each(data.messages, function (icon, text) {
              Toast.fire({
                icon: icon,
                title: text,
              });
            });
          }
        },
      });
    default:
      break;
  }
});

$(document).ready(function () {
  cloud
    .add(origin + "/api/user", {
      name: "user",
      callback: (data) => {
        table.user.ajax.reload();
      },
    })
    .then((user) => {});
  $(".preloader").animate({ left: "0%" }, 1000, function () {
    setTimeout(function () {
      $(".preloader").animate({ left: "100%" }, 1000, function () {
        $(this).slideUp();
      });
    }, 2000);
  });
});
