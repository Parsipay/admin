<?php
function ProcessRequest($request)
{
    function maskCard($num)
    {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len);
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }

    $page = new stdClass();
    // data for accordion
    $UnixTimestamp = 1616301000; // example timestamp, in reality there should be multiple
    $page->docList = [
        [
            "user" => "بنفشه ابراهیمی",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate"  => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
        ],
        [
            "user" => "بنفشه ابراهیمی",
            "unixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
        ],
    ];
    $page->Cards = [
        [
            "user" => " یگانه علیزاده",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => maskCard("5022291077470837"),
        ],
        [
            "user" => "مریم ماهور",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => maskCard("5022291077470837"),

        ],
    ];


    // deposits list
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



    // orderlist
    // bank accounts table second
    $page->bankAccount = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 12,
            "bankInfo" => " IR940150000184370199152881 ",
            "BankImage" => "../assets/img/dey.png",
            "details" => "تایید شده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 13,
            "bankInfo" => " IR940150000184370199152881 ",
            "BankImage" => "../assets/img/ansar.png",
            "details" => "تایید شده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 14,
            "bankInfo" => " IR940150000184370199152881 ",
            "BankImage" => "../assets/img/blu.png",
            "details" => "تایید شده",
        ],
    ];
    // List of authentication documents  
    $page->authentication = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 15,
            "documents" => "مشاهده مدارک",
            "Status" => "در انتظار تایید",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "documents" => "مشاهده مدارک",
            "Status" => "تایید شده",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 17,
            "documents" => "مشاهده مدارک",
            "Status" => "تکمیل نشده",
        ],
    ];

foreach ($page->authentication as &$Item) {
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
unset($Item); // prevent possible bugs later


    // only for deposits list - status colors
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
    unset($Item); // important to prevent possible bugs
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
    unset($Item); // important to prevent possible bugs

    return [
        'content'   => biiq_Template::Start('manage->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'manage/'
    ];
}
