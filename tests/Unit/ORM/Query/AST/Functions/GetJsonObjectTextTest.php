<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonObjectText;
use OpsWay\Tests\EmTestCase;

class GetJsonObjectTextTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'GET_JSON_OBJECT_TEXT' => GetJsonObjectText::class,
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
                <<<'DQL'
                SELECT GET_JSON_OBJECT_TEXT(e.metaData, '{fieldOnLevel1, fieldOnLevel2, fieldOnLevel3}')
                FROM OpsWay\Tests\Entity e
                DQL,
                <<<'EXPECTED_SQL'
                SELECT (e0_.metaData #>> '{fieldOnLevel1, fieldOnLevel2, fieldOnLevel3}') AS sclr_0 FROM Entity e0_
                EXPECTED_SQL,
            ],
        ];
    }
}
