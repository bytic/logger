<?php

namespace Nip\Logger\Tests\Manager;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Nip\Logger\Logger;
use Nip\Logger\Monolog\Handler\NewRelicHandler;
use Nip\Logger\Tests\AbstractTest;

/**
 * Class CreateDriversTest
 * @package Nip\Logger\Tests\Manager
 */
class CreateDriversTest extends AbstractTest
{
    public function test_createStackDriver()
    {
        $manager = $this->generateBaseManager();

        $config = require TEST_FIXTURE_PATH.'/config/stack.php';
        $manager::setConfig(['logging' => $config]);

        $logger = $manager->driver('stack');
        self::assertInstanceOf(Logger::class, $logger);

        $handlers = $logger->getLogger()->getHandlers();

        static::assertCount(2, $handlers);
        static::assertInstanceOf(StreamHandler::class, $handlers[0]);
        static::assertInstanceOf(StreamHandler::class, $handlers[1]);
    }

    public function test_createDailyDriver()
    {
        $manager = $this->generateBaseManager();

        $config = require TEST_FIXTURE_PATH.'/config/stack.php';
        $manager::setConfig(['logging' => $config]);

        $logger = $manager->driver('daily');

        $handlers = $logger->getLogger()->getHandlers();

        static::assertCount(1, $handlers);

        $handler = $handlers[0];
        static::assertInstanceOf(RotatingFileHandler::class, $handler);
    }

    public function test_createNewRelicDriver()
    {
        $manager = $this->generateBaseManager();

        $config = require TEST_FIXTURE_PATH.'/config/stack.php';
        $manager::setConfig(['logging' => $config]);

        $logger = $manager->driver('newrelic');
        self::assertInstanceOf(Logger::class, $logger);

        $handlers = $logger->getLogger()->getHandlers();

        static::assertCount(1, $handlers);
        static::assertInstanceOf(NewRelicHandler::class, $handlers[0]);
    }
}
