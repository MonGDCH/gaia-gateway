<?php

/*
|--------------------------------------------------------------------------
| business进程配置文件
|--------------------------------------------------------------------------
| 定义business进程配置信息
|
*/

return [
    // 启用，gaia批量启动进程时有效
    'enable'    => env('GATEWAY_SERVER', false),
    // 进程配置
    'config'    => [
        // 进程数
        'count' => \gaia\App::cpuCount() * 2,
    ],
    // business属性配置
    'property'  => [
        // 事件回调处理对象
        'eventHandler'  => \support\gateway\Event::class,
        // 秘钥
        'secretKey'     => env('GATEWAY_SECRET_KEY', ''),
    ]
];
