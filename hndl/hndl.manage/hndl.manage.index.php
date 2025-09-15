<?php
function ProcessRequest($request)
{
    $page = new stdClass();


    // orderlist
    $page->orderList = [
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => "445609806",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "موفق ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => "445609806",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "در انتظار تایید ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => "445609806",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "  ناموفق ",
        ],
    ];

    // userlist
    $page->userList = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => " مسدود",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => " موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",

            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "تکمیل نشده",
        ],
    ];

    //List of request
    $page->requestList = [
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" => " 65665454546",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "مشاهده رسید",
        ],
             [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
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


    // فقط برای لیست واریزی‌ها رنگ وضعیت
    foreach ($page->orderList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") {
            $Item["StatusColor"] = "text-success";
        } elseif ($status === "در انتظار تایید") {
            $Item["StatusColor"] = "text-warning";
        } elseif ($status === "تکمیل نشده") {
            $Item["StatusColor"] = "text-primary";
        } else {
            $Item["StatusColor"] = "text-danger";
        }
    }
    unset($Item);

    foreach ($page->userList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") {
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
   foreach ($page->requestList as &$Item) {
        $status = trim($Item["Status"]);
        
        if ($status === "مشاهده رسید") {
            $Item["StatusColor"] = "text-primary";
        } elseif ($status === "در  صف تسویه") {
            $Item["StatusColor"] = "text-warning";
        } else {
            $Item["StatusColor"] = "text-danger";
        }
    }
    unset($Item); // خیلی مهمه برای جلوگیری از باگ‌های احتمالی

    return [
        'content'   => biiq_Template::Start('manage->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'manage/'
    ];
}
