<?php

namespace Nip\Logger\Manager;

/**
 * Trait HasConfiguration
 * @package Nip\Logger\Traits
 */
trait HasConfiguration
{

    /**
     * Get the log connection configuration.
     *
     * @param string $name
     * @return array
     */
    protected function configurationFor($name)
    {
        return static::getPackageConfig('channels.{$name}', ['driver' => 'single']);
    }
}
