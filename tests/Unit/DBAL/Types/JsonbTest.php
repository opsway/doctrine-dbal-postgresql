<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\Jsonb;
use PHPUnit\Framework\TestCase;

class JsonbTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('jsonb', Jsonb::class);
    }

    public function testGetName()
    {
        $jsonb = Jsonb::getType('jsonb');
        $this->assertEquals('jsonb', $jsonb->getName());
    }

    public function testGetSQLDeclaration()
    {
        $jsonb = Jsonb::getType('jsonb');
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments(['jsonb'])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $jsonb->getSQLDeclaration([], $platform->reveal())
        );
    }
}
