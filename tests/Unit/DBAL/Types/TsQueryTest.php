<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\TsQuery;
use PHPUnit\Framework\TestCase;

class TsQueryTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('tsquery', TsQuery::class);
    }

    public function testGetName()
    {
        $tsquery = TsQuery::getType('tsquery');
        $this->assertEquals('tsquery', $tsquery->getName());
    }

    public function testGetSQLDeclaration()
    {
        $tsquery = TsQuery::getType('tsquery');
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments(['tsquery'])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $tsquery->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValueSQL()
    {
        $tsquery = TsQuery::getType('tsquery');
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'plainto_tsquery(test)',
            $tsquery->convertToDatabaseValueSQL('test', $platform->reveal())
        );
    }

    public function testCanRequireSQLConversion()
    {
        $tsquery = TsQuery::getType('tsquery');

        $this->assertTrue($tsquery->canRequireSQLConversion());
    }
}
