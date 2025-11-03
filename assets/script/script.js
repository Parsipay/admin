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


//login form
  // وقتی فرم لاگین ارسال شد
  // $("#loginForm").on("submit", function(e) {
  //   e.preventDefault(); 

  //   const username = $("#loginUsername").val().trim();
  //   const password = $("#loginPassword").val().trim();
  //   const $forgotMsg = $("#forgotMsg");


  //   if(username === "09356439532" && password === "12345") {

  //     $("#loginDiv").fadeOut(300, function() {
  //       $("#dashboardContent").fadeIn(300);
  //     });
  //   } else {
    
  //     alert("شماره یا رمز عبور اشتباه است ❌");
  //   }

   
  //   if($("#forgotPassword").is(":checked")) {
  //     $forgotMsg.show();
  //   } else {
  //     $forgotMsg.hide();
  //   }
  // });

//bakground loginform
//  const numStars = 200; // تعداد ستاره‌ها
//   const $bg = $(".login-background");

//   for (let i = 0; i < numStars; i++) {
//     const size = Math.random() * 2 + 1; // اندازه ستاره
//     const left = Math.random() * 100;   // درصد افقی
//     const top = Math.random() * 100;    // درصد عمودی
//     const duration = Math.random() * 2 + 1; // سرعت چشمک زدن

//     const $star = $("<div class='star'></div>").css({
//       width: size + "px",
//       height: size + "px",
//       left: left + "%",
//       top: top + "%",
//       animationDuration: duration + "s",
//       opacity: Math.random()
//     });

//     $bg.append($star);
//   }

  // انیمیشن تصادفی چشمک زدن
  // setInterval(function() {
  //   $(".star").each(function() {
  //     if (Math.random() > 0.5) {
  //       $(this).fadeTo(Math.random() * 1000 + 500, Math.random());
  //     }
  //   });
  // }, 500);




  $("#smsLoginLink").on("click", function (e) {
    const username = $("#loginUsername").val().trim();
    const password = $("#loginPassword").val().trim();

    // اگر هر دو باید پر باشند:
    if (username === "" || password === "") {
      e.preventDefault(); // جلوی رفتن به لینک را می‌گیرد
      alert("لطفاً شماره موبایل و رمز عبور را وارد کنید.");
      // می‌تونی به جای alert، نمایش خطای زیباتر اضافه کنی
    }
    // در غیر این صورت رفتار پیش‌فرض (رفتن به href) رخ می‌دهد
  });



  
///inja be bad js shod chon kar nemikone harkari mikonam
$(window).on('load', function() {
    // ===== Donut Chart =====
    var chart1 = echarts.init(document.getElementById('chart1'));
    var option1 = {
        title: { text: 'درصد دارایی‌ها', left: 'center' },
        tooltip: { trigger: 'item', formatter: '{b}: {d}%' },
        legend: { bottom: 0 },
        series: [{
            name: 'Assets',
            type: 'pie',
            radius: ['45%', '70%'],
            avoidLabelOverlap: false,
            itemStyle: { borderRadius: 8, borderColor: '#fff', borderWidth: 2 },
            label: { show: false },
            emphasis: { label: { show: true, fontSize: 16, fontWeight: 'bold' } },
            data: [
                { value: 40, name: 'بیت‌کوین' },
                { value: 25, name: 'اتریوم' },
                { value: 20, name: 'تتر' },
                { value: 15, name: 'سایر' }
            ]
        }]
    };
    chart1.setOption(option1);

       // ===== Line chart (chart2) =====
var chart2 = echarts.init(document.getElementById('chart2'));

    var option2 = {
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        legend: { data: ['Sales', 'Revenue'], top: 'top' },
        xAxis: {
            type: 'category',
            data: ['جمعه', 'پنج شنبه', 'چهارشنبه', 'سه شنبه ', 'دوشنبه', 'یک‌شنبه', 'شنبه']
        },
        yAxis: { type: 'value' },
        series: [
            {
                name: 'Sales',
                type: 'bar',
                data: [120, 200, 150, 80, 70, 110, 130],
                barGap: 0, // فاصله بین ستون‌ها در یک دسته
                itemStyle: { color: '#5470C6' }
            },
            {
                name: 'Revenue',
                type: 'bar',
                data: [90, 180, 120, 60, 50, 100, 120],
                itemStyle: { color: '#FF994D' }
            }
        ]
    };

    chart2.setOption(option2);
    // ===== Responsive Resize =====
    $(window).on('resize', function() {
        chart1.resize();
        chart2.resize();
    });
});
