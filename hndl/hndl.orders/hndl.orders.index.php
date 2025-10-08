<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    // === Current Date & Time ===
    $today = new DateTime();
    $today->modify('+1 hour');
    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    
    // --- Helper functions ---
    $maskCard = fn($num) =>
        (strlen($num) <= 10)
            ? str_repeat('*', strlen($num))
            : substr($num, 0, 6) . str_repeat('*', strlen($num) - 10) . substr($num, -4);

    $separateThousands = fn($n) => number_format((int)$n);

    // --- Order list ---
    $page->orderList = [
        [
            "numberOrder" => "1013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => $separateThousands(16520897),
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "موفق"
        ],
        [
            "numberOrder" => "2013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => $separateThousands(22000000),
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 555555555),
            "Status" => "در انتظار تایید"
        ],
        [
            "numberOrder" => "3013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => $separateThousands(12500000),
            "UnixTimestamp" => 9999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 99999999),
            "Status" => "ناموفق"
        ],
    ];

    // --- Sort order list by date (asc/desc) ---
    $sortOrder = $_GET['sort'] ?? 'desc';
    $sortFunc = fn($a, $b) =>
        ($sortOrder === 'asc')
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    usort($page->orderList, $sortFunc);

    // --- Request list ---
    $page->requestList = [
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" => $separateThousands(65665454546),
            "UnixTimestamp" => 9999999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "Status" => "مشاهده رسید"
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => $separateThousands(65665454546),
            "UnixTimestamp" => 777777777,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 33333333),
            "Status" => "مشاهده رسید"
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 24,
            "price" => $separateThousands(65665454546),
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "در صف تسویه"
        ],
    ];

    // --- User list ---
    $page->userList = [
        [
            "nationalCode" => "2356897845",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1111111),
            "Status" => "مسدود"
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 33333333,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 333333333),
            "Status" => "موفق"
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "مونا مارامی",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 4444444444,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 4444444444),
            "Status" => "تکمیل نشده"
        ],
    ];

    // --- Assign colors by status ---
    foreach ($page->orderList as &$item) {
        $item["StatusColor"] = match (trim($item["Status"])) {
            "موفق" => "text-success opacity-green",
            "در انتظار تایید" => "text-warning bg-opacity-warning",
            default => "text-danger opacity-danger"
        };
    }
    unset($item);

    foreach ($page->userList as &$item) {
        $item["StatusColor"] = match (trim($item["Status"])) {
            "موفق" => "text-success opacity-green",
            "تکمیل نشده" => "text-primary bg-blue",
            default => "text-danger opacity-danger"
        };
    }
    unset($item);

    foreach ($page->requestList as &$item) {
        $item["StatusColor"] = (trim($item["Status"]) === "مشاهده رسید")
            ? "text-primary"
            : "text-warning";
    }
    unset($item);

    // --- Return page data ---
    return [
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page,'dateandtime' => $page->dateandtime]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/'
    ];
}
