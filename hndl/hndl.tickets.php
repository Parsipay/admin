<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // تاریخ‌ها
    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => date("Y/m/d"),
    ];

    // تابع آیکن وضعیت (آنلاین یا آفلاین)
    $getStatusIcon = fn($status) =>
        "<i class='fa-solid fa-circle " . ($status === 'online' ? 'text-success' : 'text-danger') . " fa-xs'></i>";

    // آرایه کاربران
    $users = [
        // پیام‌های مختلف
        [
            'name'   => "یگانه علیزاده",
            'status' => "online",
            'level'  => "کاربر طلایی",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "کیف پولم شارژ نمیشه آیا مشکلی وجود داره؟",
            'isRead' => false,
            'statusType' => 'pending' // در حال پیگیری
        ],
        [
            'name'   => "بنفشه ابراهیمی",
            'status' => "offline",
            'level'  => "کاربر طلایی",
            'img'    => '../../assets/img/banafshe.png',
            'msg'    => "چرا جواب نمیدین؟؟",
            'isRead' => true,
            'statusType' => 'done' // تکمیل شده
        ],
        [
            'name'   => "نسا ارشدی",
            'status' => "online",
            'level'  => "کاربر معمولی",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "سلام خوبی؟",
            'isRead' => false,
            'statusType' => 'new' // تازه ارسال شده
        ],
        [
            'name'   => "مریم ماهور",
            'status' => "offline",
            'level'  => "کاربر معمولی",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "مشکل پرداخت دارم ",
            'isRead' => true,
            'statusType' => 'done' // تکمیل شده
        ],
        [
            'name'   => "صدف طاهریان",
            'status' => "offline",
            'level'  => "کاربر معمولی",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "مشکل پرداخت حل نشده",
            'isRead' => false,
            'statusType' => 'new' // خوانده نشده
        ],
        [
            'name'   => "مهسا نیک‌فرجام",
            'status' => "online",
            'level'  => "کاربر ویژه",
            'img'    => '../../assets/img/yegane.png',
            'msg'    => "لطفاً بررسی کنین تراکنش من انجام نشده",
            'isRead' => false,
            'statusType' => 'new' // خوانده نشده
        ],
    ];

    // اضافه کردن آیکن وضعیت و statusLabel
    foreach ($users as $key => $user) {
        $users[$key]['statusIcon'] = $getStatusIcon($user['status']);

        // تعیین متن و رنگ وضعیت
        if ($user['statusType'] == 'done') {
            $users[$key]['statusLabel'] = "<span class='text-success fw-bold'>تکمیل شده</span>";
        } elseif ($user['statusType'] == 'pending') {
            $users[$key]['statusLabel'] = "<span class='text-warning fw-bold'>در حال پیگیری</span>";
        } else {
            // پیام‌های تازه یا خوانده نشده
            $users[$key]['statusLabel'] = "<span>جمعه 24 اسفند ماه 1403 | ساعت 18:21</span>";
        }
    }

    $page->users = $users;

    // کاربران خوانده نشده فقط برای تب دوم
    $unreadUsers = array_filter($users, fn($u) => $u['isRead'] === false && $u['statusType'] === 'new');
    $page->unreadUsers = array_values($unreadUsers);

    // کاربران در حال پیگیری فقط برای تب سوم
    $pendingUsers = array_filter($users, fn($u) => $u['statusType'] === 'pending');
    $page->pendingUsers = array_values($pendingUsers);

    // کاربران تکمیل شده فقط برای تب چهارم
    $doneUsers = array_filter($users, fn($u) => $u['statusType'] === 'done');
    $page->doneUsers = array_values($doneUsers);

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
