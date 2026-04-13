<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Nip\Logger\Logger;
use Psr\Log\LoggerInterface;

/**
 * Trait HasLoggers
 *
 * @package Nip\Logger\Manager
 */
trait HasLoggers
{
    protected function createLogger(mixed $logger): Logger|LoggerInterface
    {
        return new Logger($logger);
    }
}

