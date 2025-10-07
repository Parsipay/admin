<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // --- Helper: format number with thousands separator ---
    $separateThousands = fn($n) => number_format((int)$n);

    // --- Determine sort order (default: newest first) ---
    $sortOrder = $_GET['sort'] ?? 'desc';
    $sortFunc = fn($a, $b) =>
        ($sortOrder === 'asc')
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];

    // --- Deposits list ---
    $page->Deposits = [
        [
            "ID" => "e3140202507",
            "trackingNumber" => "ATRK1001",
            "User" => "یگانه علیزاده",
            "UserID" => 2,
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Price" => $separateThousands(750000),
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "b5140202508",
            "trackingNumber" => "BTRK1002",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 3,
            "UnixTimestamp" => 1659787500,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1659787500),
            "Price" => $separateThousands(750000),
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "c6140202509",
            "trackingNumber" => "CTRK1003",
            "User" => "محمد رضایی",
            "UserID" => 4,
            "UnixTimestamp" => 1659787600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1659787600),
            "Price" => $separateThousands(750000),
            "Status" => "در صف تسویه"
        ]
    ];

    // --- Credit increases ---
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
            "UnixTimestamp" => 3704126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
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
            "UnixTimestamp" => 6704126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
            "bankData" => "IR940150000184370199152881",
            "BankImage" => "../assets/img/blu.png",
            "price" => $separateThousands(658721321),
            "trackingNumber" => "۲۲۳۶۷۸۱۶۷۰۷۸"
        ]
    ];

    // --- Settlement queue ---
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
            "ID" => "S540202507",
            "User" => "یگانه علیزاده",
            "UserID" => 8,
            "UnixTimestamp" => 1724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات"
        ],
        [
            "ID" => "S540202507",
            "User" => "یگانه علیزاده",
            "UserID" => 9,
            "UnixTimestamp" => 2724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "price" => $separateThousands(658721321),
            "Status" => "در صف تسویه",
            "properties" => "مشاهده عملیات"
        ]
    ];

    // --- Sort all lists ---
    usort($page->Deposits, $sortFunc);
    usort($page->Credits, $sortFunc);
    usort($page->Settlements, $sortFunc);

    // --- Add status colors for deposits ---
    foreach ($page->Deposits as &$item) {
        $status = trim($item["Status"]);
        $item["StatusColor"] = match ($status) {
            "مشاهده رسید" => "text-info",
            "در صف تسویه" => "text-warning",
            default        => "text-danger"
        };
    }
    unset($item);

    // --- Final output ---
    return [
        'content'   => biiq_Template::Start('transactions->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'transactions/'
    ];
}
