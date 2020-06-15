<?php

namespace Nip\Logger\Manager;

use Nip\Logger\Logger;
use Psr\Log\LoggerInterface;

/**
 * Trait HasLoggers
 * @package Nip\Logger\Manager
 */
trait HasLoggers
{
    /**
     * @param $logger
     * @return Logger|LoggerInterface
     */
    protected function createLogger($logger)
    {
        return new Logger($logger);
    }
}
