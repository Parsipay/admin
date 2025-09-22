<?php

// ============================================
// Main Page Data Preparation
// ============================================
function ProcessRequest($request) {

    // -----------------------------
    // Shows first 6 and last 4 digits, masks the rest
    // -----------------------------
    function maskCard($num) {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len); // fallback for very short numbers
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }

    // -----------------------------
    // Sample bank cards
    // -----------------------------
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

    // -----------------------------
    // Sample orders
    // -----------------------------
   

    // -----------------------------
    // Prepare main object
    // -----------------------------
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
            "price" => "445609806",
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "موفق ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => "445609806",
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 555555555),
            "Status" => "در انتظار تایید ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => "445609806",
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

    // Return payload to template
    return [
        'content'   => biiq_Template::Start('pages->index', true, ['Objects' => $p]),
        'id'        => 0,
        'title'     => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}


// ============================================
// Financial Page Data Preparation
// ============================================
function FinancialPageReady() {

    // -----------------------------
    // Seed users
    // -----------------------------
    $seedUsers = [
        ["0440202507", "شقایق علیزاده", "09353353167", 1747140621, 1727140621 ,"موفق" ],
        ["0340202507", "بنفشه ابراهیمی", "093128431937", 1747139421,1247140621, "ناموفق"],
        ["0540202507", "نازنین علیزاده", "09356439532", 1747125021,1647140621, "پرداخت نشده"],
    ];

    // -----------------------------
    // Seed bank accounts
    // -----------------------------
    $seedBankAccounts = [
        ["0440202507", "09353353167", "یگانه علیزاده", "IR940150000184370199152881", "موفق"],
        ["0340202507", "093128431937", "بنفشه ابراهیمی", "IR940150000184370199152882", "رد شده"],
        ["0540202507", "09356439532", "نازنین علیزاده", "IR940150000184370199152883", "پرداخت نشده"],
    ];

    // -----------------------------
    // Seed documents
    // -----------------------------
    $seedDocuments = [
        ["یگانه علیزاده","شناسنامه","2025-08-20 10:00:00",2],
        ["بنفشه ابراهیمی","کارت ملی","2025-08-19 09:30:00",1],
        ["نازنین علیزاده","کارت شناسایی","2025-08-18 12:15:00",3],
    ];

    // -----------------------------
    // Prepare payload
    // -----------------------------
    $payload = new stdClass();

    // Tab 1: Users
    $payload->seedUsers = [];
    foreach ($seedUsers as [$natCode, $fullName, $phone, $signupTs, $lastActive, $status]) {
        $u = new stdClass();
        $u->nationalCode = $natCode;
        $u->fullName     = $fullName;
        $u->phoneNumber  = $phone;
        $u->signupTime   = biiq_PersianDate::date("l j F Y ساعت H:i:s", $signupTs);
        $u->lastActivity = biiq_PersianDate::date("l j F Y ساعت H:i:s", $lastActive);
        $u->Status       = $status;
        $payload->seedUsers[] = $u;
    }

    // Tab 2: Bank Accounts
    $payload->allBankAccounts = [];
    foreach ($seedBankAccounts as [$natCode, $phone, $fullName, $accNumber, $status]) {
        $a = new stdClass();
        $a->nationalCode  = $natCode;
        $a->phoneNumber   = $phone;
        $a->fullName      = $fullName;
        $a->accountNumber = $accNumber;
        $a->Status        = $status;
        $payload->allBankAccounts[] = $a;
    }

    // Tab 3: Documents
    $payload->allDocuments = [];
    foreach ($seedDocuments as [$fullName, $docType, $submittedAt, $status]) {
        $d = new stdClass();
        $d->fullName     = $fullName;
        $d->documentType = $docType;
        $d->submitDate   = biiq_PersianDate::date("l j F Y ساعت H:i:s", strtotime($submittedAt));
        $d->Status       = $status;
        $payload->allDocuments[] = $d;
    }

    // Return payload
    return [
        'content'   => biiq_Template::Start('pages->FinancialPageReady', true, ['Objects' => $payload]),
        'id'        => 1,
        'title'     => 'مالی کاربران',
        'Canonical' => SITE,
    ];
}


?>
