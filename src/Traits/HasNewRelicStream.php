<?php

namespace Nip\Logger\Traits;

use Monolog\Logger;
use Nip\Logger\Monolog\Handler\NewRelicHandler;

/**
 * Trait HasNewRelicStream
 * @package Nip\Logger\Traits
 */
trait HasNewRelicStream
{
    /**
     * @param $streams
     * @param null $appName
     * @return array
     */
    public function generateNewRelicStream($streams, $appName = null)
    {
        if (extension_loaded('newrelic')) {
            $streams[] = new NewRelicHandler(Logger::ERROR, true, $appName);
        }

        return $streams;
    }
}
