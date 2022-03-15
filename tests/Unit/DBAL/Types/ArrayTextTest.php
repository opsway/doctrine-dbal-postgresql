<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\ArrayText;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ArrayTextTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::ARRAY_TEXT, ArrayText::class);
    }

    public function testGetName() : void
    {
        $arrayInt = ArrayText::getType(Types::ARRAY_TEXT);
        $this->assertEquals(Types::ARRAY_TEXT, $arrayInt->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $arrayText = ArrayText::getType(Types::ARRAY_TEXT);
        $platform  = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::ARRAY_TEXT])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $arrayText->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue() : void
    {
        $arrayText = ArrayText::getType(Types::ARRAY_TEXT);
        $platform  = $this->prophesize(AbstractPlatform::class);

        $wrongArray = null;
        $emptyArray = [];
        $validArray = ['test', null, '', 'test'];

        $this->assertEquals(
            null,
            $arrayText->convertToDatabaseValue($wrongArray, $platform->reveal())
        );

        $this->assertEquals(
            '{}',
            $arrayText->convertToDatabaseValue($emptyArray, $platform->reveal())
        );

        $this->assertEquals(
            '{"test","NULL","\"\"","test"}',
            $arrayText->convertToDatabaseValue($validArray, $platform->reveal())
        );
    }

    public function testConvertToPHPValue() : void
    {
        $arrayText = ArrayText::getType(Types::ARRAY_TEXT);
        $platform  = $this->prophesize(AbstractPlatform::class);

        $wrongValue      = null;
        $emptyArrayValue = '{}';
        $validValue      = '{"test","NULL","\"\"","test"}';

        $this->assertEquals(
            null,
            $arrayText->convertToPHPValue($wrongValue, $platform->reveal())
        );

        $this->assertEquals(
            [],
            $arrayText->convertToPHPValue($emptyArrayValue, $platform->reveal())
        );

        $this->assertEquals(
            ['test', null, '', 'test'],
            $arrayText->convertToPHPValue($validValue, $platform->reveal())
        );
    }
}
