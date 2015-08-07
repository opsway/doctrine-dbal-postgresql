<?php

namespace OpsWayTest\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Stub
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="jsonb")
     */
    public $attrs;
}
