<?php

declare(strict_types=1);

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

use function addcslashes;
use function explode;
use function implode;
use function ltrim;
use function rtrim;
use function stripcslashes;

class ArrayText extends Type
{
    public function getName() : string
    {
        return Types::ARRAY_TEXT;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::ARRAY_TEXT);
    }

    /**
     * @param array|null $array
     */
    public function convertToDatabaseValue($array, AbstractPlatform $platform) : ?string
    {
        if ($array === null) {
            return null;
        }

        $convertArray = [];
        foreach ($array as $value) {
            if ($value === null) {
                $value = 'NULL';
            }
            if ($value === '') {
                $value = '""';
            }

            $convertArray[] = '"' . addcslashes($value, '"') . '"';
        }

        return '{' . implode(',', $convertArray) . '}';
    }

    /**
     * @param string|null $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?array
    {
        if ($value === null) {
            return null;
        }

        $value = ltrim(rtrim($value, '}'), '{');
        if ($value === '') {
            return [];
        }

        $result = explode(',', $value);
        foreach ($result as $key => $item) {
            $result[$key] = rtrim(ltrim(stripcslashes($item), '"'), '"');

            if ($result[$key] === 'NULL') {
                $result[$key] = null;
            }
        }

        return $result;
    }
}
