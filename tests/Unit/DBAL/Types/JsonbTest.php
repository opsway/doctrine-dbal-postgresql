<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\Jsonb;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class JsonbTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::JSONB, Jsonb::class);
    }

    public function testGetName() : void
    {
        $jsonb = Jsonb::getType(Types::JSONB);
        $this->assertEquals(Types::JSONB, $jsonb->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $jsonb    = Jsonb::getType(Types::JSONB);
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::JSONB])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $jsonb->getSQLDeclaration([], $platform->reveal())
        );
    }
}
