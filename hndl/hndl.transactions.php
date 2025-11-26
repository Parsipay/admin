<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // === Current Date & Time ===
    $today = new DateTime();
    $today->modify('+1 hour');
    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    // --- Helper: format number with thousands separator ---
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
        } elseif ($diff < 2592000) { // کمتر از 30 روز
            return floor($diff / 86400) . " روز پیش";
        } elseif ($diff < 31104000) { // کمتر از 12 ماه
            return floor($diff / 2592000) . " ماه پیش";
        } else {
            return floor($diff / 31104000) . " سال پیش";
        }
    }
    // --- Deposits ---
    $page->Deposits = [
        [
            "ID" => "e3140202507",
            "trackingNumber" => "ATRK1001",
            "User" => "یگانه علیزاده",
            "UserID" => 2,
  "UnixTimestamp" => time() - (5 * 30 * 86400), // 5 ماه پیش
            "lastActivityTimestamp" => time() - (23 * 86400), // 23 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", time() - (5 * 2 * 86400)),
            "Price" => $separateThousands(750000),
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "b5140202508",
            "trackingNumber" => "BTRK1002",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 3,
             "UnixTimestamp" => time() - (5 * 12 * 86400), // تقریبا 2 ماه پیش
            "lastActivityTimestamp" => time() - (14 * 86400), // 14 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 896554121),
            "Price" => $separateThousands(1250000),
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "c6140202509",
            "trackingNumber" => "CTRK1003",
            "User" => "محمد رضایی",
            "UserID" => 4,
              "UnixTimestamp" => time() - (14 * 86400), // 14 روز پیش
            "lastActivityTimestamp" => time() - (1 * 86400), // 1 روز پیش
            "PersianDate" => biiq_PersianDate::date("l j F Y - H:i", 126545878),
            "Price" => $separateThousands(980000),
            "Status" => "در صف تسویه"
        ]
    ];

    // --- Add status colors for deposits (PHP <8 compatibility) ---
    foreach ($page->Deposits as &$item) {
        $status = trim($item["Status"]);
        switch ($status) {
            case "مشاهده رسید":
                $item["StatusColor"] = "text-info";
                break;
            case "در صف تسویه":
                $item["StatusColor"] = "text-warning";
                break;
            default:
                $item["StatusColor"] = "text-danger";
        }
    }
    unset($item);
    // ساخت رشته‌های نسبی
    foreach ($page->Deposits as &$Item) {
        $Item["akharin"] = timeAgo($Item["lastActivityTimestamp"]); // برای آخرین فعالیت
        $Item["PersianDateRelative"] = timeAgo($Item["UnixTimestamp"]); // برای تاریخ ثبت
    }
    unset($Item);


    usort($page->Deposits, function ($a, $b) {
        return $b["UnixTimestamp"] <=> $a["UnixTimestamp"];
    });

    // --- Credits ---
    $page->Credits = [
        [
            "ID" => "1C340202507",
            "phoneNumber" => "09356439532",
            "User" => "یگانه علیزاده",
            "UserID" => 4,
            "UnixTimestamp" => 1704126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/ansar.png",
            "price" => $separateThousands(12356598711),
            "trackingNumber" => "۷۲۳۶۷۸۱۶۷۰۷۸"
        ],
        [
            "ID" => "2C340202507",
            "phoneNumber" => "09126589832",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 5,
            "UnixTimestamp" => 1704127700,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704127700),
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/dey.png",
            "price" => $separateThousands(36598971321),
            "trackingNumber" => "۶۲۳۶۷۸۱۶۷۰۷۸"
        ],
        [
            "ID" => "3C340202507",
            "phoneNumber" => "09116589832",
            "User" => "مریم ماهور",
            "UserID" => 6,
            "UnixTimestamp" => 1704128800,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704128800),
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/blu.png",
            "price" => $separateThousands(658721321),
            "trackingNumber" => "۲۲۳۶۷۸۱۶۷۰۷۸"
        ]
    ];

    // --- Settlements ---
    $page->Settlements = [
        [
            "ID" => "S540202507",
            "User" => "یگانه علیزاده",
            "UserID" => 7,
            "UnixTimestamp" => 1724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات"
        ],
        [
            "ID" => "S540202508",
            "User" => "یگانه علیزاده",
            "UserID" => 8,
            "UnixTimestamp" => 1724127700,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724127700),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات"
        ],
        [
            "ID" => "S540202509",
            "User" => "یگانه علیزاده",
            "UserID" => 9,
            "UnixTimestamp" => 1724128800,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724128800),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات"
        ]
    ];

    // --- Final output ---
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
