<?php

namespace OpsWay\Tests\Doctrine\ORM\Query\AST\Functions;

use OpsWay\Tests\EmTestCase;
use Opsway\Doctrine\ORM\Query\AST\Functions\TsConcat;

class TsConcatTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'TS_CONCAT_OP' => TsConcat::class,
        ];
    }

    /** @dataProvider functionData */
    public function testFunction(string $dql, string $sql)
    {
        $query = $this->em->createQuery($dql);
        $this->assertEquals($sql, $query->getSql());
    }

    public function functionData()
    {
        return [
            [
                'SELECT TS_CONCAT_OP(e.id, e.id) FROM OpsWay\Tests\Entity e',
                'SELECT e0_.id || e0_.id AS sclr_0 FROM Entity e0_',
            ],
            [
                'SELECT TS_CONCAT_OP(e.id, e.id, e.id) FROM OpsWay\Tests\Entity e',
                'SELECT e0_.id || e0_.id || e0_.id AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}

