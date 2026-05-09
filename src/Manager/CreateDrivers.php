<?php

declare(strict_types=1);

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
 *
 * @package Nip\Logger\Traits
 */
trait CreateDrivers
{
    /**
     * The registered custom driver creators.
     *
     * @var array<string, callable>
     */
    protected array $customCreators = [];

    protected function initDriver(string $name): mixed
    {
        $this->channels[$name] = $this->createLogger($this->resolve($name));

        return $this->channels[$name];
    }

    /**
     * Resolve the given log instance by name.
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve(string $name): LoggerInterface
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
     * @param array<string, mixed> $config
     */
    protected function callCustomCreator(array $config): mixed
    {
        return ($this->customCreators[$config['driver']])($config);
    }

    /**
     * Create an aggregate log driver instance.
     *
     * @param array<string, mixed>|Config $config
     */
    protected function createStackDriver(array|Config $config): LoggerInterface
    {
        $config = $config instanceof Config ? $config->toArray() : $config;

        $handlers = [];
        array_map(function (string $channel) use (&$handlers): void {
            $handlers = array_merge($handlers, $this->channel($channel)->getHandlers());
        }, $config['channels']);

        return new Monolog($this->parseChannel($config), $handlers, $this->processors());
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array<string, mixed>|Config $config
     */
    protected function createSingleDriver(array|Config $config): LoggerInterface
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

        return new Monolog($this->parseChannel($config), $handlers, $this->processors());
    }

    /**
     * @param array<string, mixed>|Config $config
     */
    protected function createDailyDriver(array|Config $config): Monolog
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

        return new Monolog($this->parseChannel($config), $handlers, $this->processors());
    }

    /**
     * @param array<string, mixed>|Config $config
     */
    protected function createNewrelicDriver(array|Config $config): Monolog
    {
        $config = $config instanceof Config ? $config->toArray() : $config;
        $config['handler'] = $config['handler'] ?? \ByTIC\NewRelic\Monolog\Handler::class;

        return $this->createMonologDriver($config);
    }

    /**
     * Create an emergency log handler to avoid white screens of death.
     */
    protected function createEmergencyLogger(): LoggerInterface
    {
        $config = $this->configurationFor('emergency');

        $handler = new StreamHandler(
            $config['path'] ?? $this->getLogsFolderPath() . '/bytic.log',
            $this->level(['level' => 'debug']),
            $config['bubble'] ?? true,
            $config['permission'] ?? null,
            $config['locking'] ?? false
        );

        return $this->createLogger(
            new Monolog('bytic', [$this->prepareHandler($handler)], $this->processors())
        );
    }

    abstract protected function createLogger(mixed $logger): Logger|LoggerInterface;

    abstract protected function configurationFor(string $logger): mixed;

    abstract protected function prepareHandler(HandlerInterface $handler, array $config = []): HandlerInterface;
}

