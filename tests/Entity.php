<?php

declare(strict_types=1);

namespace OpsWay\Tests;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

/** @Entity */
class Entity
{
    /** @Id @Column(type="string") @GeneratedValue */
    private $id;

    /**
     * @var array
     * @Column(type="json", nullable=true, options={"jsonb": true})
     */
    private $metaData;
}
