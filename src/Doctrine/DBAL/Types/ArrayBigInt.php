<?php

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayBigInt extends ArrayInt
{
    public function getName()
    {
        return 'array_big_int';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return '_int8';
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform)
    {
        return array('_int8');
    }
}
