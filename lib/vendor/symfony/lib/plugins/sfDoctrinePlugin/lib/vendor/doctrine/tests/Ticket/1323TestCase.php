<?php

class Doctrine_Ticket_1323_TestCase extends Doctrine_UnitTestCase {
    public function prepareTables() {
        $this->tables = array();
        $this->tables[] = "T1323User";
        $this->tables[] = "T1323UserReference";
        parent::prepareTables();
    }
    
    public function prepareData() {}

    public function resetData()
    {
      $q = Doctrine_Query::create();
      $q->delete()->from("T1323UserReference")->execute();
      $q = Doctrine_Query::create();
      $q->delete()->from("T1323User")->execute();

      $m = new T1323User();
      $m->name = "Mother";
      $m->save();
      $f = new T1323User();
      $f->name = "Father";
      $f->save();
      $s = new T1323User();
      $s->name = "Son";
      $s->save();
      $d = new T1323User();
      $d->name = "Daughter";
      $d->save();
      $gf = new T1323User();
      $gf->name = "Grandfather";
      $gf->save();
      $gm = new T1323User();
      $gm->name = "Grandmother";
      $gm->save();
      
      $f->Children[] = $s;
      $f->Children[] = $d;
      
      $f->Parents[] = $gf;
      $f->Parents[] = $gm;
      
      $f->save();
      
      $m->Children[] = $s;
      $m->Children[] = $d;
      
      $m->save();      

    }
    
    public function testRelationsAreCorrect() {
        $this->resetData();
        
        $f = Doctrine_Core::getTable("T1323User")->findOneByName("Father");
        $childLinks = $f->childLinks;
        $this->assertEqual(2, count($childLinks));
        $this->assertEqual($f->id, $childLinks[0]->parent_id);
        $this->assertEqual($f->id, $childLinks[1]->parent_id);
        
        $parentLinks = $f->parentLinks;
        $this->assertEqual(2, count($parentLinks));
        $this->assertEqual($f->id, $parentLinks[0]->child_id);
        $this->assertEqual($f->id, $parentLinks[1]->child_id);
        
        $m = Doctrine_Core::getTable("T1323User")->findOneByName("Mother");
        $childLinks = $m->childLinks;
        $this->assertEqual(2, count($childLinks));
        $this->assertEqual($m->id, $childLinks[0]->parent_id);
        $this->assertEqual($m->id, $childLinks[1]->parent_id);
        
        $parentLinks = $m->parentLinks;
        $this->assertEqual(0, count($parentLinks));
        
        $s = Doctrine_Core::getTable("T1323User")->findOneByName("Son");
        $childLinks = $s->childLinks;
        $this->assertEqual(0, count($childLinks));
        $parentLinks = $s->parentLinks;
        $this->assertEqual(2, count($parentLinks));
        $this->assertEqual($s->id, $parentLinks[0]->child_id);
        $this->assertEqual($s->id, $parentLinks[1]->child_id);
        
        $d = Doctrine_Core::getTable("T1323User")->findOneByName("Daughter");
        $childLinks = $d->childLinks;
        $this->assertEqual(0, count($childLinks));
        $parentLinks = $d->parentLinks;
        $this->assertEqual(2, count($parentLinks));
        $this->assertEqual($d->id, $parentLinks[0]->child_id);
        $this->assertEqual($d->id, $parentLinks[1]->child_id);
        
        $gm = Doctrine_Core::getTable("T1323User")->findOneByName("Grandmother");
        $childLinks = $gm->childLinks;
        $this->assertEqual(1, count($childLinks));
        $this->assertEqual($gm->id, $childLinks[0]->parent_id);
        $parentLinks = $gm->parentLinks;
        $this->assertEqual(0, count($parentLinks));
        
        $gf = Doctrine_Core::getTable("T1323User")->findOneByName("Grandfather");
        $childLinks = $gf->childLinks;
        $this->assertEqual(1, count($childLinks));
        $this->assertEqual($gf->id, $childLinks[0]->parent_id);
        $parentLinks = $gf->parentLinks;
        $this->assertEqual(0, count($parentLinks));
    }

   /**
    * this test will fail
    */       
   public function testWithShow() {
      $this->resetData();
      
      T1323User::showAllRelations();
      $this->runTests();
   }

   /**
    * this test will pass
    */       
   public function testWithoutShow() {
      $this->resetData();
      
      $this->runTests();
   }

    
    public function runTests() {
        
      // change "Father"'s name...
      $f = Doctrine_Core::getTable("T1323User")->findOneByName("Father");
      $f->name = "Dad";
      $f->save(); 
      
      /*  just playing; makes no difference: 
          remove "Dad"'s relation to "Son"... */
      //$s = Doctrine_Core::getTable("T1323User")->findOneByName("Son");
      //$f->unlink("Children", array($s->id));
      //$f->save();
      
      $relations = Doctrine_Core::getTable("T1323UserReference")->findAll();
      foreach ($relations as $relation) {
        /*  never directly touched any relation; so no user should have 
            himself as parent or child */ 
        $this->assertNotEqual($relation->parent_id, $relation->child_id);
      }
    }
}
  

  
class T1323User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 30);
    }

    public function setUp()
    {
        $this->hasMany('T1323User as Parents', array('local' => 'child_id',
                                                'foreign'  => 'parent_id',
                                                'refClass' => 'T1323UserReference',
                                                'refClassRelationAlias' => 'childLinks'
                                                ));

        $this->hasMany('T1323User as Children', array('local' => 'parent_id',
                                                 'foreign'  => 'child_id',
                                                 'refClass' => 'T1323UserReference',
                                                 'refClassRelationAlias' => 'parentLinks'
                                                 ));
    }
    
    /**
     * just a little function to show all users and their relations
     */         
    public static function showAllRelations() {
        $users = Doctrine_Core::getTable("T1323User")->findAll();
        
        //echo "=========================================<br/>".PHP_EOL;
        //echo "list of all existing users and their relations:<br/> ".PHP_EOL;
        //echo "=========================================<br/><br/>".PHP_EOL.PHP_EOL;
        
        foreach ($users as $user) {
            $parents = $user->Parents;
            $children = $user->Children;
            
            /*echo "user: ";
            echo $user->name;
            echo PHP_EOL."<br/>";
            
            echo "parents:";
            echo PHP_EOL."<br/>";
            foreach ($parents as $parent) {
                echo $parent->name;
                echo PHP_EOL."<br/>";
            }
            echo PHP_EOL."<br/>";
            
            echo "children:";
            echo PHP_EOL."<br/>";
            foreach ($children as $child) {
                echo $child->name;
                echo PHP_EOL."<br/>";
            }
            echo PHP_EOL."<br/>";
            echo "--------------".PHP_EOL."<br/>";
            echo PHP_EOL."<br/>";*/
        }
    }
}

class T1323UserReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        //$this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('parent_id', 'integer', null, array('primary' => true));
        $this->hasColumn('child_id', 'integer', null, array('primary' => true));
    }
}

  
  
?>
