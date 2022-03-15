<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\TsVector;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TsVectorTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::TS_VECTOR, TsVector::class);
    }

    public function testGetName() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
        $this->assertEquals(Types::TS_VECTOR, $tsvector->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
        $platform = $this->prophesize(PostgreSQLPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::TS_VECTOR])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $tsvector->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testCanRequireSQLConversion() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
        $this->assertEquals(true, $tsvector->canRequireSQLConversion());
    }

    public function testConvertToPHPValue() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
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

    public function testConvertToPHPValueSQL() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'test',
            $tsvector->convertToPHPValueSQL('test', $platform->reveal())
        );
    }

    public function testConvertToDatabaseValueSQL() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'to_tsvector(test)',
            $tsvector->convertToDatabaseValueSQL('test', $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue() : void
    {
        $tsvector = TsVector::getType(Types::TS_VECTOR);
        $platform = $this->prophesize(AbstractPlatform::class);

        $value = [1, 2, 3];
        $this->assertEquals(
            '1 2 3',
            $tsvector->convertToDatabaseValue($value, $platform->reveal())
        );
    }
}
