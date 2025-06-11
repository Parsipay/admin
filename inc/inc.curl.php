<?php
class biiq_Curl{
    public 
        $Result = null,
        $Header = null,
        $Error = null;

    /**
    * Does something interesting
    *
    * @param string $url  Destination url.
    * @param array $data For add to CURLOPT_POSTFIELDS.
    * @param array $header For add to CURLOPT_HTTPHEADER.
    * @param integer $timeout Default is 45.
    * @param string $customrequest For add to CURLOPT_CUSTOMREQUEST.
    *
    * @throws Exception if curl not loaded etc.
    * @author Shayan Shahidehpour <Shayan@Shahidehpour.com>
    * @return Result
    */ 
    function __construct($url = '', $data = [], $header = [], $timeout = 45, $customrequest = ''){
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if(!empty($data)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            if(!empty($header)){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            if($customrequest !== ''){
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $customrequest);
            }
            //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if(!$this->Result = curl_exec($ch)){
                $this->Error = curl_error($ch);
            }
            $this->Header = curl_getinfo($ch);
            curl_close($ch);
        }catch(Exception $er){
            error_log($url);
            error_log(print_r($er, true));
        }
		return $this;
	}	
}
?>