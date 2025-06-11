<?php
class biiq_IP{
    public static function HasValidIP(){
        try{
            return true;
        }catch(Exception $er){
            error_log(print_r($er, true));
        }
        return false;
    }
}