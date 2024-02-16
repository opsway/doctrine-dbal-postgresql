<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\JsonArrayLength;
use OpsWay\Tests\EmTestCase;

class JsonArrayLengthTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'GET_JSON_OBJECT_TEXT' => JsonArrayLength::class,
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
                'SELECT GET_JSON_OBJECT_TEXT(e.metaData) FROM OpsWay\Tests\Entity e',
                'SELECT json_array_length(e0_.metaData) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
