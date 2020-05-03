<?php

namespace Nip\Logger\Tests;

use Monolog\Logger;
use Nip\Logger\Manager;

/**
 * Class ManagerTest
 * @package Nip\Logger\Tests
 */
class ManagerTest extends AbstractTest
{
    public function test_driver_init_default()
    {
        $manager = new Manager();
        $driver = $manager->driver();
        self::assertInstanceOf(Logger::class, $driver);
    }
}
