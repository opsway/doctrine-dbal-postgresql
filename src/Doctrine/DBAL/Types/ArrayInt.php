<?php

declare(strict_types=1);

namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

use function explode;
use function implode;
use function is_numeric;
use function ltrim;
use function rtrim;

class ArrayInt extends Type
{
    public function getName() : string
    {
        return Types::ARRAY_INT;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::ARRAY_INT);
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
            if (! is_numeric($value)) {
                continue;
            }
            $convertArray[] = (int) $value;
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

        $r = explode(',', $value);
        foreach ($r as &$v) {
            $v = (int) $v;
        }

        return $r;
    }
}
