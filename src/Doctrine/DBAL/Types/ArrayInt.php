<?php

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ArrayInt extends Type
{
    public function getName()
    {
        return 'array_int';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return '_int4';
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform)
    {
        return array('_int4');
    }

    public function convertToDatabaseValue($array, AbstractPlatform $platform)
    {
        if ($array === null) {
            return;
        }
        $convertArray = [];
        foreach ($array as $value) {
            if (!is_numeric($value)) {
                continue;
            }
            $convertArray[] = (int) $value;
        }

        return '{'.implode(',', $convertArray).'}';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return;
        }

        $value = ltrim(rtrim($value, '}'), '{');
        if ($value === '') {
            return;
        }
        $r = explode(',', $value);
        foreach ($r as &$v) {
            $v = (int) $v;
        }

        return $r;
    }
}
