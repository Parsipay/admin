<?php

function ProcessRequest($request)
{
    $page = new stdClass();

    // -----------------------------
    // Helper: Relative Time
    // -----------------------------
    $timeAgo = function ($unix) {
        $diff = time() - $unix;

        return match (true) {
            $diff < 60        => $diff . " ثانیه پیش",
            $diff < 3600      => floor($diff / 60) . " دقیقه پیش",
            $diff < 86400     => floor($diff / 3600) . " ساعت پیش",
            $diff < 2592000   => floor($diff / 86400) . " روز پیش",
            $diff < 31104000  => floor($diff / 2592000) . " ماه پیش",
            default           => floor($diff / 31104000) . " سال پیش",
        };
    };

    // -----------------------------
    // Orders List (Static Data)
    // -----------------------------
    $page->orderList = [
        [
            'ID' => '1013152343',
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "price" => 100000,
            "UnixTimestamp" => time() - (5 * 30 * 86400),
            "lastActivityTimestamp" => time() - (23 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 30 * 86400)),
            "Status" => "در انتظار تایید",
            "Level" => "فعال"
        ],
        [
            'ID' => '2013152343',
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "price" => 100000,
            "UnixTimestamp" => time() - (5 * 12 * 86400),
            "lastActivityTimestamp" => time() - (14 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 12 * 86400)),
            "Status" => "ناموفق",
            "Level" => "طلایی"
        ],
        [
            'ID' => '3013152343',
            "User" => "سارا کریمی",
            "UserID" => 3,
            "price" => 12500000,
            "UnixTimestamp" => time() - (14 * 86400),
            "lastActivityTimestamp" => time() - (1 * 86400),
            "PersianDate" => biiq_PersianDate::date("ل j F Y - H:i", time() - (14 * 86400)),
            "Status" => "ناموفق",
            "Level" => "حرفه‌ای"
        ],
        [
            'ID' => '4013152343 ',
            "User" => "علی تهرانی",
            "UserID" => 4,
            "price" => 12500000,
            "UnixTimestamp" => time() - (30 * 86400),
            "lastActivityTimestamp" => time() - (2 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (30 * 86400)),
            "Status" => "موفق",
            "Level" => "جدید"
        ],
    ];

    // -----------------------------
    // Level Icons
    // -----------------------------
    foreach ($page->orderList as &$item) {
        $item["LevelIcon"] = match (trim($item["Level"])) {
            "طلایی"     => "fa-solid fa-star text-warning",
            "حرفه‌ای"   => "fa-solid fa-medal text-red",
            "فعال"      => "fa-solid fa-circle-check text-green",
            default     => "fa-solid fa-user text-primary",
        };
    }

    // -----------------------------
    // Relative Times
    // -----------------------------
    foreach ($page->orderList as &$item) {
        $item["akharin"] = $timeAgo($item["lastActivityTimestamp"]);
        $item["PersianDateRelative"] = $timeAgo($item["UnixTimestamp"]);
    }

    // -----------------------------
    // Filter: Buy / Sell
    // -----------------------------
    if (!empty($_GET['buySellFilter'])) {
        $filter = $_GET['buySellFilter'];

        $page->orderList = array_filter($page->orderList, function ($row) use ($filter) {
            return preg_match('/(خرید|فروش)/u', $row['numberOrder'], $m) && $m[1] === $filter;
        });
    }

    // -----------------------------
    // Filter: Products
    // -----------------------------
    if (!empty($_GET['products'])) {
        $product = $_GET['products'];

        $page->orderList = array_filter($page->orderList, function ($row) use ($product) {
            return preg_match('/<span>\s*(ترون|تتر)\s*<\/span>/su', $row['OrderDetails'], $m)
                && $m[1] === $product;
        });
    }

    // -----------------------------
    // Final Sort (ASC or DESC)
    // -----------------------------
    $sort = $_GET['sort'] ?? 'desc';

    usort($page->orderList, function ($a, $b) use ($sort) {
        return $sort === 'asc'
            ? $a["UnixTimestamp"] <=> $b["UnixTimestamp"]
            : $b["UnixTimestamp"] <=> $a["UnixTimestamp"];
    });

    // -----------------------------
    // Status Colors
    // -----------------------------
    foreach ($page->orderList as &$item) {
        $item["StatusColor"] = match (trim($item["Status"])) {
            "موفق"            => "text-success opacity-green",
            "در انتظار تایید" => "text-warning bg-opacity-warning",
            default           => "text-danger opacity-danger"
        };
    }

    // -----------------------------
    // Final Output
    // -----------------------------
    return [
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/',
        'navlink'   => 3
    ];
}
