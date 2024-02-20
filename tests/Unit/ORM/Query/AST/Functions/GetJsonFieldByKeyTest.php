<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonFieldByKey;
use OpsWay\Tests\EmTestCase;

class GetJsonFieldByKeyTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'GET_JSON_FIELD_BY_KEY' => GetJsonFieldByKey::class,
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
                "SELECT GET_JSON_FIELD_BY_KEY(e.metaData, 'fieldName') FROM OpsWay\Tests\Entity e",
                "SELECT (e0_.metaData->'fieldName') AS sclr_0 FROM Entity e0_",
            ],
        ];
    }
}
