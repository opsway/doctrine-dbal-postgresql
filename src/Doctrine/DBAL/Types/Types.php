<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

final class Types
{
    public const ARRAY_BIGINT  = 'bigint[]';
    public const ARRAY_INT     = 'integer[]';
    public const ARRAY_TEXT    = 'text[]';
    public const ARRAY_VARCHAR = 'varchar[]';
    public const CITEXT        = 'citext';
    public const INET          = 'inet';
    public const JSONB         = 'jsonb';
    public const TS_QUERY      = 'tsquery';
    public const TS_VECTOR     = 'tsvector';

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
}
