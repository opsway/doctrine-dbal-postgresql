<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\Any;
use OpsWay\Tests\EmTestCase;

class AnyTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'ANY_OP' => Any::class,
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
                'SELECT e.id FROM OpsWay\Tests\Entity e WHERE 10000 = ANY_OP(e.intArray)',
                'SELECT e0_.id AS id_0 FROM Entity e0_ WHERE 10000 = ANY(e0_.intArray)',
            ],
        ];
    }
}
