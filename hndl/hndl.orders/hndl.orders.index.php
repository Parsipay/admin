<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    $date = new biiq_PersianDate();
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
    $page->orderList = [
        [
            'ID' => '۱۰۱۳۱۵۲۳۴۳',
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "price" => 100000,
            "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 2 * 86400)),
            "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 2 * 86400)),
            "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 2 * 86400)),
            "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 2 * 86400)),
            "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 2 * 86400)),
            "Status" => "در انتظار تایید",
            "Level" => "فعال"
        ],
        [
            'ID' => '۲۰۱۳۱۵۲۳۴۳',
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "price" => 100000,
            "UnixTimestamp" => time() - (5 * 12 * 86400), // تقریبا 2 ماه پیش
            "lastActivityTimestamp" => time() - (14 * 86400), // 14 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 896554121),

            "Status" => "ناموفق",
            "Level" => "طلایی"

        ],
        [
            'ID' => '۳۰۱۳۱۵۲۳۴۳',
            "User" => "سارا کریمی",
            "UserID" => 3,
            "price" => 12500000,
            "UnixTimestamp" => time() - (14 * 86400), // 14 روز پیش
            "lastActivityTimestamp" => time() - (1 * 86400), // 1 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 126545878),
            "PersianDateString" => biiq_PersianDate::ToPersianDate(time() - 655888),
            "Status" => "ناموفق",
            "Level" => "حرفه‌ای"

        ],
        [
            'ID' => '۴۰۱۳۱۵۲۳۴۳',
            "User" => " علی تهرانی",
            "UserID" => 4,
            "price" => 12500000,
            "UnixTimestamp" => time() - (45 * 86400), // 1 ماه و نیم پیش
            "lastActivityTimestamp" => time() - (2 * 86400), // 2 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 568753525),
            "Status" => "موفق",
            "Level" => "جدید",

        ],
    ];
    foreach ($page->orderList as &$Item) {
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
    // ساخت رشته‌های نسبی
    foreach ($page->orderList as &$Item) {
        $Item["akharin"] = timeAgo($Item["lastActivityTimestamp"]); // برای آخرین فعالیت
        $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]); // برای تاریخ ثبت
    }
    unset($Item);


    usort($page->orderList, function ($a, $b) {
        return $b["UnixTimestamp"] <=> $a["UnixTimestamp"];
    });

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
        'Canonical' => SITE . 'orders/',
        'navlink' => 3
    ];
}
