<?php
class biiq_Template{
    public $Output = '';
    protected 
        $file, 
        $values = array(),
        $objects = null;
    public function __construct($path = null, $run = false, $obj = null){
        $this->set('date(\'Y\')',date('Y'));
        if($path !== null){
            $this->SetPath($path);
            if($obj != null){
                $this->objects = $obj;
                $this->GetOtherObjects();
            }
            if($run === true){
                $this->output($this->Output);
            }
        }
    }
    public function set($key, $value){
        $this->values[$key] = $value;
    }
    public function output(&$output = null){
        $output = (is_null($output)) ? $this->Output : $output;
        $this->ParseForEach($output);
        $this->ParseConditionals($output);
        $this->SetImports($output);
        $this->SetSettings($output);
        $this->SetPageSettings($output);
        $this->RemoveComments($output);
        $this->SetValues($output);
        $this->ConvertToMoney($output);
        $this->ConvertToPersian($output);
        return $output;
    }
    
    public function SetPath($path){
        $directory = VIEWS_PATH;
        $file_name = $path;
        $last_directory = 'view'; 
        $path_explode = explode('->', $path);
        if(is_array($path_explode) && count($path_explode) > 0){
            $file_name = array_pop($path_explode);
            foreach($path_explode as $dir){
                $directory .= 'view.'.$dir.DIRECTORY_SEPARATOR;
                $last_directory = 'view.'.$dir;
            }
        }
        $file_name = $last_directory.'.'.$file_name.'.html';
        $this->file = $directory.$file_name;
        //error_log($this->file);
        if(file_exists($this->file)){
            $this->Output = file_get_contents($this->file);
        }else{
            $this->Output = '{MISSING}';
        }
    }
    // Private Functions
    private function ConvertToPersian(&$output = null){
        $output = (is_null($output)) ? $this->Output : $output;
        $matches = array();
        $pattern = '/\[@persian\s?(.*?)\s?\]/iums';
		if(($counts = preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) !== false && is_numeric($counts) && $counts > 0){
            if(is_array($matches)){
                foreach ($matches as $match){
                    if(is_array($match) && count($match) == 2){
                        /*
                        * For each match:
                        * [0] = full match | example: `[@persian [@settings->version]]`
                        * [1] = string | example: `[@settings->version]`
                        */
                        $full_string = $match[0];
                        $content_string = $match[1];
                        
                        if(($pos = strpos($output, $full_string)) !== false) {
                            $content_string = $this->output($content_string);
                            $content_string = ConvertToPersianNumber($content_string);
                            $output = substr_replace($output, $content_string, $pos, strlen($full_string));
                        }else{
                            if(($pos = strpos($output, $full_string)) !== false) {
                                $output = substr_replace($output, '', $pos, strlen($full_string));
                            }
                        }
                    }
                }
            }
        }
        return $output;
    }
    private function ConvertToMoney(&$output = null){
        $output = (is_null($output)) ? $this->Output : $output;
        $matches = array();
        $pattern = '/\[@money\s?(.*?)\s?\]/iums';
		if(($counts = preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) !== false && is_numeric($counts) && $counts > 0){
            if(is_array($matches)){
                foreach ($matches as $match){
                    if(is_array($match) && count($match) == 2){
                        /*
                        * For each match:
                        * [0] = full match | example: `[@persian [@settings->version]]`
                        * [1] = string | example: `[@settings->version]`
                        */
                        $full_string = $match[0];
                        $content_string = $match[1];
                        
                        if(($pos = strpos($output, $full_string)) !== false){
                            $content_string = $this->output($content_string);
                            str_replace(",", '', $content_string);
                            if(strlen($content_string) > 0){
                                if(is_numeric($content_string)){
                                    if($content_string > 0){
                                        $y = 0;
                                        if(floor($content_string) != $content_string){
                                            $y = 2;
                                            $y = strlen(substr(strrchr($content_string, "."), 1));
                                        }
                                        $content_string = ToMoney($content_string, $y);
                                    }
                                }else{
                                    error_log("Failed to convert string to money in Template => ".$content_string);
                                }
                            }
                            $output = substr_replace($output, $content_string, $pos, strlen($full_string));
                        }else{
                            if(($pos = strpos($output, $full_string)) !== false) {
                                $output = substr_replace($output, '', $pos, strlen($full_string));
                            }
                        }
                    }
                }
            }
        }
        return $output;
    }
    private function SetImports(&$output = null){
        $output = (is_null($output)) ? $this->Output : $output;
        $import_array = array();
        $regex = '/\[@import->.*\]/iU';
        if(preg_match_all($regex, $output, $import_array) > 0){
            if(is_array($import_array) && count($import_array) > 0){
                foreach ($import_array[0] as $item){
                    $matched = $item;
                    $item = str_replace('[@import->','',$item);
                    $item = str_replace(']','',$item);
                    $directory = VIEWS_PATH;
                    $file_name = $item;
                    $last_directory = 'view'; 
                    $path_explode = explode('->',$item);
                    if(is_array($path_explode) && count($path_explode) > 0){
                        $file_name = array_pop($path_explode).'.html';
                        foreach($path_explode as $dir){
                            $directory .= 'view.'.$dir.DIRECTORY_SEPARATOR;
                            $last_directory = 'view.'.$dir;
                        }
                    }
                    $the_path = $directory.$last_directory.'.'.$file_name;
                    if(file_exists($the_path)){
                        $temp_output = file_get_contents($the_path);
                        $temp_output = $this->output($temp_output);
                        $output = str_replace($matched, $temp_output, $output);
                    }else{
                        $output = str_replace($matched, '{MISSING}', $output);
                    }
                }
            }
        }
        return $output;
    }
    private function SetSettings(&$output = null){
        $output = (is_null($output)) ? $this->Output : $output;
        if(!isset($GLOBALS['config'])){
            return $output;
        }
        $settings = $GLOBALS['config']->Setting;

        $pattern = '/\[@settings->(.*?)\]/iums';
        if(($counts = preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) !== false && is_numeric($counts) && $counts > 0){
            if(is_array($matches)){
                foreach ($matches as $match){
                    if(is_array($match) && count($match) == 2){
                        /*
                        * For each match:
                        * [0] = full match | example: `[@settings->blah]`
                        * [1] = key | example: `blah`
                        */
                        $matched = $match[0];
                        $item = $match[1];
                        if($settings != null && array_key_exists($item, $settings) && $item != ''){
                            $value = $settings[$item];
                            $output = str_replace($matched, $value, $output);
                        }else{
                            $output = str_replace($matched, '', $output);
                        }
                    }
                }
            }
        }
        return $output;
    }
    private function SetPageSettings(&$output = null){
        $output = (is_null($output)) ? $this->Output : $output;

        if(!isset($GLOBALS['config'])){
            return $output;
        }
        
        $settings = $GLOBALS['config']->PageSettings;
        
        $pattern = '/\[@page->(.*?)\]/iums';
        if(($counts = preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) !== false && is_numeric($counts) && $counts > 0){
            if(is_array($matches)){
                foreach ($matches as $match){
                    if(is_array($match) && count($match) == 2){
                        /*
                        * For each match:
                        * [0] = full match | example: `[@page->blah]`
                        * [1] = key | example: `blah`
                        */
                        $matched = $match[0];
                        $item = $match[1];
                        if(array_key_exists($item, $settings) && $item != ''){
                            $value = $settings[$item];
                            $output = str_replace($matched, $value, $output);
                        }else{
                            $output = str_replace($matched, '', $output);
                        }
                    }
                }
            }
        }
        return $output;
    }
    public function SetValues(&$output = null){
        try{
            $output = (is_null($output)) ? $this->Output : $output;
            foreach($this->values as $key => $value){
                $tagToReplace = "[@$key]";
                $output = str_replace($tagToReplace, $value, $output);
            }
            if($this->objects != null && is_array($this->objects) && count($this->objects) > 0){
                foreach($this->objects as $key => $value){
                    if(is_object($value)){
                        $value = get_object_vars($value);
                    }
                    if(is_array($value) && count($value)>0){
                        foreach($value as $k => $v){
                            if(!is_array($v) && !is_object($v)){
                                $tagToReplace = "[@$key->$k]";
                                if(strpos($output, $tagToReplace) !== false){
                                    if($v === null){
                                        $v = '';
                                    }
                                    $output = str_replace($tagToReplace, $v, $output);
                                }
                            }
                        }
                    }
                }
            }
        }catch(Exception $er){
            error_log($er->getMessage());
        }
        return $output;
    }
    private function ParseConditionals(string &$output): string{
        $output = (is_null($output)) ? $this->Output : $output;
		$pattern = '/\[if\s?\((.*?)\)\s?](.*?)\[endif]/iums';
		if(($counts = preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) !== false && is_numeric($counts) && $counts > 0){
            if(is_array($matches)){
                foreach ($matches as $match){
                    $temp_pattern = '';
                    if(is_array($match) && count($match) == 3){
                        /*
                        * For each match:
                        * [0] = full match | example: `[if(blah == true)]blah[endif]`
                        * [1] = conditional | example: `blah == true`
                        * [2] = string | example: `blah`
                        */
                        $full_string = $match[0];
                        $conditional = $match[1];
                        $content_string = $match[2];
                        $is_conditional_accepted = false;
                        $array_operators = array('=='=>'equal', '!=' =>'not_equal', '<=' => 'equal_greater', '>=' => 'equal_less', '>>' =>'greater', '<<' => 'less');
                        $operator_found = false;
                        foreach($array_operators as $key => $value){
                            if($operator_found){
                                break;
                            }
                            if(strpos($conditional, $key) !== false && !$operator_found){
                                $operator_found = true;
                                try{
                                    $condition_parts = explode($key,$conditional);
                                    if(is_array($condition_parts) && count($condition_parts) == 2){
                                        //error_log(print_r($condition_parts,true));
                                        foreach($condition_parts as &$item){
                                            $item = $this->SetValues($item);
                                            $item = $this->SetSettings($item);
                                            $item = $this->SetPageSettings($item);
                                            $item = (string)$item;
                                            $item = trim($item);
                                        }
                                        //error_log(print_r($condition_parts,true));
                                        switch($value){
                                            case 'equal':{
                                                //error_log(print_r($condition_parts,true));
                                                if($condition_parts[0] == $condition_parts[1]){
                                                    $is_conditional_accepted = true;
                                                }
                                            }break;
                                            case 'not_equal':{
                                                if($condition_parts[0] != $condition_parts[1]){
                                                    $is_conditional_accepted = true;
                                                }
                                            }break;
                                            case 'equal_greater':{
                                                if($condition_parts[0] >= $condition_parts[1]){
                                                    $is_conditional_accepted = true;
                                                }
                                            }break;
                                            case 'equal_less':{
                                                if($condition_parts[0] <= $condition_parts[1]){
                                                    $is_conditional_accepted = true;
                                                }
                                            }break;
                                            case 'greater':{
                                                $condition_parts[0] = ConvertToEnglishNumber($condition_parts[0]);
                                                $condition_parts[1] =  ConvertToEnglishNumber($condition_parts[1]);
                                                if($condition_parts[0] > $condition_parts[1]){
                                                    $is_conditional_accepted = true;
                                                }
                                            }break;
                                            case 'less':{
                                                if($condition_parts[0] < $condition_parts[1]){
                                                    $is_conditional_accepted = true;
                                                }
                                            }break;
                                            default:{
                                                $is_conditional_accepted = false;
                                            }
                                        }
                                    }
                                }catch(Exception $er){
                                    error_log(print_r($er,true));
                                }
                            }
                        }
                        
                        if($is_conditional_accepted){
                            if(($pos = strpos($output, $full_string)) !== false) {
                                $content_string = $this->output($content_string);
                                $output = substr_replace($output, $content_string, $pos, strlen($full_string));
                            }
                        }else{
                            if(($pos = strpos($output, $full_string)) !== false) {
                                $output = substr_replace($output, '', $pos, strlen($full_string));
                            }
                        }
                    }
                }
            }
        }
        return $output;
    }
    private function ParseForEach(string &$output):string{
        $output = (is_null($output)) ? $this->Output : $output;
        $pattern = '/\[foreach\s?\((.*?)\)\s?](.*?)\[endforeach]/iums';
        $matches = '';
		if(($counts = preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) !== false && is_numeric($counts) && $counts > 0){
            if(is_array($matches)){
                foreach ($matches as $match){
                    $temp_pattern = '';
                    if(is_array($match) && count($match) == 3){
                        /*
                        * For each match:
                        * [0] = full match | example: `[foreach(item)]blah[endforeach]`
                        * [1] = conditional | example: `item`
                        * [2] = string | example: `blah`
                        */
                        $full_string = $match[0];
                        $conditional = $match[1];
                        $content_string = $match[2];
                        
                        $is_conditional_accepted = false;
                        $temp_out = '';
                        if($this->objects != null && is_array($this->objects) && count($this->objects) > 0){
                            $pattern_item = '/\[@(.*?)->(.*?)]/iums';
                            if(($counts_item = preg_match_all($pattern_item, $match[1], $matches_item, PREG_SET_ORDER)) !== false && is_numeric($counts_item) && $counts_item > 0){
                                if(is_array($matches_item)){
                                    foreach ($matches_item as $match_item){
                                        if(is_array($match_item) && count($match_item) == 3){
                                            if(isset($this->objects[$match_item[1]],$this->objects[$match_item[1]]->{$match_item[2]})){
                                                $temp_array = $this->objects[$match_item[1]]->{$match_item[2]};
                                                if(isset($this->objects[$match_item[1]]->{$match_item[2]})){
                                                    $temp_array = $this->objects[$match_item[1]]->{$match_item[2]};
                                                    $is_conditional_accepted = true;
                                                    if(is_array($temp_array)){
                                                        foreach($temp_array as $i){
                                                            $fx_out = $content_string;
                                                            foreach($i as $k => $v){
                                                                if(!is_array($v) && !is_object($v)){
                                                                    $tagToReplace = "[@Item->$k]";
                                                                    if(strpos($fx_out,$tagToReplace) !== false){
                                                                        if($v === null){
                                                                            $v = '';
                                                                        }
                                                                        $fx_out = str_replace($tagToReplace, $v, $fx_out);
                                                                    }
                                                                }
                                                            }
                                                            $fx_out = $this->SetValues($fx_out);
                                                            $fx_out = $this->SetSettings($fx_out);
                                                            $fx_out = $this->SetPageSettings($fx_out);
                                                            $fx_out = (string)$fx_out;
                                                            $fx_out = trim($fx_out);
    
                                                            $temp_out .= $fx_out;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        if($is_conditional_accepted){
                            if(($pos = strpos($output, $full_string)) !== false) {
                                $output = substr_replace($output, $temp_out, $pos, strlen($full_string));
                            }
                        }else{
                            if(($pos = strpos($output, $full_string)) !== false) {
                                $output = substr_replace($output, '', $pos, strlen($full_string));
                            }
                        }
                    }
                }
            }
        }
        return $output;
    }
    private function RemovePHP(string &$output = null):string{
        $output = (is_null($output)) ? $this->Output : $output;
        $output = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $output);
        return $output;
    }
    private function RemoveComments(string &$output = null): string{
        $output = (is_null($output)) ? $this->Output : $output;
        $output = preg_replace('/<!--.*?-->/s', '', $output);
        return $output;
    }
    private function GetOtherObjects(){
        if(isset($GLOBALS['config']->PageSettings['Menu'])){
            $this->objects['Menu'] = $GLOBALS['config']->PageSettings['Menu'];
        }
    }
    // Static Functions
    static public function merge($templates, $separator = "\n") {
        $output = "";
        
        foreach ($templates as $template) {
            $content = (get_class($template) !== "Template")
                ? "Error, incorrect type - expected Template."
                : $template->output();
            $output .= $content . $separator;
        }
        
        return $output;
    }
    static public function Start($path = null, $run = false, $obj = null){
        $s = new self($path, $run, $obj);
        return $s->Output;
    }
    private function EscapeRegex($sString = ''){
        //$sString = trim($sString);
        $sString = preg_quote($sString,'/');
        return $sString;
        $sString = preg_replace('/\s+/S', ' ', $sString);
        $Special_Characters = array(
            "|",
            "\l",
            '\\',
            '/',
            "+",
            "*",
            "?",
            "[",
            "^",
            "]",
            "$",
            "(",
            ")",
            "{",
            "}",
            "=",
            "!",
            "<",
            ">",
            ":",
            "-",
            "#",
        );
        foreach($Special_Characters as $item){
            $sString = str_replace($item,'\\'.$item,$sString);
        }
        return $sString;
    }
}
?>