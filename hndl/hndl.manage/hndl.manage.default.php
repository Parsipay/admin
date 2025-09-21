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
    function timeAgoWithPersianDate($timestamp)
    {
        $diff = time() - $timestamp;

        if ($diff < 60) {
            $text = $diff . " ثانیه پیش";
        } elseif ($diff < 3600) {
            $text = floor($diff / 60) . " دقیقه پیش";
        } elseif ($diff < 86400) {
            $text = floor($diff / 3600) . " ساعت پیش";
        } elseif ($diff < 2592000) { 
            $text = floor($diff / 86400) . " روز پیش";
        } elseif ($diff < 31536000) {   
            $text = floor($diff / 2592000) . " ماه پیش";
        } else {
            $text = floor($diff / 31536000) . " سال پیش";
        }
        $exactDate = biiq_PersianDate::date("l j F Y - H:i", $timestamp);
        return $text .  "<br>" . $exactDate;
    }

    //Load user $SelectedUserID
    $page->profileBox = [
        "registrationStatus" => "تایید شده",
        "authenticationStatus" => "در انتظار تایید",
        "maximumPurchase" => "100 میلیون تومان",
        "buyToday" => " 58،256،000 تومان",
        "timeLeft" => "14 دقیقه پیش"

    ];


    $page->userorderList = [
        [
            "request"    => "132313556458",
            "price"      => 44568051,
            "persianDate" => "12 اسفند",
            "comments"   => "مشاهده رسید"
        ],
        [
            "request"    => "132313556458",
            "price"      => 44568051,
            "persianDate" => "12 اسفند",
            "comments"   => "مشاهده رسید"
        ],
        [
            "request"    => "132313556458",
            "price"      => 44568051,
            "persianDate" => "12 اسفند",
            "comments"   => "مشاهده رسید"
        ]
    ];

    $page->userTransactions = [
        [
            "nationalCode"    => "132313556458",
            "phoneNumber"      => "0935646956",
            "User" => "یگانه علیزاده ",
            "bankInfo" => "صادرات",
            "detailsStatus"   => "مشاهده رسید"
        ],
        [
            "nationalCode"    => "132313556458",
            "phoneNumber"      => "0935646956",
            "User" => "یگانه علیزاده ",
            "bankInfo" => "صادرات",
            "detailsStatus"   => "مشاهده رسید"
        ],
        [
            "nationalCode"    => "132313556458",
            "phoneNumber"      => "0935646956",
            "User" => "یگانه علیزاده ",
            "bankInfo" => "صادرات",
            "detailsStatus"   => "مشاهده رسید"
        ],
    ];

    $page->userAuthentication = [
        [
            "request" => "154678952",
            "extraReq" => "درخواست تسویه - تسویه شده {IR360560611828005651602601}",
            "price" => "5697875",
            "timeAgo" => timeAgoWithPersianDate(1615301000),
            "comments" => "مشاهده رسید"
        ],

        [
            "request" => "154678952",
            "extraReq" => "درخواست تسویه - لغو شده {IR360560611828005651602601}",
            "price" => "5697875",
            "timeAgo" => timeAgoWithPersianDate(1615301000),
            "comments" => "مشاهده رسید"
        ],

        [
            "request" => "154678952",
            "extraReq" => "افزایش اعتبار - فروش {۲۰۲۳۲۷۳۸۰۳۰}",
            "price" => "5697875",
            "timeAgo" => timeAgoWithPersianDate(1615301000),
            "comments" => "مشاهده رسید"
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

    //$page->User = biiq_User::GetByID($SelectedUserID);


    $page->Title = "  مدیریت کاربران ";
    $page = array(
        'content' => biiq_Template::Start('manage->default', true, ['Objects' => $page]),
        'id' => 0,
        'title' => $page->Title,
        'Canonical' => SITE . 'manage/',
    );
    return $page;
}
