<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit;

use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Opsway\Doctrine\PostgreSQLPlatformDecorator;
use PHPUnit\Framework\TestCase;

class PostgreSQLPlatformDecoratorTest extends TestCase
{
    private PostgreSQLPlatformDecorator $platform;

    protected function setUp() : void
    {
        parent::setUp();

        $this->platform = new PostgreSQLPlatformDecorator();
    }

    /**
     * @dataProvider getCreateIndexSQLData
     */
    public function testGetCreateIndexSQL(
        string $expected,
        Index $index,
        string | Table $table
    ) : void {
        $actual = $this->platform->getCreateIndexSQL($index, $table);

        $this->assertEquals($expected, $actual);
    }

    public function getCreateIndexSQLData() : iterable
    {
        yield 'Check without flags' => [
            'CREATE INDEX index_name ON table_name (col)',
            new Index('index_name', ['col']),
            'table_name',
        ];

        yield 'Check multicolumn without flags' => [
            'CREATE INDEX index_name ON table_name (col1, col2)',
            new Index('index_name', ['col1', 'col2']),
            'table_name',
        ];

        yield 'Check without flags with Table instance' => [
            'CREATE INDEX index_name ON table_name (col)',
            new Index('index_name', ['col']),
            new Table('table_name'),
        ];

        yield 'Check gist_intbig flag' => [
            'CREATE INDEX index_name ON table_name USING GIST (col gist__intbig_ops)',
            new Index('index_name', ['col'], flags: ['gist_intbig']),
            'table_name',
        ];

        yield 'Check multicolumn gist_intbig flag' => [
            'CREATE INDEX index_name ON table_name USING GIST (col1 gist__intbig_ops, col2 gist__intbig_ops)',
            new Index('index_name', ['col1', 'col2'], flags: ['gist_intbig']),
            'table_name',
        ];

        yield 'Check gin_jsonb_path flag' => [
            'CREATE INDEX index_name ON table_name USING GIN (col jsonb_path_ops)',
            new Index('index_name', ['col'], flags: ['gin_jsonb_path']),
            'table_name',
        ];

        yield 'Check multicolumn  gin_jsonb_path flag' => [
            'CREATE INDEX index_name ON table_name USING GIN (col1 jsonb_path_ops, col2 jsonb_path_ops)',
            new Index('index_name', ['col1', 'col2'], flags: ['gin_jsonb_path']),
            'table_name',
        ];

        yield 'Check gin_trgm_ops flag' => [
            'CREATE INDEX index_name ON table_name USING GIN (col gin_trgm_ops)',
            new Index('index_name', ['col'], flags: ['gin_trgm_ops']),
            'table_name',
        ];

        yield 'Check multicolumn  gin_trgm_ops flag' => [
            'CREATE INDEX index_name ON table_name USING GIN (col1 gin_trgm_ops, col2 gin_trgm_ops)',
            new Index('index_name', ['col1', 'col2'], flags: ['gin_trgm_ops']),
            'table_name',
        ];
    }
}
