<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use OpsWay\Doctrine\ORM\Query\AST\Functions\RegexpReplace;
use OpsWay\Tests\EmTestCase;

class RegexpReplaceTest extends EmTestCase
{
    protected function customStringFunctions() : array
    {
        return [
            'REGEXP_REPLACE' => RegexpReplace::class,
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
                "SELECT REGEXP_REPLACE(e.stringValue, 'search', 'replacement') FROM OpsWay\Tests\Entity e",
                "SELECT regexp_replace(e0_.stringValue, 'search', 'replacement') AS sclr_0 FROM Entity e0_",
            ],
            [
                "SELECT REGEXP_REPLACE(e.stringValue, 'search', 'replacement', 'flags') FROM OpsWay\Tests\Entity e",
                "SELECT regexp_replace(e0_.stringValue, 'search', 'replacement', 'flags') AS sclr_0 FROM Entity e0_",
            ],
        ];
    }
}
