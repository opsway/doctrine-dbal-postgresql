<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\ToTsvector;
use OpsWay\Tests\EmTestCase;

class ToTsvectorTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'TO_TSVECTOR' => ToTsvector::class,
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
                'SELECT TO_TSVECTOR(:param) FROM OpsWay\Tests\Entity e',
                'SELECT to_tsvector(?) AS sclr_0 FROM Entity e0_',
            ],
            [
                "SELECT TO_TSVECTOR('english', :param) FROM OpsWay\Tests\Entity e",
                "SELECT to_tsvector('english', ?) AS sclr_0 FROM Entity e0_",
            ],
        ];
    }
}
