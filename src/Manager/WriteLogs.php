<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Nip\Logger\Logger;
use Psr\Log\LoggerInterface;

/**
 * Trait WriteLogs
 *
 * @package Nip\Logger\Traits
 */
trait WriteLogs
{
    #[\Override]
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $this->writeLog((string) $level, $message, $context);
    }

    /**
     * Write a message to the underlying Monolog instance.
     */
    protected function writeLog(string $level, string|\Stringable $message, array $context): void
    {
        $this->driver()->{$level}($message, $context);
    }

    abstract public function driver(?string $driver = null): Logger|LoggerInterface;
}

