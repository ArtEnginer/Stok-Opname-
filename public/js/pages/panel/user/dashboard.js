$(document).ready(function () {
  $.ajax({
    url: origin + "/api/jastip",
    success: (data) => {
      // Filter data berdasarkan user ID di sisi client
      const filteredData = data.filter((item) => item.user.id == userId);

      const totalPending = filteredData.filter(
        (item) => item.status === "pending"
      ).length;
      const totalProses = filteredData.filter(
        (item) => item.status === "proses"
      ).length;
      const totalSelesai = filteredData.filter(
        (item) => item.status === "selesai"
      ).length;

      $(".total-proses").text(totalProses);
      $(".total-selesai").text(totalSelesai);
      $(".total-pengajuan").text(totalPending);
    },
  });
});
