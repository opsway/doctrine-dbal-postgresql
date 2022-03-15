<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

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
     * @param array|null $value
     * @psalm-suppress all
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value === null) {
            return null;
        }

        $convertArray = [];
        foreach ($value as $valueItem) {
            if ($valueItem === null) {
                $valueItem = 'NULL';
            }
            if ($valueItem === '') {
                $valueItem = '""';
            }

            $convertArray[] = '"' . addcslashes($valueItem, '"') . '"';
        }

        return '{' . implode(',', $convertArray) . '}';
    }

    /**
     * @param string|null $value
     * @psalm-suppress all
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
