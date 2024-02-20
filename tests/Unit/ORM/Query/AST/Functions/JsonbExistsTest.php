<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\JsonbExists;
use OpsWay\Tests\EmTestCase;

class JsonbExistsTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'JSONB_EXISTS' => JsonbExists::class,
        ];
    }

    /** @dataProvider functionData */
    public function testFunction(string $dql, string $sql) : void
    {
        $query = $this->em->createQuery($dql);
        $this->assertEquals($sql, $query->getSql());
    }

    public function functionData() : array
    {
        return [
            [
                'SELECT JSONB_EXISTS(e.metaData, :fieldName) FROM OpsWay\Tests\Entity e',
                'SELECT jsonb_exists(e0_.metaData, ?) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
