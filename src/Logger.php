<?php

declare(strict_types=1);

namespace Nip\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * A thin PSR-3 wrapper that delegates all logging to an inner LoggerInterface
 * (typically a Monolog instance) and performs {placeholder} interpolation on
 * the message before passing it through.
 *
 * The old behaviour of prepending a timestamp and level prefix to every message
 * body has been removed.  Monolog's own LineFormatter is responsible for
 * formatting – this class no longer duplicates that work.
 */
class Logger extends AbstractLogger
{
    use LoggerAwareTrait;

    /**
     * Create a new log writer instance.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed              $level
     * @param string|\Stringable $message
     * @param mixed[]            $context
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    #[\Override]
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->logger->{$level}($this->interpolate((string) $message, $context), $context);
    }

    /**
     * Return the underlying PSR-3 logger (e.g. a Monolog instance).
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Interpolates {placeholder} tokens in `$message` using values from
     * `$context`, following the PSR-3 specification.
     *
     * This is the only formatting done at this level; timestamp/level prefixes
     * are intentionally left to the underlying logger / Monolog formatter.
     */
    protected function interpolate(string $message, array $context): string
    {
        if (!str_contains($message, '{')) {
            return $message;
        }

        $replacements = [];
        foreach ($context as $key => $val) {
            if (null === $val || is_scalar($val) || (\is_object($val) && method_exists($val, '__toString'))) {
                $replacements["{{$key}}"] = $val;
            } elseif ($val instanceof \DateTimeInterface) {
                $replacements["{{$key}}"] = $val->format(\DateTime::RFC3339);
            } elseif (\is_object($val)) {
                $replacements["{{$key}}"] = '[object ' . \get_class($val) . ']';
            } else {
                $replacements["{{$key}}"] = '[' . \gettype($val) . ']';
            }
        }

        return strtr($message, $replacements);
    }

    /**
     * Dynamically proxy method calls to the underlying logger.
     *
     * @param string  $method
     * @param mixed[] $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->logger->{$method}(...$parameters);
    }
}

