$(document).ready(function () {
   // وقتی روی آیکون سرچ کلیک شد
    $(".search-icon").click(function(e){
        e.stopPropagation(); // جلوگیری از بسته شدن فوری
        var input = $(this).siblings(".search-input");
        $(".search-input").not(input).removeClass("show"); // بقیه input ها بسته شوند
        input.toggleClass("show").focus();
    });

    // جستجو هنگام تایپ
    $(".search-input").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var column = $(this).siblings(".search-icon").data("column");
        $(this).closest("table").find("tbody tr").filter(function() {
            $(this).toggle($(this).find("td").eq(column).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // کلیک بیرون input همه inputها را می‌بندد
    $(document).click(function() {
        $(".search-input").removeClass("show");
    });
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


 // برای همه ستون‌های قابل مرتب‌سازی
    $(".sortable").click(function() {
        var table = $(this).closest("table");
        var tbody = table.find("tbody");
        var index = $(this).data("column");
        var asc = $(this).data("asc") || false;

        // حالت صعودی/نزولی
        $(this).data("asc", !asc);

        var rows = tbody.find("tr").get();

        rows.sort(function(a, b) {
            var A = parseInt($(a).find("td").eq(index).data("timestamp"));
            var B = parseInt($(b).find("td").eq(index).data("timestamp"));

            return asc ? A - B : B - A;
        });

        $.each(rows, function(i, row) {
            tbody.append(row);
        });
    });


}

