<?php

declare(strict_types=1);

namespace Nip\Logger\ErrorHandler;

use Nip\Debug\ErrorHandler;
use Psr\Log\LoggerInterface;

/**
 * Wires a PSR-3 logger into the bytic/debug ErrorHandler with per-level
 * PSR-3 severity instead of the blanket ERROR that Symfony uses by default.
 *
 * Usage (in a service provider or kernel boot):
 *
 *   LoggerErrorHandler::register($errorHandler, $psrLogger);
 *
 * @see \Nip\Logger\ErrorHandler\PhpErrorLevelMapper
 * @see \Symfony\Component\ErrorHandler\ErrorHandler::setDefaultLogger()
 */
final class LoggerErrorHandler
{
    /**
     * Attach `$logger` to every PHP error level tracked by `$errorHandler`,
     * using the accurate PSR-3 level from {@see PhpErrorLevelMapper} rather
     * than Symfony's default (which maps warnings and notices to ERROR).
     */
    public static function register(ErrorHandler $errorHandler, LoggerInterface $logger): void
    {
        $errorHandler->setDefaultLogger($logger, PhpErrorLevelMapper::getLevelMap(), true);
    }
}
