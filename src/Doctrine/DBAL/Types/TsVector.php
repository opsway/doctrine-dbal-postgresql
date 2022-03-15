<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

use function explode;
use function implode;
use function is_array;
use function sprintf;
use function trim;

class TsVector extends Type
{
    public function getName() : string
    {
        return Types::TS_VECTOR;
    }

    public function canRequireSQLConversion() : bool
    {
        return true;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::TS_VECTOR);
    }

    /**
     * @param string|null $value
     * @psalm-suppress all
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : array
    {
        $terms = [];
        if (! empty($value)) {
            foreach (explode(' ', $value) as $item) {
                [$term]  = explode(':', $item);
                $terms[] = trim($term, '\'');
            }
        }

        return $terms;
    }

    /**
     * @param string $sqlExpr
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform) : string
    {
        return sprintf('to_tsvector(%s)', $sqlExpr);
    }

    /**
     * @param array|string $value
     * @psalm-suppress all
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : string
    {
        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        return $value;
    }
}
