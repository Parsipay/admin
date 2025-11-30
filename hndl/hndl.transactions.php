<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // === Helpers ===
    $separateThousands = fn($n) => number_format((int)$n);

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
        } elseif ($diff < 2592000) {     
            return floor($diff / 86400) . " روز پیش";
        } elseif ($diff < 31104000) {     
            return floor($diff / 2592000) . " ماه پیش";
        } else {
            return floor($diff / 31104000) . " سال پیش";
        }
    }

    // ==== ICONS & STATUS (GLOBAL) ====
    $LevelIcons = [
        "طلایی"   => "fa-solid fa-star text-warning",
        "حرفه ای" => "fa-solid fa-medal text-danger",
        "فعال"    => "fa-solid fa-circle-check text-success",
        "جدید"    => "fa-solid fa-user-plus text-primary",
        "default" => "fa-solid fa-user text-secondary"
    ];

    $StatusColors = [
        "در صف تسویه" => "text-warning bg-opacity-warning",
        "مشاهده رسید" => "text-primary opacity-primary",
        "default"      => "text-danger opacity-danger"
    ];

    // ====== Deposits ======
    $page->Deposits = [
        [
            "ID" => "e3140202507",
            "trackingNumber" => "ATRK1001",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "UnixTimestamp" => time() - (5 * 30 * 86400),
            "lastActivityTimestamp" => time() - (23 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 30 * 86400)),
            "Price" => $separateThousands(750000),
            "Status" => "مشاهده رسید",
            "Level" => "فعال",
        ],
        [
            "ID" => "b5140202508",
            "trackingNumber" => "BTRK1002",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "UnixTimestamp" => time() - (60 * 86400),
            "lastActivityTimestamp" => time() - (14 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (60 * 86400)),
            "Price" => $separateThousands(1250000),
            "Status" => "مشاهده رسید",
            "Level" => "طلایی",
        ],
        [
            "ID" => "c6140202509",
            "trackingNumber" => "CTRK1003",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "UnixTimestamp" => time() - (14 * 86400),
            "lastActivityTimestamp" => time() - (1 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (14 * 86400)),
            "Price" => $separateThousands(980000),
            "Status" => "در صف تسویه",
            "Level" => "حرفه ای",
        ]
    ];

    // ====== Credits ======
    $page->Credits = [
        [
            "ID" => "1013152343",
            "phoneNumber" => "09356439532",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/ansar.png",
            "price" => $separateThousands(12356598711),
            "trackingNumber" => "۷۲۳۶۷۸۱۶۷۰۷۸",
            "Level" => "فعال",
        ],
        [
            "ID" => "2013152343",
            "phoneNumber" => "09126589832",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 2,
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/dey.png",
            "price" => $separateThousands(36598971321),
            "trackingNumber" => "۶۲۳۶۷۸۱۶۷۰۷۸",
            "Level" => "طلایی",
        ],
        [
            "ID" => "3C340202507",
            "phoneNumber" => "09116589832",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/blu.png",
            "price" => $separateThousands(658721321),
            "trackingNumber" => "۲۲۳۶۷۸۱۶۷۰۷۸",
            "Level" => "حرفه ای",
        ],
        [
            "ID" => "4013152343",
            "OrderDetails" => "09128431937",
            "phoneNumber" => "09116589832",
            "User" => "علی تهرانی",
            "UserID" => 4,
            "price" => $separateThousands(6554545121454),
            "Status" => "موفق",
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/blu.png",
            "trackingNumber" => "۲۲۳۶۷۸۱۶۷۰۷۸",
            "Level" => "جدید",
        ],
    ];

    // ====== Settlements ======
    $page->Settlements = [
        [
            "ID" => "S540202507",
            "User" => "یگانه علیزاده",
            "UserID" => 1,
            "UnixTimestamp" => time() - (60 * 86400),
            "lastActivityTimestamp" => time() - (14 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (60 * 86400)),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات",
            "Level" => "فعال"
        ],
        [
            "ID" => "S540202508",
            "User" => "بنفشه ابراهیمی",
            "UnixTimestamp" => time() - (14 * 86400),
            "lastActivityTimestamp" => time() - (1 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (14 * 86400)),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات",
            "Level" => "طلایی"
        ],
        [
            "ID" => "S540202509",
            "User" => "سارا کریمی",
            "UserID" => 3,
            "UnixTimestamp" => time() - (45 * 86400),
            "lastActivityTimestamp" => time() - (2 * 86400),
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (45 * 86400)),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات",
            "Level" => "جدید"
        ]
    ];

    // ===============================
    //   APPLY ICONS + STATUS + TIME
    // ===============================

    $ApplyProcessing = function (&$list) use ($LevelIcons, $StatusColors) {
        foreach ($list as &$Item) {

            // LevelIcon
            $Item["LevelIcon"] = $LevelIcons[$Item["Level"]] ?? $LevelIcons["default"];

            // StatusColor (optional)
            if (isset($Item["Status"])) {
                $Item["StatusColor"] = $StatusColors[$Item["Status"]] ?? $StatusColors["default"];
            }

            // Relative time (optional)
            if (isset($Item["UnixTimestamp"])) {
                $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]);
            }
            if (isset($Item["lastActivityTimestamp"])) {
                $Item["akharin"] = timeAgo($Item["lastActivityTimestamp"]);
            }
        }
    };

    $ApplyProcessing($page->Deposits);
    $ApplyProcessing($page->Credits);
    $ApplyProcessing($page->Settlements);

    // ==== Sort (only once per list) ====
    usort($page->Deposits, fn($a, $b) => $b["UnixTimestamp"] <=> $a["UnixTimestamp"]);
    usort($page->Settlements, fn($a, $b) => $b["UnixTimestamp"] <=> $a["UnixTimestamp"]);

    // ==== Final Output ====
    return [
        'content'   => biiq_Template::Start(
            'transactions->index',
            true,
            ['Objects' => $page, 'dateandtime' => $page->dateandtime]
        ),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'transactions/',
        'navlink' => 4
    ];
}
