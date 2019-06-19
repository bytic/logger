<?php

namespace Nip\Logger\Monolog\Handler;

/**
 * Class NewRelicHandler
 * @package Nip\Logger\Monolog\Handler
 */
class NewRelicHandler extends \Monolog\Handler\NewRelicHandler
{
    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritDoc
     */
    protected function setNewRelicAppName($appName)
    {
    }
}
