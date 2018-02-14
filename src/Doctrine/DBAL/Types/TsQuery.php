<?php

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TsQuery extends Type
{
    const TS_QUERY = 'tsquery';

    public function getName()
    {
        return 'tsquery';
    }

    public function canRequireSQLConversion()
    {
        return true;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping(static::TS_QUERY);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return sprintf('plainto_tsquery(%s)', $sqlExpr);
    }
}
