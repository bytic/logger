<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Nip\Logger\Logger;
use Throwable;

/**
 * Trait HasDrivers
 *
 * @package Nip\Logger\Traits
 */
trait HasDrivers
{
    /**
     * Get a log driver instance.
     */
    public function driver(?string $driver = null): Logger|\Psr\Log\LoggerInterface
    {
        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * Get the default log driver name.
     */
    public function getDefaultDriver(): string
    {
        return static::getPackageConfig('default', 'single');
    }

    /**
     * Attempt to get the log from the local cache.
     */
    protected function get(string $name): \Psr\Log\LoggerInterface
    {
        try {
            return $this->channels[$name] ?? $this->initDriver($name);
        } catch (Throwable) {
            return $this->createEmergencyLogger();
        }
    }

    abstract protected function initDriver(string $name): mixed;
}

