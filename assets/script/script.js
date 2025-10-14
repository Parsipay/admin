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

  // ---------- ECharts: Pie Chart ----------
  const chart1 = echarts.init(document.getElementById('chart1'));
  chart1.setOption({
    tooltip: { trigger: 'item' },
    legend: {
      orient: 'vertical',
      left: 'left',
      data: ['کاربران فعال', 'کاربران تکمیل نشده', 'کاربران آنلاین', 'کاربران جدید']
    },
    series: [{
      name: 'کاربران',
      type: 'pie',
      radius: ['65%', '85%'],
      label: { show: false },
      emphasis: {
        label: {
          show: true,
          fontSize: 16,
          fontWeight: 'bold',
          formatter: 'تعداد کل کاربران:\n45000 نفر'
        }
      },
      data: [
        { value: 15000, name: 'کاربران فعال', itemStyle: { color: '#3366ff' } },
        { value: 10000, name: 'کاربران تکمیل نشده', itemStyle: { color: '#ff9966' } },
        { value: 12000, name: 'کاربران آنلاین', itemStyle: { color: '#00cc66' } },
        { value: 8000, name: 'کاربران جدید', itemStyle: { color: '#9933cc' } }
      ]
    }]
  });

  // ---------- ECharts: Bar Chart ----------
  const chart2 = echarts.init(document.getElementById('chart2'));
  chart2.setOption({
    tooltip: {},
    xAxis: { type: 'category', data: ['TRX TO USDT', 'USDT', 'TRX', 'Kucoin', 'UUSD'] },
    yAxis: { type: 'value' },
    series: [{
      type: 'bar',
      data: [
        { value: 9000, itemStyle: { color: '#00cc66' } },
        { value: 3000, itemStyle: { color: '#ff9966' } },
        { value: 12000, itemStyle: { color: '#3366ff' } },
        { value: 2000, itemStyle: { color: '#ff3333' } },
        { value: 8000, itemStyle: { color: '#3366ff' } }
      ],
      barWidth: '40%'
    }]
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
 $(document).on("click", ".toggle-password", function () {
    const $group = $(this).closest(".input-group");
    const $input = $group.find(".password-input");
    const $icon = $group.find(".eye-icon");

    if (!$input.length || !$icon.length) return;

    const isPassword = $input.attr("type") === "password";
    $input.attr("type", isPassword ? "text" : "password");
    $icon.toggleClass("fa-eye fa-eye-slash");
  });
  // ---------- Password Form Validation ----------
  $(".password-form").on("submit", function (e) {
    e.preventDefault();
    const $form = $(this);
    const $inputs = $form.find(".password-input");
    const $new = $inputs.eq(0), $repeat = $inputs.eq(1);
    const $captchaInput = $form.find("#captcha-input");
    const captchaVal = $form.find("#captcha").val().trim();
    const $error = $form.find(".error-message");

    $captchaInput.removeClass("is-invalid");

    if (!$new.val().trim() || !$repeat.val().trim())
      return $error.text("رمز عبور نمی‌تواند خالی باشد 🔴"), $new.focus();

    if ($new.val() !== $repeat.val())
      return $error.text("رمز عبور با تکرارش مطابقت ندارد 🔴"), $repeat.focus();

    if ($captchaInput.val().trim() !== captchaVal)
      return $error.text("Captcha اشتباه است 🔴"), $captchaInput.addClass("is-invalid").focus();

    $error.text("");
    this.submit();
  });

}); // end of document.ready


// ---------- URL Sort (Asc / Desc) ----------


document.addEventListener('click', function(e) {
    if(!e.target.classList.contains('sort-toggle')) return;

    e.preventDefault();

    const icon = e.target;
    const th = icon.closest('th');
    const table = th.closest('table');
    const tbody = table.querySelector('tbody');

    if(!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));

    // مرتب‌سازی
    const order = icon.dataset.order === 'asc' ? 'desc' : 'asc';
    icon.dataset.order = order;

    rows.sort((a,b) => {
        const aTd = a.querySelector('td[data-timestamp]');
        const bTd = b.querySelector('td[data-timestamp]');
        const aTime = aTd ? parseInt(aTd.dataset.timestamp) : 0;
        const bTime = bTd ? parseInt(bTd.dataset.timestamp) : 0;
        return order === 'asc' ? aTime - bTime : bTime - aTime;
    });

    // اضافه کردن ردیف‌ها به tbody
    rows.forEach(row => tbody.appendChild(row));

    // تغییر آیکن فلش
    icon.classList.remove('fa-sort-up','fa-sort-down');
    icon.classList.add(order === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
});

