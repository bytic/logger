<?php

namespace Nip\Logger\Manager;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger as Monolog;
use Nip\Config\Config;
use Psr\Log\InvalidArgumentException;

/**
 * Class MonologWrappers
 * @package Nip\Logger\Manager
 */
trait MonologWrappers
{
    /**
     * Create an instance of any handler available in Monolog.
     *
     * @param array $config
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function createMonologDriver($config)
    {
        $config = $config instanceof Config ? $config->toArray() : $config;

        if (!is_a($config['handler'], HandlerInterface::class, true)) {
            throw new InvalidArgumentException(
                $config['handler'].' must be an instance of '.HandlerInterface::class
            );
        }

        $with = array_merge(
            ['level' => $this->level($config)],
            $config['with'] ?? [],
            $config['handler_with'] ?? []
        );

        return new Monolog($this->parseChannel($config), [
            $this->prepareHandler(
                $this->getContainer()->get($config['handler'], $with),
                $config
            ),
        ]);
    }

    /**
     * Prepare the handlers for usage by Monolog.
     *
     * @param array $handlers
     * @return array
     */
    protected function prepareHandlers(array $handlers)
    {
        foreach ($handlers as $key => $handler) {
            $handlers[$key] = $this->prepareHandler($handler);
        }

        return $handlers;
    }

    /**
     * Prepare the handler for usage by Monolog.
     *
     * @param \Monolog\Handler\HandlerInterface $handler
     * @param array $config
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler, array $config = [])
    {
        $isHandlerFormattable = false;

        if (Monolog::API === 1) {
            $isHandlerFormattable = true;
        } elseif (Monolog::API === 2 && $handler instanceof FormattableHandlerInterface) {
            $isHandlerFormattable = true;
        }

        if ($isHandlerFormattable && !isset($config['formatter'])) {
            $handler->setFormatter($this->formatter());
        } elseif ($isHandlerFormattable && $config['formatter'] !== 'default') {
            $handler->setFormatter($this->app->make($config['formatter'], $config['formatter_with'] ?? []));
        }

        return $handler;
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function formatter()
    {
        $formatter = new LineFormatter(null, $this->dateFormat, true, true);
        $formatter->includeStacktraces();

        return $formatter;
    }
}
