<?php
function ProcessRequest($request)
{
    $page = new stdClass();

    if (!isset($request->Parameters) || !is_array($request->Parameters) || count($request->Parameters) == 0) {
        $GLOBALS['error']->Show(401);
        exit;
    }

    $SelectedUserID = $request->Parameters[0];
    if (!is_numeric($SelectedUserID) || $SelectedUserID == 0) {
        $GLOBALS['error']->Show(401);
        exit;
    }

    // فرمت اعداد با جداکننده هزارگان
    function separateThousands($number)
    {
        return number_format((int)$number);
    }

    // زمان نسبی
    function timeAgo($timestamp)
    {
        $diff = time() - $timestamp;
        $minutes = floor($diff / 60);
        $hours   = floor($diff / 3600);
        $days    = floor($diff / 86400);
        $months  = floor($diff / 2592000);
        $years   = floor($diff / 31104000);

        if ($years > 0) return $years . " سال پیش";
        if ($months > 0) return $months . " ماه پیش";
        if ($days > 0) return $days . " روز پیش";
        if ($hours > 0) return $hours . " ساعت پیش";
        if ($minutes > 0) return $minutes . " دقیقه پیش";
        return "لحظاتی پیش";
    }

    $today = new DateTime();
    $today->modify('+1 hour');

    $page->dateandtime = [
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate'   => $today->format("Y/m/d"),
        'time'        => $today->format("H:i")
    ];

    // تابع کمکی برای ادغام همه فور ایچ‌ها
    function applyFormatting(&$items, $type)
    {
        foreach ($items as &$item) {
            switch ($type) {
                case 'orderList':
                    if (isset($item["numberOrder"])) {
                        if (strpos($item["numberOrder"], 'خرید') !== false)
                            $item["numberOrder"] = str_replace(
                                "<span class='px-1 rounded-3'>خرید</span>",
                                "<span class='px-1 rounded-3 text-success opacity-green'>خرید</span>",
                                $item["numberOrder"]
                            );
                        elseif (strpos($item["numberOrder"], 'فروش') !== false)
                            $item["numberOrder"] = str_replace(
                                "<span class='px-1 rounded-3'>فروش</span>",
                                "<span class='px-1 rounded-3 text-danger bg-red'>فروش</span>",
                                $item["numberOrder"]
                            );
                    }
                    $status = trim($item["Status"] ?? '');
                    if ($status === "موفق") $item["StatusColor"] = "text-success opacity-green";
                    elseif ($status === "در انتظار تایید") $item["StatusColor"] = "text-primary bg-blue";
                    else $item["StatusColor"] = "text-danger opacity-danger text-decoration-none";
                    break;

                case 'transactions':
                    $item["price"] = $item["price"] < 0
                        ? "<b class='text-danger'>" . separateThousands($item["price"]) . "</b>"
                        : "<b class='text-green'>" . separateThousands($item["price"]) . "</b>";
                    $item["persianDate"] = "<div class='fw-bold'>" . timeAgo($item["UnixTimestamp"]) . "</div>" .
                                           "<div class='mt-2'>" . biiq_PersianDate::date("l j F Y - H:i", $item["UnixTimestamp"]) . "</div>";
                    $item["akharin"] = timeAgo($item["lastActivityTimestamp"]);
                    $item["PersianDateRelative"] = timeAgo($item["UnixTimestamp"]);
                    break;

                case 'accountInfo':
                    $status = trim($item["details"] ?? '');
                    $item["StatusColor"] = ($status === "تایید شده") ? "text-success opacity-green" : "text-danger bg-red";
                    break;

                case 'identityDocuments':
                    $item['persianDate'] = timeAgo($item['UnixTimestamp']);
                    $status = trim($item["status"] ?? '');
                    if (strpos($status, "رد شده") !== false) $item["StatusColor"] = "text-red bg-red";
                    elseif (strpos($status, "تایید شده") !== false) $item["StatusColor"] = "text-green opacity-green";
                    elseif (strpos($status, "در انتظار تایید") !== false) $item["StatusColor"] = "text-warning bg-opacity-warning";
                    else $item["StatusColor"] = "text-secondary bg-light";
                    break;

                case 'userAuthentication':
                    $extraReq = trim($item["extraReq"] ?? '');
                    if (strpos($extraReq, "تسویه شده") !== false) $item["extraReqcolor"] = "bg-blue";
                    elseif (strpos($extraReq, "لغو شده") !== false) $item["extraReqcolor"] = "opacity-danger";
                    elseif (strpos($extraReq, "افزایش اعتبار") !== false) $item["extraReqcolor"] = "opacity-green";
                    else $item["extraReqcolor"] = "bg-secondary";
                    break;
            }
        }
        unset($item);
    }

    // ======== داده‌ها ========
    $page->profileBox = [
        "registrationStatus" => "تایید شده",
        "authenticationStatus" => "در انتظار تایید",
        "maximumPurchase" => "100 میلیون تومان",
        "buyToday" => " 58،256،000 تومان",
        "timeLeft" => "14 دقیقه پیش"
    ];

    $page->addresslist = [
        ["address"=>"TBLdjcbXLozzqp6YYvH6Z9HuFwCbzeKbFP","network"=>"TRC20","dateandtime"=>biiq_PersianDate::date("l j F Y - H:i",1266578),"desciption"=>"۲۰۲۳۲۷۳۸۰۳۰"],
        ["address"=>"TBLdjcbXLozzqp6YYvH6Z9HuFwCbzeKbFP","network"=>"TRC20","dateandtime"=>biiq_PersianDate::date("l j F Y - H:i",1266578),"desciption"=>"۲۰۲۳۲۷۳۸۰۳۰"]
    ];

    $page->orderList = [
        ['ID'=>20232336263,"OrderDetails"=>'<div class="d-flex justify-content-start"><img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;"><div class="d-flex flex-column"><span>19788</span><span>USDT (تتر)</span></div></div>',"UserID"=>16,"price"=>separateThousands(445609806),"UnixTimestamp"=>time()-(14*86400),"lastActivityTimestamp"=>time()-(1*86400),"PersianDate"=>biiq_PersianDate::date("l j F Y - H:i",126545878),"Status"=>"موفق","numberOrder"=>"20232336263# <span class='px-1 rounded-3'>فروش</span>"],
        ['ID'=>12312313123,"OrderDetails"=>'<div class="d-flex justify-content-start"><img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;"><div class="d-flex flex-column"><span>19788</span><span>USDT (تتر)</span></div></div>',"UserID"=>16,"price"=>separateThousands(445609806),"UnixTimestamp"=>time()-(5*30*86400),"lastActivityTimestamp"=>time()-(23*86400),"PersianDate"=>biiq_PersianDate::date("l j F Y - H:i",time()-(2*2*86400)),"Status"=>"در انتظار تایید","numberOrder"=>"12312313123# <span class='px-1 rounded-3'>خرید</span>"],
        ['ID'=>65988978754,"OrderDetails"=>'<div class="d-flex justify-content-start"><img src="../../assets/img/usdt.png" alt="btc" class="me-2" style="width:24px;height:24px;"><div class="d-flex flex-column"><span>19788</span><span>USDT (تتر)</span></div></div>',"UserID"=>16,"price"=>separateThousands(445609806),"UnixTimestamp"=>time()-(5*30*86400),"lastActivityTimestamp"=>time()-(23*86400),"PersianDate"=>biiq_PersianDate::date("l j F Y - H:i",time()-(15*2*86400)),"Status"=>"نا موفق","numberOrder"=>"20232336263# <span class='px-1 rounded-3'>فروش</span>"],
    ];

    // مرتب‌سازی سفارش‌ها
    usort($page->orderList, fn($a,$b)=>$b["UnixTimestamp"]<=>$a["UnixTimestamp"]);
    applyFormatting($page->orderList,'orderList');

    $page->transactions = [
        ["request"=>'<span>154852#</span><span class="bg-blue text-primary ms-2 border border-primary p-1 rounded">درخواست تسویه - تسویه شده {IR360560611828005651602601}</span>',"UserID"=>19,"price"=>-445609806,"UnixTimestamp"=>time()-(14*86400),"lastActivityTimestamp"=>time()-(1*86400),"PersianDate"=>biiq_PersianDate::date("l j F Y - H:i",126545878),"description"=>"<span class='text-primary'>مشاهده رسید <span>"],
        ["request"=>'<span>154852#</span><span class="bg-red text-danger ms-2 border border-danger p-1 rounded">درخواست تسویه - تسویه شده {IR360560611828005651602601}</span>',"UserID"=>19,"price"=>445609806,"UnixTimestamp"=>time()-(45*86400),"lastActivityTimestamp"=>time()-(2*86400),"PersianDate"=>biiq_PersianDate::date("l j F Y - H:i",568753525),"description"=>"<span class='text-primary'>۲۰۲۳۲۷۳۸۰۳۰</span>"],
        ["request"=>'<span>154852#</span><span class="bg-opacity-green text-green ms-2 border border-success p-1 rounded">درخواست تسویه - تسویه شده {IR360560611828005651602601}</span>',"UserID"=>19,"price"=>-445609806,"UnixTimestamp"=>time()-(5*12*86400),"lastActivityTimestamp"=>time()-(14*86400),"PersianDate"=>biiq_PersianDate::date("l j F Y - H:i",896554121),"description"=>"<span class='text-'>1404041100032259426803</span>"]
    ];
    usort($page->transactions, fn($a,$b)=>$b["UnixTimestamp"]<=>$a["UnixTimestamp"]);
    applyFormatting($page->transactions,'transactions');

    // حساب‌ها
    $page->accountInfo = [
        ["id"=>"1","shebaNumber"=>"IR940150000184370199152881","cartNumber"=>"6273811170944968","UserID"=>22,"bank"=>"/assets/img/blu.png","UnixTimestamp"=>9999999999,"details"=>"تایید شده"],
        ["id"=>"2","shebaNumber"=>"IR940150000184370199152881","cartNumber"=>"6273811170944968","UserID"=>22,"bank"=>"/assets/img/ansar.png","UnixTimestamp"=>9999999999,"persianDate"=>biiq_PersianDate::date("l j F Y - H:i",88888888),"details"=>"رد شده"],
        ["id"=>"3","shebaNumber"=>"IR940150000184370199152881","cartNumber"=>"6273811170944968","UserID"=>22,"bank"=>"/assets/img/dey.png","UnixTimestamp"=>8888888888,"persianDate"=>biiq_PersianDate::date("l j F Y - H:i",88888888),"details"=>"تایید شده"]
    ];
    applyFormatting($page->accountInfo,'accountInfo');

    // مدارک هویتی
    $page->identityDocuments = [
        ["description"=>"<i class='fas fa-times-circle text-red me-1'></i> تصویر احراز هویت خود را با کیفیت بالاتری ارسال نمایید.","UserID"=>22,"UnixTimestamp"=>time()-600,"status"=>"در انتظار تایید","details"=>"  مشاهده مدارک"],
        ["description"=>"<i class='fas fa-exclamation-circle text-warning me-1'></i> تصویر احراز هویت خود را با کیفیت بالاتری ارسال نمایید.","UserID"=>22,"UnixTimestamp"=>time()-3600,"status"=>"تایید شده","details"=>" مشاهده مدارک"],
        ["description"=>"<i class='fas fa-check-circle text-green me-1'></i> تصویر احراز هویت خود را با کیفیت بالاتری ارسال نمایید.","UserID"=>22,"UnixTimestamp"=>time()-300,"status"=>"رد شده","details"=>" مشاهده مدارک"]
    ];
    applyFormatting($page->identityDocuments,'identityDocuments');

    // userAuthentication (مثال: userAuth یا extraReq)
    if(isset($page->userAuthentication)) applyFormatting($page->userAuthentication,'userAuthentication');

    // اطلاعات کاربر فعلی
    $page->CurrentUser = [
        'Status' => "تایید شده",
        'SelfieStatus' => 'ارسال نشده',
        'maximumPurchase' => "صد هزار تومن",
        'buyToday' => " 28،256،000 تومان",
        'lastTime' => "10 دقیقه پیش",
        'phoneNumber' => '09356458975',
        'userNumber' => '56789',
        'introduced' => '  2 نفر ',
        'nationalCode' => '0013152343',
        'GiftCredit' => '500 هزار تومن',
        'birthday' => '1370/06/06',
        'ipAddress' => '4.126.16.37',
        'persianDate' => biiq_PersianDate::date("l j F Y"),
        'otherDate' => date("Y/m/d")
    ];

    $page->Title = "  مدیریت کاربران ";
    $page = array(
        'content' => biiq_Template::Start('manage->default', true, ['Objects' => $page, 'CurrentUser' => $page->CurrentUser, 'dateandtime' => $page->dateandtime]),
        'id' => 0,
        'title' => $page->Title,
        'Canonical' => SITE . 'manage/',
    );

    return $page;
}
