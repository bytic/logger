<?php

namespace Nip\Logger;

use Monolog\Logger as Monolog;
use Nip\Container\ServiceProviders\Providers\AbstractSignatureServiceProvider;
use Nip\Container\ServiceProviders\Providers\BootableServiceProviderInterface;
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
        return ['log', PsrLoggerInterface::class, Monolog::class];
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerLog();
        $this->getContainer()->share(Monolog::class, $this->createMonolog());
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
        $log = $this->getContainer()->get(Manager::class);

//        if ($this->app->hasMonologConfigurator()) {
//            call_user_func($this->app->getMonologConfigurator(), $log->getMonolog());
//        } else {
//            $this->configureHandler($log);
//        }
        return $log;
    }

    /**
     * Create the Monolog.
     *
     * @return Monolog
     */
    protected function createMonolog()
    {
        return new Monolog($this->channel());
    }

    /**
     * Get the name of the log "channel".
     *
     * @return string
     */
    protected function channel()
    {
        return 'production';
    }

    public function boot()
    {
    }
}
