<?php

declare(strict_types=1);

namespace OpsWay\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

class EmTestCase extends TestCase
{
    /** @var EntityManager */
    protected $em;

    protected function customStringFunctions() : array
    {
        return [];
    }

    protected function setUp() : void
    {
        $config     = Setup::createConfiguration(true);
        $driverImpl = $config->newDefaultAnnotationDriver([__DIR__]);
        $config->setMetadataDriverImpl($driverImpl);
        $this->em = EntityManager::create([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ], $config);

        foreach ($this->customStringFunctions() as $name => $className) {
            $this->em->getConfiguration()->addCustomStringFunction($name, $className);
        }
    }
}
