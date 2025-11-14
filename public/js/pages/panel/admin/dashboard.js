$(document).ready(function () {
  $.ajax({
    url: origin + "/api/jastip",
    dataSrc: function (json) {
      // Filter data di sisi client
      return json.filter((item) => item.user.id == userId);
    },
    success: (data) => {
      const totalPending = data.filter(
        (item) => item.status === "pending"
      ).length;
      const totalProses = data.filter(
        (item) => item.status === "proses"
      ).length;
      const totalSelesai = data.filter(
        (item) => item.status === "selesai"
      ).length;

      $(".total-proses").text(totalProses);
      $(".total-selesai").text(totalSelesai);
      $(".total-pengajuan").text(totalPending);
    },
  });
});
