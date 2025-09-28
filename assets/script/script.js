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


//sorted status for ordered lists
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

   // ------------------ Donut Chart ------------------
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
            radius: ['65%', '85%'], // دایره توخالی
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

    // ------------------ Bar Chart ------------------
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

