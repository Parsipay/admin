<?php

// ============================================
// Main Page Data Preparation
// ============================================
function ProcessRequest($request) {

    $p = new stdClass();

    $p->authentication = [
        [
            "nationalCode" => "0013152343",
            "phoneNumber"  => "09128431937",
            "User"         => "یگانه علیزاده",
            "UserID"       => 16,
            "documents"    => "مدرک شناسایی",
            "Status"       => "موفق",
            "StatusColor"  => "bg-success text-white",
        ],
        [
            "nationalCode" => "0013152344",
            "phoneNumber"  => "09128431938",
            "User"         => "بنفشه ابراهیمی",
            "UserID"       => 17,
            "documents"    => "کارت ملی",
            "Status"       => "در انتظار تایید",
            "StatusColor"  => "bg-warning text-dark",
        ],
        [
            "nationalCode" => "0013152345",
            "phoneNumber"  => "09128431939",
            "User"         => "بنفشه ابراهیمی",
            "UserID"       => 18,
            "documents"    => "مدرک ناقص",
            "Status"       => "ناموفق",
            "StatusColor"  => "bg-danger text-white",
        ],
    ];

    // Return payload to template
    return [
        'content'   => biiq_Template::Start('transactions->table', true, ['Objects' => $p]),
        'id'        => 0,
        'title'     => 'صفحه اصلی',
        'Canonical' => SITE,
    ];
}

?>
