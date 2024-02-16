<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\Arr;
use OpsWay\Tests\EmTestCase;

class ArrTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'ARR' => Arr::class,
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
                'SELECT ARR(e.id) FROM OpsWay\Tests\Entity e',
                'SELECT ARRAY[e0_.id] AS sclr_0 FROM Entity e0_',
            ],
            [
                'SELECT e.id, ARR(:param) FROM OpsWay\Tests\Entity e',
                'SELECT e0_.id AS id_0, ARRAY[?] AS sclr_1 FROM Entity e0_',
            ],
        ];
    }
}
