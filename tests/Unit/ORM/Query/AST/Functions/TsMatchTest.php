<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\TsMatch;
use OpsWay\Tests\EmTestCase;

class TsMatchTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'TS_MATCH_OP' => TsMatch::class,
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
                'SELECT TS_MATCH_OP(e.stringValue, :param) FROM OpsWay\Tests\Entity e',
                'SELECT (e0_.stringValue @@ ?) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
