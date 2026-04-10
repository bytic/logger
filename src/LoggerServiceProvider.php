<?php

declare(strict_types=1);

namespace Nip\Logger;

use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;
use Nip\Container\ServiceProviders\Providers\BootableServiceProviderInterface;
use Nip\Debug\ErrorHandler;
use Nip\Logger\ErrorHandler\LoggerErrorHandler;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Registers the logger into the bytic container.
 *
 * @deprecated In Symfony-based applications use {@see \Nip\Logger\ByticLoggerBundle} instead.
 *   This service provider will continue to work in legacy (bytic/container) applications
 *   but may be removed in a future major version.
 */
class LoggerServiceProvider extends AbstractSignatureServiceProvider implements BootableServiceProviderInterface
{
    #[\Override]
    public function provides(): array
    {
        return ['log', PsrLoggerInterface::class];
    }

    #[\Override]
    public function register(): void
    {
        $this->registerLog();
    }

    protected function registerLog(): void
    {
        $this->getContainer()->share('log', function () {
            return $this->createLogger();
        });
        $this->getContainer()->alias('log', PsrLoggerInterface::class);
    }

    /**
     * Create the logger manager.
     */
    protected function createLogger(): Manager
    {
        $manager = $this->getContainer()->get(Manager::class);
        $manager->setContainer($this->getContainer());

        return $manager;
    }

    /**
     * Boot: attach the PSR-3 logger to the error handler with proper per-level
     * PSR-3 severity so that E_WARNING is logged as WARNING, E_NOTICE as
     * NOTICE, and E_DEPRECATED as INFO – rather than everything at ERROR.
     */
    #[\Override]
    public function boot(): void
    {
        LoggerErrorHandler::register(
            $this->getContainer()->get(ErrorHandler::class),
            $this->getContainer()->get(PsrLoggerInterface::class)
        );
    }
}

