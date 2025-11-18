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
            "UserID" => 16,
            "price" => 16520897,
            "Level" => "فعال",
            "UnixTimestamp" => time() - 60 * 86400, // 2 ماه پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1729816000),
            "Status" => "موفق",
        ],
        [
            "ID" => "2013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => 22000000,
            "Level" => "طلایی",
            "UnixTimestamp" => time() - 3600,
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - 3600),
            "Status" => "در انتظار تایید",
        ],
        [
            "ID" => "3013152343",
            "OrderDetails" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => 12500000,
            "Level" => "حرفه ای",
            "UnixTimestamp" => 1703464000,
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1703464000),
            "Status" => "ناموفق",
        ],
        [
            "ID" => "4013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 18,
            "price" => 6598542,
            "Level" => "جدید",
            "UnixTimestamp" => time() - (5 * 30 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 30 * 86400)),
            "Status" => "موفق",
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
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1111111),
        ],
        [

            "User" => "بنفشه ابراهیمی",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 33333333,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 333333333),
        ],
        [

            "User" => "مونا مارامی",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 4444444444,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 4444444444),

        ],
    ];

    // -----------------------------
    // 💰 List of financial requests
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
    // 🎨 Status colors  
    // -----------------------------
// -----------------------------
// 🔝 اضافه کردن رنگ وضعیت
// -----------------------------
// -----------------------------
// سطح کاربر و کلاس + آیکون مخصوص
// -----------------------------
foreach ($p->orderList as &$Item) {

    // رنگ وضعیت
    $status = trim($Item["Status"]);
    if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
    elseif ($status === "در انتظار تایید") $Item["StatusColor"] = "text-warning bg-opacity-warning";
    else $Item["StatusColor"] = "text-danger opacity-danger";

    // سطح کاربر و آیکون
    $level = trim($Item["Level"]);
    switch ($level) {
        case "طلایی":
            $Item["LevelColor"] = "text-warning";
            $Item["LevelIcon"] = "fa-solid fa-star"; 
            break;
        case "حرفه ای":
            $Item["LevelColor"] = "text-red";
            $Item["LevelIcon"] = "fa-solid fa-medal"; 
            break;
        case "فعال":
            $Item["LevelColor"] = "text-success";
            $Item["LevelIcon"] = "fa-solid fa-circle-check"; 
            break;
        default: // جدید یا سایر سطح‌ها
            $Item["LevelColor"] = "text-primary";
            $Item["LevelIcon"] = "fa-solid fa-user"; 
            break;
    }
}
unset($Item);

    foreach ($p->userList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "تکمیل نشده") $Item["StatusColor"] = "text-primary bg-blue";
        else $Item["StatusColor"] = "text-danger opacity-danger";
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
