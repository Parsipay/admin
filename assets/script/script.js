$(document).ready(function () {
    // ---------- Search ----------
    $(".search-icon").click(function(e){
        e.stopPropagation();
        var input = $(this).siblings(".search-input");
        $(".search-input").not(input).removeClass("show");
        input.toggleClass("show").focus();
    });

    $(".search-input").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var column = $(this).siblings(".search-icon").data("column");
        $(this).closest("table").find("tbody tr").filter(function() {
            $(this).toggle($(this).find("td").eq(column).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(document).click(function() {
        $(".search-input").removeClass("show");
    });

    // ---------- Filter by dropdown ----------
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

    // ---------- Filter by status ----------
    $(".filter-status").on("click", function () {
        let selected = $(this).data("status").trim();
        $("tbody tr").each(function () {
            let statusEl = $(this).find(".status-text");
            if (statusEl.length === 0) return;
            let status = statusEl.text().trim();
            if (selected === "all" || status === selected) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // ---------- Fancy button ----------
    $(".fancy-btn").click(function() {
        $(this).toggleClass("active");
    });

    // ---------- Sortable columns ----------
    $(".sortable").click(function() {
        var table = $(this).closest("table");
        var tbody = table.find("tbody");
        var index = $(this).data("column");
        var asc = $(this).data("asc") || false;
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

    // ---------- Copy td text ----------
    $(document).on("click", ".fa-copy", function () {
        let text = $(this).closest("td").clone().children("i").remove().end().text().trim();
        navigator.clipboard.writeText(text).then(() => {
            alert("کپی شد ✅");
        }).catch(() => {
            alert("کپی نشد ❌");
        });
    });

    // ---------- تاریخ شمسی و میلادی ----------
    const today = new Date();
    const optionsEn = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const gregorian = today.toLocaleDateString('en-US', optionsEn);
    const optionsFa = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const persian = today.toLocaleDateString('fa-IR', optionsFa);
    $("#shamsi").text("تاریخ شمسی: " + persian);
    $("#miladi").text("تاریخ میلادی: " + gregorian);

    // ---------- Ticket Reply ----------
    $(".ticket-card").on("click", function(e) {
        if ($(e.target).closest("button, textarea, i").length) return;
        const replyBox = $(this).find(".ticket-reply");
        $(".ticket-reply").not(replyBox).slideUp(200);
        replyBox.slideToggle(200);
    });


// ---------- ECharts ----------
var chart1 = echarts.init(document.getElementById('chart1'));
chart1.setOption({
    tooltip: { trigger: 'item' },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['کاربران فعال','کاربران تکمیل نشده','کاربران آنلاین','کاربران جدید']
    },
    series: [{
        name: 'کاربران',
        type: 'pie',
        radius: ['65%', '85%'],
        avoidLabelOverlap: false,
        label: { show: false, position: 'center' },
        emphasis: {
            label: {
                show: true,
                fontSize: 16,
                fontWeight: 'bold',
                formatter: 'تعداد کل کاربران:\n45000 نفر'
            }
        },
        labelLine: { show: false },
        data: [
            { value: 15000, name: 'کاربران فعال', itemStyle:{color:'#3366ff'} },
            { value: 10000, name: 'کاربران تکمیل نشده', itemStyle:{color:'#ff9966'} },
            { value: 12000, name: 'کاربران آنلاین', itemStyle:{color:'#00cc66'} },
            { value: 8000, name: 'کاربران جدید', itemStyle:{color:'#9933cc'} }
        ]
    }]
});

var chart2 = echarts.init(document.getElementById('chart2'));
chart2.setOption({
    tooltip: {},
    xAxis: {
        type: 'category',
        data: ['TRX TO USDT', 'USDT', 'TRX', 'Kucoin', 'UUSD']
    },
    yAxis: { type: 'value' },
    series: [{
        type: 'bar',
        data: [
            { value: 9000, itemStyle:{color:'#00cc66'} },
            { value: 3000, itemStyle:{color:'#ff9966'} },
            { value: 12000, itemStyle:{color:'#3366ff'} },
            { value: 2000, itemStyle:{color:'#ff3333'} },
            { value: 8000, itemStyle:{color:'#3366ff'} }
        ],
        barWidth: '40%'
    }]
});


    // ---------- Profile Image Upload ----------
// وقتی فایل انتخاب شد، همون لحظه آپلود شود
    $('#profileInput').on('change', function(){
        const file = this.files[0];
        if(!file) return;

        const formData = new FormData();
        formData.append('profile', file);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                const data = JSON.parse(res);
                if(data.success){
                    $('#profileImg').attr('src', data.path + '?' + new Date().getTime());
                    $('#profileError').text('');
                } else {
                    $('#profileError').text(data.error);
                }
            },
            error: function(){
                $('#profileError').text('خطا در آپلود فایل.');
            }
        });
    });

    // دکمه ذخیره اختیاری برای اعمال تغییرات دیگر
    $('#saveProfileBtn').on('click', function(){
        alert('تغییرات ذخیره شد!');
    });
 });


// ---------- Sort Asc / Desc ----------
document.getElementById('sortAsc')?.addEventListener('click', function(e){
    e.preventDefault();
    let url = new URL(window.location);
    url.searchParams.set('sort', 'asc');
    window.location = url.toString();
});
document.getElementById('sortDesc')?.addEventListener('click', function(e){
    e.preventDefault();
    let url = new URL(window.location);
    url.searchParams.set('sort', 'desc');
    window.location = url.toString();
});






  // ---------- Toggle Password Visibility ----------
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

  // Toggle password visibility
  $(".toggle-password").click(function(){
    const $input = $(this).siblings(".password-input");
    const $icon = $(this).find(".eye-icon");
    $input.attr("type", $input.attr("type")==="password"?"text":"password");
    $icon.toggleClass("fa-eye fa-eye-slash");
  });

  // Form submit
  $(".password-form").on("submit", function(e){
    e.preventDefault();
    const $inputs = $(this).find(".password-input");
    const $new = $inputs.eq(0), $repeat = $inputs.eq(1);
    const $captchaInput = $(this).find("#captcha-input");
    const captchaVal = $(this).find("#captcha").val().trim();
    const $error = $(this).find(".error-message");

    $captchaInput.removeClass("is-invalid"); // Reset previous error

    if(!$new.val().trim() || !$repeat.val().trim()) return $error.text("رمز عبور نمی‌تواند خالی باشد 🔴"), $new.focus();
    if($new.val() !== $repeat.val()) return $error.text("رمز عبور با تکرارش مطابقت ندارد 🔴"), $repeat.focus();
    if($captchaInput.val().trim() !== captchaVal) {
      $error.text("Captcha اشتباه است 🔴");
      $captchaInput.addClass("is-invalid").focus();
      return;
    }

    $error.text("");
    this.submit();
  });




