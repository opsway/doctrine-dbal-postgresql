<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\ArrayInt;
use PHPUnit\Framework\TestCase;

class ArrayIntTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('integer[]', ArrayInt::class);
    }

    public function testGetName()
    {
        $arrayInt = ArrayInt::getType('integer[]');
        $this->assertEquals('integer[]', $arrayInt->getName());
    }

    public function testGetSQLDeclaration()
    {
        $arrayInt = ArrayInt::getType('integer[]');
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments(['integer[]'])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $arrayInt->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue()
    {
        $arrayInt = ArrayInt::getType('integer[]');
        $wrongArray = null;
        $rightArray = [1, 2, 3, 'test'];

        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            null,
            $arrayInt->convertToDatabaseValue($wrongArray, $platform->reveal())
        );

        $this->assertEquals(
            '{1,2,3}',
            $arrayInt->convertToDatabaseValue($rightArray, $platform->reveal())
        );
    }

    public function testConvertToPHPValue()
    {
        $arrayInt = ArrayInt::getType('integer[]');
        $wrongValue = null;
        $secondWrongValue = '{}';
        $rightValue = '{1,2,3}';

        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            null,
            $arrayInt->convertToPHPValue($wrongValue, $platform->reveal())
        );

        $this->assertEquals(
            null,
            $arrayInt->convertToPHPValue($secondWrongValue, $platform->reveal())
        );

        $this->assertEquals(
            [1, 2, 3],
            $arrayInt->convertToPHPValue($rightValue, $platform->reveal())
        );
    }
}
