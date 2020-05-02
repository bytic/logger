<?php

namespace Nip\Logger\Tests;

use Nip\Debug\Debug;
use Nip\Debug\DebugServiceProvider;
use Nip\Debug\ErrorHandler;
use Nip\Logger\LoggerServiceProvider;
use Nip\Logger\Manager;

/**
 * Class DebugServiceProviderTest
 * @package Nip\Debug\Tests
 */
class LoggerServiceProviderTest extends AbstractTest
{
    public function test_registerLog()
    {
        $provider = $this->initServiceProvider();
        $container = $provider->getContainer();

        $log = $container->get('log');
        self::assertInstanceOf(Manager::class, $log);

        $logPsr = $container->get(\Psr\Log\LoggerInterface::class);
        self::assertInstanceOf(Manager::class, $logPsr);
        self::assertSame($log, $logPsr);
    }


    /**
     * @return LoggerServiceProvider
     */
    protected function initServiceProvider()
    {
        $provider = new LoggerServiceProvider();
        $provider->initContainer();
        $provider->register();

        return $provider;
    }
}
