<?php
function ProcessRequest($request){

  $payload = new stdClass();


    return [
        'content'   => biiq_Template::Start('pages->user-management', true, ['Objects' => $payload]),
        'id'        => 1,
        'title'     => 'مدیریت کاربران',
        'Canonical' => SITE.'user-management/'
    ];
}
?>