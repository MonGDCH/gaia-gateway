<?php

/*
|--------------------------------------------------------------------------
| business进程配置文件
|--------------------------------------------------------------------------
| 定义business进程配置信息
|
*/

return [
    // 进程配置
    'config'    => [
        // 进程数
        'count' => \gaia\App::cpuCount(),
    ],
    // business属性配置
    'property'  => [
        // 事件回调处理对象
        'eventHandler'  => \app\gateway\Event::class,
        // 秘钥
        'secretKey'     => env('GATEWAY_SECRET_KEY', ''),
    ]
];
