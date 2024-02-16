<?php

declare(strict_types=1);

namespace OpsWay\Doctrine;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Index;

use function array_map;
use function count;
use function implode;
use function sprintf;

/** @psalm-suppress all */
class PostgreSQLPlatformDecorator extends PostgreSQLPlatform
{
    public function getCreateIndexSQL(Index $index, string $table) : string
    {
        $name    = $index->getQuotedName($this);
        $columns = $index->getColumns();

        if (count($columns) === 0) {
            throw new InvalidArgumentException(sprintf(
                'Incomplete or invalid index definition %s on table %s',
                $name,
                $table,
            ));
        }

        if ($index->isPrimary()) {
            return $this->getCreatePrimaryKeySQL($index, $table);
        }

        $query  = 'CREATE ' . $this->getCreateIndexSQLFlags($index) . 'INDEX ' . $name . ' ON ' . $table;
        $query .= sprintf(
            ' %s(%s)',
            $this->getIndexMethodDeclaration($index),
            $this->getIndexFieldDeclarationListSQL($index),
        );
        $query .= $this->getPartialIndexSQL($index);

        return $query;
    }

    protected function getIndexMethodDeclaration(Index $index) : string
    {
        return match (true) {
            $index->hasFlag('gist_intbig') => 'USING GIST ',
            $index->hasFlag('gin_jsonb'),
            $index->hasFlag('gin_jsonb_path'),
            $index->hasFlag('gin_trgm_ops') => 'USING GIN ',
            default => '',
        };
    }

    protected function getIndexFieldDeclarationListSQL(Index $index) : string
    {
        $quotedColumns = $index->getQuotedColumns($this);
        $quotedColumns = match (true) {
            $index->hasFlag('gist_intbig') => array_map(
                static fn (string $c) => $c . ' gist__intbig_ops',
                $quotedColumns,
            ),
            $index->hasFlag('gin_jsonb') => array_map(
                static fn (string $c) => $c . ' jsonb_ops',
                $quotedColumns,
            ),
            $index->hasFlag('gin_jsonb_path') => array_map(
                static fn (string $c) => $c . ' jsonb_path_ops',
                $quotedColumns,
            ),
            $index->hasFlag('gin_trgm_ops') => array_map(
                static fn (string $c) => $c . ' gin_trgm_ops',
                $quotedColumns,
            ),
            default => $quotedColumns,
        };

        return implode(', ', $quotedColumns);
    }
}
