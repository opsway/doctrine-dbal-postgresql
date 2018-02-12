<?php

namespace Opsway\Tests\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Opsway\Doctrine\DBAL\Types\Citext;
use PHPUnit\Framework\TestCase;

class CitextTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Type::addType('citext', Citext::class);
    }

    public function testGetName()
    {
        $citex = Citext::getType('citext');
        $this->assertEquals('citext', $citex->getName());
    }

    public function testGetSQLDeclaration()
    {
        $citex = Citext::getType('citext');
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments(['citext'])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $citex->getSQLDeclaration([], $platform->reveal())
        );
    }
}
