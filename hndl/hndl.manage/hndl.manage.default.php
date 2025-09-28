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


    //Load user $SelectedUserID
    $page->profileBox = [
        "registrationStatus" => "تایید شده",
        "authenticationStatus" => "در انتظار تایید",
        "maximumPurchase" => "100 میلیون تومان",
        "buyToday" => " 58،256،000 تومان",
        "timeLeft" => "14 دقیقه پیش"

    ];


    $page->orderList = [
        [
            "numberOrder" => "20232336263# <span class='opacity-green px-1 rounded-3 text-green'>خرید</span>",
            "OrderDetails" => '
             <div class="d-flex justify-content-start">
             <img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;">
             <div class="d-flex flex-column">
             <span>19788</span>
             <span>USDT (تتر)</span>
             </div>
            </div>',
            "User" => "یگانه 6666666علیزاده",
            "UserID" => 16,
            "price" => "445609806",
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "
             <div class='d-flex justify-content-between'>
             <span class='opacity-green text-green py-1  px-2 rounded me-4'>
            موفق<i class='fas fa-check-circle text-green ms-1'></i> 
            </span>
           <span class='text-primary'>مشاهده </span>
        </div>",
        ],
        [
            "numberOrder" => "20232336263# <span class='bg-blue px-1 rounded-3 text-primary'>فروش</span>",
            "OrderDetails" => '
             <div class="d-flex justify-content-start">
             <img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;">
             <div class="d-flex flex-column">
             <span>19788</span>
             <span>USDT (تتر)</span>
             </div>
            </div>',
            "User" => "  یگانه علیزاده",
            "UserID" => 16,
            "price" => "445609806",
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "
             <div class='d-flex justify-content-between'>
             <span class='bg-opacity-warning py-1 text-warning px-2 rounded me-4'>
           در انتظار تایید <i class='fas fa-stopwatch text-warning'></i>   
            </span>
           <span class='text-primary'>مشاهده </span>
        </div>",
        ],
        [
            "numberOrder" => "20232336263# <span class='opacity-danger px-1 rounded-3 text-danger'>فروش</span>",
            "OrderDetails" => '
             <div class="d-flex justify-content-start">
             <img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;">
             <div class="d-flex flex-column">
             <span>19788</span>
             <span>USDT (تتر)</span>
             </div>
            </div>',
            "User" => " یگانه علیزاده",
            "UserID" => 16,
            "price" => "445609806",
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "
             <div class='d-flex justify-content-between'>
             <span class='opacity-danger text-danger py-1  px-2 rounded me-4'>
             ناموفق <i class='fas fa-ban text-danger'></i>   
            </span>
           <span class='text-primary ms-5'>مشاهده </span>
        </div>",
        ],
    ];


    $page->userList = [
        [
            "nationalCode" => "************************",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1111111),
            "Status" => " مسدود",
        ],
        [
            "nationalCode" => "*********************",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 33333333,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 333333333),
            "Status" => " موفق",
        ],
        [
            "nationalCode" => "*****************",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",

            "UnixTimestamp" => 4444444444,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 4444444444),
            "Status" => "تکمیل نشده",
        ],
    ];

    $page->requestList = [
        [
            "requestCode" => "test",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" => " 65665454546",
            "UnixTimestamp" => 9999999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "Status" => "مشاهده رسید",
        ],
        [
            "requestCode" => "test",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => 777777777,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 33333333),
            "Status" => "  مشاهده رسید",
        ],
        [
            "requestCode" => "test",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 24,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "در  صف تسویه",
        ],
    ];
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
        'ipAddress' => '4.126.16.37'
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
