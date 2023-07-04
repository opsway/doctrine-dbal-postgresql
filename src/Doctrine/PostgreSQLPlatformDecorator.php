<?php

declare(strict_types=1);

namespace OpsWay\Doctrine;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;

use function array_map;
use function implode;
use function sprintf;

/** @psalm-suppress all */
class PostgreSQLPlatformDecorator extends PostgreSQL100Platform
{
    /**
     * @param Index|array $columnsOrIndex
     */
    public function getIndexFieldDeclarationListSQL($columnsOrIndex) : string
    {
        if ($columnsOrIndex instanceof Index) {
            switch (true) {
                case $columnsOrIndex->hasFlag('gist_intbig'):
                    return implode(', ', array_map(
                        static function ($column) {
                            return sprintf('%s gist__intbig_ops', $column);
                        },
                        $columnsOrIndex->getQuotedColumns($this)
                    ));
                case $columnsOrIndex->hasFlag('gin_jsonb'):
                    return implode(', ', array_map(
                        static function ($column) {
                            return sprintf('%s jsonb_ops', $column);
                        },
                        $columnsOrIndex->getQuotedColumns($this)
                    ));
                case $columnsOrIndex->hasFlag('gin_jsonb_path'):
                    return implode(', ', array_map(
                        static function ($column) {
                            return sprintf('%s jsonb_path_ops', $column);
                        },
                        $columnsOrIndex->getQuotedColumns($this)
                    ));
                case $columnsOrIndex->hasFlag('gin_trgm_ops'):
                    return implode(', ', array_map(
                        static function ($column) {
                            return sprintf('%s gin_trgm_ops', $column);
                        },
                        $columnsOrIndex->getQuotedColumns($this)
                    ));
            }
        }

        return parent::getIndexFieldDeclarationListSQL($columnsOrIndex);
    }

    /**
     * @param Table|string $table
     */
    public function getCreateIndexSQL(Index $index, $table) : string
    {
        $tableName = $table instanceof Table
            ? $table->getQuotedName($this)
            : $table;

        switch (true) {
            case $index->hasFlag('gist_intbig'):
                $table = sprintf('%s USING GIST', $tableName);
                break;
            case $index->hasFlag('gin_jsonb'):
            case $index->hasFlag('gin_jsonb_path'):
            case $index->hasFlag('gin_trgm_ops'):
                $table = sprintf('%s USING GIN', $tableName);
                break;
        }

        return parent::getCreateIndexSQL($index, $table);
    }
}
