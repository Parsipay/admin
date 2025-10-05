<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    if (!isset($request->Parameters) || !is_array($request->Parameters) || count($request->Parameters) == 0) {
        //error
        $GLOBALS['error']->Show(401);
        exit;
    }
    $SelectedUserID = $request->Parameters[0];
    if (!is_numeric($SelectedUserID) || $SelectedUserID == 0) {
        //error
        $GLOBALS['error']->Show(401);
        exit;
    }

    //timeAgoWithPersianDate  
    //function seprate money
    function separateThousands($number)
    {
        return number_format((int)$number);
    }

    //Load user $SelectedUserID
    $page->profileBox = [
        "registrationStatus" => "تایید شده",
        "authenticationStatus" => "در انتظار تایید",
        "maximumPurchase" => "100 میلیون تومان",
        "buyToday" => " 58،256،000 تومان",
        "timeLeft" => "14 دقیقه پیش"

    ];
// بعد از تعریف CurrentUser


    $page->orderList = [
        [
            "numberOrder" => "20232336263# <span class='px-1 rounded-3'>خرید</span>",
            "OrderDetails" => '
             <div class="d-flex justify-content-start">
             <img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;">
             <div class="d-flex flex-column">
             <span>19788</span>
             <span>USDT (تتر)</span>
             </div>
            </div>',
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => "<b>" . separateThousands(445609806) . "</b>",
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "
             <div class='d-flex justify-content-between'>
             <span class='opacity-green text-green py-1  px-2 rounded me-4'>
            موفق<i class='fas fa-check-circle text-green ms-1'></i> 
            </span>
         
        </div>",
        ],
        [
            "numberOrder" => "20232336263# <span class='px-1 rounded-3'>فروش</span>",
            "OrderDetails" => '
             <div class="d-flex justify-content-start">
             <img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;">
             <div class="d-flex flex-column">
             <span>19788</span>
             <span>USDT (تتر)</span>
             </div>
            </div>',
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => "<b>" . separateThousands(445609806) . "</b>",
            "UnixTimestamp" => 897887,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1236554),
            "Status" => "
             <div class='d-flex justify-content-between'>
             <span class='bg-opacity-warning py-1 text-warning px-2 rounded me-4'>
           در انتظار تایید <i class='fas fa-stopwatch text-warning'></i>   
            </span>
        </div>",
        ],
        [
            "numberOrder" => "20232336263# <span class='px-1 rounded-3'>فروش</span>",
            "OrderDetails" => '
             <div class="d-flex justify-content-start">
             <img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;">
             <div class="d-flex flex-column">
             <span>19788</span>
             <span>USDT (تتر)</span>
             </div>
            </div>',
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => "<b>" . separateThousands(445609806) . "</b>",
            "UnixTimestamp" => 789635,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1266578),
            "Status" => "
             <div class='d-flex justify-content-between'>
             <span class='opacity-danger text-danger py-1  px-2 rounded me-4'>
             ناموفق <i class='fas fa-ban text-danger'></i>   
            </span>
        </div>",
        ],
    ];
    //condition for number order span
    foreach ($page->orderList as &$Item) {
        if (strpos($Item["numberOrder"], 'خرید') !== false) {
            $Item["numberOrder"] = str_replace(
                "<span class='px-1 rounded-3'>خرید</span>",
                "<span class='px-1 rounded-3 text-success opacity-green'>خرید</span>",
                $Item["numberOrder"]
            );
        } elseif (strpos($Item["numberOrder"], 'فروش') !== false) {
            $Item["numberOrder"] = str_replace(
                "<span class='px-1 rounded-3'>فروش</span>",
                "<span class='px-1 rounded-3 text-danger bg-red'>فروش</span>",
                $Item["numberOrder"]
            );
        }
    }
    unset($Item);


    //for sort desc or asc date and time
    $sortOrder = $_GET['sort'] ?? 'desc';
    usort($page->orderList, function ($a, $b) use ($sortOrder) {
        if ($sortOrder === 'asc') {
            return $a['UnixTimestamp'] <=> $b['UnixTimestamp']; // قدیمی به جدید
        } else {
            return $b['UnixTimestamp'] <=> $a['UnixTimestamp']; // جدید به قدیم
        }
    });

    $page->transactions = [
        [
            "request" => '<span>154852#</span><span class="bg-blue text-primary ms-2 border border-primary p-1 rounded">درخواست تسویه - تسویه شده {IR360560611828005651602601}</span>',
            "UserID" => 19,
            "price" => -445609806,
            "UnixTimestamp" => 1710656897,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1266578),
            "description" => "<span class='text-primary'>مشاهده رسید <span>",
        ],
        [
            "request" => '<span>154852#</span><span class="bg-red text-danger ms-2 border border-danger p-1 rounded">درخواست تسویه - تسویه شده {IR360560611828005651602601}</span>',
            "UserID" => 19,
            "price" => 445609806,
            "UnixTimestamp" => 1720656897,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1266578),

            "description" => "<span class='text-primary'>۲۰۲۳۲۷۳۸۰۳۰</span>",
        ],
        [
            "request" => '<span>154852#</span><span class="bg-opacity-green text-green ms-2 border border-success p-1 rounded">درخواست تسویه - تسویه شده {IR360560611828005651602601}</span>',
            "UserID" => 19,
            "price" => -445609806,
            "UnixTimestamp" => 1750656897,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1266578),

            "description" => "<span class='text-'>1404041100032259426803</span>",
        ],
    ];

    function timeAgo($timestamp)
    {
        $diff = time() - $timestamp;

        $minutes = floor($diff / 60);
        $hours   = floor($diff / 3600);
        $days    = floor($diff / 86400);
        $months  = floor($diff / 2592000);
        $years   = floor($diff / 31104000);

        if ($years > 0) {
            return $years . " سال پیش";
        } elseif ($months > 0) {
            return $months . " ماه پیش";
        } elseif ($days > 0) {
            return $days . " روز پیش";
        } elseif ($hours > 0) {
            return $hours . " ساعت پیش";
        } elseif ($minutes > 0) {
            return $minutes . " دقیقه پیش";
        } else {
            return "لحظاتی پیش";
        }
    }
    // ترکیب تاریخ شمسی و زمان گذشته
    foreach ($page->transactions as &$Item) {
        $persian = biiq_PersianDate::date("l j F Y - H:i", $Item["UnixTimestamp"]);
        $relative = timeAgo($Item["UnixTimestamp"]);
        $Item["persianDate"] =
            "<div class='fw-bold'>" . $relative . "</div>" .
            "<div class='mt-2'>" . $persian . "</div>";
    }
    unset($Item);

    foreach ($page->transactions as &$Item) {
        $Item["price"] = $Item["price"] < 0
            ? "<b class='text-danger'>" . separateThousands($Item["price"]) . "</b>"
            : "<b class='text-green'>" . separateThousands($Item["price"]) . "</b>";
    }
    unset($Item);


    $sortOrder = 'desc';
    $sortOrder = $_GET['sort'] ?? 'desc';
    usort($page->transactions, function ($a, $b) use ($sortOrder) {
        if ($sortOrder === 'asc') {
            return $a['UnixTimestamp'] <=> $b['UnixTimestamp']; // قدیمی به جدید
        } else {
            return $b['UnixTimestamp'] <=> $a['UnixTimestamp']; // جدید به قدیم
        }
    });
    $page->accountInfo = [
        [
            "id" => "1",
            "shebaNumber" => "IR940150000184370199152881",
            "cartNumber" => "6273811170944968",
            "UserID" => 22,
            "bank" => "/assets/img/blu.png",
            "UnixTimestamp" => 9999999999,

            "details" => "تایید شده",
        ],
        [
            "id" => "2",
            "shebaNumber" => "IR940150000184370199152881",
            "cartNumber" => "6273811170944968",
            "UserID" => 22,
            "bank" => "/assets/img/ansar.png",
            "UnixTimestamp" => 9999999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "details" => "رد شده",
        ],
        [
            "id" => "3",
            "shebaNumber" => "IR940150000184370199152881",
            "cartNumber" => "6273811170944968",
            "UserID" => 22,
            "bank" => "/assets/img/dey.png",
            "UnixTimestamp" => 8888888888,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "details" => "تایید شده",
        ],
    ];

    foreach ($page->accountInfo as &$Item) {
        $status = trim($Item["details"]); // یا Status اگر تغییر داده باشی
        if ($status === "تایید شده") {
            $Item["StatusColor"] = "text-success opacity-green"; // رنگ متن و بک‌گراند
        } else {
            $Item["StatusColor"] = "text-danger bg-red";
        }
    }
    unset($Item);
$page->identityDocuments = [
    [
        "description" => "<i class='fas fa-times-circle text-red me-1'></i> تصویر احراز هویت خود را با کیفیت بالاتری ارسال نمایید.",
        "UserID" => 22,
        "UnixTimestamp" => time() - 600, // 10 دقیقه پیش
        "status" => "در انتظار تایید",
        "details" => "  مشاهده مدارک",
    ],
    [
        "description" => "<i class='fas fa-exclamation-circle text-warning me-1'></i> تصویر احراز هویت خود را با کیفیت بالاتری ارسال نمایید.",
        "UserID" => 22,
        "UnixTimestamp" => time() - 3600, // 1 ساعت پیش
        "status" => "تایید شده",
        "details" => " مشاهده مدارک",
    ],
    [
        "description" => "<i class='fas fa-check-circle text-green me-1'></i> تصویر احراز هویت خود را با کیفیت بالاتری ارسال نمایید.",
        "UserID" => 22,
        "UnixTimestamp" => time() - 300, // 5 دقیقه پیش
        "status" => "رد شده",
        "details" => " مشاهده مدارک",
    ],
];
foreach ($page->identityDocuments as &$Item) {
    $Item['persianDate'] = timeAgo($Item['UnixTimestamp']);
}
unset($Item);

foreach ($page->identityDocuments as &$Item) {
    $status = trim($Item["status"]); // حذف فضای اضافی

    if (strpos($status, "رد شده") !== false) {
        $Item["StatusColor"] = "text-red bg-red"; // قرمز
    } elseif (strpos($status, "تایید شده") !== false) {
        $Item["StatusColor"] = "text-green opacity-green"; // سبز
    } elseif (strpos($status, "در انتظار تایید") !== false) {
        $Item["StatusColor"] = "text-warning bg-opacity-warning"; // زرد
    } else {
        $Item["StatusColor"] = "text-secondary bg-light"; // پیش‌فرض خاکستری
    }
}
unset($Item);



    $page->er = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 19,
            "documents"    => "مدرک شناسایی",
            "Status"       => "مسدود",
            "StatusColor"  => "bg-danger text-white",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 20,
            "documents"    => "کارت ملی",
            "Status"       => "موفق",
            "StatusColor"  => "bg-success text-white",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 21,
            "documents"    => "مدرک ناقص",
            "Status"       => "تکمیل نشده",
            "StatusColor"  => "bg-warning text-dark",
        ],
    ];

    foreach ($page->userAuthentication as &$Item) {
        $extraReq = trim($Item["extraReq"]);

        if (strpos($extraReq, "تسویه شده") !== false) {
            $Item["extraReqcolor"] = "bg-blue ";
        } elseif (strpos($extraReq, "لغو شده") !== false) {
            $Item["extraReqcolor"] = "opacity-danger ";
        } elseif (strpos($extraReq, "افزایش اعتبار") !== false) {
            $Item["extraReqcolor"] = "opacity-green ";
        } else {
            $Item["extraReqcolor"] = "bg-secondary ";
        }
    }
    unset($Item); // جلوگیری از مشکلات بعدی
    // prevent possible bugs later

    //$page->CurrentUser = biiq_User::GetByID($SelectedUserID);
    $page->CurrentUser = $page; //Delete me later
    $page->CurrentUser = [
        'Status' => "تایید شده",
        'SelfieStatus' => 'ارسال نشده',
        'maximumPurchase' => "صد هزار تومن",
        'buyToday' => " 28،256،000 تومان",
        'lastTime' => "10 دقیقه پیش",
        'phoneNumber' => '09356458975',
        'userNumber' => '56789',
        'introduced' => '  2 نفر ',
        'nationalCode' => '0013152343',
        'GiftCredit' => '500 هزار تومن',
        'birthday' => '1370/06/06',
        'ipAddress' => '4.126.16.37',
        'persianDate' => biiq_PersianDate::date("l j F Y"),
'otherDate' => date("Y/m/d")
    ];



    $page->Title = "  مدیریت کاربران ";
    $page = array(
        'content' => biiq_Template::Start('manage->default', true, ['Objects' => $page, 'CurrentUser' => $page->CurrentUser]),
        'id' => 0,
        'title' => $page->Title,
        'Canonical' => SITE . 'manage/',
    );
    return $page;
}
