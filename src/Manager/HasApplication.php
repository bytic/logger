<?php

declare(strict_types=1);

namespace Nip\Logger\Manager;

use Nip\Container\ContainerAwareTrait;

/**
 * Trait HasApplication
 *
 * @package Nip\Logger\Traits
 *
 * @deprecated The application-aware helpers in this trait are not used in
 *   modern integrations.  Use the container directly to resolve
 *   `path.storage`.  The trait will be removed in a future major version.
 */
trait HasApplication
{
    use ContainerAwareTrait;

    /**
     * @var object|null
     * @deprecated Not used in modern integrations.
     */
    protected ?object $application = null;

    /**
     * @return object|null
     * @deprecated Not used in modern integrations – inject the application via the container instead.
     */
    public function getApplication(): ?object
    {
        trigger_deprecation('bytic/logger', '2.0', 'HasApplication::getApplication() is deprecated and will be removed in a future version. Use the container to resolve application services.');

        return $this->application;
    }

    /**
     * @param object $application
     * @deprecated Not used in modern integrations – inject the application via the container instead.
     */
    public function setApplication(object $application): void
    {
        trigger_deprecation('bytic/logger', '2.0', 'HasApplication::setApplication() is deprecated and will be removed in a future version. Use the container to resolve application services.');

        $this->application = $application;
    }

    public function hasApplication(): bool
    {
        return $this->application !== null;
    }

    protected function getLogsFolderPath(): string
    {
        return $this->getContainer()->get('path.storage') . '/logs';
    }
}

