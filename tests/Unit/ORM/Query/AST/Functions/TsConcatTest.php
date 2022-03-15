<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\TsConcat;
use OpsWay\Tests\EmTestCase;

class TsConcatTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'TS_CONCAT_OP' => TsConcat::class,
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
