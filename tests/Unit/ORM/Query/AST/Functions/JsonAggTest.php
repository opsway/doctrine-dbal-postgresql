<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\JsonAgg;
use OpsWay\Tests\EmTestCase;

class JsonAggTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'JSON_AGG' => JsonAgg::class,
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
                'SELECT JSON_AGG(e.metaData) FROM OpsWay\Tests\Entity e',
                'SELECT JSON_AGG(e0_.metaData) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
