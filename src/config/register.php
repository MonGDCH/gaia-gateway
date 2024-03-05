<?php

/*
|--------------------------------------------------------------------------
| register进程配置文件
|--------------------------------------------------------------------------
| 定义register进程配置信息
|
*/

return [
    // 启用
    'enable'    => false,
    // 进程配置
    'config'    => [
        // 监听协议端口，只能使用text协议
        'listen'    => 'text://127.0.0.1:1236',
        // 进程数，必须是1
        'count'     => 1
    ],
    // register属性配置
    'property'  => [
        // 秘钥
        'secretKey' => env('GATEWAY_SECRET_KEY', ''),
    ]
];
