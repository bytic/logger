<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

/**
 * Trait HasConfiguration
 *
 * @package Nip\Logger\Traits
 */
trait HasConfiguration
{
    /**
     * Get the log connection configuration.
     *
     * @return array<string, mixed>|null
     */
    protected function configurationFor(string $name): mixed
    {
        return static::getPackageConfig("channels.{$name}", ['driver' => 'single']);
    }
}

