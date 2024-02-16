<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\Contains;
use OpsWay\Tests\EmTestCase;

class ContainsTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'CONTAINS' => Contains::class,
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
                'SELECT CONTAINS(e.metaData, :search) FROM OpsWay\Tests\Entity e',
                'SELECT (e0_.metaData @> ?) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
