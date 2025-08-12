<?php
function ProcessRequest($request) {


//function for mask number
function maskCard($num){
return substr($num ,-4). str_repeat("*", strlen($num) - 10) .substr($num , 0  ,6) ;
}

// Defining account cards 
    $cards = [
        ["  یگانه علیزاده", "ملی", "IR820540102680020817909002","5022291077470837"],
        ["  بنفشه  ابراهیمی", "سامان", "IR350170000000000000123456","5894631804760130"],
        ["  نازنین علیزاده", "پاسارگاد", "IR460120000000123456789012","6104337900001234"],
        [" زهرا نوری", "تجارت", "IR780180000000000000987654","6219861000001234"],
    ];

    //defining Authentication documents
    $boxMessages = [
        ["بنفشه ابراهیمی", "1747014000"],
        ["یگانه علیزاده", "1745714000"],
        ["مریم ماهور", "1717136540"]
    ];


    $orders = [
        ["10232336263#", "خرید", "opacity-green", "/assets/img/usdt.png", "197.3499", "USDT  (تتر)", "بنفشه ابراهیمی", "۴۴,۹۳۵,۶۰۰", 1746776838],
        ["20232336263#", "فروش", "opacity-primary", "/assets/img/usdt.png", "197.3499", "USDT  (تتر)", "یگانه علیزاده", "۴۴,۹۳۵,۶۰۰", 1718199090],
        ["30232336263#", "خرید", "bg-light", "/assets/img/usdt.png", "197.3499", "USDT  (تتر)", "میلاد ابراهیم نژاد چماچائی", "۴۴,۹۳۵,۶۰۰", 1747134948],
        ["50232336263#", "خرید", "opacity-red", "/assets/img/tron.png", "197.3499", "USDT  (تتر)", "میلاد ابراهیم نژاد چماچائی", "۴۴,۹۳۵,۶۰۰", 1747114948],
        ["20232336263#", "خرید", "opacity-primary", "/assets/img/tron.png", "82.53279772", "TRX  (ترون)", "سجاد طاوسی سرحوضکی", "۴۴,۹۳۵,۶۰۰", 1717134948],
    ];

    $users = [
        ["0440202507", "یگانه علیزاده", "09353353167", "1747140621", "5", "1741837446 ", "تایید شده", "text-success"],
        ["0340202507", "یگانه علیزاده", "093128431937", "1747139421","5", "1736739846", " در انتظار بررسی", "text-info"],
        ["0540202507", "یگانه علیزاده", "09356439532", "1747125021","5", "1746956733", "رد شده", "text-warning"],
        ["3440202507", "یگانه علیزاده", "09351751766", "1747114221","5", "1746265533", "تایید شده", "text-primary"],
        ["1440202507", "یگانه علیزاده", "09353353897", "17471078467","5", "1746092733", "در انتظار بررسی", "text-danger"],
    ];

    $p = new stdClass();
    $p->Cards = [];
     foreach ($cards as [$name, $bank, $shaba, $cardNumber]) {
    $obj = new stdClass();
    $obj->UserName = $name;
    $obj->BankName = $bank;
    $obj->Shaba = maskCard($shaba);
    $obj->MaskedCard = maskCard($cardNumber);
    $p->Cards[] = $obj;
    }

     $p->box = [];
     foreach ($boxMessages as [$user, $time]) {
        $msg = new stdClass();
        $msg->userName = $user;
        $msg->timeToSend = biiq_PersianDate::UnixToAgo($time);
        $p->box[] = $msg;
    }

    $p->list = [];
    foreach ($orders as [$number, $type, $color, $img, $amount, $currency, $fullname, $price, $unix, $statusColor]) {
        if(
            $type ==="خرید"
        ){
            $colorClass = 'text-success';
            $colorType = 'opacity-green';
                $colorPrice ='text-success';
               
        }elseif($type ==="فروش"){
            $colorClass = "text-danger";
            $colorType = 'opacity-danger';
            $colorPrice ='text-danger';
           
        }else{
            $colorClass ="text-info";
            $colorType ="opacity-info";
                $colorPrice ='text-info';
               
        }

        $order = new stdClass();
        $order->orderNumber = $number;
        $order->type = "<span class=\" $colorType border px-2 ms-1 rounded-4\">$type</span>";
        $order->img = $img;
        $order->orderDetails ="<span class='d-flex justify-content-start align-items-center $colorClass fw-bold'> $amount</span>";
        $order->currencyName = $currency;
        $order->userFullName ="<span class='d-flex justify-content-start align-items-center'> $fullname</span>";
        $order->price ="<span class='$colorPrice'> $price</span>";
        $order->dateandTime = biiq_PersianDate::UnixToAgo($unix);
        $order->Status = 1;
		$order->StatusText = "<span><i class=\"fas fa-check-circle $statusColor\"></i></span>";
        $p->list[] = $order;
    }


    $p->allUser = [];
    foreach ($users as [$code, $name, $phone, $lastseen,$banks, $sabtename, $ehraz, $vaziat]) {
    $user = new stdClass();
    $user->nationalCode = $code;
    $user->fullName = $name;
    $user->phoneNumber = $phone;
    $user->lastActivity =  biiq_PersianDate::UnixToAgo($lastseen);
    $user->bankAccounts = $banks;
    $user->signupTime = biiq_PersianDate::UnixToAgo($sabtename);
	
    $ehraz = trim($ehraz);
    $circle = "";
    switch ($ehraz) {
        case "تایید شده":
			$user->Status = 2;
            
            break;
        case "رد شده":
			$user->Status = 3;
            
            break;
        case "در انتظار بررسی":
			$user->Status = 1;
            
            break;
    }
    
    
    $p->allUser[] = $user;
}


    $page = array(
        'content' => biiq_Template::Start('pages->index', true, ['Objects' => $p]),
        'id' => 0,
        'title' => 'صفحه اصلی',
        'Canonical' => SITE,
    );
    return $page;
}

?>
