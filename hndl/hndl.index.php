<?php

// ==============================================
// Utility Functions
// ==============================================

function separateThousands($number): string
{
    return number_format((int)$number);
}

//sort desc and asc
function sortByTimestamp(array &$list, string $order = 'desc'): void
{
    usort($list, function ($a, $b) use ($order) {
        return $order === 'asc'
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    });
}

//last seen time ago
function timeAgo($unixTimestamp)
{
    $diff = time() - $unixTimestamp;
    if ($diff < 60) return $diff . " ثانیه پیش";
    if ($diff < 3600) return floor($diff / 60) . " دقیقه پیش";
    if ($diff < 86400) return floor($diff / 3600) . " ساعت پیش";
    if ($diff < 2592000) return floor($diff / 86400) . " روز پیش";
    if ($diff < 31104000) return floor($diff / 2592000) . " ماه پیش";
    return floor($diff / 31104000) . " سال پیش";
}
// ==============================================
// Main Function
// ==============================================
function ProcessRequest($request)
{
    global $settings;
    $p = new stdClass();

    // -----------------------------
    //  Order list
    // -----------------------------
    $p->orderList = [
        [
            "ID" => "1013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "price" => 165208970,
            "Level" => "فعال",
            "UnixTimestamp" => time() - 60 * 86400,
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - 60 * 86400),
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
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 956565545),
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

    // -----------------------------
    //  User list
    // -----------------------------
    $p->userList = [
        ["User" => "یگانه علیزاده", "UserID" => 1, "UnixTimestamp" => time() - 3600, "lastActivityTimestamp" => 1234567890, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690000000),"Level" => "طلایی"],
        ["User" => "بنفشه ابراهیمی", "UserID" => 2, "UnixTimestamp" => time() - (5 * 30 * 86400), "lastActivityTimestamp" => 1326547896, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690500000),"Level" =>"فعال"],
        ["User" => "سارا کریمی", "UserID" => 3, "UnixTimestamp" => time() - 60 * 86400, "lastActivityTimestamp" => 1478523698, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691000000),"Level" => "حرفه ای"],
        ["User" => "علی تهرانی", "UserID" => 4, "UnixTimestamp" => time() - 12 * 86400, "lastActivityTimestamp" => 1691500000, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691500000),"Level" => "جدید"],
    ];

    // -----------------------------
    //  Financial requests
    // -----------------------------
    $p->requestList = [
        ["requestCode" => "0013152343", "trackingNumber" => "0293564635", "User" => "بنفشه ابراهیمی", "UserID" => 2, "price" => separateThousands(65665454546), "UnixTimestamp" => 1690000000, "lastActivityTimestamp" => 1690000000, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690000000), "Status" => "مشاهده رسید", "Level" => "طلایی"],
        ["requestCode" => "0013152344", "trackingNumber" => "0293564636", "User" => "یگانه علیزاده", "UserID" => 1, "price" => separateThousands(65665454546), "UnixTimestamp" => 1690500000, "lastActivityTimestamp" => 1690500000, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1690500000), "Status" => "مشاهده رسید", "Level" => "فعال"],
        ["requestCode" => "0013152345", "trackingNumber" => "0293564637", "User" => "سارا کریمی", "UserID" => 3, "price" => separateThousands(65665454546), "UnixTimestamp" => 1691000000, "lastActivityTimestamp" => 1691000000, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691000000), "Level" => "حرفه ای", "Status" => "مشاهده رسید"],
        ["requestCode" => "0013152346", "trackingNumber" => "0293564638", "User" => "علی تهرانی", "UserID" => 4, "price" => separateThousands(65665454546), "UnixTimestamp" => 1691500000, "lastActivityTimestamp" => 1691500000, "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 1691500000), "Status" => "در صف تسویه", "Level" => "جدید"],
    ];

    // -----------------------------
    //  Top dashboard items
    // ----------------------------
    $p->TopBox = [
        ['Link' => $settings['site'] . 'tickets/', 'Icon' => 'home', 'Title' => 'پیغام‌ها', 'Subtitle' => '12 تیکت | 5 اتوماسیون'],
        ['Link' => $settings['site'] . 'settings/', 'Icon' => 'gear', 'Title' => 'تنظیمات', 'Subtitle' => '2 سفارش در حال پردازش'],
        ['Link' => $settings['site'] . 'transactions/', 'Icon' => 'list-ul', 'Title' => 'تسویه', 'Subtitle' => 'نرمال'],
        ['Link' => $settings['site'] . 'manage', 'Icon' => 'file-alt', 'Title' => 'حساب‌های بانکی', 'Subtitle' => '3 مورد در حال انتظار'],
        ['Link' => '#', 'Icon' => 'id-card', 'Title' => 'مدارک احراز', 'Subtitle' => '2 مورد در حال انتظار'],
    ];

    // -----------------------------
    //  Common function to apply status & level colors/icons
    // -----------------------------
    $applyStatusAndLevel = function (&$list, $type = 'general') {
        foreach ($list as &$Item) {
            // Relative times
            if (isset($Item['UnixTimestamp'])) $Item['PersianDateRelative'] = timeAgo($Item['UnixTimestamp']);
            if (isset($Item['lastActivityTimestamp'])) $Item['akharin'] = timeAgo($Item['lastActivityTimestamp']);
    
            // StatusColor
            $status = trim($Item['Status'] ?? '');
            if ($type === 'request') {
                $Item['StatusColor'] = $status === 'مشاهده رسید' ? 'text-primary' : 'text-warning';
            } else {
                switch ($status) {
                    case "موفق":
                        $Item['StatusColor'] = "text-success opacity-green";
                        break;
                    case "پردازش":
                        $Item['StatusColor'] = "text-warning bg-opacity-warning";
                        break;
                    case "رد شده":
                        $Item['StatusColor'] = "text-danger opacity-danger";
                        break;
                    case "تکمیل نشده":
                        $Item['StatusColor'] = "text-primary opacity-primary";
                        break;
                    default:
                        $Item['StatusColor'] = "text-danger opacity-danger";
                        break;
                }
            }

            // LevelIcon
            $level = trim($Item['Level'] ?? '');
            switch ($level) {
                case "طلایی":
                    $Item['LevelIcon'] = "fa-solid fa-star text-warning";
                    break;
                case "حرفه ای":
                    $Item['LevelIcon'] = "fa-solid fa-medal text-red";
                    break;
                case "فعال":
                    $Item['LevelIcon'] = "fa-solid fa-circle-check text-green";
                    break;
                default:
                    $Item['LevelIcon'] = "fa-solid fa-user text-primary";
                    break;
            }
        }
        unset($Item);
    };

    // Apply formatting
    $applyStatusAndLevel($p->orderList);
    $applyStatusAndLevel($p->requestList, 'request');
    $applyStatusAndLevel($p->userList);

    // -----------------------------
    //  Final output
    // -----------------------------
    return [
        'content' => biiq_Template::Start('pages->index', true, ['Objects' => $p]),
        'navlink' => 1,
        'id' => 0,
        'title' => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}
