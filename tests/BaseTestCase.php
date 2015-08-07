<?php

namespace OpsWayTest;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager = null;

    protected function setUp()
    {
        if (!class_exists('\Doctrine\ORM\Configuration')) {
            $this->markTestSkipped('Doctrine is not available');
        }
        $config = new Configuration();
        $config->setMetadataCacheImpl(new ArrayCache());
        $config->setQueryCacheImpl(new ArrayCache());
        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('OpsWayTest\Proxies');
        $config->setAutoGenerateProxyClasses(true);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__ . '/Entities'));
        $config->addEntityNamespace('E', 'OpsWayTest\Entities');
        $config->setCustomStringFunctions(array(
            'CONTAINS' => 'OpsWay\Doctrine\ORM\Query\AST\Functions\Contains',
        ));

        $dbParams = array(
            'driver'   => 'pdo_pgsql',
            'host'     => 'localhost',
            'port'     => '5432',
            'dbname'   => 'testing',
            'user'     => 'postgres',
            'password' => 'secret',
        );
        $this->entityManager = \Doctrine\ORM\EntityManager::create(
            $dbParams,
            $config
        );

        $this->entityManager->getConnection()->getDatabasePlatform()
            ->registerDoctrineTypeMapping('jsonb', 'json_array');
    }

    protected function tearDown()
    {
        unset($this->entityManager);
    }
}
