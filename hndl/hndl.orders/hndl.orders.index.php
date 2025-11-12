<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    $today = new DateTime();
    $today->modify('+1 hour');
    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    $separateThousands = fn($n) => number_format((int)$n);

    $page->orderList = [
        [
            "numberOrder" => "
            <div class='d-flex align-items-center justify-content-Start'>
                <input type='checkbox' class='form-check-input me-2'>
                <a href ='#'>502336263#</a>
                <span class='ms-2 text-success'>خرید</span>
            </div>",
            "OrderDetails" => "
            <div class='d-flex justify-contetn-start gap-1'>
            <img src ='../../assets/img/usdt.png' class = 'rounded-circle'>
            <div class='d-flex flex-column'>
            <span>197.3499</span>
            <span>تتر</span>
            </div>
            </div>
            ",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => $separateThousands(16520897),
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "موفق"
        ],
        [
            "numberOrder" => "
            <div class='d-flex align-items-center justify-content-start'>
                <input type='checkbox' class='form-check-input me-2'>
                <a href='#'>2013152343#</a>
                <span class='ms-2 text-danger'>فروش</span>
            </div>",
            "OrderDetails" => "
                <div class='d-flex justify-contetn-start gap-1'>
            <img src ='../../assets/img/usdt.png' class = 'rounded-circle'>
            <div class='d-flex flex-column'>
            <span>197.3499</span>
            <span>تتر</span>
            </div>
            </div>
            ",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => $separateThousands(22000000),
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 555555555),
            "Status" => "در انتظار تایید"
        ],
        [
            "numberOrder" => "
            <div class='d-flex align-items-center justify-content-start'>
                <input type='checkbox' class='form-check-input me-2'>
                <a href='#'>3013152343#</a>
                <span class='ms-2 text-success'>خرید</span>
            </div>",
            "OrderDetails" => "
                <div class='d-flex justify-contetn-start gap-1'>
            <img src ='../../assets/img/tron.png' class = 'rounded-circle'>
            <div class='d-flex flex-column'>
            <span>197.3499</span>
            <span>ترون</span>
            </div>
            </div>
            
            ",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => $separateThousands(12500000),
            "UnixTimestamp" => 9999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 99999999),
            "Status" => "ناموفق"
        ],
    ];

    // --- Filter by Buy/Sell ---
    $buySellFilter = $_GET['buySellFilter'] ?? '';
    if ($buySellFilter !== '') {
        $page->orderList = array_filter($page->orderList, function ($order) use ($buySellFilter) {
            preg_match('/(خرید|فروش)/u', $order['numberOrder'], $matches);
            return isset($matches[1]) && $matches[1] === $buySellFilter;
        });
    }

    // --- Filter by Products ---
    $products = $_GET['products'] ?? '';
    if ($products !== '') {
        $page->orderList = array_filter($page->orderList, function ($b) use ($products) {
            preg_match('/<span>\s*(ترون|تتر)\s*<\/span>/su', $b['OrderDetails'], $matches);
            return isset($matches[1]) && $matches[1] === $products;
        });
    }

    // --- Sort order list by date (asc/desc) ---
    $sortOrder = $_GET['sort'] ?? 'desc';
    $sortFunc = fn($a, $b) => ($sortOrder === 'asc')
        ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
        : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    usort($page->orderList, $sortFunc);

    // --- Assign colors by status ---
    foreach ($page->orderList as &$item) {
        $item["StatusColor"] = match (trim($item["Status"])) {
            "موفق" => "text-success opacity-green",
            "در انتظار تایید" => "text-warning bg-opacity-warning",
            default => "text-danger opacity-danger"
        };
    }
    unset($item);

    return [
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page, 'dateandtime' => $page->dateandtime]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/'
    ];
}
