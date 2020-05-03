<?php

namespace Nip\Logger\Manager;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\InvalidArgumentException;

/**
 * Trait CreateDrivers
 * @package Nip\Logger\Traits
 */
trait CreateDrivers
{

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * @param $name
     * @return mixed
     */
    protected function initDriver($name)
    {
        $this->channels[$name] = $this->createLogger($this->resolve($name));

        return $this->channels[$name];
    }

    /**
     * Resolve the given log instance by name.
     *
     * @param string $name
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->configurationFor($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Log [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param array $config
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    /**
     * Create an emergency log handler to avoid white screens of death.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createEmergencyLogger()
    {
        $config = $this->configurationFor('emergency');

        $handler = new StreamHandler(
            $config['path'] ?? $this->app->storagePath().'/logs/laravel.log',
            $this->level(['level' => 'debug'])
        );

        return $this->createLogger(
            new Monolog('bytic', $this->prepareHandlers([$handler]))
        );
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array $config
     * @return \Psr\Log\LoggerInterface
     */
    protected function createSingleDriver(array $config)
    {
        $handlers = [
            $this->prepareHandler(
                new StreamHandler(
                    $config['path'] ?? sys_get_temp_dir().'/bytic-framework.log',
                    $this->level($config),
                    $config['bubble'] ?? true,
                    $config['permission'] ?? null,
                    $config['locking'] ?? false
                ),
                $config
            ),
        ];

        return new Monolog($this->parseChannel($config), $handlers);
    }
}