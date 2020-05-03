<?php

namespace Nip\Logger\Manager;

/**
 * Class WriteLogs
 * @package Nip\Logger\Traits
 */
trait WriteLogs
{

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
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
}
