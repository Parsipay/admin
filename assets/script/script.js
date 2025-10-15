$(document).ready(function () {

  // ---------- Search ----------
  $(".search-icon").on("click", function (e) {
    e.stopPropagation();
    const $input = $(this).siblings(".search-input");
    $(".search-input").not($input).removeClass("show");
    $input.toggleClass("show").focus();
  });

  $(".search-input").on("keyup", function () {
    const value = $(this).val().toLowerCase();
    const column = $(this).siblings(".search-icon").data("column");
    $(this).closest("table").find("tbody tr").each(function () {
      $(this).toggle($(this).find("td").eq(column).text().toLowerCase().includes(value));
    });
  });

  $(document).on("click", () => $(".search-input").removeClass("show"));

  // ---------- Dropdown Filter ----------
  $(document).on("click", ".dropdownitem", function (e) {
    e.preventDefault();
    const selected = $(this).data("value").trim();
    const $rows = $("#tablesearch tr");
    if (selected === "all") return $rows.show();
    $rows.each(function () {
      $(this).toggle($(this).data("item-status")?.trim() === selected);
    });
  });

  // ---------- Status Filter ----------
  $(".filter-status").on("click", function () {
    const selected = $(this).data("status").trim();
    $("tbody tr").each(function () {
      const status = $(this).find(".status-text").text().trim();
      $(this).toggle(selected === "all" || status === selected);
    });
  });

  // ---------- Fancy Button Toggle ----------
  $(".fancy-btn").on("click", function () {
    $(this).toggleClass("active");
  });

  // ---------- Sortable Columns ----------
  $(".sortable").on("click", function () {
    const $table = $(this).closest("table");
    const $tbody = $table.find("tbody");
    const index = $(this).data("column");
    const asc = $(this).data("asc") || false;
    $(this).data("asc", !asc);

    const rows = $tbody.find("tr").get().sort((a, b) => {
      const A = +$(a).find("td").eq(index).data("timestamp");
      const B = +$(b).find("td").eq(index).data("timestamp");
      return asc ? A - B : B - A;
    });
    $tbody.append(rows);
  });

  // ---------- Copy TD Text ----------
  $(document).on("click", ".fa-copy", function () {
    const text = $(this).closest("td").clone().children("i").remove().end().text().trim();
    navigator.clipboard.writeText(text)
      .then(() => alert("کپی شد ✅"))
      .catch(() => alert("کپی نشد ❌"));
  });

  // ---------- Persian & Gregorian Dates ----------
  const today = new Date();
  $("#shamsi").text("تاریخ شمسی: " + today.toLocaleDateString('fa-IR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));
  $("#miladi").text("تاریخ میلادی: " + today.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));

  // ---------- Ticket Reply Toggle ----------
  $(".ticket-card").on("click", function (e) {
    if ($(e.target).closest("button, textarea, i").length) return;
    const $replyBox = $(this).find(".ticket-reply");
    $(".ticket-reply").not($replyBox).slideUp(200);
    $replyBox.slideToggle(200);
  });


  // ---------- Profile Image Upload ----------
  $('#profileInput').on('change', function () {
    const file = this.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('profile', file);

    $.ajax({
      url: 'upload.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (res) {
        const data = JSON.parse(res);
        if (data.success) {
          $('#profileImg').attr('src', data.path + '?' + Date.now());
          $('#profileError').text('');
        } else {
          $('#profileError').text(data.error);
        }
      },
      error: () => $('#profileError').text('خطا در آپلود فایل.')
    });
  });

  // ---------- Save Profile (Optional) ----------
  $('#saveProfileBtn').on('click', () => alert('تغییرات ذخیره شد!'));

  // ---------- Toggle Password Visibility ----------
  $(".password-form").on("submit", function(e) {
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
      $form.find("#newPassword").focus();
      return;
    }

    if (newPass !== repeatPass) {
      $error.text("رمز عبور با تکرارش مطابقت ندارد 🔴");
      $form.find("#repeatPassword").focus();
      return;
    }

    if (captchaInput !== captchaVal) {
      $error.text("Captcha اشتباه است 🔴");
      $form.find("#captcha-input").focus();
      return;
    }

    // همه چیز درست است
    $error.text("");
    alert("رمز عبور با موفقیت تغییر کرد ✅");
    $form[0].submit(); // حالا فرم submit میشه
  });

  // Toggle Password Visibility
  $(".toggle-password").on("click", function() {
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


  $(document).on('click', '.sort-toggle', function(e) {
    e.preventDefault();

    var $icon = $(this);
    var $th = $icon.closest('th');
    var $table = $th.closest('table');
    var $tbody = $table.find('tbody');

    if (!$tbody.length) return;

    var order = $icon.data('order') === 'asc' ? 'desc' : 'asc';
    $icon.data('order', order);

    var $rows = $tbody.find('tr').toArray();

    $rows.sort(function(a, b) {
        var aTime = parseInt($(a).find('td[data-timestamp]').data('timestamp')) || 0;
        var bTime = parseInt($(b).find('td[data-timestamp]').data('timestamp')) || 0;
        return order === 'asc' ? aTime - bTime : bTime - aTime;
    });

    // اضافه کردن ردیف‌ها به tbody
    $.each($rows, function(i, row) {
        $tbody.append(row);
    });

    // تغییر آیکن فلش
    $icon.removeClass('fa-sort-up fa-sort-down')
         .addClass(order === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
});


// $("#persian-date").persianDatepicker({
//     format: 'YYYY/MM/DD',
//     initialValue: false,
//     autoClose: true,
//     calendarType: 'persian',
//     toolbox: {
//       calendarSwitch: { enabled: false },
//       todayButton: { enabled: true, text: { fa: "امروز" } },
//       submitButton: { enabled: true, text: { fa: "تأیید" } }
//     }
//   });


//   var pdp = $("#persian-date").persianDatepicker({
//     format: 'YYYY/MM/DD',
//     initialValue: false,
//     autoClose: true,
//     calendarType: 'persian',
//     observer: true,
//     toolbox: {
//       calendarSwitch: { enabled: false },
//       todayButton: { enabled: true, text: { fa: "امروز" } },
//       submitButton: { enabled: true, text: { fa: "تأیید" } }
//     },
//     onShow: function(inst) {
//       // اضافه کردن دکمه‌های Quick Filter فقط یک بار
//       if (!$("#startWeekBtn").length) {
//         var custom = `
//           <div class="pd-custom-buttons">
//             <button type="button" id="todayBtn" class="btn btn-sm btn-outline-primary">امروز</button>
//             <button type="button" id="startWeekBtn" class="btn btn-sm btn-outline-primary">ابتدای هفته</button>
//             <button type="button" id="startMonthBtn" class="btn btn-sm btn-outline-primary">ابتدای ماه</button>
//             <button type="button" id="startYearBtn" class="btn btn-sm btn-outline-primary">ابتدای سال</button>
//           </div>
//         `;
//         $(this).find(".pwt-date").after(custom);

//         $("#todayBtn").on("click", function() {
//           var now = new persianDate();
//           $("#persian-date").val(now.format('YYYY/MM/DD'));
//           pdp.hide();
//         });
//         $("#startWeekBtn").on("click", function() {
//           var now = new persianDate();
//           $("#persian-date").val(now.startOf('week').format('YYYY/MM/DD'));
//           pdp.hide();
//         });
//         $("#startMonthBtn").on("click", function() {
//           var now = new persianDate();
//           $("#persian-date").val(now.startOf('month').format('YYYY/MM/DD'));
//           pdp.hide();
//         });
//         $("#startYearBtn").on("click", function() {
//           var now = new persianDate();
//           $("#persian-date").val(now.startOf('year').format('YYYY/MM/DD'));
//           pdp.hide();
//         });
//       }
//     }
//   });
  // وقتی input کلیک شد، modal باز بشه
 var calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
  $("#persian-date").on("click", function(){
    calendarModal.show();
  });

  $('#calendarModal').on('shown.bs.modal', function () {
    // PersianDatepicker برای input ها
    $("#fromDate, #toDate").persianDatepicker({
      format: 'YYYY/MM/DD',
      autoClose: true,
      initialValue: false
    });

    // تقویم وسط inline
    if(!$('#persian-calendar').hasClass('pdp-initialized')){
      $('#persian-calendar').addClass('pdp-initialized').persianDatepicker({
        inline: true,
        format: 'YYYY/MM/DD',
        observer: true,
        autoClose: false,
        initialValue: false,
        onSelect: function(unix){
          var selected = new persianDate(unix).format('YYYY/MM/DD');
          $("#fromDate").val(selected);
          $("#toDate").val(selected);
        }
      });
    }

    // Quick Filter
// تابع کمکی برای تنظیم بازه تاریخ
  function setRange(from, to) {
    $("#fromDate").val(from.format("YYYY/MM/DD"));
    $("#toDate").val(to.format("YYYY/MM/DD"));
  }

  // امروز
  $("#todayBtn").on("click", function () {
    const now = new persianDate();
    setRange(now, now);
  });

  // از ابتدای هفته تا امروز
  $("#startWeekBtn").on("click", function () {
    const now = new persianDate();
    setRange(now.startOf("week"), now);
  });

  // بازه یک هفته گذشته
  $("#weekBtn").on("click", function () {
    const now = new persianDate();
    const weekAgo = now.clone().subtract("days", 6);
    setRange(weekAgo, now);
  });

  // از ابتدای ماه تا امروز
  $("#startMonthBtn").on("click", function () {
    const now = new persianDate();
    setRange(now.startOf("month"), now);
  });

  // بازه یک ماه گذشته
  $("#monthBtn").on("click", function () {
    const now = new persianDate();
    const monthAgo = now.clone().subtract("months", 1);
    setRange(monthAgo, now);
  });

  // از ابتدای سال تا امروز
  $("#startYearBtn").on("click", function () {
    const now = new persianDate();
    setRange(now.startOf("year"), now);
  });

  // بازه یک سال گذشته
  $("#yearBtn").on("click", function () {
    const now = new persianDate();
    const yearAgo = now.clone().subtract("years", 1);
    setRange(yearAgo, now);
  });
  });
  


$("#buySellFilter").on("change", function() {
    var filter = $(this).val(); // "" یا "خرید" یا "فروش"

    $("#ordersTable tbody tr").each(function() {
        var orderType = $(this).find("td:first span.ms-2").text().trim(); 
        if (filter === "" || orderType === filter) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});


 $("#statusFilter").on("change", function() {
        var selectedStatus = $(this).val().trim(); // وضعیت انتخاب شده

        $(".searchable-table tbody tr").each(function() {
            var statusText = $(this).find("td:last .status-text").text().trim(); // آخرین ستون وضعیت
            if (selectedStatus === "" || statusText === selectedStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
  
}); // end of document.ready






