<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\ToTsquery;
use OpsWay\Tests\EmTestCase;

class ToTsqueryTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'TO_TSQUERY' => ToTsquery::class,
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
                'SELECT TO_TSQUERY(:param) FROM OpsWay\Tests\Entity e',
                'SELECT to_tsquery(?) AS sclr_0 FROM Entity e0_',
            ],
            [
                "SELECT TO_TSQUERY('english', :param) FROM OpsWay\Tests\Entity e",
                "SELECT to_tsquery('english', ?) AS sclr_0 FROM Entity e0_",
            ],
        ];
    }
}
