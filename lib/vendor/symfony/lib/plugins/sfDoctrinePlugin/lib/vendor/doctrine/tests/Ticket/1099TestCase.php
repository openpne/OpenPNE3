<?php
class Doctrine_Ticket_1099_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'T1099_Page';
        $this->tables[] = 'T1099_SubPage';

        parent::prepareTables();
    }

    public function prepareData()
    {
        $page = new T1099_Page();
        $page->type = 'page';
        $tree = $page->getTable()->getTree();
        $tree->createRoot($page);

        $subPage = new T1099_SubPage();
        $subPage->getNode()->insertAsLastChildOf($page);
    }

    public function testCAINestedSet()
    {
        $child = Doctrine_Query::create()
           ->from('T1099_Page p')
           ->where('p.type = \'subpage\'')
           ->fetchOne();

        $this->assertEqual('T1099_SubPage', get_class($child));
        $this->assertNotEqual(false, $child->getNode()->getParent());
    }
}

class T1099_Page extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('pages');
        $this->hasColumn('id', 'integer', 15, array('autoincrement' => true, 'primary' => true, 'notnull' => true));
        $this->hasColumn('type', 'string', 10);
    }

    public function setUp()
    {
        $this->actAs('Doctrine_Template_NestedSet');
        $this->setSubclasses(array(
        'T1099_SubPage' => array('type' => 'subpage')
        ));
        parent::setUp();
    }
}

class T1099_SubPage extends T1099_Page
{
}