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
        "persianDate"=> "12 اسفند",
        "comments"   => "مشاهده رسید"
    ],
    [
        "request"    => "132313556458",
        "price"      => -44568051,
        "persianDate"=> "12 اسفند",
        "comments"   => "مشاهده رسید"
    ],
    [
        "request"    => "132313556458",
        "price"      => -44568051,
        "persianDate"=> "12 اسفند",
        "comments"   => "مشاهده رسید"
    ]
];

$page->userTransactions = [
    [
        "request"    => "132313556458",
        "price"      => 44568051,
        "persianDate"=> "12 اسفند",
        "comments"   => "مشاهده رسید"
    ],
    [
        "request"    => "132313556458",
        "price"      => -44568051,
        "persianDate"=> "12 اسفند",
        "comments"   => "مشاهده رسید"
    ],
    [
        "request"    => "132313556458",
        "price"      => -44568051,
        "persianDate"=> "12 اسفند",
        "comments"   => "مشاهده رسید"
    ]
];

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
