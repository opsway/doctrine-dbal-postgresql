<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

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
            if (! is_numeric($valueItem)) {
                continue;
            }
            $convertArray[] = (int) $valueItem;
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

        $r = explode(',', $value);
        foreach ($r as &$v) {
            $v = (int) $v;
        }

        return $r;
    }
}
