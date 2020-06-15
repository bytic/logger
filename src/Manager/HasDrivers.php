<?php

namespace Nip\Logger\Manager;

use Nip\Logger\Logger;
use Throwable;

/**
 * Trait HasDrivers
 * @package Nip\Logger\Traits
 */
trait HasDrivers
{

    /**
     * Get a log driver instance.
     *
     * @param string|null $driver
     * @return Logger|\Psr\Log\LoggerInterface
     */
    public function driver($driver = null)
    {
        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * Get the default log driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return static::getPackageConfig("default", 'single');
    }

    /**
     * Attempt to get the log from the local cache.
     *
     * @param string $name
     * @return \Psr\Log\LoggerInterface
     */
    protected function get($name)
    {
        try {
            return $this->channels[$name] ?? $this->initDriver($name);
        } catch (Throwable $exception) {
            return $this->createEmergencyLogger();
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    abstract protected function initDriver($name);
}
