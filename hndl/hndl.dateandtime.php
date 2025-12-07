z<?php
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
    // --- Return page data ---
    return [
        'content'   => biiq_Template::Start('layout->dateandtime', true, ['Objects' => $page]),
        'id'        => 1,
        'title'     => 'date',
        'Canonical' => SITE . 'dateandtime/'
    ];
}
