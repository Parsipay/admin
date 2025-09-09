<?php
function ProcessRequest($request){




    return [
        'content'   => biiq_Template::Start('pages->user-management', true, ['Objects' => $payload]),
        'id'        => 1,
        'title'     => 'مدیریت کاربران',
        'Canonical' => SITE.'user-management/'
    ];
}
?>