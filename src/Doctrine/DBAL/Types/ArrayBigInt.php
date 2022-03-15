<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayBigInt extends ArrayInt
{
    public function getName() : string
    {
        return Types::ARRAY_BIGINT;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::ARRAY_BIGINT);
    }
}
