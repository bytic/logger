<?php

namespace Nip\Logger\Tests;

use Nip\Logger\Manager;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends TestCase
{
    protected $object;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @return Manager
     */
    protected function generateBaseManager()
    {
        $manager = new Manager();
        $manager->initContainer();
        $manager->getContainer()->set('path.storage', TEST_FIXTURE_PATH . '/storage');

        return $manager;
    }
}
