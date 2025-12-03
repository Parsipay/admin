<?php
/**
 * Returns a Bootstrap-style badge class based on Persian status
 */
function getStatusColor($status)
{
    $status = trim($status);

    $colors = [
        'موفق'                       => "text-green opacity-green p-1 px-2 rounded",
        'رد شده'                     => "text-danger bg-red p-1 px-2 rounded",
        'تکمیل نشده'                  => "text-primary bg-blue p-1 px-2 rounded",
        'در انتظار تایید'            => "text-warning bg-opacity-warning p-1 px-2 rounded",
        'پردازش'                     => "text-warning bg-opacity-warning p-1 px-2 rounded",
        'پرداخت با کارت نامعتبر'     => "text-red bg-red p-1 px-2 rounded",
    ];

    return $colors[$status] ?? "text-secondary p-1 px-2 rounded";
}

/**
 * Main request processor for "currency" page
 */
function ProcessRequest($request)
{
    $page = new stdClass();

    // -------------------------
    // Current Date & Time
    // -------------------------
    $today = new DateTime('+1 hour');

    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    // -------------------------
    // Orders Data
    // -------------------------
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

    // Add CSS color for each order
    foreach ($page->orders as &$order) {
        $order['statusColor'] = getStatusColor($order['status']);
    }
    unset($order); // break reference

    // -------------------------
    // Status Bar (Transfer Info)
    // -------------------------
    $page->statusbar = [
        [
            'status'      => 'موفق',
            'wallet'      => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5',
            'tx_id'       => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5TN7TeTQxA1ZNEcT',
            'transfer_id' => 'CW2528380666409'
        ]
    ];

    foreach ($page->statusbar as &$tx) {
        $tx['statusColor'] = getStatusColor($tx['status']);
    }
    unset($tx);

    // -------------------------
    // Transaction Info
    // -------------------------
$page->transaction = (object)[
    'status'     => 'موفق',
    'number'     => '3215402',
    'ip'         => '45.93.169.254',
    'discount'   => '۵۰۴,۵۰۴ تومان',
    'wallet'     => '۵۰۴,۵۰۴ تومان',
    'cartNumber' => '5022291577226309',
    'paid'       => '۵۰۴,۵۰۴ تومان',
    'statusColor'=> getStatusColor('موفق')
];

    // -------------------------
    // Return Template Output
    // -------------------------
return [
    'content' => biiq_Template::Start('orders->default', true, [
        'transaction' => $page->transaction
    ]),
    'id' => 1,
    'title' => 'ارز دیجیتال',
    'Canonical' => SITE . 'orders/'
];
}
