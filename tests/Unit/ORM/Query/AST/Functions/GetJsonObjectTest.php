<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonObject;
use OpsWay\Tests\EmTestCase;

class GetJsonObjectTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'GET_JSON_OBJECT' => GetJsonObject::class,
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
                "SELECT GET_JSON_OBJECT(e.metaData, '{fieldOnLevel1, fieldOnLevel2}') FROM OpsWay\Tests\Entity e",
                "SELECT (e0_.metaData #> '{fieldOnLevel1, fieldOnLevel2}') AS sclr_0 FROM Entity e0_",
            ],
        ];
    }
}
