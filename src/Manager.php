<?php

namespace Nip\Logger;

use Nip\Config\Utils\PackageHasConfigTrait;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Class Manager
 *
 * @package Nip\Logger
 *
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
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @return string
     */
    protected static function getPackageConfigName()
    {
        return 'logging';
    }
}
