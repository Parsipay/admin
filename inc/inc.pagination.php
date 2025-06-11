<?php
class biiq_Pagination{
    public $Content = '',
    $TotalRecords = 0,
    $LastRecord = 20,
    $FirstRecord = 1;
    function __construct($total_elements = 0, $limit = 20, $fragment = ''){
        $get = $GLOBALS['get_req'];
        if($total_elements == 0){
            return $this->Content;
        }
        $current_page = 1;
        if(isset($get['page']) && is_numeric($get['page']) && $get['page'] > 1){
            $current_page = $get['page'];
        }
        if(isset($get['limit'])){
            switch($get['limit']){
                case 5:
                case 10:
                case 20:
                case 50:
                case 100:{
                    $limit = $get['limit'];
                }break;
            }
        }
        $max_pages = ceil($total_elements/$limit);
        if($limit > $total_elements){
            $limit = $total_elements;
        }
        $this->TotalRecords = $total_elements;
        $this->LastRecord = $current_page * $limit;
        if($this->LastRecord > $this->TotalRecords){
            $this->LastRecord  = $this->TotalRecords;
        }
        $this->FirstRecord = $current_page * $limit - $limit + 1;
        if($max_pages > 1){
            $this->Content = '
                <nav aria-label="صفحه بندی" class="scrollbar overflow-auto">
                    <ul class="pagination justify-content-start">';
        }else{
            return;
        }
        if($current_page <= 1){
            $link = $this->build_q(array('page' => 1, 'limit' => $limit), $fragment);
            $first_link = $this->build_q(array('page' => 1, 'limit' => $limit), $fragment);
            $this->Content .='
                <li class="page-item disabled d-none d-md-inline">
                    <a class="page-link" href="./?'.$first_link.'" tabindex="-1" title="صفحه نخست">
                        <i class="fa-light fa-angle-double-right"></i>
                    </a>
                </li>
                <li class="page-item disabled d-none d-md-inline">
                    <a class="page-link" href="./?'.$link.'" tabindex="-1">
                        <i class="fa-light fa-angle-right"></i>
                    </a>
                </li>';
        }else{
            $p = $current_page -1;
            $link = $this->build_q(array('page' => $p, 'limit' => $limit), $fragment);
            $first_link = $this->build_q(array('page' => 1, 'limit' => $limit), $fragment);
            $this->Content .='
                <li class="page-item d-none d-md-inline">
                    <a class="page-link" title="نخست" href="./?'.$first_link.'" tabindex="-1">
                        <i class="fa-light fa-angle-double-right"></i>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="./?'.$link.'">
                        <i class="fa-light fa-angle-right"></i>
                    </a>
                </li>';
        }
        $start = 1;
        $end = $max_pages;
        if($max_pages > 5){
            $steps_to_end = 2;
            $steps_from_start = 2;
            $start = $current_page - $steps_from_start;
            $end = $current_page + $steps_to_end;
            while($start < 1){
                $steps_to_end++;
                $steps_from_start--;
                $start = $current_page - $steps_from_start;
                $end = $current_page + $steps_to_end;
            }
            
            while($end > $max_pages){
                $steps_to_end--;
                $steps_from_start++;
                $start = $current_page - $steps_from_start;
                $end = $current_page + $steps_to_end;
            }
        }
        for($i = $start;$i <= $end; $i++){
            //if($i > 0){
                $link = $this->build_q(array('page' => $i,'limit' => $limit), $fragment);
                $this->Content .= '
                    <li class="page-item'.($i == $current_page ?' active':'').'">
                        <a class="page-link" href="./?'.$link.'">'.ConvertToPersianNumber($i).'</a>
                    </li>';
            //}
            
        }


        if($current_page >= $max_pages){
            $link = $this->build_q(array('page'=> $max_pages, 'limit' => $limit), $fragment);            
            $last_link = $this->build_q(array('page' => $max_pages, 'limit' => $limit), $fragment);  
            $this->Content .='
                <li class="page-item disabled">
                    <a class="page-link" href="'.$last_link.'" tabindex="-1">
                        <i class="fa-light fa-angle-left"></i>
                    </a>
                </li>
                <li class="page-item disabled d-none d-md-inline">
                    <a class="page-link" title="آخرین" href="'.$last_link.'" tabindex="-1">
                        <i class="fa-light fa-angle-double-left"></i>
                    </a>
                </li>';
        }else{
            $p = $current_page +1;
            $link = $this->build_q(array('page'=>$p,'limit'=>$limit), $fragment);            
            $last_link = $this->build_q(array('page'=>$max_pages,'limit'=>$limit), $fragment);     
            $this->Content .='
                <li class="page-item">
                    <a class="page-link" href="./?'.$link.'">
                        <i class="fa-light fa-angle-left"></i>
                    </a>
                </li>
                <li class="page-item d-none d-md-inline">
                    <a class="page-link" title="آخرین" href="./?'.$last_link.'" tabindex="-1">
                        <i class="fa-light fa-angle-double-left"></i>
                    </a>
                </li>';
        }
        $this->Content .='
                </ul>
            </nav>';
       
    }
    private function build_q($array = null, $fragment = ''){
        try{
            if(is_array($array) && count($array) > 0 && $array != null){
                $x = $GLOBALS['get_req'];
                unset($x['request']);
                $x = array_merge((array)$x, $array);
                if($fragment != ''){
                    $fragment = '#'.$fragment;
                }
                return http_build_query($x).$fragment;
            }
        }catch(Exception $er){
            error_log(print_r($array,true));
        }
        return null;
    }
}