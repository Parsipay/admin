<?php
function ProcessRequest($request)
{
    $p = new stdClass();

    // مشخص می‌کنه sidebar مخفی باشه
    $p->hideSidebar = true;

    return [
        'content'   => biiq_Template::Start('pages->index', true, ['page' => $p]),
        'id'        => 0,
        'title'     => 'صفحه ورود',
        'Canonical' => SITE,
    ];
}
?>
 