<?php

function ProcessRequest($request)
{
    /* =========================
       Helper Functions
    ========================= */

    $getLevelIcon = fn($level) => match (trim($level)) {
        "طلایی"   => "fa-solid fa-star text-warning",
        "حرفه ای" => "fa-solid fa-medal text-danger",
        "فعال"    => "fa-solid fa-circle-check text-success",
        default   => "fa-solid fa-user text-primary",
    };
    
$getStatusColor = fn($status) => match (trim($status)) {
    "تایید شده", "موفق"              => "text-success opacity-green",
    "در انتظار تایید", "عدم تایید مدارک" => "text-warning bg-opacity-warning",
    "تکمیل نشده", "در انتظار بررسی"     => "text-primary bg-blue",
    "مسدود"                          => "text-danger bg-red",
    default                           => "text-secondary bg-light",
};
    // -------- Details Color --------
    $getDetailsColor = fn($details) => match (trim($details)) {
        "تایید شده" => "text-success opacity-green",
        "رد شده"    => "text-danger bg-red",
        default     => "text-primary bg-blue",
    };

    $timeAgo = function ($timestamp) {
        $diff = time() - $timestamp;

        if ($diff < 60)        return "لحظاتی پیش";
        if ($diff < 3600)      return floor($diff / 60) . " دقیقه پیش";
        if ($diff < 86400)     return floor($diff / 3600) . " ساعت پیش";
        if ($diff < 604800)    return floor($diff / 86400) . " روز پیش";
        if ($diff < 2592000)   return floor($diff / 604800) . " هفته پیش";
        if ($diff < 31536000)  return floor($diff / 2592000) . " ماه پیش";

        return floor($diff / 31536000) . " سال پیش";
    };

   $enrichList = function (
    array &$list,
    bool $withTime = false,
    bool $withStatus = false,
    bool $withDetails = false
) use ($getLevelIcon, $getStatusColor, $getDetailsColor, $timeAgo) {

    foreach ($list as &$item) {

        // Level
        if (isset($item["Level"])) {
            $item["LevelIcon"] = $getLevelIcon($item["Level"]);
        }

        // Status
        if ($withStatus && isset($item["Status"])) {
            $item["StatusColor"] = $getStatusColor($item["Status"]);
        }

        // Details ✅
        if ($withDetails && isset($item["details"])) {
            $item["detailsColor"] = $getDetailsColor($item["details"]);
        }

        // Time
        if ($withTime && isset($item["UnixTimestamp"])) {
            $item["lastActivity"] = $timeAgo($item["UnixTimestamp"]);
        }
    }

    unset($item);
};

    /* =========================
       Build Page Data
    ========================= */

    $page = new stdClass();
    $timestamp = 1616301000;

    // ---------------- Documents ----------------
    $page->docList = array_map(fn($user) => [
        "user"           => $user,
        "UnixTimestamp"  => $timestamp,
        "persianDate"    => biiq_PersianDate::date("l j F Y - H:i", $timestamp),
    ], ["بنفشه ابراهیمی", "بنفشه ابراهیمی"]);

    // ---------------- Cards ----------------
    $page->Cards = [
        [
            "user" => "یگانه علیزاده",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => biiq_Engine::maskCard("5022291077470837"),
        ],
        [
            "user" => "مریم ماهور",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => biiq_Engine::maskCard("5022291077470837"),
        ],
    ];

    // ---------------- Users ----------------
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
            "Status" => "تایید شده",
            "Level" => "طلایی",
        ],
        
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "علی نهرانی",
            "UserID" => 4,
            "UnixTimestamp" => time() - (45 * 86400),
            "lastActivityTimestamp" => time() - (2 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 568753525),
            "Status" => "در انتظار بررسی",
            "Level" => "جدید",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "UnixTimestamp" => time() - (14 * 86400),
            "lastActivityTimestamp" => time() - (1 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 126545878),
            "Status" => "عدم تایید مدارک",
            "Level" => "حرفه ای",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "UnixTimestamp" => time() - (14 * 86400),
            "lastActivityTimestamp" => time() - (1 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 126545878),
            "Status" => " در انتظار ارسال مدارک",
            "Level" => "حرفه ای",
        ],
    ];

    $enrichList($page->userList, true, true);
    //usort($page->userList, fn($a, $b) => $b["UnixTimestamp"] <=> $a["UnixTimestamp"]);

    // ---------------- Bank Accounts ----------------
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
            "details" => "رد شده",
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
            "details" => "در انتظار بررسی",
            "Level" => "جدید",
        ],
    ];
    $enrichList($page->bankAccount, false, false, true);


    // ---------------- Authentication ----------------
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

    $enrichList($page->authentication, false, true);

    /* =========================
       Return
    ========================= */

    return [
        'content'   => biiq_Template::Start('manage->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'manage/',
        'navlink'   => 2
    ];
}
