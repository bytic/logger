<?php

/**
 * @deprecated Use a proper PSR-3 logger (e.g. {@see \Psr\Log\LoggerInterface}) to log errors.
 *   This class will be removed in a future major version.
 */
class Nip_PHPException extends \Exception
{
    /**
     * @deprecated Trigger errors via a PSR-3 logger instead of calling trigger_error() directly.
     */
    public function log(): void
    {
        trigger_deprecation('bytic/logger', '2.0', 'Nip_PHPException::log() is deprecated. Use a PSR-3 logger to record errors instead.');

        trigger_error($this->getMessage(), $this->getCode());
    }
}

