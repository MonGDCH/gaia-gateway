<?php

/*
|--------------------------------------------------------------------------
| business进程配置文件
|--------------------------------------------------------------------------
| 定义business进程配置信息
|
*/

use process\gateway\Register;

return [
    // 启用
    'enable'    => true,
    // 进程配置
    'config'    => [
        // 进程数
        'count' => cpu_count(),
    ],
    // gateway属性配置
    'property'  => [
        // 事件回调处理对象
        'eventHandler' => \app\gateway\Event::class
    ]
];
