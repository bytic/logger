<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Monolog\Processor\UidProcessor;

/**
 * Trait HasProcessors
 *
 * Provides the default set of Monolog processors applied to every channel.
 * Adding UidProcessor ensures every log record carries a unique request/run
 * identifier, making it easy to correlate related entries across channels
 * (file, New Relic, etc.).
 *
 * @package Nip\Logger\Manager
 */
trait HasProcessors
{
    /**
     * Return the list of Monolog processors to attach to every channel.
     *
     * @return list<\Monolog\Processor\ProcessorInterface|callable>
     */
    protected function processors(): array
    {
        return [
            new UidProcessor(),
        ];
    }
}
