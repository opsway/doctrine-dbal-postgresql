<?php

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class Jsonb extends JsonArrayType
{
    const JSONB = 'jsonb';

    public function getName()
    {
        return static::JSONB;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping(static::JSONB);
    }

}
