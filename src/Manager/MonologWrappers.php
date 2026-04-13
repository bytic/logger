<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger as Monolog;
use Nip\Config\Config;
use Nip\Container\Container;
use Psr\Log\InvalidArgumentException;

/**
 * Trait MonologWrappers
 *
 * @package Nip\Logger\Manager
 *
 * @method Container getContainer()
 */
trait MonologWrappers
{
    /**
     * Create an instance of any handler available in Monolog.
     *
     * @param array<string, mixed>|Config $config
     *
     * @throws \InvalidArgumentException
     */
    protected function createMonologDriver(array|Config $config): \Psr\Log\LoggerInterface
    {
        $config = $config instanceof Config ? $config->toArray() : $config;

        if (!is_a($config['handler'], HandlerInterface::class, true)) {
            throw new InvalidArgumentException(
                $config['handler'] . ' must be an instance of ' . HandlerInterface::class
            );
        }

        $with = array_merge(
            ['level' => $this->level($config)],
            $config['with'] ?? [],
            $config['handler_with'] ?? []
        );

        $handler = $this->getContainer()->make($config['handler'], $with);

        return new Monolog(
            $this->parseChannel($config),
            [$this->prepareHandler($handler, $config)]
        );
    }

    /**
     * Prepare multiple handlers for usage by Monolog.
     *
     * @param HandlerInterface[] $handlers
     * @return HandlerInterface[]
     */
    protected function prepareHandlers(array $handlers): array
    {
        foreach ($handlers as $key => $handler) {
            $handlers[$key] = $this->prepareHandler($handler);
        }

        return $handlers;
    }

    /**
     * Prepare a single handler for usage by Monolog.
     *
     * Sets the default LineFormatter on any FormattableHandlerInterface unless
     * the config provides a custom formatter class name.
     *
     * @param array<string, mixed> $config
     */
    protected function prepareHandler(HandlerInterface $handler, array $config = []): HandlerInterface
    {
        if (!($handler instanceof FormattableHandlerInterface)) {
            return $handler;
        }

        if (!isset($config['formatter'])) {
            $handler->setFormatter($this->formatter());
        } elseif ($config['formatter'] !== 'default') {
            $handler->setFormatter(
                $this->getContainer()->make($config['formatter'], $config['formatter_with'] ?? [])
            );
        }

        return $handler;
    }

    /**
     * Get a default Monolog LineFormatter instance.
     */
    protected function formatter(): \Monolog\Formatter\FormatterInterface
    {
        $formatter = new LineFormatter(null, $this->dateFormat, true, true);
        $formatter->includeStacktraces();

        return $formatter;
    }
}

