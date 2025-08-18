<?php
function ProcessRequest($request) {

    // -----------------------------
    // Helper function to mask card or IBAN
    // Shows first 6 and last 4 digits, masks the rest
    // -----------------------------
    function maskCard($num) {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len); // fallback
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }

    // -----------------------------
    // Sample bank cards data
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
    // Sample orders data
    // -----------------------------
    $orders = [
        ["10232336263#", "خرید", "/assets/img/usdt.png", "197.3499", "USDT (تتر)", "بنفشه ابراهیمی", "۴۴,۹۳۵,۶۰۰", 1746776838, "تکمیل نشده"],
        ["20232336263#", "فروش", "/assets/img/usdt.png", "197.3499", "USDT (تتر)", "یگانه علیزاده", "۴۴,۹۳۵,۶۰۰", 1718199090, "تکمیل نشده"],
        ["30232336263#", "خرید", "/assets/img/usdt.png", "197.3499", "USDT (تتر)", "میلاد ابراهیم نژاد چماچائی", "۴۴,۹۳۵,۶۰۰", 1747134948, "در حال پردازش"],
        ["50232336263#", "خرید", "/assets/img/tron.png", "197.3499", "USDT (تتر)", "میلاد ابراهیم نژاد چماچائی", "۴۴,۹۳۵,۶۰۰", 1747114948, "رد شده"],
        ["20232336263#", "خرید", "/assets/img/tron.png", "82.53279772", "TRX (ترون)", "سجاد طاوسی سرحوضکی", "۴۴,۹۳۵,۶۰۰", 1717134948, "تکمیل شده"],
    ];

    // -----------------------------
    // Sample users data
    // -----------------------------
    $users = [
        ["0440202507", "یگانه علیزاده", "09353353167", "1747140621", "5", "1741837446", "تایید شده", "text-success"],
        ["0340202507", "یگانه علیزاده", "093128431937", "1747139421", "5", "1736739846", "در انتظار بررسی", "text-info"],
        ["0540202507", "یگانه علیزاده", "09356439532", "1747125021", "5", "1746956733", "رد شده", "text-warning"],
        ["3440202507", "یگانه علیزاده", "09351751766", "1747114221", "5", "1746265533", "تایید شده", "text-primary"],
        ["1440202507", "یگانه علیزاده", "09353353897", "17471078467", "5", "1746092733", "در انتظار بررسی", "text-danger"],
    ];

    // -----------------------------
    // Sample financial requests
    // -----------------------------
    $requests = [
        ["15495319","835565","یگانه علیزاده","۴۴,۹۳۵,۶۰۰","1741837446","مشاهده رسید"],
        ["25495319","635565","بنفشه ابراهیمی","۳۴,۹۳۵,۶۰۰","1641837446","مشاهده رسید"],
        ["35495319","435565","فاطمه احمدیان","۹۴,۹۳۵,۶۰۰","3741837446","در صف تسویه"],
    ];

    // -----------------------------
    // Prepare main object for template
    // -----------------------------
    $p = new stdClass();

    // -----------------------------
    // Mask cards
    // -----------------------------
    $p->Cards = [];
    foreach ($cards as [$name, $bank, $shaba, $cardNumber]) {
        $obj = new stdClass();
        $obj->UserName = $name;
        $obj->BankName = $bank;
        $obj->Shaba = maskCard($shaba);
        $obj->MaskedCard = maskCard($cardNumber);
        $p->Cards[] = $obj;
    }

    // -----------------------------
    // Messages box
    // -----------------------------
    $p->box = [];
    foreach ($boxMessages as [$user, $time]) {
        $msg = new stdClass();
        $msg->userName = $user;
        $msg->timeToSend = biiq_PersianDate::UnixToAgo($time);
        $p->box[] = $msg;
    }

    // -----------------------------
    // Process orders
    // -----------------------------
    $p->list = [];
    foreach ($orders as [$number, $type, $img, $amount, $currency, $fullname, $price, $unix, $statusText]) {

        // Determine color classes
        $colorMap = [
            "خرید" => ['type'=>'opacity-green','text'=>'text-success','price'=>'text-success'],
            "فروش" => ['type'=>'opacity-danger','text'=>'text-danger','price'=>'text-danger'],
        ];
        $color = $colorMap[$type] ?? ['type'=>'opacity-info','text'=>'text-info','price'=>'text-info'];

        $statusColorMap = [
            "تکمیل نشده"=>"text-info",
            "رد شده"=>"text-danger",
            "تکمیل شده"=>"text-success",
            "در حال پردازش"=>"text-warning"
        ];
        $statusColor = $statusColorMap[$statusText] ?? "text-secondary";

        // Persian date & time
        $persianDate = biiq_PersianDate::date("l j F Y", $unix);
        $persianTime = biiq_PersianDate::date("H:i:s", $unix);

        $order = new stdClass();
        $order->orderNumber = $number;
        $order->type = "<span class='{$color['type']} border px-2 ms-1 rounded-4'>$type</span>";
        $order->img = $img;
        $order->orderDetails = "<span class='d-flex justify-content-start align-items-center {$color['text']} fw-bold'>$amount</span>";
        $order->currencyName = $currency;
        $order->userFullName = "<span class='d-flex justify-content-start align-items-center'>$fullname</span>";
        $order->price = "<span class='{$color['price']}'>$price</span>";
        $order->dateandTime = "<span class='d-block text-start' style='line-height:1.3;'>$persianDate<br>ساعت $persianTime</span>";
        $order->Status = $statusText;
        $order->StatusText = "<span><i class='fas fa-check-circle $statusColor'></i> $statusText</span>";
        $order->unix = $unix;

        $p->list[] = $order;
    }

    // -----------------------------
    // Process users
    // -----------------------------
    $p->allUser = [];
    foreach ($users as [$code, $name, $phone, $lastseen, $banks, $signupTime, $ehraz, $vaziat]) {
        $user = new stdClass();
        $user->nationalCode = "<span class='d-block text-start'>$code</span>";
        $user->fullName = "<span class='d-block text-start'>$name</span>";
        $user->phoneNumber ="<span class='d-block text-start'> $phone</span>";
        $user->lastActivity = biiq_PersianDate::UnixToAgo($lastseen);
        $user->bankAccounts = $banks;

        // Sample date display (can be adjusted)
        $user->signupTime = "<span data-timeStamp='true' class='d-block text-center'>$signupTime</span>";

        // Normalize status
        $statusMap = ["تایید شده"=>2, "رد شده"=>3, "در انتظار بررسی"=>1];
        $user->Status = $statusMap[trim($ehraz)] ?? 0;

        $p->allUser[] = $user;
    }

    // -----------------------------
    // Process financial requests
    // -----------------------------
    $p->allRequests = [];
    foreach ($requests as [$reqNum, $trackNum, $fullName, $amount, $unix, $statusText]) {
        $item = new stdClass();
        $item->requestNumber = "<span class='d-block text-start'>$reqNum</span>";
        $item->trackingNumber ="<span class='d-block text-start'> $trackNum</span>";
        $item->fullName = "<span class='d-block text-start'>$fullName</span>";
        $item->amount = "<span class='d-block text-start'>$amount</span>";

        $persianDate = biiq_PersianDate::date("l j F Y", $unix);
        $persianTime = biiq_PersianDate::date("H:i:s", $unix);
        $item->dateandTime = "<span class='d-block text-center' style='line-height:1.3;'>$persianDate<br>ساعت $persianTime</span>";

        $item->statusClass = ($statusText === "مشاهده رسید") ? "text-info" : "text-warning";
        $item->statusText = $statusText;

        $p->allRequests[] = $item;
    }

    // -----------------------------
    // Return page object for template
    // -----------------------------
    return [
        'content'   => biiq_Template::Start('pages->index', true, ['Objects' => $p]),
        'id'        => 0,
        'title'     => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}
?>
