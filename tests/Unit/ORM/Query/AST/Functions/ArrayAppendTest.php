<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayAppend;
use OpsWay\Tests\EmTestCase;

class ArrayAppendTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'ARR_APPEND' => ArrayAppend::class,
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
                'SELECT ARR_APPEND(e.intArray, :newItem) FROM OpsWay\Tests\Entity e',
                'SELECT array_append(e0_.intArray, ?) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
