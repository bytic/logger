<?php

namespace Nip\Logger\Manager;

use Nip\Container\ContainerAwareTrait;

/**
 * Trait HasApplication
 * @package Nip\Logger\Traits
 */
trait HasApplication
{
    use ContainerAwareTrait;

    /**
     * @var Application
     */
    protected $application = null;

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param Application $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return bool
     */
    public function hasApplication()
    {
        return is_object($this->application);
    }
}
