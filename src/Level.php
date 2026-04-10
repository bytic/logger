<?php

declare(strict_types=1);

namespace Nip\Logger;

use Monolog\Level as MonologLevel;
use Psr\Log\LogLevel;

/**
 * PSR-3 log levels mapped to their Monolog integer values.
 *
 * Using this enum is the preferred way to reference log levels in new code.
 * It replaces the plain `$levels` array that was historically stored in
 * {@see \Nip\Logger\Manager\HasLevelsTrait}.
 */
enum Level: int
{
    case Debug = 100;
    case Info = 200;
    case Notice = 250;
    case Warning = 300;
    case Error = 400;
    case Critical = 500;
    case Alert = 550;
    case Emergency = 600;

    /**
     * Create a Level from a PSR-3 log-level string (e.g. "debug", "warning").
     */
    public static function fromPsrLevel(string $level): self
    {
        return match (strtolower($level)) {
            LogLevel::DEBUG     => self::Debug,
            LogLevel::INFO      => self::Info,
            LogLevel::NOTICE    => self::Notice,
            LogLevel::WARNING   => self::Warning,
            LogLevel::ERROR     => self::Error,
            LogLevel::CRITICAL  => self::Critical,
            LogLevel::ALERT     => self::Alert,
            LogLevel::EMERGENCY => self::Emergency,
            default             => throw new \InvalidArgumentException(\sprintf('Invalid PSR-3 log level "%s".', $level)),
        };
    }

    /**
     * Return the Monolog Level enum equivalent.
     */
    public function toMonologLevel(): MonologLevel
    {
        return MonologLevel::from($this->value);
    }

    /**
     * Return the PSR-3 log-level string (all lower-case).
     *
     * @return string
     * @phpstan-return \Psr\Log\LogLevel::*
     */
    public function toPsrLevel(): string
    {
        return match ($this) {
            self::Debug     => LogLevel::DEBUG,
            self::Info      => LogLevel::INFO,
            self::Notice    => LogLevel::NOTICE,
            self::Warning   => LogLevel::WARNING,
            self::Error     => LogLevel::ERROR,
            self::Critical  => LogLevel::CRITICAL,
            self::Alert     => LogLevel::ALERT,
            self::Emergency => LogLevel::EMERGENCY,
        };
    }
}
