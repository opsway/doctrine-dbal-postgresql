<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayAggregate;
use OpsWay\Tests\EmTestCase;

class ArrayAggregateTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'ARR_AGGREGATE' => ArrayAggregate::class,
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
                'SELECT ARR_AGGREGATE(e.id) FROM OpsWay\Tests\Entity e',
                'SELECT array_agg(e0_.id) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
