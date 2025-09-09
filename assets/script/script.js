$(document).ready(function () {
  // Filter by order status
  $(document).on("click", ".dropdownitem", function (e) {
    e.preventDefault();
    const selectedStatus = $(this).data("value").trim();
    const $tableBody = $("#tablesearch");
    if (selectedStatus === "all") {
      $tableBody.find("tr").show();
      return;
    }
    $tableBody.find("tr").each(function () {
      const rowStatus = String($(this).data("item-status")).trim();
      $(this).toggle(rowStatus === selectedStatus);
    });
  });
});
// Fancy button toggle
const fancyBtn = document.querySelector(".fancy-btn");
if (fancyBtn) {
  fancyBtn.addEventListener("click", function () {
    this.classList.toggle("active");
  });
}

