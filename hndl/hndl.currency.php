<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // === Function to determine status color ===
    function getStatusColor($status)
    {
        switch (trim($status)) {
            case 'موفق':
                return "text-green opacity-green p-1 px-2 rounded";
            case 'رد شده':
                return "text-danger bg-red p-1 px-2 rounded";
            case 'تکمیل نشده':
                return "text-primary bg-blue p-1 px-2 rounded";
            case 'در انتظار تایید':
            case 'پردازش':
                return "text-warning bg-opacity-warning p-1 px-2 rounded";
            case 'پرداخت با کارت نامعتبر':
                return "text-red bg-red p-1 px-2 rounded";
            default:
                return "text-secondary p-1 px-2 rounded";
        }
    }

    // === Current Date & Time ===
    $today = new DateTime();
    $today->modify('+1 hour');
    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    // === Orders Data ===
    $page->orders = [
        [
            'id'       => "۲۰۲۳۲۲۴۶۴۴۸",
            'status'   => "موفق",
            'amount'   => "12 USDT",
            'currency' => "ترون (TRC20)",
            'network'  => "TRC20",
            'price'    => "504,504 تومان",
            'date'     => $page->dateandtime['persianDate'],
            'time'     => $page->dateandtime['time'],
            'img'      => "../../assets/img/usdt.png",
        ]
    ];

    // Add color style to each order status
    foreach ($page->orders as $key => $order) {
        $page->orders[$key]['statusColor'] = getStatusColor($order['status']);
    }

    // === Status Bar Data ===
    $statusbar = [
        [
            'status'      => 'پردازش',
            'wallet'      => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5',
            'tx_id'       => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5TN7TeTQxA1ZNEcT',
            'transfer_id' => 'CW2528380666409'
        ]
    ];

    // Add color style to each statusbar item
    foreach ($statusbar as $key => $tx) {
        $statusbar[$key]['statusColor'] = getStatusColor($tx['status']);
    }
    $page->statusbar = $statusbar;

    // === Transaction Info ===
    $transaction = [
        'status'     => 'پرداخت با کارت نامعتبر',
        'number'     => '3215402',
        'ip'         => '45.93.169.254',
        'discount'   => '۵۰۴,۵۰۴ تومان',
        'wallet'     => '۵۰۴,۵۰۴ تومان',
        'cartNumber' => '5022291577226309',
        'paid'       => '۵۰۴,۵۰۴ تومان'
    ];
    $transaction['statusColor'] = getStatusColor($transaction['status']);
    $page->transaction = $transaction;

    // === Return Template Data ===
    return [
        'content'   => biiq_Template::Start('currency->index', true, [
            'Objects'     => $page,
            'dateandtime' => $page->dateandtime,
            'transaction' => $page->transaction
        ]),
        'id'        => 1,
        'title'     => 'ارز دیجیتال',
        'Canonical' => SITE . 'currency/'
    ];
}
