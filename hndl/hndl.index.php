<?php
function ProcessRequest($request)
{
    $p = new stdClass();

    // 💰 Currency cards list
    $p->cards = [
        [
            'name' => 'شیبا (SHIBA INU)',
            'image' => '../../assets/img/shiba.png',
            'price' => '0.00001',
            'currencySymbol' => '$',
            'trend' => 'up',          // up or down
            'percent' => '3%',
            'buy' => '0.0001$',
            'sell' => '0.0001$',
            'showDetailBoxes' => true // display buy/sell boxes
        ],
        [
            'name' => 'تتر (Tether)',
            'image' => '../../assets/img/usdt.png',
            'price' => '1.00',
            'currencySymbol' => '$',
            'trend' => '',            // no trend icon or percentage
            'percent' => '',
            'showDetailBoxes' => false
        ],
        [
            'name' => 'ترون (TRX)',
            'image' => '../../assets/img/tron.png',
            'price' => '0.0984',
            'currencySymbol' => '$',
            'trend' => 'up',
            'percent' => '3%',
            'showDetailBoxes' => false
        ],
    ];

    // 🔧 Return page data
    return [
        'content'   => biiq_Template::Start('pages->index', true, ['page' => $p]),
        'id'        => 0,
        'title'     => 'صفحه ورود',
        'Canonical' => SITE,
    ];
}
?>
