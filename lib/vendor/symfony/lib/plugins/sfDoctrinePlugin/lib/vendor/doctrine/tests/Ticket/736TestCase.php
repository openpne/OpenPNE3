<?php
/**
 * Doctrine_Ticket_736_TestCase
 *
 * @package     Doctrine
 * @author      Peter Petermann <Peter.Petermann@rtl.de>
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @version     $Revision$
 */
class Doctrine_Ticket_736_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData() 
    { 
        $delegate = new T736_ModuleDelegate();
        $delegate->content = "Lorem Ipsum and so on...";
        $delegate->save();

        $module = new T736_Module();
        $module->moduledelegateid = $delegate->id;
        
        $delegate->parent = $module;
        $delegate->save();
    }
    
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'T736_Module';
        $this->tables[] = 'T736_ModuleDelegate';
        parent::prepareTables();
    }

    public function testForHydrationOverwrintingLocalInstancesWhenItShouldnt()
    {
        $module = Doctrine_Core::getTable("T736_Module")->find(1);
        $module->moduledata->content = "foo";
        $module->moduledata->save();
	    $this->assertTrue($module->moduledata->content == "foo"); // should be "foo" is "Lorem Ipsum and so on..."
	    
    }
}


class T736_Module extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('lastchange', 'timestamp');
        $this->hasColumn('moduledelegateid', 'integer', 4, array('notnull' => true));
    }

    public function setUp()
    {
        $this->addListener(new T736_ModuleLoaderListener());
    }

}

class T736_ModuleDelegate extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn("moduleid", "integer", 4, array());
        $this->hasColumn("content", "string", 2000);
    }
    
    public function setUp()
    {
        $this->hasOne("T736_Module as parent", array('local' => 'moduleid', 'foreign' => 'id'));
    }
    
    
    public function preUpdate($event)
    {
        $this->parent->lastchange = date('Y-m-d H:i:s', time());
        $this->parent->save();
    }
}


class T736_ModuleLoaderListener extends Doctrine_Record_Listener
{
    public function postHydrate(Doctrine_Event $event)
    {
        $contents = $event->data;
        $delegate = Doctrine_Core::getTable("T736_ModuleDelegate")->find($contents["moduledelegateid"], ($contents instanceof Doctrine_Record) ? Doctrine_Core::HYDRATE_RECORD :Doctrine_Core::HYDRATE_ARRAY );
        if ($contents instanceof Doctrine_Record)
        {
            $contents->mapValue("moduledata", $delegate);
            $delegate->parent = $contents;
        } else {
            $contents["moduledata"] = $delegate;
        }
        $event->data = $contents;
    }
}
?>
