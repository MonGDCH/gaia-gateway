<?php

declare(strict_types=1);

namespace support\gateway\process;

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
     * 获取进程配置
     *
     * @return array
     */
    public static function getProcessConfig(): array
    {
        $config = Config::instance()->get('gateway.business.config', []);
        // 先强转再比较，兼容env返回的字符串、空串等场景，0或无效值则按cpu核心数分配
        $count = (int) ($config['count'] ?? 0);
        $config['count'] = $count === 0 ? \gaia\App::cpuCount() : $count;
        return $config;
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
        // Register进程服务注册地址，存在多个则使用数组，如：['192.168.1.1', '192.168.1.2']
        $this->registerAddress = Register::getListenHost() . ':' . Register::getListenPort();
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
