<?php
class biiq_Engine{
    public 
        $Setting        = [],
        $PageSettings   = [];
    public function __construct(){
        $persian_date = $user = $error = $get_req = $post_req = $uri_req = $uri_req_exploded = $settings = null;
        global $get_req, $post_req, $uri_req, $uri_req_exploded, $error, $user, $persian_date, $settings;
        $GLOBALS['error'] = new biiq_Errors();
        $this->CheckRequest();
        $this->LoadSettings();
        new biiq_handler();
    }
    private function CheckRequest(){
        $GLOBALS['get_req']  = (!empty($_GET)?$this->FixUserInput($_GET):null);
        $GLOBALS['post_req'] = (!empty($_POST)?$this->FixUserInput($_POST):null);
        if(isset($GLOBALS['post_req']['captcha'])){
            $GLOBALS['post_req']['captcha'] = ConvertToEnglishNumber($GLOBALS['post_req']['captcha']);
        }
        if(isset($GLOBALS['post_req']['mobile'])){
            $GLOBALS['post_req']['mobile'] = ConvertToEnglishNumber($GLOBALS['post_req']['mobile']);
        }
        if(isset($GLOBALS['get_req']['view_file'])){
            $GLOBALS['get_req']['view_file'] = str_replace(".php", "", $GLOBALS['get_req']['view_file']);
            $GLOBALS['get_req']['view_file'] = str_replace("../", "", $GLOBALS['get_req']['view_file']);
        }
        if(isset($GLOBALS['get_req']['request']) && $GLOBALS['get_req']['request'] != ''){
            $GLOBALS['uri_req'] = $GLOBALS['get_req']['request'];
            $GLOBALS['uri_req_exploded'] = array_filter(explode('/',$GLOBALS['get_req']['request']),'strlen');
        }else{
            $GLOBALS['uri_req'] = '';
            $GLOBALS['uri_req_exploded'] = '';
        }
    }
    private function FixUserInput($input = array()){
        $output = array();
        //error_log(print_r($input, true));
        if(is_array($input)){
            foreach($input as $var => $val){
                if(strpos($var, "view_file") > 0){
                    error_log(print_r($input, true));
                    $output[$var] = '';
                }
                if($var == 'html'){
                    $output[$var] = $val;
                    continue;
                }
                $output[$var] = $this->FixUserInput($val);
            }
        }else{
            $input = str_replace(".php", "", $input);
            $input = stripslashes($input);
            $search = array(
                '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
                '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                //'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
            );
            $output = preg_replace($search, '', $input);
            //$output = $GLOBALS['db']->Real_Escape_String($input);//Since we are using PDO, No Real Escape String needed.
        }
        return $output;
    }
    private function LoadCookieName(){
        $site = DOMAIN;
        $site = explode('.',$site);
        if(is_array($site) && count($site)>0){
            define('COOKIE', $site[0]);
            return;
        }
        define('COOKIE', $site);
    }
    private function LoadSettings(){
        $this->LoadCookieName();
        define('TIME_ZONE', 'Asia/Tehran');
        define('CHARSET', 'utf8');
        date_default_timezone_set('Asia/Tehran');
        if(defined('TIME_ZONE') && TIME_ZONE != ''){
            date_default_timezone_set(TIME_ZONE);
        }
        define('LOGO', ASSETS_PATH.'icons/logo.png');
        $d = new biiq_PersianDate();
        $d = ConvertToPersianNumber($d->Today());
        $global_settigns = array(
            'img'           => LOGO,
            'logo'          => LOGO,
            'url'           => SITE,
            'site'          => SITE,
            'domain'        => DOMAIN,
            'assets_path'   => ASSETS_PATH,
            'version'       => APPLICATION_VERSION,
            'date_time'     => $d,
        );
        foreach($global_settigns as $key => $value){
            $this->Setting[$key] = $value;
        }
        
        $this->PageSettings = array(
            'layout' => 'main',
            'Canonical' => 0,
            'HasGoogleCaptcha' => 0,
            'navlink'       => 0
        );
        $GLOBALS['config']  = $this;
    }
    public static function LoadDatabase(){
        //$GLOBALS['db'] = biiq_DB::GetDB();
    }
    public static function OutputBuffer_PostProcessor($buffer){
        return Trimmer($buffer);
    }


    // Helpers
    public static function maskCard($num) {// this function should move to a library that is about Cards.
        $len = strlen($num);
        if ($len <= 10) return str_repeat("*", $len); // fallback for very short numbers
        return substr($num, 0, 6) . str_repeat("*", $len - 10) . substr($num, -4);
    }
}

?>