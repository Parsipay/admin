<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    $date = new biiq_PersianDate();

    $page->orderList = [
        [
            'ID' => 11133441,
            "User" => "بنفشه مورد دوم",
            "UserID" => 18,
            "price" => 100000,
            "PersianDate" => biiq_PersianDate::UnixToAgo(time() - 255888),
            "PersianDateString" => biiq_PersianDate::ToPersianDate(time() - 255888),
            "Status" => "ناموفق"
        ],
        [
            'ID' => 123135334,
            "User" => "بنفشه مورد دوم",
            "UserID" => 18,
            "price" => 100000,
            "PersianDate" => biiq_PersianDate::UnixToAgo(time() - 455888),
            "PersianDateString" => biiq_PersianDate::ToPersianDate(time() - 455888),
            "Status" => "ناموفق"
        ],
        [
            'ID' => 965486241,
            "User" => "بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => 12500000,
            "UnixTimestamp" => time() - 655888,
            "PersianDate" => biiq_PersianDate::UnixToAgo(time() - 655888),
            "PersianDateString" => biiq_PersianDate::ToPersianDate(time() - 655888),
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
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/'
    ];
}
