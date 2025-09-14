<?php
function ProcessRequest($request){
    $page = new stdClass();

    // =======================
    // مرتب‌سازی تراکنش‌ها
    // =======================
    // مقدار sortOrder می‌تواند 'asc' یا 'desc' باشد
    $sortOrder = 'desc'; // 'asc' برای صعودی، 'desc' برای نزولی

    // =======================
    // لیست واریزی‌ها
    // =======================
    $page->Deposits = [
        [
            "ID" => "e3140202507",
            "trackingNumber" => "ATRK1001",
            "User" => "یگانه علیزاده",
            "UserID" => 2,
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Price" => 750000,
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "b5140202508",
            "trackingNumber" => "BTRK1002",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 3,
            "UnixTimestamp" => 1659787500,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1659787500),
            "Price" => 1200000,
            "Status" => "مشاهده رسید"
        ],
        [
            "ID" => "c6140202509",
            "trackingNumber" => "CTRK1003",
            "User" => "محمد رضایی",
            "UserID" => 4,
            "UnixTimestamp" => 1659787600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1659787600),
            "Price" => 900000,
            "Status" => "در صف تسویه"
        ]
    ];

    // =======================
    // افزایش اعتبار
    // =======================
$page->Credits = [
    [
        "ID" => "1C340202507",
        "phoneNumber"=>"09356439532",
        "User" => "یگانه علیزاده",
        "UserID" => 4,
        "UnixTimestamp" => 1704126600,
        "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
        "bankData" => "IR940150000184370199152881",
        "BankImage" => "../assets/img/ansar.png",
        "price" =>"266565",
        "trackingNumber" => "۷۲۳۶۷۸۱۶۷۰۷۸",
    ],
    [
        "ID" => "2C340202507",
        "phoneNumber"=>"09126589832",
        "User" => " بنفشه ابراهیمی",
        "UserID" => 5,
        "UnixTimestamp" => 3704126600,
        "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
        "bankData" => "IR940150000184370199152881",
        "price" =>"56565",
        "BankImage" => "../assets/img/dey.png",
        "trackingNumber" => "۶۲۳۶۷۸۱۶۷۰۷۸",
    ],
    [
        "ID" => "3C340202507",
        "phoneNumber"=>"09116589832",
        "User" => "مریم ماهور",
        "UserID" => 6,
        "UnixTimestamp" => 6704126600,
        "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1704126600),
        "bankData" => "IR940150000184370199152881",
        "price" =>"56565",
        "BankImage" => "../assets/img/blu.png",
        "trackingNumber" => "۲۲۳۶۷۸۱۶۷۰۷۸",
    ],
];

    // =======================
    // صف تسویه
    // =======================
    $page->Settlements = [
        [
            "ID" => "S540202507",
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "UnixTimestamp" => 1724126600,
            "User" => "یگانه علیزاده",
            "Price" => 450000,
            "UserID" => 7,
            "Status" => "در صف تسویه",
            "properies" => "مشاهده عملیات"
        ],
       [
            "ID" => "S540202507",
            "UnixTimestamp" => 1724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "User" => "یگانه علیزاده",
            "Price" => 450000,
            "UserID" => 8,
            "Status" => "در صف تسویه",
            "properies" => "مشاهده عملیات"
        ],
       [
            "ID" => "S540202507",
            "UnixTimestamp" => 2724126600,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1724126600),
            "User" => "یگانه علیزاده",
            "Price" => 450000,
            "UserID" => 9,
            "Status" => "در صف تسویه",
            "properies" => "مشاهده عملیات"
        ],
    ];

    // =======================
    // مرتب‌سازی براساس UnixTimestamp
    // =======================
    $sortFunction = function($a, $b) use ($sortOrder) {
        return ($sortOrder === 'asc') 
            ? $a['UnixTimestamp'] <=> $b['UnixTimestamp'] 
            : $b['UnixTimestamp'] <=> $a['UnixTimestamp'];
    };

    usort($page->Deposits, $sortFunction);
    usort($page->Credits, $sortFunction);
    usort($page->Settlements, $sortFunction);

    // =======================
    // فقط برای لیست واریزی‌ها رنگ وضعیت
    // =======================
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
        'Canonical' => SITE.'transactions/'
    ];
}
?>
