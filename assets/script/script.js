$(document).ready(function () {

    // =====================
    // 🔎 Generic Search Function
    // =====================
    function toggleSearchInput(toggleBtn, inputSelector, tableId, colIndex) {
        const $input = $(inputSelector);
        const $tableBody = $("#" + tableId);

        // Toggle search input visibility
        $(toggleBtn).on("click", function (e) {
            e.stopPropagation();
            if ($input.hasClass("d-none")) {
                $input.removeClass("d-none").focus();
            } else {
                $input.addClass("d-none").val("");
                filterTable("", $tableBody, colIndex);
            }
        });

        // Filter table on input
        $input.on("input", function () {
            filterTable($(this).val().trim().toLowerCase(), $tableBody, colIndex);
        });

        // Prevent closing when clicking inside input
        $input.on("click", function (e) {
            e.stopPropagation();
        });

        // Close input when clicking outside
        $(document).on("click", function () {
            if (!$input.hasClass("d-none")) {
                $input.addClass("d-none").val("");
                filterTable("", $tableBody, colIndex);
            }
        });
    }

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
    // 🔹 Tab 1: Orders
    // =====================
    toggleSearchInput("#toggleSearchOrder", "#searchOrderNumber", "tablesearch", 0);
    toggleSearchInput("#toggleSearchOrderDetails", "#searchOrderDetails", "tablesearch", 1);
    toggleSearchInput("#toggleSearchUserFullName", "#searchUserFullName", "tablesearch", 2);

    // =====================
    // 🔹 Tab 2: Users
    // =====================
    toggleSearchInput("#toggleSearchNationalCode", "#searchNationalCode", "tablesearchUsers", 0);
    toggleSearchInput("#toggleSearchPhone", "#searchPhone", "tablesearchUsers", 1);
    toggleSearchInput("#toggleSearchUserFullName2", "#searchUserFullName2", "tablesearchUsers", 2);

    // =====================
    // 🔹 Tab 3: Requests
    // =====================
    toggleSearchInput("#toggleSearchRequestNumber", "#searchRequestNumber", "tablesearchRequests", 0);
    toggleSearchInput("#toggleSearchTrackingNumber", "#searchTrackingNumber", "tablesearchRequests", 1);
    toggleSearchInput("#toggleSearchRequestFullName", "#searchRequestFullName", "tablesearchRequests", 2);

    // =====================
    // 📅 Sort by Date (using data-item-unix)
    // =====================
    $(".dropitem").on("click", function (e) {
        e.preventDefault();
        const direction = $(this).data("value"); // "asc" or "desc"
        const $tableBody = $("#tablesearch");

        // Convert table rows to array for sorting
        const rowsArray = $tableBody.find("tr").get();

        // Sort rows by data-item-unix attribute
        rowsArray.sort(function (a, b) {
            const aUnix = parseInt($(a).data("item-unix"));
            const bUnix = parseInt($(b).data("item-unix"));
            return direction === "asc" ? aUnix - bUnix : bUnix - aUnix;
        });

        // Append sorted rows back to tbody
        $.each(rowsArray, function (i, row) {
            $tableBody.append(row);
        });
    });

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
