<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\Unnest;
use OpsWay\Tests\EmTestCase;

class UnnestTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'UNNEST' => Unnest::class,
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
                'SELECT UNNEST(e.metaData) FROM OpsWay\Tests\Entity e',
                'SELECT UNNEST(e0_.metaData) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
