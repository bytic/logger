<?php

declare(strict_types=1);

namespace Nip\Logger;

use Nip\Config\Utils\PackageHasConfigTrait;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Central logging manager.
 *
 * Resolves named log channels from configuration and delegates all PSR-3 log
 * calls to the default channel's underlying Monolog instance.
 */
class Manager extends AbstractLogger implements PsrLoggerInterface
{
    use Manager\CreateDrivers;
    use Manager\HasApplication;
    use Manager\HasChannels;
    use Manager\HasConfiguration;
    use Manager\HasDrivers;
    use Manager\HasLevelsTrait;
    use Manager\HasLoggers;
    use Manager\MonologWrappers;
    use Manager\WriteLogs;
    use PackageHasConfigTrait;

    /**
     * The standard date format to use when writing logs.
     */
    protected string $dateFormat = 'Y-m-d H:i:s';

    protected static function getPackageConfigName(): string
    {
        return 'logging';
    }
}

