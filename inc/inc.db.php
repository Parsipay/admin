<?php
class biiq_DB{
    public $PDO = null;
    public function __construct(){
        $db_pass = 'uGY23%chAl53';
        $db_user = 'root';
        $db_dsn = 'mysql:host=localhost;dbname=tlg;charset=utf8mb4';
        try{
            $this->PDO = new PDO($db_dsn, $db_user, $db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false, 1002 => "SET NAMES utf8mb4,time_zone = '+4:30';"]);
            //$GLOBALS['db'] = $this->PDO;
            //error_log('hi');
        }catch(PDOException $ex){
            error_log(print_r($ex->getMessage(), true));
        }
    }
    public static function GetDB(){
        try{
            if(isset($GLOBALS['db']) && $GLOBALS['db'] instanceof biiq_DB){
                return $GLOBALS['db']->PDO;
            }else{
                $f = new self();
                return $f->PDO;
            }
        }catch(Exception $er){
            error_log(print_r($er, true));
        }
    }
}
?>