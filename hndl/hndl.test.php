<?php
function ProcessRequest($request){
    $page = new stdClass();
    //test inja

    $page->Time = date("Y/m/d");
    $page->mohtava = "contentest";  
    
    $page->Jafar = "sdkasdhakjsd";
    $page->Jafar = "sdkasdhakjsd";
    $page->Jafar2 = "0000000";
    $page->Jafar = "sdkasdhakjsd";
    $page->Jafar3 = "12313123";
    $page->Jafar = "sdkasdhakjsd";
    $page->Jafar = "sdkasdhakjsd";
    $page->Jafar = "sdkasdhakjsd";
    $page->Jafar = "sdkasdhakjsd";
    $page->dates  = date("Y-m-d h:i:sa", $d);

    $page->Teams = [
        ["ThisIsKey" => "ThisIsValue", "Name" => "Shayan"],
        ["ThisIsKey" => "ThisIsValue", "Name" => "Banafshe"],
        ["ThisIsKey" => "ThisIsValue3", "Name" => "Yegane"],
        ["ThisIsKey" => "ThisIsValue", "Name" => "Jafaaaari"],
        ["ThisIsKey" => "ThisIsValue", "Name" => "Heesam"],
    ];


    


    
    $page->UserCount = 1600;
    $pp = array(
        'content' => biiq_Template::Start('test->test', true, ['Objects' => $page]),
        'id'    => 0,
        'title' => 'گیفت آپ | ربات تبدیل ووچر',
        'Canonical' => SITE,
    );
    return $pp;
}
?> 

