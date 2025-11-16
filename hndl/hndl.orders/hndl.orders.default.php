<?php
function ProcessRequest($request){
    $page = new stdClass();
    if(!isset($request->Parameters) || !is_array($request->Parameters) || count($request->Parameters) == 0){
        //error
        $GLOBALS['error']->Show(401);
        exit;
    }
    $SelectedUserID = $request->Parameters[0];
    if(!is_numeric($SelectedUserID) || $SelectedUserID == 0){
        //error
        $GLOBALS['error']->Show(401);
        exit;
    }


    //Load user $SelectedUserID

    //$page->User = biiq_User::GetByID($SelectedUserID);
    echo "inja bayad joziat sefaresh beshe";

    $page->Title = " مشاهده سفارش ";
    $page = array(
        'content' => biiq_Template::Start('orders->default', true, ['Objects' => $page]),
        'id' => 0,
        'title' => $page->Title,
        'Canonical' => SITE.'orders/',
    );
    return $page;
}

?>