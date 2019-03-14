<?php

namespace OpsWay\Tests;

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
