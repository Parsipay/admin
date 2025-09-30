<?php
function ProcessRequest($request)
{
    $page = new stdClass();
    function maskCard($num)
    {
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len); // fallback for very short numbers
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }


    //function seprate money
    function separateThousands($number)
    {
        return number_format((int)$number);
    }


    $page->orderList = [
        //mask number for bank carts

        [
            "numberOrder" => "1013152343",
            "OrderDetails" => "09128431937",
            "User" => "یگانه علیزاده",
            "UserID" => 16,
            "price" => separateThousands(16520897),
            "UnixTimestamp" => 111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 11111111),
            "Status" => "موفق ",
        ],
        [
            "numberOrder" => "2013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 17,
            "price" => separateThousands(22000000),
            "UnixTimestamp" => 11111111,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 555555555),
            "Status" => "در انتظار تایید ",
        ],
        [
            "numberOrder" => "3013152343",
            "OrderDetails" => "09128431937",
            "User" => " بنفشه ابراهیمی",
            "UserID" => 18,
            "price" => separateThousands(12500000),
            "UnixTimestamp" => 9999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 99999999),
            "Status" => "  ناموفق ",
        ],
    ];

    //for sort desc or asc date and time
    $sortOrder = $_GET['sort'] ?? 'desc';
    usort($page->orderList, function ($a, $b) use ($sortOrder) {
        if ($sortOrder === 'asc') {
            return $a['UnixTimestamp'] <=> $b['UnixTimestamp']; // قدیمی به جدید
        } else {
            return $b['UnixTimestamp'] <=> $a['UnixTimestamp']; // جدید به قدیم
        }
    });
    //List of request
    $page->requestList = [
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 22,
            "price" =>  separateThousands(65665454546),
            "UnixTimestamp" => 9999999999,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 88888888),
            "Status" => "مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 23,
            "price" => separateThousands(65665454546),
            "UnixTimestamp" => 777777777,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 33333333),
            "Status" => "  مشاهده رسید",
        ],
        [
            "requestCode" => "0013152343",
            "trackingNumber" => "0293564635",
            "User" => "بنفشه ابراهیمی",
            "UserID" => 24,
            "price" => separateThousands(65665454546),
            "UnixTimestamp" => 1616301000,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 1616301000),
            "Status" => "در  صف تسویه",
        ],
    ];

    $page->userList = [
        [
            "nationalCode" => "2356897845",
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
            "User" => " بنفشه ابراهیمی",
            "UserID" => 20,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 33333333,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 333333333),
            "Status" => " موفق",
        ],
        [
            "nationalCode" => "0013152343",
            "phoneNumber" => "09128431937",
            "User" => " مونا مارامی",
            "UserID" => 21,
            "lastActivity" => "2 ماه پیش",
            "UnixTimestamp" => 4444444444,
            "persianDate" => biiq_PersianDate::date("l j F Y - H:i", 4444444444),
            "Status" => "تکمیل نشده",
        ],
    ];

    foreach ($page->orderList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green";
        elseif ($status === "در انتظار تایید") $Item["StatusColor"] = "text-warning bg-opacity-warning ";
        else $Item["StatusColor"] = "text-danger opacity-danger";
    }
    unset($Item);

    //condition for userlist's status
    foreach ($page->userList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "موفق") $Item["StatusColor"] = "text-success opacity-green ";
        elseif ($status === "تکمیل نشده") $Item["StatusColor"] = "text-primary bg-blue";
        else $Item["StatusColor"] = "text-danger opacity-danger";
    }
    unset($Item);

    foreach ($page->requestList as &$Item) {
        $status = trim($Item["Status"]);
        if ($status === "مشاهده رسید") $Item["StatusColor"] = "text-primary ";
        else $Item["StatusColor"] = "text-warning";
    }
    unset($Item);
    return [
        'content'   => biiq_Template::Start('orders->index', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'orders/'
    ];
}
