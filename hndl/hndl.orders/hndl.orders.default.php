<?php
function ProcessRequest($request)
{

    // -------------------------
    // Validation
    // -------------------------
    if (
        !isset($request->Parameters) ||
        !is_array($request->Parameters) ||
        count($request->Parameters) == 0
    ) {
        $GLOBALS['error']->Show(401);
        exit;
    }

    $SelectedUserID = $request->Parameters[0];
    if (!is_numeric($SelectedUserID) || $SelectedUserID == 0) {
        $GLOBALS['error']->Show(401);
        exit;
    }

    // -------------------------
    // Status Color
    // -------------------------
    function getStatusColor($status)
    {
        return match ($status) {
            'موفق'        => 'text-green opacity-green p-1 px-2 rounded',
            'پردازش'      => 'text-warning bg-opacity-warning p-1 px-2 rounded',
            'رد شده'      => 'text-danger bg-red p-1 px-2 rounded',
            'تکمیل نشده'  => 'text-primary bg-blue p-1 px-2 rounded',
            default       => 'text-secondary p-1 px-2 rounded'
        };
    }

    // -------------------------
    // Init
    // -------------------------

    $page = new stdClass();
    $today = new DateTime();
    $page->date = biiq_PersianDate::date("l j F Y");
    $page->time = $today->format("H:i");

    // -------------------------
    // Sample Transaction
    // -------------------------
    $tx = (object)[
        'status' => 'پردازش', // موفق | پردازش | رد شده | تکمیل نشده
        'subStatus' => 'half_wallet_half_pending', // حالت جدید

        'wallet' => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5',
        'transfer_id' => 'CW2528380666409',
        'tx_id' => 'TN7TeTQxA1ZNEcThVABvwSunjaZJs2PeT5TN7',

        'discount' => '۲۵۰,۰۰۰ تومان',
        'walletAmount' => '۲۵۰,۰۰۰ تومان',

        'trackingCode' => '224167232630',
        'trackingBankCode' => '224167232631',

        'ip' => '45.93.169.254',
        'cartNumber' => '5022291577226309',
        'paid' => '۵,۰۰۰,۰۰۰ تومان'
    ];

    // -------------------------
    // Order Details
    // -------------------------
    $page->ip   = $tx->ip;
    $page->card = $tx->cartNumber;
    $page->paid = $tx->paid;
    $page->orderDetails = [
        (object)[
            'label' => 'آدرس کیف پول',
            'value' => $tx->wallet
        ]
    ];

    if ($tx->status === 'موفق') {
        $page->orderDetails[] = (object)[
            'label' => 'شناسه انتقال',
            'value' => $tx->transfer_id
        ];
        $page->orderDetails[] = (object)[
            'label' => 'شناسه تراکنش',
            'value' => $tx->tx_id
        ];
    }

    // -------------------------
    // Transaction Rows
    // -------------------------
    $page->transactionRows = [];

    if ($tx->status === 'موفق' || $tx->status === 'پردازش') {
        $page->transactionRows[] = (object)[
            'rightValue' => $tx->discount,
            'rightLabel' => 'برداشت از کیف پول',
            'leftValue'  => $tx->trackingCode,
            'leftLabel'  => 'کد رهگیری',
            'icon'       => 'fa-circle-check text-green'
        ];

        if ($tx->status === 'موفق') {
            $page->transactionRows[] = (object)[
                'rightValue' => $tx->walletAmount,
                'rightLabel' => 'پرداخت از حساب بانکی',
                'leftValue'  => $tx->trackingBankCode,
                'leftLabel'  => 'کد رهگیری بانک',
                'icon'       => 'fa-circle-check text-green'
            ];
        }
    }

    if ($tx->status === 'رد شده') {
        $page->transactionRows[] = (object)[
            'rightValue' => 'پرداخت با کارت نامعتبر',
            'rightLabel' => 'پرداخت از حساب بانکی',
            'leftValue'  => '',
            'leftLabel'  => '',
            'icon'       => 'fa-circle-xmark text-danger'
        ];
        $page->paid = '0 تومان';
        $page->card = 'پرداخت با کارت نامعتبر';
    }

    // -------------------------
    // تکمیل نشده
    // -------------------------
    if ($tx->status === 'تکمیل نشده') {

        if ($tx->subStatus === 'invalid_card_full') {
            $page->transactionRows[] = (object)[
                'rightValue' => 'پرداخت با کارت نامعتبر',
                'rightLabel' => 'پرداخت از حساب بانکی',
                'leftValue'  => '',
                'leftLabel'  => '',
                'icon'       => 'fa-circle-xmark text-danger'
            ];
            $page->paid = '0 تومان';
            $page->card = 'پرداخت با کارت نامعتبر';
        }

        if ($tx->subStatus === 'invalid_card_half') {
            $page->transactionRows[] = (object)[
                'rightValue' => $tx->discount,
                'rightLabel' => 'برداشت از کیف پول',
                'leftValue'  => $tx->trackingCode,
                'leftLabel'  => 'کد رهگیری',
                'icon'       => 'fa-circle-check text-green'
            ];
            $page->transactionRows[] = (object)[
                'rightValue' => 'پرداخت با کارت نامعتبر',
                'rightLabel' => 'پرداخت از حساب بانکی',
                'leftValue'  => '',
                'leftLabel'  => '',
                'icon'       => 'fa-circle-xmark text-danger'
            ];
            $page->paid = $tx->discount;
            $page->card = 'پرداخت با کارت نامعتبر';
        }

        // -------------------------
        // حالت جدید: نصف کیف پول، نصف تکمیل نشده
        // -------------------------
        if ($tx->subStatus === 'half_wallet_half_pending') {
            $page->transactionRows[] = (object)[
                'rightValue' => $tx->discount,
                'rightLabel' => 'برداشت از کیف پول',
                'leftValue'  => $tx->trackingCode,
                'leftLabel'  => 'کد رهگیری',
                'icon'       => 'fa-circle-check text-green'
            ];
            $page->transactionRows[] = (object)[
                'rightValue' => 'پرداخت تکمیل نشده',
                'rightLabel' => 'پرداخت از حساب بانکی',
                'leftValue'  => '',
                'leftLabel'  => '',
                'icon'       => ''
            ];
            // IP, card, paid به جای نمایش => پیام "موردی برای نمایش وجود دارد"
            $page->ip   = 'موردی برای نمایش وجود ندارد';
            $page->card = 'موردی برای نمایش وجود ندارد';
            $page->paid = 'موردی برای نمایش وجود ندارد';
        }
    }
    // -------------------------
    // Order Info
    // -------------------------
    $page->Order = [
        'ID' => $SelectedUserID,
        'status' => $tx->status,
        'statusColor' => getStatusColor($tx->status),
        'amount' => '12 USDT',
        'currency' => 'ترون (TRC20)',
        'price' => '۵۰۰,۰۰۰ تومان',
        'date' => $page->date,
        'time' => $page->time,
        'img' => '../../assets/img/usdt.png'
    ];

    return [
        'content' => biiq_Template::Start(
            'orders->default',
            true,
            ['Objects' => $page, 'Order' => $page->Order]
        ),
        'title' => 'مشاهده سفارش'
    ];
}
