<?php
function ProcessRequest($request)
{
    // -----------------------------
    // Utility Functions
    // -----------------------------

    // Mask sensitive parts of card numbers
    function maskCard($num)
    {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len); // fallback for short numbers
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }

    // Format numbers with thousand separators
    function separateThousands($number)
    {
        return number_format((int)$number);
    }

    // Generic function to sort arrays by UnixTimestamp
    function sortByTimestamp(array &$list, string $order = 'desc')
    {
        usort($list, function ($a, $b) use ($order) {
            return $order === 'asc'
                ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
                : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
        });
    }

    // -----------------------------
    // Initialize Payload
    // -----------------------------
    $p = new stdClass();

    // === Current Date & Time ===
    $today = new DateTime();
    $today->modify('+1 hour');
    $p->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    // -----------------------------
    // Orders List
    // -----------------------------
    $p->orderList = [
        [
            "numberOrder" => "1013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده ",
            "UserID" => 16,
            "price" => separateThousands(16520897),
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "موفق",
        ],
        [
            "numberOrder" => "2013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => separateThousands(22000000),
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 555555555),
            "Status" => "در انتظار تایید",
        ],
        [
            "numberOrder" => "3013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => separateThousands(12500000),
            "UnixTimestamp" => 9999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 99999999),
            "Status" => "ناموفق",
        ],
    ];

    // -----------------------------
    // Users List
    // -----------------------------
    $p->userList = [
        [
            "nationalCode" => "2356897845",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1111111),
            "Status" => "مسدود",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 33333333,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 333333333),
            "Status" => "موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "مونا مارامی",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 4444444444,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 4444444444),
            "Status" => "تکمیل نشده",
        ],
    ];

    // -----------------------------
    // Financial Requests
    // -----------------------------
    $p->requestList = [
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" => separateThousands(65665454546),
            "UnixTimestamp" => 9999999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "Status" => "مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => separateThousands(65665454546),
            "UnixTimestamp" => 777777777,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 33333333),
            "Status" => "مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 24,
            "price" => separateThousands(65665454546),
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "در صف تسویه",
        ],
    ];

    // -----------------------------
    // Sorting all lists by UnixTimestamp
    // -----------------------------
// $sortOrder = $_GET['sort'] ?? 'desc';
// $activeTab = $_GET['tab'] ?? 'deposits';

// switch ($activeTab) {
//     case 'deposits':
//         sortByTimestamp($p->orderList, $sortOrder);
//         break;
//     case 'credits':
//         sortByTimestamp($p->userList, $sortOrder);
//         break;
//     case 'settlements':
//         sortByTimestamp($p->requestList, $sortOrder);
//         break;
// }

    // -----------------------------
    // Top Box Items
    // -----------------------------
    $p->TopBox = [
        ['Link' => "#", "Icon" => "home", "Title" => "پیغام‌ها", "Subtitle" => "12 تیکت | 5 اتوماسیون"],
        ['Link' => "#", "Icon" => "gear", "Title" => "تنظیمات", "Subtitle" => "2 سفارش در حال پردازش"],
        ['Link' => "#", "Icon" => "list-ul", "Title" => "تسویه", "Subtitle" => "نرمال"],
        ['Link' => "#", "Icon" => "file-alt", "Title" => "حساب‌های بانکی", "Subtitle" => "3 مورد در حال انتظار"],
        ['Link' => "#", "Icon" => "id-card", "Title" => "مدارک احراز", "Subtitle" => "2 مورد در حال انتظار"],
    ];

    // -----------------------------
    // Status Color Conditions
    // -----------------------------
    // Orders
    foreach ($p->orderList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "در انتظار تایید") $Item["StatusColor"] = "text-warning bg-opacity-warning";
        else $Item["StatusColor"] = "text-danger opacity-danger";
    }
    unset($Item);

    // Users
    foreach ($p->userList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "تکمیل نشده") $Item["StatusColor"] = "text-primary bg-blue";
        else $Item["StatusColor"] = "text-danger opacity-danger";
    }
    unset($Item);

    // Financial Requests
    foreach ($p->requestList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "مشاهده رسید") $Item["StatusColor"] = "text-primary";
        else $Item["StatusColor"] = "text-warning";
    }
    unset($Item);

    // -----------------------------
    // Return payload to template
    // -----------------------------
    return [
        'content'   => biiq_Template::Start('pages->index', true, ['Objects' => $p, 'dateandtime' => $p->dateandtime]),
        'id'        => 0,
        'title'     => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}
