<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\ToChar;
use OpsWay\Tests\EmTestCase;

class ToCharTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'TO_CHAR' => ToChar::class,
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
                "SELECT TO_CHAR(e.metaData, 'YYYY-MM-DD HH24:MI:SS') FROM OpsWay\Tests\Entity e",
                "SELECT TO_CHAR(e0_.metaData, 'YYYY-MM-DD HH24:MI:SS') AS sclr_0 FROM Entity e0_",
            ],
        ];
    }
}
