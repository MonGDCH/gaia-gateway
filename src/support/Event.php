<?php

declare(strict_types=1);

namespace support\gateway;

use mon\env\Config;
use mon\log\Logger;
use mon\thinkORM\ORM;
use GatewayWorker\Lib\Gateway;
use support\cache\CacheService;
use GatewayWorker\BusinessWorker;

/**
 * GateWay事件处理回调
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Event
{
    /**
     * 当businessWorker进程启动时触发。每个进程生命周期内都只会触发一次。
     *
     * @param BusinessWorker $businessWorker
     * @return void
     */
    public static function onWorkerStart(BusinessWorker $worker)
    {
        // 日志通道初始化
        $log_channel = Config::instance()->get('gateway.app.log.channel', 'gateway');
        $log_config = Config::instance()->get('gateway.app.log.config', []);
        Logger::instance()->createChannel($log_channel, $log_config);
        Logger::instance()->setDefaultChannel($log_channel);

        // 定义数据库配置，自动识别是否已安装ORM库
        if (class_exists(ORM::class)) {
            $config = Config::instance()->get('database', []);
            // 识别是否存在缓存库
            if (class_exists(CacheService::class)) {
                ORM::register(true, $config, Logger::instance()->channel(), CacheService::instance()->getService()->store());
            } else {
                ORM::register(true, $config, Logger::instance()->channel());
            }
        }
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param string $client_id 连接id
     * @return void
     */
    public static function onConnect(string $client_id)
    {
        Gateway::sendToCurrentClient('Hello！' . $client_id);
    }

    /**
     * 当客户端连接上gateway完成websocket握手时触发的回调函数。
     * 注意：此回调只有gateway为websocket协议并且gateway没有设置onWebSocketConnect时才有效。
     *
     * @param string $client_id 客户端ID
     * @param array $data 请求的GET数据及$_SERVER数据(含HTTP相关数据)
     * @return void
     */
    public static function onWebSocketConnect(string $client_id, array $data)
    {
        Gateway::sendToCurrentClient('Your ID => ' . $client_id);
    }

    /**
     * 当客户端发来消息时触发
     *
     * @param string $client_id 连接id
     * @param string $message 具体消息
     * @return void
     */
    public static function onMessage(string $client_id, string $message)
    {
        Gateway::sendToCurrentClient('Your Message => ' . $message);
    }

    /**
     * 当用户断开连接时触发
     *
     * @param string $client_id 连接id
     * @return void
     */
    public static function onClose(string $client_id)
    {
    }
}
