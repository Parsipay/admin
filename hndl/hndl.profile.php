<?php
function ProcessRequest($request){

    $page = array(
        'content' => biiq_Template::Start('pages->profile', true, []),
        'id'    => 0,
        'title' => '',
        'Canonical' => SITE,
    );
    return $page;
}
?>