<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

class Citext extends TextType
{
    public function getName() : string
    {
        return Types::CITEXT;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::CITEXT);
    }
}
