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


    // === Return Template Data ===
    return [
        'content'   => biiq_Template::Start('settings->index', true, [
            'Objects'     => $page,
            'dateandtime' => $page->dateandtime,
            'transaction' => $page->transaction
        ]),
        'id'        => 1,
        'title'     => ' تنظیمات',
        'Canonical' => SITE . 'settings/'
    ];
}
