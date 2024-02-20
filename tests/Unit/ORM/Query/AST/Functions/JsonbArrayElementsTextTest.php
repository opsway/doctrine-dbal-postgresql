<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\JsonbArrayElementsText;
use OpsWay\Tests\EmTestCase;

class JsonbArrayElementsTextTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'JSONB_ARRAY_ELEM_TEXT' => JsonbArrayElementsText::class,
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
                'SELECT JSONB_ARRAY_ELEM_TEXT(e.metaData) FROM OpsWay\Tests\Entity e',
                'SELECT jsonb_array_elements_text(e0_.metaData) AS sclr_0 FROM Entity e0_',
            ],
        ];
    }
}
