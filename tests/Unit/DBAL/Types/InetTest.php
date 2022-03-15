<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\Inet;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class InetTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::INET, Inet::class);
    }

    public function testGetName() : void
    {
        $inet = Inet::getType(Types::INET);
        $this->assertEquals(Types::INET, $inet->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $inet     = Inet::getType(Types::INET);
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::INET])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $inet->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValue() : void
    {
        $inet     = Inet::getType(Types::INET);
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
