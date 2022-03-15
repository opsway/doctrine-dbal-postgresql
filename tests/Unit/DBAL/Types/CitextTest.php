<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\Citext;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CitextTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::CITEXT, Citext::class);
    }

    public function testGetName() : void
    {
        $citex = Citext::getType(Types::CITEXT);
        $this->assertEquals(Types::CITEXT, $citex->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $citex    = Citext::getType(Types::CITEXT);
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::CITEXT])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $citex->getSQLDeclaration([], $platform->reveal())
        );
    }
}
