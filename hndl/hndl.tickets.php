<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    // --- Dates and time ---
    $today = new DateTime();
    $today->modify('+1 hour'); // Add one hour to current time

    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    // --- Status icon helper ---
    $getStatusIcon = fn($status) =>
        "<i class='fa-solid fa-circle " . ($status === 'online' ? 'text-success' : 'text-danger') . " fa-xs'></i>";

    // --- Users data ---
    $users = [
        [
            'name'        => "یگانه علیزاده",
            'status'      => "online",
            'level'       => "کاربر طلایی",
            'img'         => '../../assets/img/yegane.png',
            'msg'         => "کیف پولم شارژ نمیشه آیا مشکلی وجود داره؟",
            'isRead'      => false,
            'statusType'  => 'pending'
        ],
        [
            'name'        => "بنفشه ابراهیمی",
            'status'      => "offline",
            'level'       => "کاربر طلایی",
            'img'         => '../../assets/img/banafshe.png',
            'msg'         => "چرا جواب نمیدین؟؟",
            'isRead'      => true,
            'statusType'  => 'done'
        ],
        [
            'name'        => "نسا ارشدی",
            'status'      => "online",
            'level'       => "کاربر معمولی",
            'img'         => '../../assets/img/yegane.png',
            'msg'         => "سلام خوبی؟",
            'isRead'      => false,
            'statusType'  => 'new'
        ],
        [
            'name'        => "مریم ماهور",
            'status'      => "offline",
            'level'       => "کاربر معمولی",
            'img'         => '../../assets/img/yegane.png',
            'msg'         => "مشکل پرداخت دارم ",
            'isRead'      => true,
            'statusType'  => 'done'
        ],
        [
            'name'        => "صدف طاهریان",
            'status'      => "offline",
            'level'       => "کاربر معمولی",
            'img'         => '../../assets/img/yegane.png',
            'msg'         => "مشکل پرداخت حل نشده",
            'isRead'      => false,
            'statusType'  => 'new'
        ],
        [
            'name'        => "مهسا نیک‌فرجام",
            'status'      => "online",
            'level'       => "کاربر ویژه",
            'img'         => '../../assets/img/yegane.png',
            'msg'         => "لطفاً بررسی کنین تراکنش من انجام نشده",
            'isRead'      => false,
            'statusType'  => 'new'
        ],
    ];

    // --- Add icons and labels ---
    foreach ($users as &$user) {
        $user['statusIcon'] = $getStatusIcon($user['status']);

        $user['statusLabel'] = match ($user['statusType']) {
            'done'    => "<span class='text-success fw-bold'>تکمیل شده</span>",
            'pending' => "<span class='text-warning fw-bold'>در حال پیگیری</span>",
            default   => "<span>جمعه 24 اسفند ماه 1403 | ساعت 18:21</span>"
        };
    }

    $page->users = $users;

    // --- Filter users by status ---
    $page->unreadUsers  = array_values(array_filter($users, fn($u) => !$u['isRead'] && $u['statusType'] === 'new'));
    $page->pendingUsers = array_values(array_filter($users, fn($u) => $u['statusType'] === 'pending'));
    $page->doneUsers    = array_values(array_filter($users, fn($u) => $u['statusType'] === 'done'));

    // --- Final output ---
    return [
        'content'   => biiq_Template::Start('tickets->index', true, [
            'Objects'     => $page,
            'dateandtime' => $page->dateandtime
        ]),
        'id'        => 1,
        'title'     => 'مالی',
        'Canonical' => SITE . 'tickets/',
        'navlink' => 5
    ];
}
