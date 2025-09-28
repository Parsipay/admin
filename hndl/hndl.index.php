<?php
function ProcessRequest($request)
{

    //mask number for bank carts
    function maskCard($num)
    {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len); // fallback for very short numbers
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }


    //function seprate money
    function separateThousands($number)
    {
        return number_format((int)$number);
    }


    $cards = [
        ["یگانه علیزاده", "ملی", "IR820540102680020817909002", "5022291077470837"],
        ["بنفشه ابراهیمی", "سامان", "IR350170000000000000123456", "5894631804760130"],
        ["نازنین علیزاده", "پاسارگاد", "IR460120000000123456789012", "6104337900001234"],
        ["زهرا نوری", "تجارت", "IR780180000000000000987654", "6219861000001234"],
    ];

    // -----------------------------
    // Authentication messages
    // -----------------------------
    $boxMessages = [
        ["بنفشه ابراهیمی", "1747014000"],
        ["یگانه علیزاده", "1745714000"],
        ["مریم ماهور", "1717136540"]
    ];


    $p = new stdClass();

    // Cards
    $p->Cards = [];
    foreach ($cards as [$name, $bank, $shaba, $cardNumber]) {
        $obj = new stdClass();
        $obj->UserName   = $name;
        $obj->BankName   = $bank;
        $obj->Shaba      = maskCard($shaba);
        $obj->MaskedCard = maskCard($cardNumber);
        $p->Cards[]      = $obj;
    }

    // Messages Box
    $p->box = [];
    foreach ($boxMessages as [$user, $time]) {
        $msg = new stdClass();
        $msg->userName   = $user;
        $msg->timeToSend = biiq_PersianDate::UnixToAgo($time);
        $p->box[] = $msg;
    }

    // Orders
    $p->orderList = [
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => separateThousands(16520897),
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "موفق ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => separateThousands(22000000),
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 555555555),
            "Status" => "در انتظار تایید ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => separateThousands(12500000),
            "UnixTimestamp" => 9999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 99999999),
            "Status" => "  ناموفق ",
        ],
    ];

    // Users
    $p->userList = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1111111),
            "Status" => " مسدود",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 33333333,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 333333333),
            "Status" => " موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",

            "UnixTimestamp" => 4444444444,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 4444444444),
            "Status" => "تکمیل نشده",
        ],
    ];
foreach($p->userList as &$user){
    $status = trim($user["Status"]);
    if($status === "مسدود"){
        $user["StatusColor"] = "red";
    } elseif($status === "موفق"){
        $user["StatusColor"] = "green";
    } elseif($status === "تکمیل نشده"){
        $user["StatusColor"] = "blue";
    } else{
        $user["StatusColor"] = "transparent"; // حالت پیش‌فرض
    }
}
unset($user);
    // Financial Requests
    $p->requestList = [
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" => " 65665454546",
            "UnixTimestamp" => 9999999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "Status" => "مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => 777777777,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 33333333),
            "Status" => "  مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 24,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "در  صف تسویه",
        ],
    ];

    $p->TopBox = [
        ['Link' => "#", "Icon" => "home", "Title" => "پیغام‌ها", "Subtitle" => "12 تیکت | 5 اتوماسیون"],
        ['Link' => "#", "Icon" => "gear", "Title" => "تنظیمات", "Subtitle" => "2 سفارش در حال پردازش"],
        ['Link' => "#", "Icon" => "list-ul", "Title" => "تسویه", "Subtitle" => "نرمال"],
        ['Link' => "#", "Icon" => "file-alt", "Title" => "حساب‌های بانکی", "Subtitle" => "3 مورد در حال انتظار"],
        ['Link' => "#", "Icon" => "id-card", "Title" => "مدارک احراز", "Subtitle" => "2 مورد در حال انتظار"],
    ];

    foreach ($p->orderList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success";
        elseif ($status === "در انتظار تایید") $Item["StatusColor"] = "text-warning";
        else $Item["StatusColor"] = "text-danger";
    }
    unset($Item);

    // Return payload to template
    return [
        'content'   => biiq_Template::Start('pages->index', true, ['Objects' => $p]),
        'id'        => 0,
        'title'     => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}


// ============================================
// FEKR KONAM BAYAD INARO AK KNAM INA DG HICH JA ESTEFADE NEMISHE
// ============================================


