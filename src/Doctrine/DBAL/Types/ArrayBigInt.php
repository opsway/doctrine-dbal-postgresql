<?php
namespace Opsway\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ArrayBigInt extends ArrayInt
{
    const ARRAY_INT = 'bigint[]';
}
