<?php

namespace Nip\Logger\Tests;

use Nip\Logger\Logger;
use Monolog\Logger as Monolog;

/**
 * Class ManagerTest
 * @package Nip\Logger\Tests
 */
class ManagerTest extends AbstractTest
{
    public function test_driver_init_default()
    {
        $manager = $this->generateBaseManager();

        $driver = $manager->driver();
        self::assertInstanceOf(Logger::class, $driver);

        $logger = $driver->getLogger();
        self::assertInstanceOf(Monolog::class, $logger);
    }
}
