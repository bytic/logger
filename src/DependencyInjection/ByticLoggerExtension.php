<?php

declare(strict_types=1);

namespace Nip\Logger\DependencyInjection;

use Nip\Logger\ErrorHandler\LoggerErrorHandler;
use Nip\Logger\ErrorHandler\PhpErrorLevelMapper;
use Nip\Logger\Logger;
use Nip\Logger\Manager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Symfony DependencyInjection Extension for the ByticLoggerBundle.
 *
 * This extension registers the logger services into the Symfony container.
 * It is automatically used by {@see \Nip\Logger\ByticLoggerBundle} and can
 * also be loaded standalone via `$container->registerExtension(new ByticLoggerExtension())`.
 *
 * @see \Nip\Logger\ByticLoggerBundle
 */
class ByticLoggerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(Manager::class, Manager::class)
            ->setPublic(true);

        $container->setAlias(LoggerInterface::class, Manager::class)
            ->setPublic(true);

        $container->register(PhpErrorLevelMapper::class, PhpErrorLevelMapper::class)
            ->setPublic(false);

        $container->register(LoggerErrorHandler::class, LoggerErrorHandler::class)
            ->setPublic(false);
    }

    public function getAlias(): string
    {
        return 'bytic_logger';
    }
}
