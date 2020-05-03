<?php

namespace Nip\Logger\Manager;

use Monolog\Logger as Monolog;
use Nip\Logger\Logger;

/**
 * Trait HasLoggers
 * @package Nip\Logger\Manager
 */
trait HasLoggers
{
    /**
     * @param $logger
     * @return Logger
     */
    protected function createLogger($logger)
    {
        return new Logger($logger);
    }
}
