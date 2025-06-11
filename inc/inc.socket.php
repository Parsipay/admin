<?php
class biiq_Socket{
    public static function Get($url, $data = []){
        $out_json = '';
        try{
            $micro_start = microtime(true);
            $sock = @fsockopen("127.0.0.1", 8888, $errno, $errstr, 2);
            if($sock){
                $data = [
                    'function' => $url,
                    'data' => $data
                ];
                $out = json_encode($data);
                fwrite($sock, $out);
                $lines = [];
                while(!feof($sock)){
                    $lines[] = fgets($sock);
                }
                fclose($sock);
                $output_str = implode("", $lines);
                $micro_end = microtime(true);
                $ms = (float) $micro_end - (float) $micro_start;
                $ms = round($ms, 4);
                error_log("Function {$url} took {$ms} ms."); //0.0136
                error_log("{$output_str}");
                $out_json = json_decode($output_str, true);
                if($out_json === null){
                    return '';
                }
            }else{
                if($errno != 10061){
                    error_log("No connection to server {$errno}, {$errstr}");
                }
            }
        }catch(Exception $er){
            error_log(print_r($er, true));
        }
        return $out_json;
    }
}
?>