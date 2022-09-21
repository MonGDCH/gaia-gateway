<?php

declare(strict_types=1);

namespace process\gateway;

use mon\env\Config;
use gaia\interfaces\ProcessInterface;

/**
 * workermn\channel 进程通信服务
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Gateway extends \GatewayWorker\Gateway implements ProcessInterface
{
    /**
     * 进程配置
     *
     * @var array
     */
    protected static $processConfig = [
        // 监听协议断开
        'listen'        => 'websocket://0.0.0.0:12301',
        // 进程数，必须是1
        'count'         =>  2,
        // 关闭进程重启
        'reloadable'    => false
    ];

    /**
     * 是否启用进程
     *
     * @return boolean
     */
    public static function enable(): bool
    {
        return true;
    }

    /**
     * 获取进程配置
     *
     * @return array
     */
    public static function getProcessConfig(): array
    {
        return static::$processConfig;
    }

    /**
     * 重载构造方法
     */
    public function __construct()
    {
        // 定义配置
        $config = Config::instance()->get('gateway.gateway', []);
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        // 路由函数定义
        $this->router = [\GatewayWorker\Gateway::class, 'routerBind'];
    }

    /**
     * 进程启动
     *
     * @return void
     */
    public function onWorkerStart()
    {
        // 保存用户的回调，当对应的事件发生时触发
        $this->_onWorkerStart = $this->onWorkerStart;
        $this->onWorkerStart  = [$this, 'onWorkerStart'];
        // 保存用户的回调，当对应的事件发生时触发
        $this->_onConnect = $this->onConnect;
        $this->onConnect  = [$this, 'onClientConnect'];

        // onMessage禁止用户设置回调
        $this->onMessage = [$this, 'onClientMessage'];

        // 保存用户的回调，当对应的事件发生时触发
        $this->_onClose = $this->onClose;
        $this->onClose  = [$this, 'onClientClose'];
        // 保存用户的回调，当对应的事件发生时触发
        $this->_onWorkerStop = $this->onWorkerStop;
        $this->onWorkerStop  = [$this, 'onWorkerStop'];

        if (!is_array($this->registerAddress)) {
            $this->registerAddress = [$this->registerAddress];
        }

        // 记录进程启动的时间
        $this->_startTime = time();

        $args = func_get_args();
        $this->id = $args[0]->id;
        parent::onWorkerStart();
    }

    /**
     * 建立连接
     *
     * @param TcpConnection $connection
     * @return void
     */
    public function onConnect($connection)
    {
        parent::onClientConnect($connection);
    }

    public function onClose($connection)
    {
        parent::onClientClose($connection);
    }

    public function onMessage($connection, $data)
    {
        parent::onClientMessage($connection, $data);
    }
}
