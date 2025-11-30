<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    $date = new biiq_PersianDate();

    // Convert Unix timestamp to relative time
    function timeAgo($unixTimestamp)
    {
        $now = time();
        $diff = $now - $unixTimestamp;

        if ($diff < 60) return $diff . " ثانیه پیش";
        if ($diff < 3600) return floor($diff / 60) . " دقیقه پیش";
        if ($diff < 86400) return floor($diff / 3600) . " ساعت پیش";
        if ($diff < 2592000) return floor($diff / 86400) . " روز پیش";
        if ($diff < 31104000) return floor($diff / 2592000) . " ماه پیش";
        return floor($diff / 31104000) . " سال پیش";
    }

    // -----------------------------
    // Orders list
    // -----------------------------
    $page->orderList = [
        [
            'ID' => '۱۰۱۳۱۵۲۳۴۳',
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "price" => 100000,
            "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 30 * 86400)),
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
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 12 * 86400)),
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
            "PersianDate" => biiq_PersianDate::date("ل j F Y - H:i", time() - (14 * 86400)),
            "Status" => "ناموفق",
            "Level" => "حرفه‌ای"
        ],
        [
            'ID' => '۴۰۱۳۱۵۲۳۴۳',
            "User" => " علی تهرانی",
            "UserID" => 4,
            "price" => 12500000,
            "UnixTimestamp" => time() - (30 * 86400), // 1 ماه و نیم پیش
            "lastActivityTimestamp" => time() - (2 * 86400), // 2 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (30 * 86400)),
            "Status" => "موفق",
            "Level" => "جدید"
        ],
    ];

    // -----------------------------
    // Assign Level Icons
    // -----------------------------
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

    // -----------------------------
    // Add relative date strings
    // -----------------------------
    foreach ($page->orderList as &$Item) {
        $Item["akharin"] = timeAgo($Item["lastActivityTimestamp"]); // Last activity
        $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]); // Registration date
    }
    unset($Item);

    // -----------------------------
    // Sort orders by UnixTimestamp descending
    // -----------------------------
    usort($page->orderList, function ($a, $b) {
        return $b["UnixTimestamp"] <=> $a["UnixTimestamp"];
    });

    // -----------------------------
    // Filter by Buy/Sell
    // -----------------------------
    $buySellFilter = $_GET['buySellFilter'] ?? '';
    if ($buySellFilter !== '') {
        $page->orderList = array_filter($page->orderList, function ($order) use ($buySellFilter) {
            preg_match('/(خرید|فروش)/u', $order['numberOrder'], $matches);
            return isset($matches[1]) && $matches[1] === $buySellFilter;
        });
    }

    // -----------------------------
    // Filter by Products
    // -----------------------------
    $products = $_GET['products'] ?? '';
    if ($products !== '') {
        $page->orderList = array_filter($page->orderList, function ($b) use ($products) {
            preg_match('/<span>\s*(ترون|تتر)\s*<\/span>/su', $b['OrderDetails'], $matches);
            return isset($matches[1]) && $matches[1] === $products;
        });
    }

    // -----------------------------
    // Sort order list by date (asc/desc)
    // -----------------------------
    $sortOrder = $_GET['sort'] ?? 'desc';
    usort($page->orderList, fn($a, $b) => 
        $sortOrder === 'asc' ? $a['UnixTimestamp'] <=> $b['UnixTimestamp'] : $b['UnixTimestamp'] <=> $a['UnixTimestamp']
    );

    // -----------------------------
    // Assign colors by status
    // -----------------------------
    foreach ($page->orderList as &$item) {
        $status = trim($item["Status"]);
        $item["StatusColor"] = match ($status) {
            "موفق" => "text-success opacity-green",
            "در انتظار تایید" => "text-warning bg-opacity-warning",
            default => "text-danger opacity-danger"
        };
    }
    unset($item);

    // -----------------------------
    // Final output
    // -----------------------------
    return [
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/',
        'navlink'   => 3
    ];
}
