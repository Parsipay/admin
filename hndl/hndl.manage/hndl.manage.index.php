<?php

function ProcessRequest($request)
{
    // =========================
    // Helper Functions
    // =========================

// Card number mask
    $maskCard = fn($num) => strlen($num) <= 10
        ? str_repeat("*", strlen($num))
        : substr($num, 0, 6) . str_repeat("*", strlen($num) - 10) . substr($num, -4);

// User level icon
    $getLevelIcon = fn($level) => match (trim($level)) {
        "طلایی"   => "fa-solid fa-star text-warning",
        "حرفه ای" => "fa-solid fa-medal text-danger",
        "فعال"    => "fa-solid fa-circle-check text-success",
        default   => "fa-solid fa-user text-primary",
    };

// Status color
    $getStatusColor = fn($status) => match (trim($status)) {
        "تایید شده", "موفق"       => "text-success opacity-green",
        "در انتظار تایید"          => "text-warning bg-opacity-warning",
        "تکمیل نشده"               => "text-primary bg-blue",
        default                    => "text-danger bg-red",
    };

// Relative time
    $timeAgo = function($timestamp) {
        $diff = time() - $timestamp;
        return match (true) {
            $diff < 60       => "لحظاتی پیش",
            $diff < 3600     => round($diff / 60) . " دقیقه پیش",
            $diff < 86400    => round($diff / 3600) . " ساعت پیش",
            $diff < 604800   => round($diff / 86400) . " روز پیش",
            $diff < 2592000  => round($diff / 604800) . " هفته پیش",
            $diff < 31536000 => round($diff / 2592000) . " ماه پیش",
            default          => round($diff / 31536000) . " سال پیش",
        };
    };

    // ✨ Common function to add level icon, status color and time
    $enrichList = function(&$list, $hasTime = false, $hasStatus = false, $levelKey = "Level", $statusKey = "Status", $timeKey = "UnixTimestamp") use ($getLevelIcon, $getStatusColor, $timeAgo) {
        foreach ($list as &$item) {
            if (isset($item[$levelKey])) $item["LevelIcon"] = $getLevelIcon($item[$levelKey]);
            if ($hasStatus && isset($item[$statusKey])) $item["StatusColor"] = $getStatusColor($item[$statusKey]);
            if ($hasTime && isset($item[$timeKey])) $item["lastActivity"] = $timeAgo($item[$timeKey]);
        }
        unset($item);
    };

    // =========================
    // Build Page Data
    // =========================
    $page = new stdClass();
    $timestamp = 1616301000;

    // ------------------------------
    // Documents
    // ------------------------------
    $page->docList = array_map(fn($user) => [
        "user" => $user,
        "UnixTimestamp" => $timestamp,
        "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $timestamp),
    ], ["بنفشه ابراهیمی", "بنفشه ابراهیمی"]);

    // ------------------------------
    // Cards
    // ------------------------------
    $page->Cards = [
        [
            "user" => "یگانه علیزاده",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => $maskCard("5022291077470837"),
        ],
        [
            "user" => "مریم ماهور",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => $maskCard("5022291077470837"),
        ],
    ];

    // ------------------------------
    // Users
    // ------------------------------
    $page->userList = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "UnixTimestamp" => time() - (150 * 86400),
            "lastActivityTimestamp" => time() - (23 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time()),
            "Status" => "مسدود",
            "Level" => "فعال",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "UnixTimestamp" => time() - (60 * 86400),
            "lastActivityTimestamp" => time() - (14 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 896554121),
            "Status" => "موفق",
            "Level" => "طلایی",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "UnixTimestamp" => time() - (14 * 86400),
            "lastActivityTimestamp" => time() - (1 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 126545878),
            "Status" => "موفق",
            "Level" => "حرفه ای",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "علی نهرانی",
            "UserID" => 4,
            "UnixTimestamp" => time() - (45 * 86400),
            "lastActivityTimestamp" => time() - (2 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 568753525),
            "Status" => "تکمیل نشده",
            "Level" => "جدید",
        ],
    ];

    $enrichList($page->userList, hasTime: true, hasStatus: true);
    // Sort by latest activity
    usort($page->userList, fn($a, $b) => $b["UnixTimestamp"] <=> $a["UnixTimestamp"]);
    // ------------------------------
    // Bank Accounts
    // ------------------------------
    $page->bankAccount = [
        [
            "nationalCode" => "2313152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "bankInfo" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/dey.png",
            "details" => "تایید شده",
            "Level" => "فعال",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "bankInfo" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/ansar.png",
            "details" => "تایید شده",
            "Level" => "طلایی",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "bankInfo" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/blu.png",
            "details" => "تایید شده",
            "Level" => "حرفه ای",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "علی تهرانی",
            "UserID" => 4,
            "bankInfo" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/blu.png",
            "details" => "تایید شده",
            "Level" => "جدید",
        ],
    ];

    $enrichList($page->bankAccount);

    // ------------------------------
    // Authentication
    // ------------------------------
    $page->authentication = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "documents" => "مشاهده مدارک",
            "Status" => "در انتظار تایید",
            "Level" => "فعال",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "documents" => "مشاهده مدارک",
            "Status" => "تایید شده",
            "Level" => "طلایی",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "documents" => "مشاهده مدارک",
            "Status" => "تکمیل نشده",
            "Level" => "حرفه ای",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "علی تهرانی",
            "UserID" => 4,
            "documents" => "مشاهده مدارک",
            "Status" => "رد شده",
            "Level" => "جدید",
        ],
    ];

    $enrichList($page->authentication, hasStatus: true);

    // ------------------------------
    // Return payload
    // ------------------------------
    return [
        'content'   => biiq_Template::Start('manage->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'manage/',
        'navlink'   => 2
    ];
}
