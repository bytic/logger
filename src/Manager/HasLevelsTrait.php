<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Monolog\Level as MonologLevel;
use Nip\Logger\Level;
use Psr\Log\InvalidArgumentException;

/**
 * Trait HasLevelsTrait
 *
 * @package Nip\Logger\Traits
 */
trait HasLevelsTrait
{
    /**
     * Map native PHP errors to Monolog level.
     *
     * @var array<int, int>
     *
     * @deprecated Use {@see \Nip\Logger\ErrorHandler\PhpErrorLevelMapper::getLevelMap()} instead.
     *   This static property contains incorrect mappings (e.g. E_ERROR was mapped to WARNING)
     *   and will be removed in a future major version.
     */
    public static array $errorLevelMap = [
        \E_NOTICE           => MonologLevel::Notice->value,
        \E_USER_NOTICE      => MonologLevel::Notice->value,
        \E_WARNING          => MonologLevel::Warning->value,
        \E_CORE_WARNING     => MonologLevel::Warning->value,
        \E_USER_WARNING     => MonologLevel::Warning->value,
        \E_ERROR            => MonologLevel::Error->value,    // was incorrectly Warning
        \E_USER_ERROR       => MonologLevel::Error->value,
        \E_CORE_ERROR       => MonologLevel::Critical->value,
        \E_RECOVERABLE_ERROR => MonologLevel::Error->value,
        \E_PARSE            => MonologLevel::Critical->value,
        \E_COMPILE_ERROR    => MonologLevel::Critical->value,
        \E_COMPILE_WARNING  => MonologLevel::Warning->value,
        \E_STRICT           => MonologLevel::Debug->value,
        \E_DEPRECATED       => MonologLevel::Info->value,
        \E_USER_DEPRECATED  => MonologLevel::Info->value,
    ];

    /**
     * The Log levels (PSR-3 string → Monolog integer).
     *
     * @var array<string, int>
     *
     * @deprecated Use the {@see \Nip\Logger\Level} enum instead.
     */
    protected array $levels = [
        'debug'     => MonologLevel::Debug->value,
        'info'      => MonologLevel::Info->value,
        'notice'    => MonologLevel::Notice->value,
        'warning'   => MonologLevel::Warning->value,
        'error'     => MonologLevel::Error->value,
        'critical'  => MonologLevel::Critical->value,
        'alert'     => MonologLevel::Alert->value,
        'emergency' => MonologLevel::Emergency->value,
    ];

    /**
     * Parse the string level from a config array into a Monolog Level enum.
     *
     * @param array<string, mixed> $config
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config): MonologLevel
    {
        $levelName = $config['level'] ?? 'debug';

        try {
            return Level::fromPsrLevel((string) $levelName)->toMonologLevel();
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

