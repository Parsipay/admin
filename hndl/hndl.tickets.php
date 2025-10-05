<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // تاریخ‌ها
    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => date("Y/m/d"),
    ];

    // تابع کمکی برای تولید آیکن وضعیت
    $getStatusIcon = fn($status) =>
        "<i class='fa-solid fa-circle " . ($status === 'online' ? 'text-success' : 'text-danger') . " fa-xs'></i>";

    // اطلاعات کاربران
    $users = [
        [
            'name'   => "یگانه علیزاده",
            'status' => "online",
            'level'  => "کاربر طلایی",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "کیف پولم شارژ نمیشه آیا مشکلی وجود داره؟"
        ],
        [
            'name'   => "بنفشه ابراهیمی",
            'status' => "offline",
            'level'  => "کاربر طلایی",
            'img'    => '../../assets/img/banafshe.png',
            'msg'    => "چرا جواب نمیدین؟؟"
        ],
        [
            'name'   => "نسا ارشدی",
            'status' => "online",
            'level'  => "کاربر معمولی",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "سلام خوبی؟"
        ],
    ];

    // اضافه کردن آیکن وضعیت به هر کاربر
    foreach ($users as $key => $user) {
        $users[$key]['statusIcon'] = $getStatusIcon($user['status']);
    }

    $page->users = $users;

    // خروجی نهایی
    return [
        'content'   => biiq_Template::Start('tickets->index', true, [
            'Objects'     => $page,
            'dateandtime' => $page->dateandtime
        ]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'tickets/'
    ];
}
