<?php
function ProcessRequest($request){

    $page = new stdClass();
   
    $pp = array(
        'content' => biiq_Template::Start('pages->about', true, ['Objects' => $page]),
        'id'    => 0,
        'title' => 'Title here',
        // 'Canonical' => SITE,
    );
    return $pp;
}
?>

