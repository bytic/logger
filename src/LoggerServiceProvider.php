<?php

namespace Nip\Logger;

use Monolog\Logger as Monolog;
use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;
use Nip\Container\ServiceProviders\Providers\BootableServiceProviderInterface;
use Nip\Debug\ErrorHandler;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class LoggerServiceProvider
 * @package Nip\Logger
 */
class LoggerServiceProvider extends AbstractSignatureServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['log', PsrLoggerInterface::class];
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerLog();
    }

    protected function registerLog()
    {
        $this->getContainer()->share('log', function () {
            return $this->createLogger();
        });
        $this->getContainer()->alias('log', PsrLoggerInterface::class);
    }

    /**
     * Create the logger.
     *
     * @return Manager
     */
    protected function createLogger()
    {
        $manager =  $this->getContainer()->get(Manager::class);
        $manager->setContainer($this->getContainer());
        return $manager;
    }

    public function boot()
    {
        $this->getContainer()->get(ErrorHandler::class)
            ->setDefaultLogger($this->getContainer()->get(PsrLoggerInterface::class));
    }
}
