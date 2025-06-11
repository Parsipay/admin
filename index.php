<?php
    /*
    ==============================================================
    biiqEngine Version 3.5 Build July 6 2023
    Copyright biiq 2007-2023
    Development by Shayan Shahidehpour  ❣
    Shayan@Shahidehpour.com  ❣
    https://biiq.ir (｡♥‿♥｡)
    ==============================================================
    */
    function biiqEngine_AutoloadRegister($class_name){
        try{
            $class_name = strtolower(str_replace('biiq_', '', $class_name));
            $filenames = array(
                INC_PATH.'inc.'.$class_name.'.php',
                LIBRARY_PATH.'lib.'.$class_name.'.php'
            );
            foreach($filenames as $filename){
                if(file_exists($filename)){
                    if(@include_once($filename)){
                        return true;
                    }
                }
            }
            error_log("Failed to load biiq's \"".ucfirst($class_name)."\" class. Operation has been stopped due to file not exists.");
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 300');//300 seconds
            exit;
        }catch(Exception $er){
            error_log(print_r($er, true));
            exit;
        }
    }
    #public functions.
    //rtrim($string, '/');
    if(!function_exists('RemoveTrailingSlash')){
        function RemoveTrailingSlash($url = ''){
            $parts = explode('?', $url, 2);
            return rtrim($parts[0],"/").(isset($parts[1]) ? '?'.$parts[1] : '');
        }
    }
    if(!function_exists('AddTrailingSlash')){
        function AddTrailingSlash($url = ''){
            if(substr($url, -1) !== '/'){
                $parts = explode('?', $url, 2);
                return rtrim($parts[0],"/").'/'.(isset($parts[1]) ? '?'.$parts[1] : '');
            }
            return rtrim($url,"/").'/';
        }
    }
    if(!function_exists('ConvertToPersianNumber')){
        function ConvertToPersianNumber($x = null){
            if($x !== null){
                $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                $num = range(0, 9);
                return str_replace($num,$persian,$x);
            }
            return $x;
        }
    }
    if(!function_exists('ConvertToEnglishNumber')){
        function ConvertToEnglishNumber($x = 0){
            $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $arabic =  ['٠', '١', '٢', '٣', '٤', '٥', '٦','٧','٨','٩'];
            $num = range(0, 9);
            if($x !== null){
                $x = str_replace(',', '', $x);
                $x = str_replace($persian, $num, $x);
                $x = str_replace($arabic, $num, $x);
            }else{
                $x = 0;
            }
            
            return $x;
        }
    }
    if(!function_exists('Trimmer')){
        function Trimmer($x = ''){
            return preg_replace('/\s+/S', ' ', $x);
        }  
    }
    if(!function_exists('left')){
        function left($str, $length){
            return substr($str, 0, $length);
        }
    }
    if(!function_exists('right')){
        function right($str, $length){
            return substr($str, -$length);
        }
    }
    if(!function_exists('ToMoney')){
        function ToMoney($x, $y = 0){
            if(!is_numeric($x)){
                return $x;
            }
            $x = $x + 0;
            if(is_numeric($y) && $y > 0 && floor($x) == $x){
                $y = 0;
            }
            return @number_format($x, $y);
        }
    }
    if(!function_exists('RandomString')){
        function RandomString(int $max_char = 10):string{
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            return substr(str_shuffle($permitted_chars),0,$max_char);
        }
    }
    try{
        define('APPLICATION_DEBUG', false);
        error_reporting(0);
        if(defined('APPLICATION_DEBUG') && APPLICATION_DEBUG === true){
            error_reporting(E_ALL);
            ini_set('error_reporting', E_ALL);
        }
        //@session_start();
        define('StartTime', microtime(true));
        #including required files.
        if(!@include_once('inc'.DIRECTORY_SEPARATOR.'inc.cfg.php')){
            echo '<br><br><b>Failed to load biiq Engine Configuration files. Operation has been stopped.</b>';
            exit;
        }
        spl_autoload_register('biiqEngine_AutoloadRegister');
        $GLOBALS['engine'] = new biiq_Engine();
    }catch(Exception $er){
        error_log(print_r($er, true));
        $GLOBALS['error']->Show(1000);
    }
?>