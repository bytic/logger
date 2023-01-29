<?php

namespace Nip\Logger\Manager;

use Nip\Logger\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class WriteLogs
 * @package Nip\Logger\Traits
 */
trait WriteLogs
{

    /**
     * @inheritdoc
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->writeLog($level, $message, $context);
    }

    /**
     * Write a message to Monolog.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return bool
     */
    protected function writeLog($level, $message, $context)
    {
        return $this->driver()->{$level}($message, $context);
    }

    /**
     * @param string|null $driver
     * @return Logger|LoggerInterface
     */
    abstract public function driver($driver = null);
}
