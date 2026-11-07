<?php

declare(strict_types=1);

namespace gaia\gateway\tests;

use gaia\App;
use mon\env\Config;
use PHPUnit\Framework\TestCase;
use support\gateway\process\Business;
use support\gateway\process\Gateway;
use support\gateway\process\Register;

/**
 * 进程配置解析测试
 *
 * @author Mon <985558837@qq.com>
 */
class ProcessConfigTest extends TestCase
{
    /**
     * 测试前置：重置配置数据与cpu核心数
     */
    protected function setUp(): void
    {
        App::$cpuCount = 4;
        Config::instance()->setData([
            'gateway' => [
                'business' => [
                    'config' => [
                        'count' => 0,
                    ],
                ],
                'gateway' => [
                    'config' => [
                        'listen' => 'websocket://0.0.0.0:12301',
                        'count'  => 2,
                    ],
                ],
                'register' => [
                    'config' => [
                        'listen' => 'text://127.0.0.1:1236',
                        'count'  => 1,
                    ],
                ],
            ],
        ]);
    }

    public function testCountZeroFallbackToCpuCount(): void
    {
        $this->assertSame(4, Business::getProcessConfig()['count']);
    }

    public function testCountMissingFallbackToCpuCount(): void
    {
        Config::instance()->setData(['gateway' => ['business' => ['config' => []]]]);
        $this->assertSame(4, Business::getProcessConfig()['count']);
    }

    public function testCountEmptyStringFallbackToCpuCount(): void
    {
        // env返回空串时，(int)'' === 0，应回退到cpu核心数而非传0给workerman
        Config::instance()->setData(['gateway' => ['business' => ['config' => ['count' => '']]]]);
        $this->assertSame(4, Business::getProcessConfig()['count']);
    }

    public function testCountNumericStringCastedToInt(): void
    {
        // env返回的数字字符串应强转为int
        Config::instance()->setData(['gateway' => ['business' => ['config' => ['count' => '8']]]]);
        $this->assertSame(8, Business::getProcessConfig()['count']);
    }

    public function testCountPositiveIntKept(): void
    {
        $this->assertSame(2, Gateway::getProcessConfig()['count']);
    }

    public function testGatewayConfigOtherKeysPreserved(): void
    {
        $config = Gateway::getProcessConfig();
        $this->assertSame('websocket://0.0.0.0:12301', $config['listen']);
    }

    public function testRegisterConfigPassThrough(): void
    {
        // register进程数必须为1，不做count转换，原样返回
        $config = Register::getProcessConfig();
        $this->assertSame('text://127.0.0.1:1236', $config['listen']);
        $this->assertSame(1, $config['count']);
    }

    public function testRegisterConfigEmptyFallback(): void
    {
        Config::instance()->setData([]);
        $this->assertSame([], Register::getProcessConfig());
    }
}
