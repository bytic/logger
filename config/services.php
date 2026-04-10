<?php

declare(strict_types=1);

/**
 * Symfony DI service definitions for bytic/logger.
 *
 * Loaded by {@see \Nip\Logger\ByticLoggerBundle::loadExtension()} when the
 * bundle is registered in a Symfony application.
 *
 * Legacy (bytic/container) applications should use
 * {@see \Nip\Logger\LoggerServiceProvider} instead.
 */

use Nip\Logger\ErrorHandler\LoggerErrorHandler;
use Nip\Logger\ErrorHandler\PhpErrorLevelMapper;
use Nip\Logger\Manager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(Manager::class)
        ->public();

    $services->alias(LoggerInterface::class, Manager::class)
        ->public();

    $services->set(PhpErrorLevelMapper::class)
        ->public(false);

    $services->set(LoggerErrorHandler::class)
        ->public(false);
};
