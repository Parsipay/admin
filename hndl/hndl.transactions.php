<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    //function seprate money
    function separateThousands($number)
    {
        return number_format((int)$number);
    }

    // Sort transactions
    // sortOrder can be 'asc' or 'desc'
    $sortOrder = 'desc'; // 'asc' for ascending, 'desc' for descending
    // Deposits list
    $page->Deposits = [
        [
            "ID" => "e3140202507",
            "trackingNumber" => "ATRK1001",
            "User" => "یگاkkkنه علیزاده",
            "UserID" => 2,
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Price" => separateThousands(750000),
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "b5140202508",
            "trackingNumber" => "BTRK1002",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 3,
            "UnixTimestamp" => 1659787500,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1659787500),
            "Price" => separateThousands(750000),
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "c6140202509",
            "trackingNumber" => "CTRK1003",
            "User" => "محمد رضایی",
            "UserID" => 4,
            "UnixTimestamp" => 1659787600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1659787600),
            "Price" => separateThousands(750000),
            "Status" => "در صف تسویه"
        ]
    ];
    $sortOrder = $_GET['sort'] ?? 'desc';
    usort($page->Deposits, function ($a, $b) use ($sortOrder) {
        if ($sortOrder === 'asc') {
            return $a['UnixTimestamp'] <=> $b['UnixTimestamp']; // قدیمی به جدید
        } else {
            return $b['UnixTimestamp'] <=> $a['UnixTimestamp']; // جدید به قدیم
        }
    });
    // Credit increases
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
            "price" => separateThousands(12356598711),
            "trackingNumber" => "۷۲۳۶۷۸۱۶۷۰۷۸",
        ],
        [
            "ID" => "2C340202507",
            "phoneNumber" => "09126589832",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 5,
            "UnixTimestamp" => 3704126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
            "bankData" => "IR940150000184370199152881",
            "price" => separateThousands(36598971321),
            "BankImage" => "../assets/img/dey.png",
            "trackingNumber" => "۶۲۳۶۷۸۱۶۷۰۷۸",
        ],
        [
            "ID" => "3C340202507",
            "phoneNumber" => "09116589832",
            "User" => "مریم ماهور",
            "UserID" => 6,
            "UnixTimestamp" => 6704126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
            "bankData" => "IR940150000184370199152881",
            "price" => separateThousands(658721321),
            "BankImage" => "../assets/img/blu.png",
            "trackingNumber" => "۲۲۳۶۷۸۱۶۷۰۷۸",
        ],
    ];
    // Settlement queue
    $page->Settlements = [
        [
            "ID" => "S540202507",
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "UnixTimestamp" => 1724126600,
            "User" => "یگانه علیزاده",
            "price" => separateThousands(658721321),
            "UserID" => 7,
            "Status" => "در صف تسویه",
            "properies" => "مشاهده عملیات"
        ],
        [
            "ID" => "S540202507",
            "UnixTimestamp" => 1724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "User" => "یگانه علیزاده",
            "price" => separateThousands(658721321),
            "UserID" => 8,
            "Status" => "در صف تسویه",
            "properies" => "مشاهده عملیات"
        ],
        [
            "ID" => "S540202507",
            "UnixTimestamp" => 2724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "User" => "یگانه علیزاده",
           "price" => separateThousands(658721321),
            "UserID" => 9,
            "Status" => "در صف تسویه",
            "properies" => "مشاهده عملیات"
        ],
    ];

    // Sort by UnixTimestamp
    $sortFunction = function ($a, $b) use ($sortOrder) {
        return ($sortOrder === 'asc')
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp']
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    };

    usort($page->Deposits, $sortFunction);
    usort($page->Credits, $sortFunction);
    usort($page->Settlements, $sortFunction);

    // Status colors only for deposits
    foreach ($page->Deposits as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "مشاهده رسید") {
            $Item["StatusColor"] = "text-info";
        } elseif ($status === "در صف تسویه") {
            $Item["StatusColor"] = "text-warning";
        } else {
            $Item["StatusColor"] = "text-danger";
        }
    }
    unset($Item);

    return [
        'content'   => biiq_Template::Start('transactions->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'transactions/'
    ];
}
