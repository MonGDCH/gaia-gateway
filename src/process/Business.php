<?php

declare(strict_types=1);

namespace process\gateway;

use mon\env\Config;
use gaia\ProcessTrait;
use gaia\interfaces\ProcessInterface;

/**
 * gatewaywork 的 business 服务进程
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Business extends \GatewayWorker\BusinessWorker implements ProcessInterface
{
    use ProcessTrait;

    /**
     * 是否启用进程
     *
     * @return boolean
     */
    public static function enable(): bool
    {
        return Config::instance()->get('gateway.business.enable', false);
    }

    /**
     * 获取进程配置
     *
     * @return array
     */
    public static function getProcessConfig(): array
    {
        return Config::instance()->get('gateway.business.config', []);
    }

    /**
     * 重载构造方法
     */
    public function __construct()
    {
        // 定义配置
        $config = Config::instance()->get('gateway.business.property', []);
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        // Register进程服务注册地址，存在多个则使用数组，如：['192.168.123.1', '192.168.1232']
        $this->registerAddress = (Register::getListenHost() == '0.0.0.0' ? '127.0.0.1' : Register::getListenHost()) . ':' . Register::getListenPort();
    }


    /**
     * 进程启动
     *
     * @return void
     */
    public function onWorkerStart()
    {
        $this->_onWorkerStart  = $this->onWorkerStart;
        $this->_onWorkerReload = $this->onWorkerReload;
        $this->_onWorkerStop   = $this->onWorkerStop;
        $this->onWorkerStop    = [$this, 'onWorkerStop'];
        $this->onWorkerStart   = [$this, 'onWorkerStart'];
        $this->onWorkerReload  = [$this, 'onWorkerReload'];

        $args = func_get_args();
        $this->id = $args[0]->id;
        parent::onWorkerStart();
    }
}
