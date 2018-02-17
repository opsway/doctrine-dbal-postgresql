<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\Inet;
use PHPUnit\Framework\TestCase;

class InetTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('inet', Inet::class);
    }

    public function testGetName()
    {
        $inet = Inet::getType('inet');
        $this->assertEquals('inet', $inet->getName());
    }

    public function testGetSQLDeclaration()
    {
        $inet = Inet::getType('inet');
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments(['inet'])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $inet->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue()
    {
        $inet = Inet::getType('inet');
        $platform = $this->prophesize(AbstractPlatform::class);

        $emptyValue = null;
        $wrongValue = 'test';
        $validValue = '192.168.100.128/25';

        $this->assertEquals(
            null,
            $inet->convertToDatabaseValue($emptyValue, $platform->reveal())
        );

        $this->expectExceptionMessage('test is not a properly formatted INET type.');

        $this->assertEquals(
            $validValue,
            $inet->convertToDatabaseValue($validValue, $platform->reveal())
        );

        $inet->convertToDatabaseValue($wrongValue, $platform->reveal());
    }
}
