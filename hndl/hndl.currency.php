<?php
function ProcessRequest($request)
{
    $page = new stdClass();

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
            'id'          => "۲۰۲۳۲۲۴۶۴۴۸",
            'status'      => "موفق",
            'amount'      => "12 USDT",
            'currency'    => "ترون (TRC20)",
            'network'     => "TRC20",
            'price'       => "504,504 تومان",
            'date'        => $page->dateandtime['persianDate'],
            'time'        => $page->dateandtime['time'],
            'img'         => "../../assets/img/usdt.png",
            'statusColor' => "text-green opacity-green p-1 px-2 rounded"
        ]
    ];

    // === Status Bar Data ===
    $statusbar = [
        [
            'status'      => 'موفق',
            'wallet'      => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5',
            'tx_id'       => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5TN7TeTQxA1ZNEcT',
            'transfer_id' => 'CW2528380666409'
        ]
    ];

    // Assign colors based on status
    foreach ($statusbar as $key => $tx) {
        $statusbar[$key]['statusColor'] = $tx['status'] === 'موفق'
            ? "text-green opacity-green p-1 px-2 rounded"
            : "text-danger bg-red p-1 px-2 rounded";
    }
    $page->statusbar = $statusbar;

    // === Transaction Info ===
    $transaction = [
        'status'     => 'موفق',
        'number'     => '3215402',
        'ip'         => '45.93.169.254',
        'discount'   => '۵۰۴,۵۰۴ تومان',
        'wallet'     => '۵۰۴,۵۰۴ تومان',
        'cartNumber' => '5022291577226309',
        'paid'       => '۵۰۴,۵۰۴ تومان',
        'statusColor'=> 'موفق' === 'موفق'
            ? "text-green opacity-green p-1 px-2 rounded"
            : "text-danger bg-red p-1 px-2 rounded"
    ];
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
