<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Ticket_1621b_TestCase
 *
 * @package     Doctrine
 * @author      floriank
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$ 
 */
class Doctrine_Ticket_1621b_TestCase extends Doctrine_UnitTestCase 
{
	const LANG = "deu";
	
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1621b_Term';
        $this->tables[] = 'Ticket_1621b_AltTerm';
        $this->tables[] = 'Ticket_1621b_PrefTerm';
        $this->tables[] = 'Ticket_1621b_CurrentLanguagePrefTerm';
        $this->tables[] = 'Ticket_1621b_Language';
        $this->tables[] = 'Ticket_1621b_Concept';
        $this->tables[] = 'Ticket_1621b_ConceptHierarchicalRelation';
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        $lang = new Ticket_1621b_Language();
        $lang->id = 'deu';
        $lang->Translation['de']->display = 'Deutsch';
        $lang->Translation['de']->description = 'Die Sprache Deutsch';
        $lang->Translation['en']->display = 'german';
        $lang->Translation['en']->description = 'The german language';
        
        $lang->save(); 
        
        $lang = new Ticket_1621b_Language();
        $lang->id = 'eng';
        $lang->Translation['de']->display = 'Englisch';
        $lang->Translation['de']->description = 'Die Sprache Englisch';
        $lang->Translation['en']->display = 'german';
        $lang->Translation['en']->description = 'The english language';
                
        $lang->save();
        
        
        
                
        $plant = new Ticket_1621b_Concept(); 
        $plant->identifier = "1";
        
        $pref_de = new Ticket_1621b_PrefTerm();
        $pref_de->lexicalValue = "Pflanze";
        $pref_de->langId = "deu";
        
        $pref_en = new Ticket_1621b_PrefTerm();
        $pref_en->lexicalValue = "plant";
        $pref_en->langId = "eng";
        
        $plant->preferedTerm = $pref_de;
        $plant->preferedTerms[] = $pref_en;
        
        $plant->save();
        
        
        
        
        $tree = new Ticket_1621b_Concept(); 
        $tree->identifier = "1.1";
        
        $pref_de = new Ticket_1621b_PrefTerm();
        $pref_de->lexicalValue = "Baum";
        $pref_de->langId = "deu";
        
        $pref_en = new Ticket_1621b_PrefTerm();
        $pref_en->lexicalValue = "tree";
        $pref_en->langId = "eng";
        
        $alt = new Ticket_1621b_AltTerm();
        $alt->lexicalValue = "bush";
        $alt->langId = "eng";
        
        $tree->preferedTerm = $pref_de;
        $tree->preferedTerms[] = $pref_en;
        $tree->altTerms[] = $alt;
        
        $tree->broaderConcepts[] = $plant;
        
        $tree->save();
             
        
        
        
        $oak = new Ticket_1621b_Concept(); 
        $oak->identifier = "1.1";
        
        $pref_de = new Ticket_1621b_PrefTerm();
        $pref_de->lexicalValue = "Eiche";
        $pref_de->langId = "deu";
        
        $pref_en = new Ticket_1621b_PrefTerm();
        $pref_en->lexicalValue = "oak";
        $pref_en->langId = "eng";
        
        $oak->preferedTerm = $pref_de;
        $oak->preferedTerms[] = $pref_en;
        
        $oak->broaderConcepts[] = $tree;
        
        $oak->save();
    }

    public function testInheritance() 
    {
        try {
	        $q = Doctrine_Query::create()
	                ->from("Ticket_1621b_Concept c")
	                ->innerJoin('c.preferedTerm p')
	                ->leftJoin('c.narrowerConcepts n')
	                ->where('c.id = ?', 2);
	        $rs = $q->fetchOne();  
	        
	        $this->assertEqual($rs->preferedTerm->lexicalValue, "Baum");
        } catch (Exception $e) {
        	$this->fail($e);
        }
    }
}
    


class Ticket_1621b_Concept extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('unsigned' => true, 'primary' => true, 'autoincrement' => true, 'type' => 'integer', 'length' => '4'));
    $this->hasColumn('identifier', 'string', 255, array('type' => 'string', 'length' => '255'));
  }

  public function setUp()
  {
    $this->hasOne('Ticket_1621b_CurrentLanguagePrefTerm as preferedTerm', array('local' => 'id',
                                                                          'foreign' => 'conceptId',
                                                                          'onDelete' => 'CASCADE'));

    $this->hasMany('Ticket_1621b_PrefTerm as preferedTerms', array('local' => 'id',
                                                             'foreign' => 'conceptId',
                                                             'onDelete' => 'CASCADE'));

    $this->hasMany('Ticket_1621b_AltTerm as altTerms', array('local' => 'id',
                                                       'foreign' => 'conceptId',
                                                       'onDelete' => 'CASCADE'));
    
    $this->hasMany('Ticket_1621b_Concept as broaderConcepts', array('refClass' => 'Ticket_1621b_ConceptHierarchicalRelation',
                                                       'refClassRelationAlias' => 'narrowerLinks',
                                                       'local' => 'conceptIdSource',
                                                       'foreign' => 'conceptIdTarget'));

    $this->hasMany('Ticket_1621b_Concept as narrowerConcepts', array('refClass' => 'Ticket_1621b_ConceptHierarchicalRelation',
                                                        'refClassRelationAlias' => 'broaderLinks',
                                                        'local' => 'conceptIdTarget',
                                                        'foreign' => 'conceptIdSource'));
  }
}



class Ticket_1621b_Term extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('unsigned' => true, 'primary' => true, 'autoincrement' => true, 'type' => 'integer', 'length' => '4'));
    $this->hasColumn('concept_id as conceptId', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'length' => '4'));
    $this->hasColumn('lexical_value as lexicalValue', 'string', 255, array('notnull' => true, 'type' => 'string', 'length' => '255'));
    $this->hasColumn('type', 'string', 20, array('notnull' => true, 'type' => 'string', 'length' => '20'));
    $this->hasColumn('lang_id as langId', 'string', 3, array('notnull' => true, 'type' => 'string', 'length' => '3'));

    $this->setSubClasses(array('Ticket_1621b_AltTerm' => array('type' => 'alt'), 'Ticket_1621b_PrefTerm' => array('type' => 'pref'), 'Ticket_1621b_CurrentLanguagePrefTerm' => array('langId' => Doctrine_Ticket_1621b_TestCase::LANG)));
  }

  public function setUp()
  {
  	$this->hasOne('Ticket_1621b_Concept as concept', array('local' => 'conceptId',
                                              'foreign' => 'id'));
    $this->hasOne('Ticket_1621b_Language as language', array('local' => 'langId',
                                                'foreign' => 'id'));
  }
}


class Ticket_1621b_AltTerm extends Ticket_1621b_Term
{
  public function setUp()
  {
    parent::setUp();
  }
}



class Ticket_1621b_PrefTerm extends Ticket_1621b_Term
{
  public function setUp()
  {
    parent::setUp();
  }
}


class Ticket_1621b_CurrentLanguagePrefTerm extends Ticket_1621b_PrefTerm
{
  public function setUp()
  {
    parent::setUp();
  }
}



class Ticket_1621b_Language extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'string', 3, array('type' => 'string', 'primary' => true, 'length' => '3'));
    $this->hasColumn('display', 'string', 50, array('type' => 'string', 'notnull' => true, 'length' => '50'));
    $this->hasColumn('description', 'string', 50, array('type' => 'string', 'length' => '50'));
  }

  public function setUp()
  {
    $this->hasMany('Ticket_1621b_Term', array('local' => 'id',
                                        'foreign' => 'langId'));

    $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'display', 1 => 'description')));
    $this->actAs($i18n0);
  }
}

class Ticket_1621b_ConceptHierarchicalRelation extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('unsigned' => true, 'primary' => true, 'autoincrement' => true, 'type' => 'integer', 'length' => '4'));
    $this->hasColumn('concept_id_s as conceptIdSource', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'length' => '4'));
    $this->hasColumn('concept_id_t as conceptIdTarget', 'integer', 4, array('type' => 'integer', 'unsigned' => true, 'length' => '4'));
  }

  public function setUp()
  {
    $this->hasOne('Ticket_1621b_Concept as hierarchieTarget', array('local' => 'conceptIdTarget',
                                                       'foreign' => 'id'));

    $this->hasOne('Ticket_1621b_Concept as hierarchieSource', array('local' => 'conceptIdSource',
                                                       'foreign' => 'id'));
  }
}



