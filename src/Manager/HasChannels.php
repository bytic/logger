<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Nip\Container\ContainerAwareTrait;

/**
 * Trait HasChannels
 *
 * @package Nip\Logger\Traits
 */
trait HasChannels
{
    /**
     * The array of resolved channels.
     *
     * @var array<string, \Psr\Log\LoggerInterface>
     */
    protected array $channels = [];

    /**
     * Get a log channel instance.
     */
    public function channel(?string $channel = null): \Psr\Log\LoggerInterface
    {
        return $this->driver($channel);
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array<string, mixed> $config
     */
    protected function parseChannel(array $config): string
    {
        return $config['name'] ?? $this->getFallbackChannelName();
    }

    /**
     * Get the fallback log channel name.
     *
     * When the container provides an `env` binding the actual environment name
     * is used (e.g. "production", "staging", "testing").  This makes every
     * Monolog channel name reflect the current runtime environment.
     */
    protected function getFallbackChannelName(): string
    {
        if (method_exists($this, 'getContainer')) {
            try {
                /** @var \Nip\Container\ContainerInterface $container */
                $container = $this->getContainer();
                $env = $container->get('env');
                if (\is_string($env) && $env !== '') {
                    return $env;
                }
            } catch (\Throwable) {
                // container does not have 'env' – fall through to default
            }
        }

        return 'production';
    }
}

