$(document).ready(function () {

    // =====================
    // 🔎 Generic Search Function
    // =====================
 // =====================
    // 🔎 Generic Search Handler
    // =====================
    $(document).on("click", ".toggle-search", function(e) {
        e.stopPropagation();

        let $btn = $(this);
        let $input = $($btn.data("input"));
        let $tableBody = $("#" + $btn.data("table"));
        let colIndex = $btn.data("col");

        // Toggle input visibility
        if ($input.hasClass("d-none")) {
            $input.removeClass("d-none").focus();
        } else {
            $input.addClass("d-none").val("");
            filterTable("", $tableBody, colIndex);
        }

        // سرچ زنده
        $input.off("input").on("input", function() {
            filterTable($(this).val().trim().toLowerCase(), $tableBody, colIndex);
        });
    });

    // Prevent closing when clicking inside input
    $(document).on("click", "input", function(e) {
        e.stopPropagation();
    });

    // Close input when clicking خارج
    $(document).on("click", function() {
        $("input").each(function() {
            let $input = $(this);
            if (!$input.hasClass("d-none")) {
                $input.addClass("d-none").val("");
                let tableId = $input.closest(".table-section").find("tbody").attr("id");
                filterTable("", $("#" + tableId), $input.closest(".search-header").index());
            }
        });
    });

    // =====================
    // Filter rows by column
    // =====================
    function filterTable(value, $tableBody, colIndex) {
        $tableBody.find("tr").each(function () {
            const text = $(this).find("td").eq(colIndex).text().toLowerCase();
            $(this).toggle(text.includes(value));
        });
    }





    // =====================
    // 📌 Filter by Order Status
    // =====================
    $(document).on("click", ".dropdownitem", function (e) {
        e.preventDefault();
        let selectedStatus = $(this).data("value").trim();
        const $tableBody = $("#tablesearch");

        if (selectedStatus === "all") {
            $tableBody.find("tr").show();
            return;
        }

        $tableBody.find("tr").each(function () {
            let rowStatus = String($(this).data("item-status")).trim();
            $(this).toggle(rowStatus === selectedStatus);
        });
    });

});
//for menu button
const fancyBtn = document.querySelector('.fancy-btn');
if (fancyBtn) {
    fancyBtn.addEventListener('click', function() { this.classList.toggle('active'); });
}

$(document).ready(function() {

    // =========================
    // Sidebar Click => تغییر سکشن پایین
    // =========================
    $(".sidebar-link").click(function(e) {
        e.preventDefault();
        let target = $(this).data("target"); // user-tab یا tableFinancial

        // همه سکشن‌ها مخفی
        $("#dynamicContent > .table-section, #dynamicContent > .py-5").hide();

        // نمایش سکشن هدف
        $("#" + target).show();
    });

    // =========================
    // Bootstrap Tabs داخل user-tab
    // =========================
    $('#myTab button').on('click', function (e) {
        e.preventDefault();
        let tabTrigger = new bootstrap.Tab(this);
        tabTrigger.show();
    });

    // Tabs دوم (جدول مالی)
    $('#myTab2 button').on('click', function (e) {
        e.preventDefault();
        let tabTrigger = new bootstrap.Tab(this);
        tabTrigger.show();
    });



    //aside
      $(".sidebar-link, .nav-link").on("click", function(e){
            e.preventDefault();
            $(".sidebar-link, .nav-link").removeClass("active");
            $(this).addClass("active");
        });
});


