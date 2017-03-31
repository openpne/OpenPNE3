<?php
class Doctrine_Ticket_1768_TestCase extends Doctrine_UnitTestCase
{
    public function testResultCacheHashShouldProduceDifferentHashesWhenPassingParamsToWhereMethod()
    {
        $queryOne = Doctrine_Query::create()
            ->from('Ticket_1768_Foo f')
            ->where('f.bar = ?', 1);

        $queryTwo = Doctrine_Query::create()
            ->from('Ticket_1768_Foo f')
            ->where('f.bar = ?', 2);

        //Result hashes should be different
        $this->assertNotEqual($queryOne->calculateResultCacheHash(), $queryTwo->calculateResultCacheHash());
    }
}