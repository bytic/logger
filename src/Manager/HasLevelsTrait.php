<?php

namespace Nip\Logger\Manager;

use Monolog\Logger as Monolog;
use Psr\Log\InvalidArgumentException;

/**
 * Trait HasLevelsTrait
 * @package Nip\Logger\Traits
 */
trait HasLevelsTrait
{

    /**
     * Map native PHP errors to level.
     *
     * @var array
     * @deprecated variable deprecated
     */
    public static $errorLevelMap = [
        E_NOTICE => Monolog::NOTICE,
        E_USER_NOTICE => Monolog::NOTICE,
        E_WARNING => Monolog::WARNING,
        E_CORE_WARNING => Monolog::WARNING,
        E_USER_WARNING => Monolog::WARNING,
        E_ERROR => Monolog::WARNING,
        E_USER_ERROR => Monolog::ERROR,
        E_CORE_ERROR => Monolog::ERROR,
        E_RECOVERABLE_ERROR => Monolog::ERROR,
        E_PARSE => Monolog::ERROR,
        E_COMPILE_ERROR => Monolog::ERROR,
        E_COMPILE_WARNING => Monolog::ERROR,
        E_STRICT => Monolog::DEBUG,
        E_DEPRECATED => Monolog::DEBUG,
        E_USER_DEPRECATED => Monolog::DEBUG,
    ];

    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Monolog::DEBUG,
        'info' => Monolog::INFO,
        'notice' => Monolog::NOTICE,
        'warning' => Monolog::WARNING,
        'error' => Monolog::ERROR,
        'critical' => Monolog::CRITICAL,
        'alert' => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }
}
