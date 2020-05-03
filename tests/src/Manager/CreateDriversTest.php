<?php

namespace Nip\Logger\Tests\Manager;

use Monolog\Handler\StreamHandler;
use Nip\Logger\Logger;
use Nip\Logger\Manager;
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

        $this->assertCount(2, $handlers);
        $this->assertInstanceOf(StreamHandler::class, $handlers[0]);
        $this->assertInstanceOf(StreamHandler::class, $handlers[1]);
    }
}
