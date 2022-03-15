<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpsWay\Doctrine\DBAL\Types\TsQuery;
use OpsWay\Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TsQueryTest extends TestCase
{
    use ProphecyTrait;

    public static function setUpBeforeClass() : void
    {
        Type::addType(Types::TS_QUERY, TsQuery::class);
    }

    public function testGetName() : void
    {
        $tsQuery = TsQuery::getType(Types::TS_QUERY);
        $this->assertEquals(Types::TS_QUERY, $tsQuery->getName());
    }

    public function testGetSQLDeclaration() : void
    {
        $tsQuery  = TsQuery::getType(Types::TS_QUERY);
        $platform = $this->prophesize(AbstractPlatform::class);

        $platform->getDoctrineTypeMapping()
            ->shouldBeCalled()
            ->withArguments([Types::TS_QUERY])
            ->willReturn('test');

        $this->assertEquals(
            'test',
            $tsQuery->getSQLDeclaration([], $platform->reveal())
        );
    }

    public function testConvertToDatabaseValueSQL() : void
    {
        $tsQuery  = TsQuery::getType(Types::TS_QUERY);
        $platform = $this->prophesize(AbstractPlatform::class);

        $this->assertEquals(
            'plainto_tsquery(test)',
            $tsQuery->convertToDatabaseValueSQL('test', $platform->reveal())
        );
    }

    public function testCanRequireSQLConversion() : void
    {
        $tsQuery = TsQuery::getType(Types::TS_QUERY);

        $this->assertTrue($tsQuery->canRequireSQLConversion());
    }
}
