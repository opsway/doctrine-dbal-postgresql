<?php

declare(strict_types=1);

namespace OpsWay\Tests;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
class Entity
{
    #[Mapping\Id]
    #[Mapping\Column]
    #[Mapping\GeneratedValue]
    private string $id;

    #[Mapping\Column(type: 'json', nullable: false, options: ['jsonb' => true])]
    private array $metaData;

    #[Mapping\Column(type: 'integer[]', nullable: false)]
    private array $intArray;

    #[Mapping\Column]
    private string $stringValue;

    #[Mapping\Column]
    private DateTimeImmutable $updatedAt;
}
