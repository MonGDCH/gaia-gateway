<?php

/*
|--------------------------------------------------------------------------
| gateway进程配置文件
|--------------------------------------------------------------------------
| 定义gateway进程配置信息
|
*/

return [
    // 进程配置
    'config'    => [
        // 监听协议端口，只能使用text协议
        'listen'        => 'websocket://0.0.0.0:12301',
        // 进程数
        'count'         => \Gaia\App::cpuCount(),
        // 关闭进程重启
        'reloadable'    => false
    ],
    // gateway属性配置
    'property'  => [
        // 本机ip，分布式部署时使用内网ip
        'lanIp'                 => '127.0.0.1',
        //内部通讯起始端口，假如$gateway->count = 4，起始端口为4000, 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口，注意：起始端口不要重复
        'startPort'             => 4900,
        // 心跳间隔
        'pingInterval'          => 60,
        // 心跳数据，不为空时，由服务端定时向客户端发送心跳数据（不推荐）
        'pingData'              => '',
        // 多少心跳间隔时间内客户端未报通信则断开连接(pingInterval * pingNotResponseLimit = 时间间隔)，0表示允许客户端不发送心跳
        'pingNotResponseLimit'  => 1,
        // 传输协议，一般不需要改动，如需使用ssl，则修改为ssl，建议使用nginx做代理ssl。https://www.workerman.net/doc/workerman/faq/secure-websocket-server.html
        'transport'             => 'tcp',
        // 秘钥
        'secretKey'             => env('GATEWAY_SECRET_KEY', ''),
    ]
];
