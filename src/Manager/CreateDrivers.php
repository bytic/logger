<?php

namespace Nip\Logger\Manager;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Nip\Config\Config;
use Nip\Logger\Logger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

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
     * @return LoggerInterface
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

        $driverMethod = 'create' . ucfirst($config['driver']) . 'Driver';

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
        return $this->customCreators[$config['driver']](app(), $config);
    }

    /**
     * Create an aggregate log driver instance.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createStackDriver($config)
    {
        $config = $config instanceof Config ? $config->toArray() : $config;

        $handlers = [];
        array_map(function ($channel) use (&$handlers) {
            $handlers = array_merge($handlers, $this->channel($channel)->getHandlers());
        }, $config['channels']);

//        if ($config['ignore_exceptions'] ?? false) {
//            $handlers = [new WhatFailureGroupHandler($handlers)];
//        }

        return new Monolog($this->parseChannel($config), $handlers);
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createSingleDriver($config)
    {
        $config = $config instanceof Config ? $config->toArray() : $config;
        $handlers = [
            $this->prepareHandler(
                new StreamHandler(
                    $config['path'] ?? $this->getLogsFolderPath() . '/bytic.log',
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

    /**
     * @param array $config
     * @return Monolog
     */
    protected function createDailyDriver($config)
    {
        $config = $config instanceof Config ? $config->toArray() : $config;

        $handlers = [
            $this->prepareHandler(new RotatingFileHandler(
                $config['path'],
                $config['days'] ?? 7,
                $this->level($config),
                $config['bubble'] ?? true,
                $config['permission'] ?? null,
                $config['locking'] ?? false
            ), $config),
        ];

        return new Monolog($this->parseChannel($config), $handlers);
    }

    /**
     * @param array $config
     * @return Monolog
     */
    protected function createNewrelicDriver($config)
    {
        $config = $config instanceof Config ? $config->toArray() : $config;
        $config['handler'] = isset($config['handler']) ? $config['handler'] : \ByTIC\NewRelic\Monolog\Handler::class;

        return $this->createMonologDriver($config);
    }

    /**
     * Create an emergency log handler to avoid white screens of death.
     *
     * @return LoggerInterface
     */
    protected function createEmergencyLogger()
    {
        $config = $this->configurationFor('emergency');

        $handler = new StreamHandler(
            $config['path'] ?? $this->getLogsFolderPath() . '/bytic.log',
            $this->level(['level' => 'debug'])
        );

        return $this->createLogger(
            new Monolog('bytic', [$this->prepareHandler($handler)])
        );
    }

    /**
     * @param $logger
     * @return Logger|LoggerInterface
     */
    abstract protected function createLogger($logger);

    /**
     * @param string $logger
     * @return array
     */
    abstract protected function configurationFor($logger);

    /**
     * @param HandlerInterface $handler
     * @param array $config
     * @return HandlerInterface
     */
    abstract protected function prepareHandler(HandlerInterface $handler, array $config = []);
}
