<?php


class Doctrine_Ticket_1830_TestCase extends Doctrine_UnitTestCase
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
        try {
            $this->conn->exec('DROP TABLE ticket_1830__article_translation');
        } catch(Doctrine_Connection_Exception $e) {
        }
        $this->tables = array('Ticket_1830_Article');
        parent::prepareTables();
    }

    public function testDuplicatedParamsInSubQuery()
    {
        $this->connection->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $article = new Ticket_1830_Article();
        $article->Translation['en']->title = 'Node1';
        $article->save($this->connection);
        $article = new Ticket_1830_Article();
        $article->Translation['en']->title = 'Node2';
        $article->save($this->connection);
        $article = new Ticket_1830_Article();
        $article->Translation['en']->title = 'Node3';
        $article->save($this->connection);
        $article = new Ticket_1830_Article();
        $article->Translation['en']->title = 'Node4';
        $article->save($this->connection);
        $article = new Ticket_1830_Article();
        $article->Translation['en']->title = 'Node5';
        $article->save($this->connection);

        try
        {
            $q = Doctrine_Core::getTable('Ticket_1830_Article')
                ->createQuery('a')
                ->select('a.*, t.*')
                ->leftJoin('a.Translation t')
                ->addWhere('a.id = ? OR a.id = ?', array(2, 3))
                ->orderBy('a.id DESC')
                ->limit(1);
            $results = $q->execute();
            $this->assertEqual(count($results), 1);
            $this->assertEqual($results[0]->id, 3);
        }
        catch (Exception $e)
        {
          $this->fail($e->getMessage());
        }

        $this->connection->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class Ticket_1830_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('ticket_1830_article');
        $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => '255'));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'title')));
        $this->actAs($i18n0);
    }
}