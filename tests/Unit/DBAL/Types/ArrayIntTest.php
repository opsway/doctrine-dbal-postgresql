<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\ArrayInt;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ArrayIntTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::ARRAY_INT, ArrayInt::class);
    }

    public function testGetName() : void
    {
        $arrayInt = ArrayInt::getType(Types::ARRAY_INT);
        $this->assertEquals(Types::ARRAY_INT, $arrayInt->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $arrayInt = ArrayInt::getType(Types::ARRAY_INT);
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::ARRAY_INT])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $arrayInt->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue() : void
    {
        $arrayInt   = ArrayInt::getType(Types::ARRAY_INT);
        $wrongArray = null;
        $emptyArray = [];
        $rightArray = [1, 2, 3, 'test'];

        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            null,
            $arrayInt->convertToDatabaseValue($wrongArray, $platform->reveal())
        );

        $this->assertEquals(
            '{}',
            $arrayInt->convertToDatabaseValue($emptyArray, $platform->reveal())
        );

        $this->assertEquals(
            '{1,2,3}',
            $arrayInt->convertToDatabaseValue($rightArray, $platform->reveal())
        );
    }

    public function testConvertToPHPValue() : void
    {
        $arrayInt        = ArrayInt::getType(Types::ARRAY_INT);
        $wrongValue      = null;
        $emptyArrayValue = '{}';
        $rightValue      = '{1,2,3}';

        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            null,
            $arrayInt->convertToPHPValue($wrongValue, $platform->reveal())
        );

        $this->assertEquals(
            [],
            $arrayInt->convertToPHPValue($emptyArrayValue, $platform->reveal())
        );

        $this->assertEquals(
            [1, 2, 3],
            $arrayInt->convertToPHPValue($rightValue, $platform->reveal())
        );
    }
}
