<?php
class biiq_Handler{
    public 
        $GET,
        $Path = HANDLERS_PATH,
        $MainPath = '',
        $UrlExploded = [],
        $Parent_Path = '',
        $Parameters = array(),
        $Handler = 'index';
    function __construct(){
        $error = $GLOBALS['error'];
        $url = $GLOBALS['uri_req'];
        $this->UrlExploded = $GLOBALS['uri_req_exploded'];
        $this->GET = $GLOBALS['get_req'];
        unset($this->GET['request']);
        
        if($url === ''){
            $this->LoadIndex();
            return;
        }
        if(is_array($this->UrlExploded)){
            $c = 0;
            $this->Parent_Path = '';
            $jobs_done = false;
            $posible_handlers = array();
            foreach($this->UrlExploded as $item){
                if($jobs_done){
                    $this->Parameters[] = $item;
                    continue;
                }
                $c++;
                $item_dir = 'hndl.'.$this->Parent_Path.$item.DIRECTORY_SEPARATOR;
                $item_file = $this->Path.'hndl.'.$this->Parent_Path.$item.'.php';
                $item_default = $this->Path.'hndl.'.$this->Parent_Path.'default.php';
                $item_index = $this->Path.'hndl.'.$this->Parent_Path.'index.php';
                if(file_exists($this->Path.$item_dir)){
                    if($this->MainPath == ''){
                        $this->MainPath = $item;
                    }
                    $this->Parent_Path .= $item.'.';
                    $this->Path .= $item_dir;
                    //error_log(print_r($this,true));
                    if($c == count($this->UrlExploded)){//Last one
                        if(file_exists($this->Path.'hndl.'.$this->Parent_Path.'php')){
                            $this->Handler = $this->Path.'hndl.'.$this->Parent_Path.'php';
                            $jobs_done = true;
                            continue;
                        }
                        $this->Handler = $this->Path.'hndl.'.$this->Parent_Path.'index.php';
                        $jobs_done = true;
                    }
                    continue;
                }else{
                    if(file_exists($item_file)){
                        if($c == count($this->UrlExploded)){//Last one
                            $this->Handler = $item_file;
                            $jobs_done = true;
                            continue;
                        }
                    }else{
                        if(file_exists($item_default)){
                            $this->Handler = $item_default;
                            $this->Parameters[] = $item;
                            $jobs_done = true;
                            continue;
                        }else{
                            // if($c == count($url_exploded) && $this->Parent_Path != '' && file_exists($item_index)){
                            //     $this->Handler = $item_index;
                            //     $this->Parameters[] = $item;
                            //     $jobs_done = true;
                            //     continue;
                            // }else{
                            //     $error->Show(404);
                            // }
                            $error->Show(404);
                        }
                    }
                }
            }
            if(!$jobs_done){
                $error->Show(404);
            }
        }else{
            $this->LoadIndex();
        }
        $this->Process();
        $error->Show(404);
    }
    private function LoadIndex(){
        $this->Handler = HANDLERS_PATH.'hndl.index.php';
        $this->Process();
    }
    private function SaveToPageSetting(){
        try{
            return;
            $data_to_save = array(
                'MainPath',
                'Handler',
                'ID'
            );
            foreach($data_to_save as $item){
                if(isset($this->{$item})){
                    $GLOBALS['config']->PageSettings[$item] = $this->{$item};
                }
            }
            $GLOBALS['config']->PageSettings['request_method'] = $_SERVER['REQUEST_METHOD'];
        }catch(Exception $er){
            error_log(print_r($er, true));
        }
    }
    private function CheckMaintenance($redirect = false){
        if(defined('Maintenance')){
            if(Maintenance){
                if(is_array($this->UrlExploded) && $this->UrlExploded[0] == 'maintenance'){
                    return;
                }
                if($redirect){
                    header("Location: ".SITE."maintenance/", true, 302);
                    exit;
                }
                return true;
            }else{
                if(is_array($this->UrlExploded) && $this->UrlExploded[0] == 'maintenance'){
                    if($redirect){
                        header("Location: ".SITE, true, 302);
                        exit;
                    }
                }
            }
        }
        return false;
    }
    private function Process(){
        $this->SaveToPageSetting();
        //error_log(print_r($this,true));
        if(file_exists($this->Handler)){
            include_once($this->Handler);
            if(function_exists('ProcessRequest')){
                //biiq_Engine::LoadDatabase();
                $this->CheckMaintenance(true);
                $pp = ProcessRequest($this);
                if(is_array($pp)){
                    biiq_Theme::DisplayPage($pp, $this);
                }else{
                    $GLOBALS['error']->Show(1000);
                }
            }
            // header('Location: '.SITE.'login/', true, 302);
            // exit;
        }
    }
}
?>
