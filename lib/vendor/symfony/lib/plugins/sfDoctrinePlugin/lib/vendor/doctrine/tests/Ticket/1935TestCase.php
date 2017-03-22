<?php

class Doctrine_Ticket_1935_TestCase extends Doctrine_UnitTestCase
{
    public function init()
    {
        Doctrine_Manager::connection('mysql://root:password@localhost/doctrine', 'Mysql');
        $this->driverName = 'Mysql';
        parent::init();
        Doctrine_Manager::connection('mysql://root:password@localhost/doctrine', 'Mysql');
        $this->prepareTables();
        $this->prepareData();
    }

    public function run(DoctrineTest_Reporter $reporter = null, $filter = null)
    {
        parent::run($reporter, $filter);
        $this->manager->closeConnection($this->connection);
    }

    public function prepareData()
    {
    }

    public function prepareTables()
    {
        $this->tables = array('Ticket_1935_Article');
        parent::prepareTables();
    }

    public function testDuplicatedParamsInSubQuery()
    {
        $this->connection->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

        try
        {
            $q = Doctrine_Query::create()->select('COUNT(a.id) as num_records')
                ->from('Ticket_1935_Article a')
                ->having('num_records > 1')
                ;
            //$results = $q->execute();
            $this->assertEqual($q->getSqlQuery(), 'SELECT COUNT(`t`.`id`) AS `t__0` FROM `ticket_1935_article` `t` HAVING `t__0` > 1');
        }
        catch(Exception $e)
        {
            $this->fail($e->getMessage());
        }

        $this->connection->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);
    }
}

class Ticket_1935_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('ticket_1935_article');
        $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => '255'));
    }
}
