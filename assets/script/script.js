$(document).ready(function () {
  /* ---------------------- 🔹 POPOVER INIT ---------------------- */
  const popoverTriggerList = document.querySelectorAll(
    '[data-bs-toggle="popover"]'
  );
  const popoverList = [...popoverTriggerList].map(
    (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
  );

  /* ---------------------- 🔍 SEARCH ---------------------- */
  $(".search-icon").on("click", function (e) {
    e.stopPropagation();
    const $input = $(this).siblings(".search-input");
    $(".search-input").not($input).removeClass("show");
    $input.toggleClass("show").focus();
  });

  $(".search-input").on("keyup", function () {
    const value = $(this).val().toLowerCase();
    const column = $(this).siblings(".search-icon").data("column");
    $(this)
      .closest("table")
      .find("tbody tr")
      .each(function () {
        $(this).toggle(
          $(this).find("td").eq(column).text().toLowerCase().includes(value)
        );
      });
  });

  $(document).on("click", () => $(".search-input").removeClass("show"));

  /* ---------------------- 🧾 DROPDOWN FILTER ---------------------- */
  $(document).on("click", ".dropdownitem", function (e) {
    e.preventDefault();
    const selected = $(this).data("value").trim();
    const $rows = $("#tablesearch tr");
    if (selected === "all") {
      $rows.show();
      return;
    }
    $rows.each(function () {
      const status = ($(this).data("item-status") || "").toString().trim();
      $(this).toggle(status === selected);
    });
  });

  /* ---------------------- ⚙️ STATUS FILTER ---------------------- */
  $(".filter-status").on("click", function () {
    const selected = $(this).data("status").trim();
    $("tbody tr").each(function () {
      const status = $(this).find(".status-text").text().trim();
      $(this).toggle(selected === "all" || status === selected);
    });
  });

  /* ---------------------- 💡 FANCY BUTTON TOGGLE ---------------------- */
  $(".fancy-btn").on("click", function () {
    $(this).toggleClass("active");
  });

  /* ---------------------- 🔽 SORTABLE COLUMNS ---------------------- */
  document.querySelectorAll(".sortable").forEach((th) => {
    th.addEventListener("click", () => {
      const table = th.closest("table");
      const tbody = table.querySelector("tbody");
      const columnIndex = Array.from(th.parentNode.children).indexOf(th);
      Array.from(tbody.querySelectorAll("tr"))
        .sort((a, b) => {
          const t1 =
            +a.querySelectorAll("td")[columnIndex].dataset.timestamp || 0;
          const t2 =
            +b.querySelectorAll("td")[columnIndex].dataset.timestamp || 0;
          return t2 - t1;
        })
        .forEach((tr) => tbody.appendChild(tr));
    });
  });

  /* ---------------------- 📋 COPY TEXT ---------------------- */
  $(document).on("click", ".fa-copy", function () {
    const text = $(this)
      .closest("td")
      .clone()
      .children("i")
      .remove()
      .end()
      .text()
      .trim();
    navigator.clipboard
      .writeText(text)
      .then(() => alert("کپی شد ✅"))
      .catch(() => alert("کپی نشد ❌"));
  });

  /* ---------------------- 📅 DATES ---------------------- */
  const today = new Date();
  $("#shamsi").text(
    "تاریخ شمسی: " +
      today.toLocaleDateString("fa-IR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      })
  );
  $("#miladi").text(
    "تاریخ میلادی: " +
      today.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      })
  );

  /* ---------------------- 🎫 TICKET REPLY TOGGLE ---------------------- */
  $(".ticket-card").on("click", function (e) {
    if ($(e.target).closest("button, textarea, i").length) return;
    const $replyBox = $(this).find(".ticket-reply");
    $(".ticket-reply").not($replyBox).slideUp(200);
    $replyBox.slideToggle(200);
  });

  /* ---------------------- 🖼️ PROFILE IMAGE UPLOAD ---------------------- */
  $("#profileInput").on("change", function () {
    const file = this.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("profile", file);

    $.ajax({
      url: "upload.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (res) {
        const data = JSON.parse(res);
        if (data.success) {
          $("#profileImg").attr("src", data.path + "?" + Date.now());
          $("#profileError").text("");
        } else {
          $("#profileError").text(data.error);
        }
      },
      error: () => $("#profileError").text("خطا در آپلود فایل."),
    });
  });

  /* ---------------------- 💾 SAVE PROFILE ---------------------- */
  $("#saveProfileBtn").on("click", () => alert("تغییرات ذخیره شد!"));

  /* ---------------------- 🔒 PASSWORD VALIDATION ---------------------- */
  $(".password-form").on("submit", function (e) {
    e.preventDefault();
    const $form = $(this);
    const newPass = $form.find("#newPassword").val().trim();
    const repeatPass = $form.find("#repeatPassword").val().trim();
    const captchaInput = $form.find("#captcha-input").val().trim();
    const captchaVal = $form.find("#captcha").text().trim();
    const $error = $form.find(".error-message");
    $error.text("");
    if (!newPass || !repeatPass) {
      $error.text("رمز عبور نمی‌تواند خالی باشد 🔴");
      return $form.find("#newPassword").focus();
    }
    if (newPass !== repeatPass) {
      $error.text("رمز عبور با تکرارش مطابقت ندارد 🔴");
      return $form.find("#repeatPassword").focus();
    }
    if (captchaInput !== captchaVal) {
      $error.text("Captcha اشتباه است 🔴");
      return $form.find("#captcha-input").focus();
    }
    alert("رمز عبور با موفقیت تغییر کرد ✅");
    $form[0].submit();
  });

  /* ---------------------- 👁️ TOGGLE PASSWORD VISIBILITY ---------------------- */
  $(".toggle-password").on("click", function () {
    const $input = $(this).siblings(".password-input");
    const $icon = $(this).find(".eye-icon");
    if ($input.attr("type") === "password") {
      $input.attr("type", "text");
      $icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
      $input.attr("type", "password");
      $icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });

  /* ---------------------- 🔼 SORT TOGGLE ICONS ---------------------- */
  $(document).on("click", ".sort-toggle", function (e) {
    e.preventDefault();
    const $th = $(this);
    const $icon = $th.find("i");
    const $table = $th.closest("table");
    const $tbody = $table.find("tbody");
    if (!$tbody.length) return;

    const order = $th.data("order") === "asc" ? "desc" : "asc";
    $th.data("order", order);

    const rows = $tbody
      .find("tr")
      .toArray()
      .sort((a, b) => {
        const aTime = +$(a).find("td[data-timestamp]").data("timestamp") || 0;
        const bTime = +$(b).find("td[data-timestamp]").data("timestamp") || 0;
        return order === "asc" ? aTime - bTime : bTime - aTime;
      });
    $.each(rows, (_, row) => $tbody.append(row));
    $icon.removeClass("fa-sort fa-sort-up fa-sort-down").addClass(order === "asc" ? "fa-sort-down" : "fa-sort-up");
  });

  /* ---------------------- 📅 PERSIAN CALENDAR MODAL ---------------------- */
  if ($("#calendarModal").length) {
    const calendarModal = new bootstrap.Modal(
      document.getElementById("calendarModal")
    );
    $("#persian-date").on("click", function () {
      calendarModal.show();
    });
    $("#calendarModal").on("shown.bs.modal", function () {
      $("#fromDate, #toDate").persianDatepicker({
        format: "YYYY/MM/DD",
        autoClose: true,
        initialValue: false,
      });
      if (!$("#persian-calendar").hasClass("pdp-initialized")) {
        $("#persian-calendar")
          .addClass("pdp-initialized")
          .persianDatepicker({
            inline: true,
            format: "YYYY/MM/DD",
            observer: true,
            autoClose: false,
            initialValue: false,
            onSelect: function (unix) {
              const selected = new persianDate(unix).format("YYYY/MM/DD");
              $("#fromDate, #toDate").val(selected);
            },
          });
      }

      function setRange(from, to) {
        $("#fromDate").val(from.format("YYYY/MM/DD"));
        $("#toDate").val(to.format("YYYY/MM/DD"));
      }

      $("#todayBtn").on("click", () =>
        setRange(new persianDate(), new persianDate())
      );
      $("#startWeekBtn").on("click", () => {
        const now = new persianDate();
        setRange(now.startOf("week"), now);
      });
      $("#weekBtn").on("click", () => {
        const now = new persianDate();
        setRange(now.clone().subtract("days", 6), now);
      });
      $("#startMonthBtn").on("click", () => {
        const now = new persianDate();
        setRange(now.startOf("month"), now);
      });
      $("#monthBtn").on("click", () => {
        const now = new persianDate();
        setRange(now.clone().subtract("months", 1), now);
      });
      $("#startYearBtn").on("click", () => {
        const now = new persianDate();
        setRange(now.startOf("year"), now);
      });
      $("#yearBtn").on("click", () => {
        const now = new persianDate();
        setRange(now.clone().subtract("years", 1), now);
      });
    });
  }

  /* ---------------------- 💱 FILTERS ---------------------- */
  $("#buySellFilter").on("change", function () {
    const filter = $(this).val();
    $("#ordersTable tbody tr").each(function () {
      const orderType = $(this).find("td:first span.ms-2").text().trim();
      $(this).toggle(filter === "" || orderType === filter);
    });
  });

  $("#statusFilter").on("change", function () {
    const selectedStatus = $(this).val().trim();
    $(".searchable-table tbody tr").each(function () {
      const statusText = $(this).find("td:last .status-text").text().trim();
      $(this).toggle(selectedStatus === "" || statusText === selectedStatus);
    });
  });

  /* ---------------------- 📊 CHARTS ---------------------- */
  $(window).on("load", function () {
    const chart1 = echarts.init(document.getElementById("chart1"));
    chart1.setOption({
      title: {
        text: "درصد دارایی‌ها",
        left: "center",
        textStyle: { color: " rgb(103, 102, 112)" },
      },
      tooltip: { trigger: "item", formatter: "{b}: {d}%" },
      legend: { bottom: 0 },
      color: ["#9b59b6", "#3498db", "#f1c40f", "#2ecc71"],
      series: [
        {
          name: "Assets",
          type: "pie",
          radius: ["60%", "75%"],
          avoidLabelOverlap: false,
          itemStyle: { borderRadius: 6, borderColor: "#ffffff", borderWidth: 2 },
          label: { show: false },
          emphasis: { label: { show: true, fontSize: 16, fontWeight: "bold" } },
          data: [
            { value: 40, name: "بیت‌کوین" },
            { value: 25, name: "اتریوم" },
            { value: 20, name: "تتر" },
            { value: 15, name: "سایر" },
          ],
        },
      ],
    });

    const chart2 = echarts.init(document.getElementById("chart2"));
    const colors = ["#3498db", "#e67e22", "#2ecc71", "#9b59b6", "#e74c3c"];
    chart2.setOption({
      tooltip: { trigger: "axis", axisPointer: { type: "shadow" } },
      legend: { data: ["Coins"], top: "top" },
      xAxis: { type: "category", data: ["BTC", "ETH", "BNB", "SOL", "XRP"] },
      yAxis: { type: "value" },
      series: [
        {
          name: "Coins",
          type: "bar",
          barWidth: 30,
          data: [120, 90, 150, 70, 110],
          itemStyle: {
            borderRadius: [8, 8, 0, 0],
            color: function (params) {
              return colors[params.dataIndex];
            },
          },
        },
      ],
    });

    $(window).on("resize", function () {
      chart1.resize();
      chart2.resize();
    });
  });

  /* ---------------------- 🕰️ DATE FILTER HELPERS ---------------------- */
  function persianToUnix(dateStr, timeStr) {
    if (!dateStr) return null;
    const parts = dateStr.split("/");
    if (parts.length < 3) return null;
    let hour = 0,
      min = 0;
    if (timeStr) {
      const timeParts = timeStr.split(":");
      hour = parseInt(timeParts[0]) || 0;
      min = parseInt(timeParts[1]) || 0;
    }
    const d = new Date(parts[0], parts[1] - 1, parts[2], hour, min, 0);
    return Math.floor(d.getTime() / 1000);
  }

  function filterTableByDate(fromTS, toTS) {
    $("#ordersTable tbody tr").each(function () {
      const rowTS = parseInt($(this).find("[data-timestamp]").data("timestamp"));
      $(this).toggle((!fromTS || rowTS >= fromTS) && (!toTS || rowTS <= toTS));
    });
  }

  $("#confirmBtn").on("click", function () {
    const fromDate = $("#fromDate").val();
    const toDate = $("#toDate").val();
    const fromTime = $("#fromTime").val() || "00:00";
    const toTime = $("#toTime").val() || "23:59";
    const fromTS = persianToUnix(fromDate, fromTime);
    const toTS = persianToUnix(toDate, toTime);
    filterTableByDate(fromTS, toTS);
  });

  /* ---------------------- 📂 FILE UPLOAD MODAL ---------------------- */
  $("#uploadBtn").click(() => $("#fileInput").click());
  $("#fileInput").on("change", function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      $(".documentBox").html(
        `<img src="${e.target.result}" alt="file" style="max-width:100%; max-height:100%;">`
      );
    };
    reader.readAsDataURL(file);
  });
});
