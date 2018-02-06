<?php

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class Citext extends TextType
{
    const CITEXT = 'citext';

    public function getName()
    {
        return static::CITEXT;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping(static::CITEXT);
    }

}
