<?php

namespace OpsWayTest\Doctrine\ORM\Query\AST\Functions;

use OpsWayTest\BaseTestCase;

class ContainsTest extends BaseTestCase
{

    public function testSql()
    {
        $resultSql = $this
            ->entityManager
            ->createQuery("SELECT t.id FROM E:Stub t WHERE CONTAINS(t.attrs, :param1) = TRUE")
            ->setParameter('param1', '{\"test\": 1}')
            ->getSql();
        $this->assertEquals(
            "SELECT t0_.id AS id0 FROM Stub t0_ WHERE (t0_.attrs @> ?) = true",
            $resultSql
        );
    }
}
