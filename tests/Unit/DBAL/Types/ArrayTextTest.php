<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\ArrayText;
use PHPUnit\Framework\TestCase;

class ArrayTextTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('text[]', ArrayText::class);
    }

    public function testGetName()
    {
        $arrayInt = ArrayText::getType('text[]');
        $this->assertEquals('text[]', $arrayInt->getName());
    }

    public function testGetSQLDeclaration()
    {
        $arrayText = ArrayText::getType('text[]');
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments(['text[]'])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $arrayText->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue()
    {
        $arrayText = ArrayText::getType('text[]');
        $platform = $this->prophesize(AbstractPlatform::class);

        $wrongArray = null;
        $validArray = ['test', null, '', 'test'];

        $this->assertEquals(
            null,
            $arrayText->convertToDatabaseValue($wrongArray, $platform->reveal())
        );

        $this->assertEquals(
            '{"test","NULL","\"\"","test"}',
            $arrayText->convertToDatabaseValue($validArray, $platform->reveal())
        );
    }

    public function testConvertToPHPValue()
    {
        $arrayText = ArrayText::getType('text[]');
        $platform = $this->prophesize(AbstractPlatform::class);

        $wrongValue = null;
        $anotherWrongValue = '{}';
        $validValue = '{"test","NULL","\"\"","test"}';

        $this->assertEquals(
            null,
            $arrayText->convertToPHPValue($wrongValue, $platform->reveal())
        );

        $this->assertEquals(
            null,
            $arrayText->convertToPHPValue($anotherWrongValue, $platform->reveal())
        );

        $this->assertEquals(
            ['test', null, '', 'test'],
            $arrayText->convertToPHPValue($validValue, $platform->reveal())
        );
    }
}
