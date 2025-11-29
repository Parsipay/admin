<?php

// ==============================================
// 📦 Utility Functions
// ==============================================
// ✅ Covering card numbers (masking)
function maskCard(string $num): string
{
    $len = strlen($num);
    if ($len <= 10) return str_repeat("*", $len);
    return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
}

// ✅ Separate thousands
function separateThousands($number): string
{
    return number_format((int)$number);
}
// ✅ Sorting lists by UnixTimestamp
function sortByTimestamp(array &$list, string $order = 'desc'): void
{
    usort($list, function ($a, $b) use ($order) {
        return $order === 'asc'
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    });
}
// ==============================================
// 🧩 Main Function
// ==============================================
function ProcessRequest($request)
{
    $p = new stdClass();
    function timeAgo($unixTimestamp)
    {
        $now = time();
        $diff = $now - $unixTimestamp;

        if ($diff < 60) {
            return $diff . " ثانیه پیش";
        } elseif ($diff < 3600) {
            return floor($diff / 60) . " دقیقه پیش";
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . " ساعت پیش";
        } elseif ($diff < 2592000) { // کمتر از 30 روز
            return floor($diff / 86400) . " روز پیش";
        } elseif ($diff < 31104000) { // کمتر از 12 ماه
            return floor($diff / 2592000) . " ماه پیش";
        } else {
            return floor($diff / 31104000) . " سال پیش";
        }
    }

    // -----------------------------
    // 🧾 Order list
    // -----------------------------
    $p->orderList = [
        [
            "ID" => "1013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "price" => 165208970,
            "Level" => "فعال",
            "UnixTimestamp" => time() - 60 * 86400, // 2 ماه پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i",  time() - 60 * 86400),
            "Status" => "موفق",
        ],
        [
            "ID" => "2013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "price" => 220000000,
            "Level" => "طلایی",
            "UnixTimestamp" => time() - 3600,
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - 3600),
            "Status" => "پردازش",
        ],
        [
            "ID" => "3013152343",
            "OrderDetails" => "09128431937",
            "User" => " سارا کریمی",
            "UserID" => 3,
            "price" => 125000000,
            "Level" => "حرفه ای",
            "UnixTimestamp" => 956565545,
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i",956565545 ),
            "Status" => "موفق ",
        ],
        [
            "ID" => "4013152343",
            "OrderDetails" => "09128431937",
            "User" => " علی تهرانی",
            "UserID" => 18,
            "price" => 6598542,
            "Level" => "جدید",
            "UnixTimestamp" => time() - (5 * 30 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 30 * 86400)),
            "Status" => " موفق",
        ],
    ];
    foreach ($p->orderList as &$Item) {
        $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]);
    }
    unset($Item);
    // -----------------------------
    // 👥 User list    
    // -----------------------------
$p->userList = [
    [
        "User" => "یگانه علیزاده",
        "UserID" => 1,
        "UnixTimestamp" => 1690000000,   // یک عدد یونیکس دلخواه
        "lastActivityTimestamp" => 1701656900,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690000000),
    ],
    [
        "User" => "بنفشه ابراهیمی",
        "UserID" => 2,
        "UnixTimestamp" => 1690500000,
        "lastActivityTimestamp" => 1701653900,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690500000),
    ],
    [
        "User" => "سارا کریمی",
        "UserID" => 3,
        "UnixTimestamp" => 1691000000,
        "lastActivityTimestamp" => 1701225500,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691000000),
    ],
    [
        "User" => "علی تهرانی",
        "UserID" => 4,
        "UnixTimestamp" => 1691500000,
        "lastActivityTimestamp" => 1691500000,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691500000),
    ],
];
    // ساخت رشته‌های نسبی
    foreach ($p->userList as &$Item) {
        $Item["akharin"] = timeAgo($Item["lastActivityTimestamp"]); // برای آخرین فعالیت
        $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]); // برای تاریخ ثبت
    }
    unset($Item);


    usort($p->userList, function ($a, $b) {
        return $b["UnixTimestamp"] <=> $a["UnixTimestamp"];
    });


    // -----------------------------
    // 💰 List of financial requests
    // -----------------------------
$p->requestList = [
    [
        "requestCode" => "0013152343",
        "trackingNumber" => "0293564635",
        "User" => "بنفشه ابراهیمی",
        "UserID" => 2,
        "price" => separateThousands(65665454546),
        "UnixTimestamp" => 1690000000,
        "lastActivityTimestamp" => 1690000000,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690000000),
        "Status" => "مشاهده رسید",
        "Level" => "طلایی",
    ],
    [
        "requestCode" => "0013152344",
        "trackingNumber" => "0293564636",
        "User" => "یگانه علیزاده",
        "UserID" => 1,
        "price" => separateThousands(65665454546),
        "UnixTimestamp" => 1690500000,
        "lastActivityTimestamp" => 1690500000,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690500000),
        "Status" => "مشاهده رسید",
        "Level" => "فعال",
    ],
    [
        "requestCode" => "0013152345",
        "trackingNumber" => "0293564637",
        "User" => "سارا کریمی",
        "UserID" => 3,
        "price" => separateThousands(65665454546),
        "UnixTimestamp" => 1691000000,
        "lastActivityTimestamp" => 1691000000,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691000000),
        "Level" => "حرفه ای",
        "Status" => "مشاهده رسید",
    ],
    [
        "requestCode" => "0013152346",
        "trackingNumber" => "0293564638",
        "User" => "علی تهرانی",
        "UserID" => 4,
        "price" => separateThousands(65665454546),
        "UnixTimestamp" => 1691500000,
        "lastActivityTimestamp" => 1691500000,
        "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691500000),
        "Status" => "در صف تسویه",
        "Level" => "جدید",
    ],
];


    // ساخت رشته‌های نسبی
    foreach ($p->userList as &$Item) {
        $Item["akharin"] = timeAgo($Item["lastActivityTimestamp"]); // برای آخرین فعالیت
        $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]); // برای تاریخ ثبت
    }
    unset($Item);


    usort($p->userList, function ($a, $b) {
        return $b["UnixTimestamp"] <=> $a["UnixTimestamp"];
    });


    // -----------------------------
    // 🔝 Top dashboard items
    // -----------------------------
    /** @var array $settings */
    $p->TopBox = [
        [
            'Link'     => $settings['site'] . 'tickets/',
            'Icon'     => 'home',
            'Title'    => 'پیغام‌ها',
            'Subtitle' => '12 تیکت | 5 اتوماسیون'
        ],
        ['Link' => $settings['site'] . 'settings/', "Icon" => "gear", "Title" => "تنظیمات", "Subtitle" => "2 سفارش در حال پردازش"],
        ['Link' => $settings['site'] . 'transactions/', "Icon" => "list-ul", "Title" => "تسویه", "Subtitle" => "نرمال"],
        ['Link' => "#", "Icon" => "file-alt", "Title" => "حساب‌های بانکی", "Subtitle" => "3 مورد در حال انتظار"],
        ['Link' => "#", "Icon" => "id-card", "Title" => "مدارک احراز", "Subtitle" => "2 مورد در حال انتظار"],
    ];

    // -----------------------------
    // -----------------------------
    foreach ($p->orderList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "پردازش") $Item["StatusColor"] = "text-warning bg-opacity-warning";
        elseif ($status === "رد شده") $Item["StatusColor"] = "text-danger opacity-danger";
        elseif ($status === "تکمیل نشده") $Item["StatusColor"] = "text-primary opacity-primary";
        else $Item["StatusColor"] = "text-danger opacity-danger";
        $level = trim($Item["Level"]);
        switch ($level) {
            case "طلایی":

                $Item["LevelIcon"] = "fa-solid fa-star text-warning";
                break;
            case "حرفه ای":

                $Item["LevelIcon"] = "fa-solid fa-medal text-red";
                break;
            case "فعال":

                $Item["LevelIcon"] = "fa-solid fa-circle-check text-green";
                break;
            default:
                $Item["LevelIcon"] = "fa-solid fa-user text-primary";
                break;
        }
    }
    unset($Item);
    foreach ($p->requestList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "پردازش") $Item["StatusColor"] = "text-warning bg-opacity-warning";
        elseif ($status === "رد شده") $Item["StatusColor"] = "text-danger opacity-danger";
        elseif ($status === "تکمیل نشده") $Item["StatusColor"] = "text-primary opacity-primary";
        else $Item["StatusColor"] = "text-danger opacity-danger";
        $level = trim($Item["Level"]);
        switch ($level) {
            case "طلایی":

                $Item["LevelIcon"] = "fa-solid fa-star text-warning";
                break;
            case "حرفه ای":

                $Item["LevelIcon"] = "fa-solid fa-medal text-red";
                break;
            case "فعال":

                $Item["LevelIcon"] = "fa-solid fa-circle-check text-green";
                break;
            default:
                $Item["LevelIcon"] = "fa-solid fa-user text-primary";
                break;
        }
    }
    unset($Item);

    foreach ($p->userList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "تکمیل نشده") $Item["StatusColor"] = "text-primary bg-blue";
        else $Item["StatusColor"] = "text-danger opacity-danger text-decoration-none";
    }
    unset($Item);

    foreach ($p->requestList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "مشاهده رسید") $Item["StatusColor"] = "text-primary";
        else $Item["StatusColor"] = "text-warning";
    }
    unset($Item);

    // -----------------------------
    // 🔙 Final output
    // -----------------------------
    return [
        'content'   => biiq_Template::Start('pages->index', true, [
            'Objects' => $p,
            'dateandtime' => $p->dateandtime,
        ]),
        'navlink' => 1,
        'id'        => 0,
        'title'     => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}
