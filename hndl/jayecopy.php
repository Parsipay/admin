<?php
function ProcessRequest($request)
{
    // Function to mask a card number (show first 6 and last 4 digits only)
    function maskCard($num)
    {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len);
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }

    $page = new stdClass();
    
    // Example timestamp (in real case there should be multiple ones)
    $UnixTimestamp = 1616301000;

    // Data for accordion
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

    // Cards list
    $page->Cards = [
        [
            "user" => " یگانه علیزاده",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => maskCard("5022291077470837"), // masked card number
        ],
        [
            "user" => "مریم ماهور",
            "BankName" => "صادرات",
            "Shaba" => "IR940150000184370199152881",
            "MaskedCard" => maskCard("5022291077470837"), // masked card number
        ],
    ];

    // Orders list
    $page->orderList = [
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => "445609806",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "موفق ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => "445609806",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "در انتظار تایید ",
        ],
        [
            "numberOrder" => "0013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => "445609806",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "  ناموفق ",
        ],
    ];

    // Users list
    $page->userList = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 19,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => " مسدود",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => " موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "تکمیل نشده",
        ],
    ];

    // Request list
    $page->requestList = [
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" => " 65665454546",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "  مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 24,
            "price" => "مشاهده مدارک",
            "UnixTimestamp" => $UnixTimestamp,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", $UnixTimestamp),
            "Status" => "در  صف تسویه",
        ],
    ];


    // Add status color for order list
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

    // Add status color for user list
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
    unset($Item); // Important to avoid unexpected bugs

    // Add status color for request list
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
    unset($Item); // Important to avoid unexpected bugs

    // Final return
    return [
        'content'   => biiq_Template::Start('manage->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'manage/'
    ];
}
