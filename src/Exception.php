<?php

declare(strict_types=1);

namespace Nip\Logger;

/**
 * Base exception class for the bytic/logger package.
 */
class Exception extends \Exception
{
    public function log(): void
    {
    }
}

