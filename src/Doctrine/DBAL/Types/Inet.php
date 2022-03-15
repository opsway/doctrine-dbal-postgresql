<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

use function preg_match;
use function sprintf;

class Inet extends Type
{
    public function getName() : string
    {
        return Types::INET;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getDoctrineTypeMapping(Types::INET);
    }

    /**
     * @param string|null $value
     * @psalm-suppress all
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value === null) {
            return null;
        }

        if (preg_match(static::getPattern(), $value)) {
            return $value;
        }

        throw new InvalidArgumentException(sprintf('%s is not a properly formatted INET type.', $value));
    }

    private static function getPattern() : string
    {
        $ipV4Part = '(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)';
        $ipV4     = '(' . $ipV4Part . '.' . $ipV4Part . '.' . $ipV4Part . '.' . $ipV4Part . ')';

        $ipV6Part1 = '[0-9A-Fa-f]{1,4}';
        $ipV6Part2 = '(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b)';
        $ipV6      = '('
            . '((' . $ipV6Part1 . ':){7}' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){6}:' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){5}:(' . $ipV6Part1 . ':)?' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){4}:(' . $ipV6Part1 . ':){0,2}' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){3}:(' . $ipV6Part1 . ':){0,3}' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){2}:(' . $ipV6Part1 . ':){0,4}' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){6}(' . $ipV6Part2 . '.){3}' . $ipV6Part2 . ')|'
            . '((' . $ipV6Part1 . ':){0,5}:(' . $ipV6Part2 . '.){3}' . $ipV6Part2 . ')|'
            . '(::(' . $ipV6Part1 . ':){0,5}(' . $ipV6Part2 . '.){3}' . $ipV6Part2 . ')|'
            . '(' . $ipV6Part1 . '::(' . $ipV6Part1 . ':){0,5}' . $ipV6Part1 . ')|'
            . '(::(' . $ipV6Part1 . ':){0,6}' . $ipV6Part1 . ')|'
            . '((' . $ipV6Part1 . ':){1,7}:)'
            . ')';

        return '/^' . $ipV4 . '|' . $ipV6 . '$/';
    }
}
