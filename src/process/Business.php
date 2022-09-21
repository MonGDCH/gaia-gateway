<?php

declare(strict_types=1);

namespace process\gateway;

use mon\env\Config;
use gaia\interfaces\ProcessInterface;

/**
 * gateway\BusinessWorker
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Business extends \GatewayWorker\BusinessWorker implements ProcessInterface
{
    /**
     * 进程配置
     *
     * @var array
     */
    protected static $processConfig = [
        // 进程数
        'count'     =>  2,
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
        $config = Config::instance()->get('gateway.business', []);
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
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
        $this->_onWorkerStop = $this->onWorkerStop;
        $this->onWorkerStop   = array($this, 'onWorkerStop');
        $this->onWorkerStart   = array($this, 'onWorkerStart');
        $this->onWorkerReload  = array($this, 'onWorkerReload');

        $args = func_get_args();
        $this->id = $args[0]->id;
        parent::onWorkerStart();
    }
}
