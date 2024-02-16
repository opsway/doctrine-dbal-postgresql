<?php

declare(strict_types=1);

namespace OpsWay\Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
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
        $config     = ORMSetup::createAttributeMetadataConfiguration([__DIR__], isDevMode: true);
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ], $config);

        $this->em = new EntityManager(
            $connection,
            $config,
        );

        foreach ($this->customStringFunctions() as $name => $className) {
            $this->em->getConfiguration()->addCustomStringFunction($name, $className);
        }
    }
}
