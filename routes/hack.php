<?php

use App\Helpers\Response;
use Illuminate\Support\Facades\Route;

$aryHackedRoute = [
    'wp-includes/wlwmanifest.xml',
    'view-source:',
    'misc/ajax.js',
    'wordpress',
    'wp',
    'blog',
    'new',
    'old',
    'test',
    'main',
    'testing',
    'wp-includes/ID3/license.txt',
    'feed',
    'blog/wp-includes/wlwmanifest.xml',
    'web/wp-includes/wlwmanifest.xml',
    'wordpress/wp-includes/wlwmanifest.xml',
    'wp/wp-includes/wlwmanifest.xml',
    '2020/wp-includes/wlwmanifest.xml',
    '2019/wp-includes/wlwmanifest.xml',
    '2021/wp-includes/wlwmanifest.xml',
    'shop/wp-includes/wlwmanifest.xml',
    'wp1/wp-includes/wlwmanifest.xml',
    'test/wp-includes/wlwmanifest.xml',
    'site/wp-includes/wlwmanifest.xml',
    'cms/wp-includes/wlwmanifest.xml',
    'ALFA_DATA/alfacgiapi/perl.alfa',
    'alfacgiapi/perl.alfa',
];

foreach ($aryHackedRoute as $route) {
    Route::get($route, function () {
        return Response::badRequest([
            'message_vi' => 'Chỉ quản trị viên mới có thể truy cập đường dẫn này!',
            'message_en' => 'Only administrators can access this path!',
            'message_kr' => '관리자만 이 경로에 액세스할 수 있습니다!',
            'message_ja' => '管理者のみがこのパスにアクセスできます。',
            'message_cn' => '只有管理员才能访问此路径！',
        ]);
    });
}
