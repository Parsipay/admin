<?php
function ProcessRequest($request)
{
    $page = new stdClass();


    // لیست واریزی‌ها
    $page->userList = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 9,
            "UnixTimestamp" => 1616301000,
            "lastActivity" => "2 ماه پیش",
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "مسدود ",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 10,
            "UnixTimestamp" => 1616301000,
            "lastActivity" => "2 ماه پیش",
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => " موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 11,
            "UnixTimestamp" => 1616301000,
            "lastActivity" => "2 ماه پیش",
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => " تکمیل نشده",
        ],
    ];

    // bank accounts table second
    $page->bankAccount = [
     [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 12,
            "bankInfo" => " IR940150000184370199152881 ",
             "BankImage" => "../assets/img/dey.png",
            "details"=> "تایید شده",
        ],
          [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 13,
            "bankInfo" => " IR940150000184370199152881 ",
             "BankImage" => "../assets/img/ansar.png",
            "details"=> "تایید شده",
        ],
         [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 14,
            "bankInfo" => " IR940150000184370199152881 ",
             "BankImage" => "../assets/img/blu.png",
            "details"=> "تایید شده",
        ],
    ];

    //List of authentication documents  
    $page->authentication = [
          [
            "nationalCode" => "0013152343",
            "phoneNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 15,
            "documents" => "مشاهده مدارک",
            "Status"=> "در انتظار تایید",
        ],
     [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "documents" => "مشاهده مدارک",
            "Status"=> "تایید شده",
        ],
      [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 17,
            "documents" => "مشاهده مدارک",
            "Status"=> "تکمیل نشده",
        ],
    ];


    // فقط برای لیست واریزی‌ها رنگ وضعیت
    foreach ($page->userList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") {
            $Item["StatusColor"] = "text-success";
        } elseif ($status === "تکمیل نشده") {
            $Item["StatusColor"] = "text-warning";
        } else {
            $Item["StatusColor"] = "text-danger";
        }
    }
    unset($Item);

foreach($page->authentication as &$Item) {
    $status = trim($Item["Status"]);
    if ($status === "تایید شده") {
        $Item["StatusColor"] = "text-success";
    } elseif ($status === "تکمیل نشده") {
        $Item["StatusColor"] = "text-primary";
    } elseif ($status === "در انتظار تایید") {
        $Item["StatusColor"] = "text-warning";
    } else {
        $Item["StatusColor"] = "text-danger";
    }
}
unset($Item); // خیلی مهمه برای جلوگیری از باگ‌های احتمالی

    unset($Item);
    return [
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/'
    ];
}
