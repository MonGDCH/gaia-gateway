<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| 测试引导文件
|--------------------------------------------------------------------------
| 本包不依赖安装 gaia 框架及 mon 组件，此处桩掉被测代码引用到的类，
| 仅用于测试进程配置解析等静态逻辑
|
*/

namespace mon\env {

    /**
     * mon\env\Config 桩类，支持点语法读取数组配置
     */
    class Config
    {
        /**
         * 单例
         *
         * @var Config|null
         */
        private static ?Config $instance = null;

        /**
         * 配置数据
         *
         * @var array
         */
        private array $data = [];

        /**
         * 获取实例
         *
         * @return Config
         */
        public static function instance(): Config
        {
            if (is_null(static::$instance)) {
                static::$instance = new static();
            }
            return static::$instance;
        }

        /**
         * 设置配置数据
         *
         * @param array $data
         * @return void
         */
        public function setData(array $data): void
        {
            $this->data = $data;
        }

        /**
         * 点语法获取配置
         *
         * @param string $key     支持点语法的键名，如 gateway.business.config
         * @param mixed  $default 默认值
         * @return mixed
         */
        public function get(string $key, $default = null)
        {
            $value = $this->data;
            foreach (explode('.', $key) as $segment) {
                if (!is_array($value) || !array_key_exists($segment, $value)) {
                    return $default;
                }
                $value = $value[$segment];
            }
            return $value;
        }
    }
}

namespace gaia\interfaces {

    /**
     * gaia\interfaces\ProcessInterface 桩接口
     */
    interface ProcessInterface
    {
        /**
         * 获取进程配置
         *
         * @return array
         */
        public static function getProcessConfig(): array;

        /**
         * 获取服务监听的协议端口
         *
         * @return string
         */
        public static function getListen(): string;

        /**
         * 获取监听的协议scheme
         *
         * @return string
         */
        public static function getListenScheme(): string;

        /**
         * 获取协议host
         *
         * @return string
         */
        public static function getListenHost(): string;

        /**
         * 获取协议port
         *
         * @return integer
         */
        public static function getListenPort(): int;
    }
}

namespace gaia {

    /**
     * gaia\ProcessTrait 桩trait，仅实现进程配置的静态读取
     */
    trait ProcessTrait
    {
        /**
         * 获取服务监听的协议端口
         *
         * @return string
         */
        public static function getListen(): string
        {
            return static::getProcessConfig()['listen'] ?? '';
        }

        /**
         * 获取监听的协议scheme
         *
         * @return string
         */
        public static function getListenScheme(): string
        {
            $listen = static::getListen();
            $parse = explode('://', $listen, 2);
            return count($parse) == 2 ? $parse[0] : '';
        }

        /**
         * 获取协议host
         *
         * @return string
         */
        public static function getListenHost(): string
        {
            $listen = static::getListen();
            $parse = explode('://', $listen, 2);
            if (count($parse) != 2) {
                return '';
            }
            return explode(':', $parse[1], 2)[0];
        }

        /**
         * 获取协议port
         *
         * @return integer
         */
        public static function getListenPort(): int
        {
            $listen = static::getListen();
            $parse = explode('://', $listen, 2);
            if (count($parse) != 2) {
                return -1;
            }
            $parseHost = explode(':', $parse[1], 2);
            return count($parseHost) == 2 ? intval($parseHost[1]) : -1;
        }
    }

    /**
     * gaia\App 桩类，仅提供 cpuCount
     */
    class App
    {
        /**
         * 模拟的cpu核心数
         *
         * @var int
         */
        public static int $cpuCount = 4;

        /**
         * 获取cpu核心数
         *
         * @return integer
         */
        public static function cpuCount(): int
        {
            return static::$cpuCount;
        }
    }
}

namespace {
    // 载入vendor依赖
    require_once __DIR__ . '/../vendor/autoload.php';

    // 进程驱动类为发布型类（发布后由宿主应用autoload），本包autoload未映射，测试时手动载入
    require_once __DIR__ . '/../src/support/process/Business.php';
    require_once __DIR__ . '/../src/support/process/Gateway.php';
    require_once __DIR__ . '/../src/support/process/Register.php';
}
