<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class biiq_Mail{
    public
        $To = '', $From = '',
        $WebSite = '',
        $Title = 'ایمیل ای از طرف Parsipay',
        $Buddy = '',
        $HTML = '',
        $Unsubscribe_link = '',
        $SenderName = 'Parsipay',
        $PHPMailer = null;
    private 
        $accounts = array(
            ['From' => 'info@parsipay.org', 'Password' => 'bnKfP8~', 'Host' => '127.0.0.1'],
            // ['From' => 'no-reply@parsipay.org',     'Password' => 'Hz#d<]7"5R*>/jS3', 'Host' => '88.99.148.34'],
            // ['From' => 'no-reply@md1.parsipay.org', 'Password' => 'Hz#d<]7"5R*>/jS3', 'Host' => '88.99.148.34'],
            // ['From' => 'no-reply@md2.parsipay.org', 'Password' => 'Hz#d<]7"5R*>/jS3', 'Host' => '88.99.148.34'],
            // ['From' => 'no-reply@md3.parsipay.org', 'Password' => 'Hz#d<]7"5R*>/jS3', 'Host' => '88.99.148.34'],
        );
    function __construct($title = "ایمیل ای از طرف Parsipay", $dialog = '', $to = '', $receiver_name = 'کاربر پارسی پی'){
        //return false;
        try{
            $path_to_phpmailer = biiq_PATH.'ext.libs'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR;
            require_once($path_to_phpmailer.'Exception.php');
            require_once($path_to_phpmailer.'PHPMailer.php');
            require_once($path_to_phpmailer.'SMTP.php');
            $next_acc = 0;
            
            $this->Title = $title;
            $this->To = $to;
            $this->Buddy = $dialog;
            $this->Unsubscribe_link = SITE."manage-subscribe/?e={$this->To}";
            $this->HTML = biiq_Template::Start('mail->main', true, array('Email' => $this));
            $this->PHPMailer = new PHPMailer;
            $this->PHPMailer->CharSet = PHPMailer::CHARSET_UTF8;
            $this->PHPMailer->isSMTP();
            $this->PHPMailer->SMTPDebug = SMTP::DEBUG_OFF;
            $this->PHPMailer->Host = $this->accounts[$next_acc]['Host'];
            //Set the SMTP port number - likely to be 25, 465 or 587
            $this->PHPMailer->AddCustomHeader("List-Unsubscribe", "<mailto:unsubscribe@parsipay.org?subject=Unsubscribe>, <{$this->Unsubscribe_link}>");

            $this->PHPMailer->SMTPAutoTLS = true;
            $this->PHPMailer->Port = 25;
            $this->PHPMailer->SMTPAuth = true;
            $this->PHPMailer->Username = $this->accounts[$next_acc]['From'];
            $this->PHPMailer->Password = $this->accounts[$next_acc]['Password'];
            $this->PHPMailer->setFrom($this->accounts[$next_acc]['From'], $this->SenderName);
            $this->PHPMailer->addReplyTo($this->accounts[$next_acc]['From'], $this->SenderName);
            $this->PHPMailer->addAddress($this->To, $receiver_name);
            $this->PHPMailer->Subject = $this->Title;
            $this->PHPMailer->Body = $this->HTML;
            $this->PHPMailer->isHTML(true);
            $this->PHPMailer->AltBody = $this->Title;
            if(!$this->PHPMailer->send()){
                if($this->PHPMailer->ErrorInfo != 'You must provide at least one recipient email address.'){
                    error_log('Mailer Error: '.$this->PHPMailer->ErrorInfo);
                }
                return false;
            }
        }catch(Exception $er){
            error_log(print_r($er, true));
        }
        return true;
    }
    public function Send(){
        return false;
        //$msg = wordwrap($msg, 70);
        if(!isset($this->To)){
            return false;
        }
        $headers  = "MIME-Version: 1.0 \r\n";
        $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "From: ".'=?utf-8?B?'.base64_encode($this->Title).'?='." <$this->From> \r\n";
		$headers .= "Reply-To: ".'=?utf-8?B?'.base64_encode($this->Title).'?='." <$this->From> \r\n";
		$headers .= "Return-Path: $this->From\r\n";
		$headers .= "X-PHP-Script: $this->WebSite\r\n";
		$headers .= "X-Mailer: PHP/".phpversion()."\r\n";
        return mail($this->To, '=?utf-8?B?'.base64_encode($this->Title).'?=', $this->HTML, $headers);
    }
}//K_DN8scvn3X2f8gX
?>