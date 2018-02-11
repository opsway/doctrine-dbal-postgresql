<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\TsVector;
use PHPUnit\Framework\TestCase;

class TsVectorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('tsvector', TsVector::class);
    }

    public function testGetName()
    {
        $tsvector = TsVector::getType('tsvector');
        $this->assertEquals('tsvector', $tsvector->getName());
    }

    public function testGetSQLDeclaration()
    {
        $tsvector = TsVector::getType('tsvector');
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'TSVECTOR',
            $tsvector->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testCanRequireSQLConversion()
    {
        $tsvector = TsVector::getType('tsvector');
        $this->assertEquals(true, $tsvector->canRequireSQLConversion());
    }

    public function testConvertToPHPValue()
    {
        $tsvector = TsVector::getType('tsvector');
        $platform = $this->prophesize(AbstractPlatform::class);

        $emptyValue = null;
        $rightValue = 'first:test1 second:test2';

        $this->assertEquals(
            [],
            $tsvector->convertToPHPValue($emptyValue, $platform->reveal())
        );

        $this->assertEquals(
            ['first', 'second'],
            $tsvector->convertToPHPValue($rightValue, $platform->reveal())
        );
    }

    public function testConvertToPHPValueSQL()
    {
        $tsvector = TsVector::getType('tsvector');
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'test',
            $tsvector->convertToPHPValueSQL('test', $platform->reveal())
        );
    }

    public function testConvertToDatabaseValueSQL()
    {
        $tsvector = TsVector::getType('tsvector');
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'to_tsvector(test)',
            $tsvector->convertToDatabaseValueSQL('test', $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue()
    {
        $tsvector = TsVector::getType('tsvector');
        $platform = $this->prophesize(AbstractPlatform::class);

        $value = [1, 2, 3];
        $this->assertEquals(
            '1 2 3',
            $tsvector->convertToDatabaseValue($value, $platform->reveal())
        );
    }
}
