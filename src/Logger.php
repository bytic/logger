<?php

namespace Nip\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class Logger
 * @package Nip\Logger
 */
class Logger extends AbstractLogger
{
    use LoggerAwareTrait;

    protected $formatter;

    /**
     * Create a new log writer instance.
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->formatter = [$this, 'format'];
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $formatter = $this->formatter;

        $this->logger->{$level}($formatter($level, $message, $context), $context);
    }

    /**
     * @return array
     */
    public function getFormatter(): array
    {
        return $this->formatter;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function format(string $level, string $message, array $context): string
    {
        if (false !== strpos($message, '{')) {
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

            $message = strtr($message, $replacements);
        }

        return sprintf('%s [%s] %s', date(\DateTime::RFC3339), $level, $message) . PHP_EOL;
    }

    /**
     * Dynamically proxy method calls to the underlying logger.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->logger->{$method}(...$parameters);
    }
}
