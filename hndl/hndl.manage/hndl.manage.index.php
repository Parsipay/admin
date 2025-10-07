<?php
function ProcessRequest($request)
{
    // Helper: Mask card number except first 6 and last 4 digits
    function maskCard($num)
    {
        $len = strlen($num);
        return $len <= 10
            ? str_repeat("*", $len)
            : substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }

    $page = new stdClass();

    // --- Accordion documents list ---
    $timestamp = 1616301000;
    $page->docList = [
        [
            "user" => "بنفشه ابراهیمی",
            "UnixTimestamp" => $timestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $timestamp),
        ],
        [
            "user" => "بنفشه ابراهیمی",
            "UnixTimestamp" => $timestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $timestamp),
        ],
    ];

    // --- Cards list ---
    $page->Cards = [
        [
            "user" => "یگانه علیزاده",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => maskCard("5022291077470837"),
        ],
        [
            "user" => "مریم ماهور",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => maskCard("5022291077470837"),
        ],
    ];

    // --- User deposits list ---
    $page->userList = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 9,
            "UnixTimestamp"=> 6365897855,
            "persianDate"  => biiq_PersianDate::date("l j F Y - H:i", 665150314),
            "Status"       => "مسدود",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 10,
            "UnixTimestamp"=> 1756301000,
            "persianDate"  => biiq_PersianDate::date("l j F Y - H:i", 45468892),
            "Status"       => "موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 11,
            "UnixTimestamp"=> 1616301000,
            "persianDate"  => biiq_PersianDate::date("l j F Y - H:i", 18978752),
            "Status"       => "تکمیل نشده",
        ],
    ];

    // --- Sort deposits by timestamp (default: newest first) ---
    $sortOrder = $_GET['sort'] ?? 'desc';
    usort($page->userList, function ($a, $b) use ($sortOrder) {
        return $sortOrder === 'asc'
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    });

    // --- Auto-calculate last activity for each user ---
    foreach ($page->userList as &$item) {
        $diff = time() - $item['UnixTimestamp'];
        if ($diff < 60)
            $item['lastActivity'] = 'لحظاتی پیش';
        elseif ($diff < 3600)
            $item['lastActivity'] = floor($diff / 60) . ' دقیقه پیش';
        elseif ($diff < 86400)
            $item['lastActivity'] = floor($diff / 3600) . ' ساعت پیش';
        elseif ($diff < 2592000)
            $item['lastActivity'] = floor($diff / 86400) . ' روز پیش';
        else
            $item['lastActivity'] = floor($diff / 2592000) . ' ماه پیش';
    }
    unset($item);

    // --- Bank accounts list (keep nationalCode & phoneNumber for HTML) ---
    $page->bankAccount = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 12,
            "bankInfo"     => "IR940150000184370199152881",
            "BankImage"    => "../assets/img/dey.png",
            "details"      => "تایید شده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 13,
            "bankInfo"     => "IR940150000184370199152881",
            "BankImage"    => "../assets/img/ansar.png",
            "details"      => "تایید شده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 14,
            "bankInfo"     => "IR940150000184370199152881",
            "BankImage"    => "../assets/img/blu.png",
            "details"      => "تایید شده",
        ],
    ];

    // --- Authentication documents list (keep nationalCode & phoneNumber) ---
    $page->authentication = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "0293564635",
            "User"         => "بنفشه ابراهیمی",
            "UserID"       => 15,
            "documents"    => "مشاهده مدارک",
            "Status"       => "در انتظار تایید",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 16,
            "documents"    => "مشاهده مدارک",
            "Status"       => "تایید شده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 17,
            "documents"    => "مشاهده مدارک",
            "Status"       => "تکمیل نشده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 18,
            "documents"    => "مشاهده مدارک",
            "Status"       => "رد شده",
        ],
    ];

    // --- Add color class based on document status ---
    foreach ($page->authentication as &$item) {
        $status = trim($item["Status"]);
        $item["StatusColor"] = match ($status) {
            "تایید شده" => "text-success opacity-green",
            "در انتظار تایید" => "text-warning bg-opacity-warning",
            "تکمیل نشده" => "text-primary bg-blue",
            default => "text-danger bg-red",
        };
    }
    unset($item);

    // --- Add color class for deposit status ---
    foreach ($page->userList as &$item) {
        $status = trim($item["Status"]);
        $item["StatusColor"] = match ($status) {
            "موفق" => "text-success opacity-green",
            "تکمیل نشده" => "text-primary bg-blue",
            "در انتظار تایید" => "text-warning",
            default => "text-danger bg-red",
        };
    }
    unset($item);

    // --- Final output ---
    return [
        'content'   => biiq_Template::Start('manage->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'manage/'
    ];
}
