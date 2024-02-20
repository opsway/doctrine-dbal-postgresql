<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\Extract;
use OpsWay\Tests\EmTestCase;

class ExtractTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'EXTRACT' => Extract::class,
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
                'SELECT EXTRACT(EPOCH FROM e.updatedAt) FROM OpsWay\Tests\Entity e',
                'SELECT EXTRACT(EPOCH FROM e0_.updatedAt) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
