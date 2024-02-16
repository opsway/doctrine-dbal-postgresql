<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\Cast;
use OpsWay\Tests\EmTestCase;

class CastTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'CAST' => Cast::class,
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
                'SELECT CAST(e.metaData AS TEXT) FROM OpsWay\Tests\Entity e',
                'SELECT CAST(e0_.metaData AS TEXT) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
