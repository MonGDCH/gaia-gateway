<?php

declare(strict_types=1);

namespace process\gateway;

use mon\env\Config;
use Workerman\Worker;
use gaia\ProcessTrait;
use gaia\interfaces\ProcessInterface;

/**
 * gatewaywork 的 register 服务进程
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Register extends \GatewayWorker\Register implements ProcessInterface
{
    use ProcessTrait;

    /**
     * 是否启用进程
     *
     * @return boolean
     */
    public static function enable(): bool
    {
        return Config::instance()->get('gateway.register.enable', false);
    }

    /**
     * 获取进程配置
     *
     * @return array
     */
    public static function getProcessConfig(): array
    {
        return Config::instance()->get('gateway.register.config', []);
    }


    /**
     * 重载构造方法
     */
    public function __construct()
    {
    }

    /**
     * 进程启动
     *
     * @param Worker $worker
     * @return void
     */
    public function onWorkerStart(Worker $worker)
    {
        // 设置 onMessage 连接回调
        $this->onConnect = [$this, 'onConnect'];

        // 设置 onMessage 回调
        $this->onMessage = [$this, 'onMessage'];

        // 设置 onClose 回调
        $this->onClose = [$this, 'onClose'];

        // 记录进程启动的时间
        $this->_startTime = time();

        // 强制使用text协议
        $this->protocol = \Workerman\Protocols\Text::class;

        // reusePort
        $this->reusePort = false;
    }
}
