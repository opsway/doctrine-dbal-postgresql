<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\All;
use OpsWay\Tests\EmTestCase;

class AllTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'ALL_OP' => All::class,
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
                'SELECT e.id FROM OpsWay\Tests\Entity e WHERE 10000 = ALL_OP(e.intArray)',
                'SELECT e0_.id AS id_0 FROM Entity e0_ WHERE 10000 = ALL(e0_.intArray)',
            ],
        ];
    }
}
