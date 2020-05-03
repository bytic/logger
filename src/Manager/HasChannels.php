<?php

namespace Nip\Logger\Manager;

/**
 * Trait HasChannels
 * @package Nip\Logger\Traits
 */
trait HasChannels
{
    /**
     * The array of resolved channels.
     *
     * @var array
     */
    protected $channels = [];

    /**
     * Get a log channel instance.
     *
     * @param string|null $channel
     * @return \Psr\Log\LoggerInterface
     */
    public function channel($channel = null)
    {
        return $this->driver($channel);
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array $config
     * @return string
     */
    protected function parseChannel(array $config)
    {
        return $config['name'] ?? $this->getFallbackChannelName();
    }

    /**
     * Get fallback log channel name.
     *
     * @return string
     */
    protected function getFallbackChannelName()
    {
        return 'production';
//        return $this->app->bound('env') ? $this->app->environment() : 'production';
    }
}
