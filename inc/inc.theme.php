<?php
class biiq_Theme{
    public static function DisplayPage($page_settings = null, $page = null){
        //error_reporting(E_ALL);
        if(is_array($page_settings) && count($page_settings) > 0){
            foreach($page_settings as $key => $value){
                $GLOBALS['config']->PageSettings[$key] = $value;
            }
        }
        
        $layout = 'layout->main';
        if(isset($GLOBALS['config']->PageSettings['layout']) && $GLOBALS['config']->PageSettings['layout'] != 'main'){
            $layout = "layout->".$GLOBALS['config']->PageSettings['layout'];
        }
        
        $objects = new stdClass();
        $objects->UserCount = 1600;
        $data = ['Objects' => $objects]; 
        ob_start(array('biiq_Engine', 'OutputBuffer_PostProcessor'));
        header("Content-Type: text/html; charset=utf-8");
        echo biiq_Template::Start($layout, true, $data);
        define('EndTime', microtime(true));
        $ms = (float) EndTime - (float) StartTime;
        $ms = round($ms,4);
        echo '<!-- '.$ms.' -->';
        $GLOBALS['db'] = null;
        ob_end_flush();
        exit;
    }


    public static function Truncate($text, $chars = 25) {
        if(strlen($text) > $chars){
            $text = $text." ";
            $text = substr($text,0,$chars);
            if(strrpos($text,' ') !== false){
                $text = substr($text,0,strrpos($text,' '));
            }
            $text = $text."...";
        }
        return $text;
    }
    public static function PersianText($text){
        try{
            $text = str_replace('ي', 'ی', $text);
            $text = str_replace('ك', 'ک', $text);
            $text = str_replace('ي', 'ی', $text);
            $text = str_replace('ك', 'ک', $text);
        }catch(Exception $er){
            error_log(print_r($er, true));
        }
        return $text;
    }
}
?>