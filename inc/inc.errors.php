<?php
//namespace biiq\error;
class biiq_Errors{
    public function Show($error_code = 404, $msg = ''){
        if($error_code == 401){
            header("Location: ".SITE."login/", true, 302);
            exit;
        }
        $PageContent = new stdClass();
        $PageContent->ErrorCode = $error_code;
        $Status_Reason = array(
            200  => "OK",
            400  => "Bad Request",
            401  => "Unauthorized",
            403  => "Forbidden",
            404  => "Not Found",
            500  => "Internal Server Error",
            503  => "Service Unavailable",
            1000 => "Database Error"
        );
        $Status_Message = array(
            200  =>  "سایت درحال توسعه میباشد، لطفا شکیبا باشید.",
            400  =>  "Your browser sent a request that we could not understand.<br>Try this link: <a href='%site%' title='%title%'>home page</a>",
            401  =>  "لطفا قبل از ارسال درخواست به شبکه، وارد حساب کاربری خود شوید. <a href='%site%login/' title='%title%'>ورود به حساب کاربری</a>",
            403  =>  "شما اجازه دسترسی به این صفحه را ندارید.<br>لطفا به صفحه اصلی بازگردید.",
            404  =>  "متاسفانه، صفحه مورد نظر پیدا نشد!<br>لطفا به صفحه اصلی بازگردید",
            500  =>  "سرور به یک مشکل داخلی برخورد کرده است، <br>بنظر می رسد قادر به تکمیل درخواست شما نیست.",
            503  =>  "سرور در حال بروزرسانی می باشد. لطفا چند دقیقه بعد مجددا امتحان بفرمایید.",
            1000 =>  "خطایی در سیستم رخ داده است.<br>لطفا به صفحه اصلی بازگردید"
        );
        if($PageContent->ErrorCode == 200){
            if(isset($_SERVER['REDIRECT_STATUS']) && ($_SERVER['REDIRECT_STATUS'] != 200)){
                $PageContent->ErrorCode = $_SERVER['REDIRECT_STATUS'];
            }elseif(isset($_SERVER['REDIRECT_REDIRECT_STATUS']) && ($_SERVER['REDIRECT_REDIRECT_STATUS'] != 200)){
                $PageContent->ErrorCode = $_SERVER['REDIRECT_REDIRECT_STATUS'];
            }
        }
        $PageContent->ErrorCode = abs(intval($PageContent->ErrorCode));
        if(!array_key_exists($PageContent->ErrorCode, $Status_Reason) || !array_key_exists($PageContent->ErrorCode, $Status_Message)){
            $PageContent->ErrorCode = 200;
        }
        $PageContent->Paragraph = str_replace("%site%", 'https://new.parsipay.org/', $Status_Message[$PageContent->ErrorCode]);
        $PageContent->Paragraph = str_replace("%title%", 'پارسی پی', $PageContent->Paragraph);
        if(strlen($msg) > 0){
            $PageContent->Paragraph = $msg;
        }
        
        $PageContent->H1 = $Status_Reason[$PageContent->ErrorCode];
        $PageContent->Title = $PageContent->ErrorCode.' '.$Status_Reason[$PageContent->ErrorCode];
        if($PageContent->ErrorCode == 200){
            $PageContent->H1 = 'Unknown Error';
        }
        if(class_exists('biiq_Engine', false)){
            ob_start(array('biiq_Engine', 'OutputBuffer_PostProcessor'));
        }else{
            ob_start();
        }
        header($_SERVER['SERVER_PROTOCOL']." ".$PageContent->ErrorCode." ".$Status_Reason[$PageContent->ErrorCode], true, $PageContent->ErrorCode);
        if(class_exists('biiq_Template')){
            echo biiq_Template::Start('error->index', true, array('Content' => $PageContent));
        }else{
            echo $PageContent->Paragraph;
        }
        
        define('EndTime', microtime(true));
        $ms = (float)EndTime - (float)StartTime;
        $ms = round($ms,4);
        echo '<!-- '.$ms.' -->';
        ob_end_flush();
        exit;
    }
}
?>