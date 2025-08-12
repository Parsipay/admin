// $(document).ready(function () {


// function sortTableByUnixJQ(order = 'desc') {
//   let $rows = $("#tablesearch tr").get();

//   $rows.sort(function(a, b) {
//     let aTime = parseInt($(a).data("item-unix"));
//     let bTime = parseInt($(b).data("item-unix"));
//     return order === 'asc' ? aTime - bTime : bTime - aTime;
//   });

//   $.each($rows, function(index, row) {
//     $("#tablesearch").append(row);
//   });

//   updateSortIcons(order);
// }

// });

document.getElementById('toggleSearchOrder').addEventListener('click', function() {
    const input = document.getElementById('searchOrderNumber');
    if (input.classList.contains('d-none')) {
        input.classList.remove('d-none');
        input.focus();
    } else {
        input.classList.add('d-none');
        input.value = '';
        filterTable('');  // خالی کردن فیلتر وقتی input بسته میشه
    }
});

document.getElementById('searchOrderNumber').addEventListener('input', function() {
    filterTable(this.value.trim().toLowerCase());
});

function filterTable(searchValue) {
    const rows = document.querySelectorAll('#tablesearch tr');
    rows.forEach(row => {
        const orderNumberCell = row.querySelector('td:first-child span');
        if (orderNumberCell) {
            const orderNumberText = orderNumberCell.textContent.toLowerCase();
            if (orderNumberText.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

// نمایش و مخفی کردن input مشخصات سفارش
document.getElementById('toggleSearchOrderDetails').addEventListener('click', function() {
    const input = document.getElementById('searchOrderDetails');
    if (input.classList.contains('d-none')) {
        input.classList.remove('d-none');
        input.focus();
    } else {
        input.classList.add('d-none');
        input.value = '';
        filterOrderDetails('');
    }
});

// جستجو روی مشخصات سفارش وقتی تایپ میشه
document.getElementById('searchOrderDetails').addEventListener('input', function() {
    filterOrderDetails(this.value.trim().toLowerCase());
});

function filterOrderDetails(searchValue) {
    const rows = document.querySelectorAll('#tablesearch tr');
    rows.forEach(row => {
        // مشخصات سفارش در td دوم (index 1) هست:
        const amountSpan = row.querySelector('td:nth-child(2) div > div > span:first-child');
        const currencySpan = row.querySelector('td:nth-child(2) div > div > span:nth-child(2)');
        let amountText = amountSpan ? amountSpan.textContent.toLowerCase() : '';
        let currencyText = currencySpan ? currencySpan.textContent.toLowerCase() : '';

        if (amountText.includes(searchValue) || currencyText.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('toggleSearchUserFullName');
  const input = document.getElementById('searchUserFullName');
  const tableBody = document.getElementById('tablesearch');
  const colIndex = 2; // ستون سوم (index صفر بیس)

  // تابع فیلتر جدول بر اساس مقدار و ستون مشخص
  function filterTable(value) {
    const search = value.toLowerCase();
    const rows = tableBody.querySelectorAll('tr');

    rows.forEach(row => {
      const cell = row.querySelector(`td:nth-child(${colIndex + 1})`);
      const text = cell ? cell.textContent.toLowerCase() : '';
      row.style.display = text.includes(search) ? '' : 'none';
    });
  }

  // نمایش/مخفی کردن input با کلیک روی دکمه
  toggle.addEventListener('click', function(e) {
    e.stopPropagation();
    if (input.classList.contains('d-none')) {
      input.classList.remove('d-none');
      input.focus();
    } else {
      input.classList.add('d-none');
      input.value = '';
      filterTable('');
    }
  });

  // وقتی داخل input تایپ می‌کنیم، جدول فیلتر میشه
  input.addEventListener('input', function() {
    filterTable(this.value.trim());
  });

  // جلوگیری از بسته شدن input هنگام کلیک روی خودش
  input.addEventListener('click', function(e) {
    e.stopPropagation();
  });

  // کلیک بیرون input => مخفی شدن input و پاک شدن فیلتر
  document.addEventListener('click', function() {
    if (!input.classList.contains('d-none')) {
      input.classList.add('d-none');
      input.value = '';
      filterTable('');
    }
  });
});




