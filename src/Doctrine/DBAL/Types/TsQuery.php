<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

use function sprintf;

class TsQuery extends Type
{
    public function getName() : string
    {
        return Types::TS_QUERY;
    }

    public function canRequireSQLConversion() : bool
    {
        return true;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::TS_QUERY);
    }

    /**
     * @param string $sqlExpr
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform) : string
    {
        return sprintf('plainto_tsquery(%s)', $sqlExpr);
    }
}
