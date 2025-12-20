<?php
function ProcessRequest($request){
    // -------------------------
    // Validation
    // -------------------------
    if(!isset($request->Parameters) || !is_array($request->Parameters) || count($request->Parameters) == 0){
        $GLOBALS['error']->Show(401);
        exit;
    }

    $SelectedUserID = $request->Parameters[0];
    if(!is_numeric($SelectedUserID) || $SelectedUserID == 0){
        $GLOBALS['error']->Show(401);
        exit;
    }

    // -------------------------
    // Helper: Status Color
    // -------------------------
    function getStatusColor($status){
        switch (trim($status)) {
            case 'موفق': return "text-green opacity-green p-1 px-2 rounded";
            case 'رد شده': return "text-danger bg-red p-1 px-2 rounded";
            case 'تکمیل نشده': return "text-primary bg-blue p-1 px-2 rounded";
            case 'در انتظار تایید':
            case 'پردازش': return "text-warning bg-opacity-warning p-1 px-2 rounded";
            case 'پرداخت با کارت نامعتبر': return "text-red bg-red p-1 px-2 rounded";
            default: return "text-secondary p-1 px-2 rounded";
        }
    }

    // -------------------------
    // Init Page Object
    // -------------------------
    $page = new stdClass();

    // -------------------------
    // Current Date & Time
    // -------------------------
    $today = new DateTime();
    $today->modify('+1 hour');

    $page->dateandtime = (object)[
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];


    // -------------------------
    // Status Bar & Transaction
    // -------------------------
    $tx = (object)[
        'status'     => 'موفق',
        'wallet'     => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5',
        'tx_id'      => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5TN7TeTQxA1ZNEcT',
        'transfer_id'=> 'CW2528380666409',
        'number'     => '3215402',
        'ip'         => '45.93.169.254',
        'discount'   => '۵۰۴,۵۰۴ تومان',
        'walletAmount'=> '۵۰۴,۵۰۴ تومان',
        'cartNumber' => '5022291577226309',
        'paid'       => '۵۰۴,۵۰۴ تومان',
        'statusColor'=> getStatusColor('موفق')
    ];

    $page->statusbar    = [$tx];          

    // -------------------------
    // Page Title
    // -------------------------
    $page->Title = "مشاهده سفارش";

    // -------------------------
    // Return Template Output
    // -------------------------
    $page->Order = [
        'ID' => $SelectedUserID,
        'status'   => "موفق",
        'amount'   => "12 USDT",
        'currency' => "ترون (TRC20)",
        'network'  => "TRC20",
        'price'    => "504,504 تومان",
        'date'     => $page->dateandtime->persianDate,
        'time'     => $page->dateandtime->time,
        'img'      => "../../assets/img/usdt.png",
        'statusColor' => getStatusColor("موفق")
    ];
    return [
        'content'   => biiq_Template::Start('orders->default', true, ['Objects' => $page, 'Order' => $page->Order]),
        'id'        => 1,
        'title'     => $page->Title,
        'Canonical' => SITE.'orders/',
    ];
}
?>
