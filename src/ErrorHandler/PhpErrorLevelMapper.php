<?php

declare(strict_types=1);

namespace Nip\Logger\ErrorHandler;

use Psr\Log\LogLevel;

/**
 * Maps PHP native E_* error constants to PSR-3 log-level strings.
 *
 * The mapping intentionally uses more accurate severity levels than Symfony's
 * default (which logs E_WARNING and E_NOTICE as ERROR).  Pass the result of
 * {@see getLevelMap()} to
 * {@see \Symfony\Component\ErrorHandler\ErrorHandler::setDefaultLogger()} so
 * that warnings are logged as warnings, notices as notices, and deprecations
 * as info – instead of everything landing at ERROR.
 */
final class PhpErrorLevelMapper
{
    /**
     * Returns an E_* → PSR-3 LogLevel::* map suitable for passing as the
     * `$levels` argument of
     * {@see \Symfony\Component\ErrorHandler\ErrorHandler::setDefaultLogger()}.
     *
     * @return array<int, string>
     */
    public static function getLevelMap(): array
    {
        return [
            \E_NOTICE           => LogLevel::NOTICE,
            \E_USER_NOTICE      => LogLevel::NOTICE,
            \E_WARNING          => LogLevel::WARNING,
            \E_CORE_WARNING     => LogLevel::WARNING,
            \E_USER_WARNING     => LogLevel::WARNING,
            \E_ERROR            => LogLevel::ERROR,
            \E_USER_ERROR       => LogLevel::ERROR,
            \E_CORE_ERROR       => LogLevel::CRITICAL,
            \E_RECOVERABLE_ERROR => LogLevel::ERROR,
            \E_PARSE            => LogLevel::CRITICAL,
            \E_COMPILE_ERROR    => LogLevel::CRITICAL,
            \E_COMPILE_WARNING  => LogLevel::WARNING,
            \E_STRICT           => LogLevel::DEBUG,
            \E_DEPRECATED       => LogLevel::INFO,
            \E_USER_DEPRECATED  => LogLevel::INFO,
        ];
    }

    /**
     * Map a single PHP E_* constant to the corresponding PSR-3 log level.
     *
     * @param int $errorType One of the PHP E_* constants
     * @return string A \Psr\Log\LogLevel::* constant value
     */
    public static function toLogLevel(int $errorType): string
    {
        return self::getLevelMap()[$errorType] ?? LogLevel::ERROR;
    }
}
