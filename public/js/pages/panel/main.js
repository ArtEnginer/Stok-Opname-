$("body").on("click", ".btn-logout", function (e) {
  e.preventDefault();
  Swal.fire({
    title: "Apakah anda yakin ingin keluar ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "keluar",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "/logout";
    }
  });
});

$("body").on("click", ".btn-slider", function (e) {
  const page = $(this).data("target");
  $(".page.slider[data-page=" + page + "]").addClass("active");
});

$("body").on("click", ".btn-slider-close", function (e) {
  $(this).closest(".page.slider").removeClass("active");
});

$("body").on("click", ".btn-popup", function (e) {
  const page = $(this).data("target");
  if (!$(".popup-wrapper").hasClass("open")) {
    $(".popup-wrapper").addClass("open");
  }
  $(".popup[data-page=" + page + "]").addClass("active");
});
$("body").on("click", ".btn-popup-close", function (e) {
  $(this).closest(".popup").removeClass("active");
  $(this).closest(".popup-wrapper").removeClass("open");
});
$("body").on("click", ".nav-toggle", function (e) {
  $(".nav-wrapper").addClass("open");
});
$("body").on("click", ".nav-close", function (e) {
  $(".nav-wrapper").removeClass("open");
});

$.each($(".popup"), function (i, popup) { 
  const content = popup.innerHTML;
  $(this).empty();
  $(this).append(`<div class="popup-nav"><a class="btn-popup-close"><i class="fas fa-times"></i></a></div>`);
  $(this).append(`<div class="popup-content">${content}</div>`);
});

$(document).ready(function () {
  $(`.nav-item[data-page=${page}]`).addClass("active");
  M.AutoInit();
});
