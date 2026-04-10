<?php

declare(strict_types=1);

namespace Nip\Logger;

/**
 * Symfony Bundle integration for bytic/logger.
 *
 * Register this bundle in `config/bundles.php` (or your `AppKernel`) to have
 * the {@see Manager} and its PSR-3 interface wired into the Symfony container
 * automatically, replacing the legacy {@see LoggerServiceProvider}.
 *
 * **Requirements:** `symfony/http-kernel ^6.0|^7.0` must be installed.
 * Run `composer require symfony/http-kernel` if it is not already a dependency
 * of your application.
 *
 * @see \Nip\Logger\DependencyInjection\ByticLoggerExtension
 */
class ByticLoggerBundle extends \Symfony\Component\HttpKernel\Bundle\AbstractBundle
{
    public function loadExtension(
        array $config,
        \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container,
        \Symfony\Component\DependencyInjection\ContainerBuilder $builder
    ): void {
        $container->import('../config/services.php');
    }
}
